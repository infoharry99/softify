<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\MonthlyPayroll;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;

class EmployeeSalaryController extends Controller
{
    /**
     * Display my salary structure, revision history, and monthly slips.
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        $structure = $employee->salaryStructure;
        $histories = SalaryHistory::with('updater')
            ->where('employee_id', $employee->id)
            ->latest()
            ->get();

        $payrolls = MonthlyPayroll::where('employee_id', $employee->id)
            ->where('payment_status', 'Paid')
            ->latest()
            ->paginate(12);

        return view('employee.salary.index', compact('structure', 'histories', 'payrolls'));
    }

    /**
     * View printable salary slip.
     */
    public function slip(MonthlyPayroll $payroll)
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        if ($payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to salary slip.');
        }

        $payroll->load(['employee.user', 'employee.joiningDetail', 'processor']);
        $salary = $payroll->employee->salaryStructure;

        return view('admin.payroll.slip', compact('payroll', 'salary'));
    }

    /**
     * Download printable salary slip (PDF Print Trigger).
     */
    public function download(MonthlyPayroll $payroll)
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        if ($payroll->employee_id !== $employee->id) {
            abort(403, 'Unauthorized access to salary slip.');
        }

        $payroll->load(['employee.user', 'employee.joiningDetail', 'processor']);
        $salary = $payroll->employee->salaryStructure;
        $autoPrint = true;

        return view('admin.payroll.slip', compact('payroll', 'salary', 'autoPrint'));
    }
}
