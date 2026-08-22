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
        $baseQuery = LeaveApplication::whereHas('employee.user');
        if (!auth()->user()->hasRole('super-admin')) {
            $baseQuery->whereHas('employee.user', function ($q) {
                $q->whereDoesntHave('roles', function ($rq) {
                    $rq->where('slug', 'super-admin');
                });
            });
        }

        $pendingCount = (clone $baseQuery)->where('status', 'Pending')->count();
        $approvedCount = (clone $baseQuery)->where('status', 'Approved')->count();
        $rejectedCount = (clone $baseQuery)->where('status', 'Rejected')->count();

        $query = clone $baseQuery;
        $query->with(['employee.user', 'leaveType', 'approver']);

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

    /**
     * Download leave attachment document.
     */
    public function downloadAttachment(LeaveApplication $application)
    {
        if (!$application->attachment || !\Illuminate\Support\Facades\Storage::disk('public')->exists($application->attachment)) {
            return back()->with('error', 'Leave attachment document not found on server.');
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->download($application->attachment);
    }

    /**
     * Preview leave attachment document in browser.
     */
    public function previewAttachment(LeaveApplication $application)
    {
        if (!$application->attachment || !\Illuminate\Support\Facades\Storage::disk('public')->exists($application->attachment)) {
            return back()->with('error', 'Leave attachment document not found on server.');
        }

        $filePath = \Illuminate\Support\Facades\Storage::disk('public')->path($application->attachment);
        $mimeType = \Illuminate\Support\Facades\Storage::disk('public')->mimeType($application->attachment) ?? 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="Leave_Attachment_' . $application->id . '"',
        ]);
    }

    /**
     * Update employee leave balance quotas.
     */
    public function updateBalances(Request $request, \App\Models\Employee $employee)
    {
        $validated = $request->validate([
            'balances' => 'required|array',
        ]);

        foreach ($request->input('balances', []) as $leaveTypeId => $balData) {
            $allowed = (int) ($balData['allowed_days'] ?? 0);
            $used = (int) ($balData['used_days'] ?? 0);
            $remaining = max(0, $allowed - $used);

            \App\Models\LeaveBalance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveTypeId,
                    'year' => date('Y'),
                ],
                [
                    'allowed_days' => $allowed,
                    'used_days' => $used,
                    'remaining_days' => $remaining,
                ]
            );
        }

        ActivityLogger::log('Leave Balances Modified', "Updated leave quotas for {$employee->user->name}", \App\Models\Employee::class, $employee->id);

        return back()->with('success', "Leave balances for {$employee->user->name} updated successfully.");
    }

    /**
     * Update Company Leave Policy & Quotas dynamically.
     */
    public function updateCompanyPolicy(Request $request)
    {
        $validated = $request->validate([
            'types' => 'required|array',
            'types.*.name' => 'required|string|max:255',
            'types.*.days_allowed_per_year' => 'required|numeric|min:0|max:365',
            'types.*.is_paid' => 'nullable|boolean',
        ]);

        foreach ($request->input('types', []) as $id => $typeData) {
            $leaveType = LeaveType::find($id);
            if ($leaveType) {
                $leaveType->update([
                    'name' => trim($typeData['name']),
                    'slug' => \Illuminate\Support\Str::slug($typeData['name']),
                    'days_allowed_per_year' => (float) $typeData['days_allowed_per_year'],
                    'is_paid' => !empty($typeData['is_paid']),
                ]);
            }
        }

        // Sync updated policy across all active employees for current year
        LeaveService::syncCompanyPolicyToEmployees();

        ActivityLogger::log('Company Leave Policy Updated', "Updated global company leave quotas and synced active employee balances.", LeaveType::class, 0);

        return back()->with('success', 'Company Leave Policy updated and quotas synced across all employees successfully.');
    }

    /**
     * Add a new dynamic Leave Type to Company Policy.
     */
    public function storeLeaveType(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'days_allowed_per_year' => 'required|numeric|min:0|max:365',
            'is_paid' => 'nullable|boolean',
        ]);

        $leaveType = LeaveType::create([
            'name' => trim($validated['name']),
            'slug' => \Illuminate\Support\Str::slug($validated['name']),
            'days_allowed_per_year' => (float) $validated['days_allowed_per_year'],
            'is_paid' => !empty($validated['is_paid']),
        ]);

        LeaveService::syncCompanyPolicyToEmployees();

        ActivityLogger::log('New Leave Type Created', "Added new leave type '{$leaveType->name}' with {$leaveType->days_allowed_per_year} days/year", LeaveType::class, $leaveType->id);

        return back()->with('success', "New Leave Type '{$leaveType->name}' added to company policy successfully.");
    }

    /**
     * Delete a Leave Type from Company Policy.
     */
    public function destroyLeaveType(LeaveType $leaveType)
    {
        $name = $leaveType->name;

        // Check if applications exist
        if ($leaveType->applications()->exists()) {
            return back()->with('error', "Cannot delete Leave Type '{$name}' because employee leave applications exist for it.");
        }

        $leaveType->balances()->delete();
        $leaveType->delete();

        ActivityLogger::log('Leave Type Deleted', "Deleted leave type '{$name}' from company policy", LeaveType::class, 0);

        return back()->with('success', "Leave Type '{$name}' removed from company policy.");
    }
}
