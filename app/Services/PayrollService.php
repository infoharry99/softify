<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\SalaryStructure;
use App\Models\SalaryHistory;
use App\Models\MonthlyPayroll;
use App\Models\Attendance;
use App\Models\LeaveApplication;
use App\Models\AppNotification;
use App\Models\User;
use Carbon\Carbon;

class PayrollService
{
    /**
     * Process monthly payroll for an employee.
     */
    public static function processMonthlyPayroll(Employee $employee, string $month, User $adminUser)
    {
        $yearMonth = Carbon::parse($month . '-01');
        $daysInMonth = $yearMonth->daysInMonth;

        $salary = $employee->salaryStructure;
        if (!$salary) {
            $salary = SalaryStructure::create([
                'employee_id' => $employee->id,
                'basic_salary' => 20000,
                'hra' => 5000,
                'allowances' => 3000,
                'gross_salary' => 28000,
                'pf_deduction' => 0,
                'other_deductions' => 0,
                'net_salary' => 28000,
                'effective_date' => now(),
            ]);
        } else {
            // Ensure PF deduction is removed globally
            if ($salary->pf_deduction > 0) {
                $salary->update(['pf_deduction' => 0]);
            }
        }

        // Calculate attendance metrics
        $attendances = Attendance::where('employee_id', $employee->id)
            ->whereYear('date', $yearMonth->year)
            ->whereMonth('date', $yearMonth->month)
            ->get();

        $presentDays = $attendances->whereIn('status', ['Present', 'Late', 'Half Day'])->count();
        $paidLeaveDays = (int) LeaveApplication::where('employee_id', $employee->id)
            ->where('status', 'Approved')
            ->whereYear('from_date', $yearMonth->year)
            ->whereMonth('from_date', $yearMonth->month)
            ->sum('total_days');

        $recordedDays = $attendances->count();

        // If no attendance records logged yet for the month, default present days to full month
        if ($recordedDays == 0) {
            $presentDays = $daysInMonth;
            $unpaidLeaveDays = 0;
            $unpaidLeaveDeduction = 0;
        } else {
            $unpaidLeaveDays = max(0, $daysInMonth - ($presentDays + $paidLeaveDays));
            $perDaySalary = $salary->gross_salary > 0 ? ($salary->gross_salary / $daysInMonth) : 0;
            $unpaidLeaveDeduction = round($unpaidLeaveDays * $perDaySalary, 2);
        }

        $gross = $salary->gross_salary + $salary->bonus + $salary->incentives;
        $totalDeductions = ($salary->other_deductions ?? 0) + $unpaidLeaveDeduction;
        $netSalary = max(0, round($gross - $totalDeductions, 2));

        $payroll = MonthlyPayroll::updateOrCreate(
            ['employee_id' => $employee->id, 'month' => $month],
            [
                'working_days' => $daysInMonth,
                'present_days' => $presentDays,
                'paid_leave_days' => $paidLeaveDays,
                'unpaid_leave_days' => $unpaidLeaveDays,
                'absent_days' => $unpaidLeaveDays,
                'leave_deductions' => $unpaidLeaveDeduction,
                'bonus_amount' => $salary->bonus ?? 0,
                'gross_salary' => $gross,
                'total_deductions' => $totalDeductions,
                'net_salary' => $netSalary,
                'payment_status' => 'Paid',
                'payment_date' => now(),
                'processed_by' => $adminUser->id,
            ]
        );

        // Notify Employee
        AppNotification::create([
            'user_id' => $employee->user_id,
            'type' => 'payroll_generated',
            'title' => 'Salary Slip Generated',
            'message' => "Your salary slip for {$month} has been finalized. Net Amount: ₹" . number_format($netSalary, 2),
        ]);

        return $payroll;
    }

    /**
     * Update custom monthly payroll for an employee.
     */
    public static function updatePayroll(MonthlyPayroll $payroll, array $data, User $adminUser)
    {
        $workingDays = (int) ($data['working_days'] ?? $payroll->working_days);
        $presentDays = (int) ($data['present_days'] ?? $payroll->present_days);
        $paidLeaveDays = (int) ($data['paid_leave_days'] ?? $payroll->paid_leave_days);
        $unpaidLeaveDays = (int) ($data['unpaid_leave_days'] ?? $payroll->unpaid_leave_days);
        $grossSalary = (float) ($data['gross_salary'] ?? $payroll->gross_salary);
        $bonusAmount = (float) ($data['bonus_amount'] ?? $payroll->bonus_amount);
        $otherDeductions = (float) ($data['other_deductions'] ?? 0);

        $perDay = $workingDays > 0 ? ($grossSalary / $workingDays) : 0;
        $leaveDeductions = round($unpaidLeaveDays * $perDay, 2);
        $totalDeductions = round($leaveDeductions + $otherDeductions, 2);
        $netSalary = max(0, round(($grossSalary + $bonusAmount) - $totalDeductions, 2));

        $payroll->update([
            'working_days' => $workingDays,
            'present_days' => $presentDays,
            'paid_leave_days' => $paidLeaveDays,
            'unpaid_leave_days' => $unpaidLeaveDays,
            'absent_days' => $unpaidLeaveDays,
            'gross_salary' => $grossSalary,
            'bonus_amount' => $bonusAmount,
            'leave_deductions' => $leaveDeductions,
            'total_deductions' => $totalDeductions,
            'net_salary' => $netSalary,
            'payment_status' => $data['payment_status'] ?? $payroll->payment_status,
            'processed_by' => $adminUser->id,
            'payment_date' => now(),
        ]);

        return $payroll;
    }

    /**
     * Update employee salary structure and record revision history.
     */
    public static function updateSalaryStructure(Employee $employee, array $newStructure, User $adminUser, ?string $reason = null)
    {
        $oldStructure = $employee->salaryStructure;
        $oldNet = $oldStructure ? $oldStructure->net_salary : 0;

        $gross = ($newStructure['basic_salary'] ?? 0) + ($newStructure['hra'] ?? 0) + ($newStructure['conveyance'] ?? 0) + ($newStructure['allowances'] ?? 0) + ($newStructure['bonus'] ?? 0) + ($newStructure['incentives'] ?? 0);
        $deductions = ($newStructure['pf_deduction'] ?? 0) + ($newStructure['esi_deduction'] ?? 0) + ($newStructure['pt_deduction'] ?? 0) + ($newStructure['tds_deduction'] ?? 0) + ($newStructure['other_deductions'] ?? 0);
        $newNet = max(0, $gross - $deductions);

        $newStructure['gross_salary'] = $gross;
        $newStructure['net_salary'] = $newNet;
        $newStructure['effective_date'] = $newStructure['effective_date'] ?? now();

        $structure = SalaryStructure::updateOrCreate(
            ['employee_id' => $employee->id],
            $newStructure
        );

        // Log Revision History (never overwrite past records)
        SalaryHistory::create([
            'employee_id' => $employee->id,
            'previous_net_salary' => $oldNet,
            'new_net_salary' => $newNet,
            'salary_components' => $newStructure,
            'effective_date' => $newStructure['effective_date'],
            'reason' => $reason ?? 'Annual Revision',
            'updated_by' => $adminUser->id,
        ]);

        // Notify Employee
        AppNotification::create([
            'user_id' => $employee->user_id,
            'type' => 'salary_updated',
            'title' => 'Salary Revision Updated',
            'message' => "Your salary has been updated. New Net Salary: ₹" . number_format($newNet, 2),
        ]);

        return $structure;
    }
}
