<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveApplication;
use App\Models\LeaveType;
use App\Services\LeaveService;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class AdminLeaveController extends Controller
{
    /**
     * Display leave requests dashboard and filters.
     */
    public function index(Request $request)
    {
        $pendingCount = LeaveApplication::where('status', 'Pending')->count();
        $approvedCount = LeaveApplication::where('status', 'Approved')->count();
        $rejectedCount = LeaveApplication::where('status', 'Rejected')->count();

        $query = LeaveApplication::with(['employee.user', 'leaveType', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->leave_type_id);
        }

        $applications = $query->latest()->paginate(12)->withQueryString();
        $leaveTypes = LeaveType::all();

        return view('admin.leave.index', compact(
            'pendingCount',
            'approvedCount',
            'rejectedCount',
            'applications',
            'leaveTypes'
        ));
    }

    /**
     * Approve leave application.
     */
    public function approve(Request $request, LeaveApplication $application)
    {
        $remark = $request->input('admin_remark');
        LeaveService::approveLeave($application, auth()->user(), $remark);

        ActivityLogger::log('Leave Approved', "Approved leave application #{$application->id} for {$application->employee->user->name}", LeaveApplication::class, $application->id);

        return back()->with('success', 'Leave application approved successfully.');
    }

    /**
     * Reject leave application.
     */
    public function reject(Request $request, LeaveApplication $application)
    {
        $remark = $request->input('admin_remark', 'Rejected by HR/Admin');
        LeaveService::rejectLeave($application, auth()->user(), $remark);

        ActivityLogger::log('Leave Rejected', "Rejected leave application #{$application->id} for {$application->employee->user->name}", LeaveApplication::class, $application->id);

        return back()->with('success', 'Leave application rejected.');
    }
}
