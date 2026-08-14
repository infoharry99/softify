<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\LeaveApplication;
use App\Services\LeaveService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeLeaveController extends Controller
{
    /**
     * Display my leave balance & applications.
     */
    public function index()
    {
        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        LeaveService::initializeBalances($employee);

        $balances = LeaveBalance::with('leaveType')
            ->where('employee_id', $employee->id)
            ->where('year', Carbon::now()->year)
            ->get();

        $applications = LeaveApplication::with(['leaveType', 'approver'])
            ->where('employee_id', $employee->id)
            ->latest()
            ->paginate(10);

        $leaveTypes = LeaveType::all();

        return view('employee.leave.index', compact('balances', 'applications', 'leaveTypes'));
    }

    /**
     * Submit leave application.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
            'is_half_day' => 'nullable|boolean',
            'reason' => 'required|string|max:500',
            'attachment_file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $user = auth()->user();
        $employee = Employee::where('user_id', $user->id)->firstOrFail();

        if ($request->hasFile('attachment_file')) {
            $validated['attachment'] = $request->file('attachment_file')->store("leave_attachments/{$employee->id}", 'public');
        }

        LeaveService::applyLeave($employee, $validated);

        return back()->with('success', 'Your leave application has been submitted successfully for HR approval.');
    }
}
