<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveApplication;
use App\Models\AppNotification;
use App\Models\User;
use Carbon\Carbon;

class LeaveService
{
    /**
     * Initialize annual leave balances for a new employee.
     */
    public static function initializeBalances(Employee $employee, ?int $year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $leaveTypes = LeaveType::all();

        foreach ($leaveTypes as $type) {
            LeaveBalance::firstOrCreate(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $type->id,
                    'year' => $year,
                ],
                [
                    'allowed_days' => $type->days_allowed_per_year,
                    'used_days' => 0,
                    'remaining_days' => $type->days_allowed_per_year,
                ]
            );
        }
    }

    /**
     * Submit a leave application.
     */
    public static function applyLeave(Employee $employee, array $data)
    {
        $year = Carbon::parse($data['from_date'])->year;
        self::initializeBalances($employee, $year);

        $from = Carbon::parse($data['from_date']);
        $to = Carbon::parse($data['to_date']);
        $totalDays = !empty($data['is_half_day']) ? 0.5 : ($from->diffInDays($to) + 1);

        $application = LeaveApplication::create([
            'employee_id' => $employee->id,
            'leave_type_id' => $data['leave_type_id'],
            'from_date' => $data['from_date'],
            'to_date' => $data['to_date'],
            'total_days' => $totalDays,
            'is_half_day' => !empty($data['is_half_day']),
            'reason' => $data['reason'],
            'attachment' => $data['attachment'] ?? null,
            'status' => 'Pending',
        ]);

        // Notify Admins/HR
        $user = $employee->user;
        $admins = User::whereHas('roles', function ($q) {
            $q->whereIn('slug', ['super-admin', 'admin', 'hr']);
        })->get();

        foreach ($admins as $admin) {
            AppNotification::create([
                'user_id' => $admin->id,
                'type' => 'leave_applied',
                'title' => 'New Leave Application',
                'message' => "Employee {$user->name} applied for {$totalDays} day(s) leave ({$application->from_date->format('M d')} to {$application->to_date->format('M d')}).",
            ]);
        }

        return $application;
    }

    /**
     * Approve leave application.
     */
    public static function approveLeave(LeaveApplication $application, User $adminUser, ?string $remark = null)
    {
        $application->update([
            'status' => 'Approved',
            'approved_by' => $adminUser->id,
            'admin_remark' => $remark,
        ]);

        // Deduct leave balance
        $year = $application->from_date->year;
        $balance = LeaveBalance::where('employee_id', $application->employee_id)
            ->where('leave_type_id', $application->leave_type_id)
            ->where('year', $year)
            ->first();

        if ($balance) {
            $used = $balance->used_days + $application->total_days;
            $remaining = max(0, $balance->allowed_days - $used);
            $balance->update([
                'used_days' => $used,
                'remaining_days' => $remaining,
            ]);
        }

        // Notify Employee
        AppNotification::create([
            'user_id' => $application->employee->user_id,
            'type' => 'leave_status',
            'title' => 'Leave Application Approved',
            'message' => "Your leave request for {$application->total_days} day(s) from {$application->from_date->format('M d, Y')} has been approved.",
        ]);

        return $application;
    }

    /**
     * Reject leave application.
     */
    public static function rejectLeave(LeaveApplication $application, User $adminUser, ?string $remark = null)
    {
        $application->update([
            'status' => 'Rejected',
            'approved_by' => $adminUser->id,
            'admin_remark' => $remark,
        ]);

        // Notify Employee
        AppNotification::create([
            'user_id' => $application->employee->user_id,
            'type' => 'leave_status',
            'title' => 'Leave Application Rejected',
            'message' => "Your leave request from {$application->from_date->format('M d, Y')} was rejected. Remark: " . ($remark ?? 'None'),
        ]);

        return $application;
    }
}
