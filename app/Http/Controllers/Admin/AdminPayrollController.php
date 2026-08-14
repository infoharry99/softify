<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MonthlyPayroll;
use App\Models\SalaryStructure;
use App\Services\PayrollService;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminPayrollController extends Controller
{
    /**
     * Display monthly payroll dashboard.
     */
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));

        $payrolls = MonthlyPayroll::with(['employee.user'])
            ->where('month', $month)
            ->get();

        $employees = Employee::with(['user', 'salaryStructure'])->get();

        $totalPayrollCost = $payrolls->sum('net_salary');
        $paidCount = $payrolls->where('payment_status', 'Paid')->count();
        $pendingCount = count($employees) - $paidCount;

        return view('admin.payroll.index', compact(
            'month',
            'payrolls',
            'employees',
            'totalPayrollCost',
            'paidCount',
            'pendingCount'
        ));
    }

    /**
     * Process payroll for a specific employee.
     */
    public function process(Request $request, Employee $employee)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        PayrollService::processMonthlyPayroll($employee, $month, auth()->user());

        ActivityLogger::log('Payroll Processed', "Processed payroll for {$employee->user->name} for month {$month}", Employee::class, $employee->id);

        return back()->with('success', "Payroll for {$employee->user->name} ({$month}) processed successfully.");
    }

    /**
     * Update employee salary structure and record history.
     */
    public function updateStructure(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'hra' => 'required|numeric|min:0',
            'conveyance' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'incentives' => 'nullable|numeric|min:0',
            'pf_deduction' => 'nullable|numeric|min:0',
            'esi_deduction' => 'nullable|numeric|min:0',
            'pt_deduction' => 'nullable|numeric|min:0',
            'tds_deduction' => 'nullable|numeric|min:0',
            'other_deductions' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'reason' => 'nullable|string|max:255',
        ]);

        PayrollService::updateSalaryStructure($employee, $validated, auth()->user(), $validated['reason'] ?? 'Salary Revision');

        ActivityLogger::log('Salary Revised', "Updated salary structure for {$employee->user->name}", Employee::class, $employee->id);

        return back()->with('success', 'Salary structure updated and revision history recorded.');
    }

    /**
     * Display printable salary slip.
     */
    public function slip(MonthlyPayroll $payroll)
    {
        $payroll->load(['employee.user', 'employee.joiningDetail', 'processor']);
        $salary = $payroll->employee->salaryStructure;

        return view('admin.payroll.slip', compact('payroll', 'salary'));
    }
}
