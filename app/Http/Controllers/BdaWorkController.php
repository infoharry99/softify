<?php

namespace App\Http\Controllers;

use App\Models\BdaWorkAssignment;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class BdaWorkController extends Controller
{
    /**
     * Display listing of BDA work assignments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isLead = $user->hasRole('bda-team-lead') || $user->hasRole('super-admin') || $user->hasRole('admin');

        $query = BdaWorkAssignment::has('assignee')->with(['assigner', 'assignee']);

        if (!$isLead) {
            // Standard BDA Employee sees only their own work assignments
            $query->where('assigned_to', $user->id);
        } else {
            // Lead can filter by employee or date
            if ($request->filled('assigned_to')) {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        if ($request->filled('date')) {
            $query->whereDate('assigned_date', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $assignments = $query->latest('assigned_date')->paginate(12)->withQueryString();

        // Get list of BDA employees for Lead's assignment dropdown
        $bdaEmployees = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->whereIn('slug', ['bda', 'bda-team-lead']);
            })->orWhere('department', 'BDA')
              ->orWhere('department', 'Sales');
        })->where('status', 'active')->get();

        return view('bda.work.index', compact('assignments', 'bdaEmployees', 'isLead'));
    }

    /**
     * Assign new daily work to a BDA employee (Team Lead / Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'target_new_companies' => 'required|integer|min:0',
            'target_linkedin_requests' => 'required|integer|min:0',
            'target_emails' => 'required|integer|min:0',
            'target_cold_calls' => 'required|integer|min:0',
            'target_followups' => 'required|integer|min:0',
            'target_meetings' => 'required|integer|min:0',
            'lead_notes' => 'nullable|string',
        ]);

        $assignment = BdaWorkAssignment::create([
            'assigned_by' => auth()->id(),
            'assigned_to' => $validated['assigned_to'],
            'assigned_date' => $validated['assigned_date'],
            'title' => $validated['title'] ?? 'Daily BDA Work & Targets',
            'status' => 'Pending',
            'target_new_companies' => $validated['target_new_companies'],
            'target_linkedin_requests' => $validated['target_linkedin_requests'],
            'target_emails' => $validated['target_emails'],
            'target_cold_calls' => $validated['target_cold_calls'],
            'target_followups' => $validated['target_followups'],
            'target_meetings' => $validated['target_meetings'],
            'lead_notes' => $validated['lead_notes'] ?? null,
        ]);

        $assignee = User::find($validated['assigned_to']);
        ActivityLogger::log('BDA Work Assigned', "Assigned BDA daily target for {$assignment->assigned_date->format('M d, Y')} to {$assignee->name}", BdaWorkAssignment::class, $assignment->id);

        return back()->with('success', "Daily work assigned successfully to {$assignee->name}.");
    }

    /**
     * Display specific BDA work details (schedule & KPI matrix).
     */
    public function show(BdaWorkAssignment $task)
    {
        $user = auth()->user();
        $isLead = $user->hasRole('bda-team-lead') || $user->hasRole('super-admin') || $user->hasRole('admin');

        if (!$isLead && $task->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access to work assignment.');
        }

        $task->load(['assigner', 'assignee']);

        return view('bda.work.show', compact('task', 'isLead'));
    }

    /**
     * BDA Employee updates actual achieved KPIs, status, and report notes.
     */
    public function updateEmployee(Request $request, BdaWorkAssignment $task)
    {
        $user = auth()->user();
        if ($task->assigned_to !== $user->id && !$user->hasRole('super-admin') && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Done',
            'achieved_new_companies' => 'required|integer|min:0',
            'achieved_linkedin_requests' => 'required|integer|min:0',
            'achieved_emails' => 'required|integer|min:0',
            'achieved_cold_calls' => 'required|integer|min:0',
            'achieved_followups' => 'required|integer|min:0',
            'achieved_meetings' => 'required|integer|min:0',
            'employee_notes' => 'nullable|string',
        ]);

        $task->update([
            'status' => $validated['status'],
            'achieved_new_companies' => $validated['achieved_new_companies'],
            'achieved_linkedin_requests' => $validated['achieved_linkedin_requests'],
            'achieved_emails' => $validated['achieved_emails'],
            'achieved_cold_calls' => $validated['achieved_cold_calls'],
            'achieved_followups' => $validated['achieved_followups'],
            'achieved_meetings' => $validated['achieved_meetings'],
            'employee_notes' => $validated['employee_notes'] ?? null,
        ]);

        ActivityLogger::log('BDA Work Updated by Employee', "Updated daily work status to {$validated['status']} for assignment #{$task->id}", BdaWorkAssignment::class, $task->id);

        return back()->with('success', 'Your daily achievements and work status have been updated successfully.');
    }

    /**
     * BDA Team Lead / Admin updates status and lead review notes.
     */
    public function updateLead(Request $request, BdaWorkAssignment $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Done',
            'lead_notes' => 'nullable|string',
        ]);

        $task->update([
            'status' => $validated['status'],
            'lead_notes' => $validated['lead_notes'] ?? null,
        ]);

        ActivityLogger::log('BDA Work Reviewed by Lead', "Updated status to {$validated['status']} and added notes for assignment #{$task->id}", BdaWorkAssignment::class, $task->id);

        return back()->with('success', 'Work assignment status and review notes updated.');
    }

    /**
     * Edit / Update full BDA work assignment targets (Team Lead / Admin).
     */
    public function updateTask(Request $request, BdaWorkAssignment $task)
    {
        $user = auth()->user();
        $isLead = $user->hasRole('bda-team-lead') || $user->hasRole('super-admin') || $user->hasRole('admin');
        if (!$isLead) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'title' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,In Progress,Done',
            'target_new_companies' => 'required|integer|min:0',
            'target_linkedin_requests' => 'required|integer|min:0',
            'target_emails' => 'required|integer|min:0',
            'target_cold_calls' => 'required|integer|min:0',
            'target_followups' => 'required|integer|min:0',
            'target_meetings' => 'required|integer|min:0',
            'lead_notes' => 'nullable|string',
        ]);

        $task->update([
            'assigned_to' => $validated['assigned_to'],
            'assigned_date' => $validated['assigned_date'],
            'title' => $validated['title'] ?? 'Daily BDA Work & Targets',
            'status' => $validated['status'],
            'target_new_companies' => $validated['target_new_companies'],
            'target_linkedin_requests' => $validated['target_linkedin_requests'],
            'target_emails' => $validated['target_emails'],
            'target_cold_calls' => $validated['target_cold_calls'],
            'target_followups' => $validated['target_followups'],
            'target_meetings' => $validated['target_meetings'],
            'lead_notes' => $validated['lead_notes'] ?? null,
        ]);

        ActivityLogger::log('BDA Work Assignment Edited', "Edited BDA daily work assignment #{$task->id}", BdaWorkAssignment::class, $task->id);

        return back()->with('success', 'BDA daily work assignment updated successfully.');
    }

    /**
     * Delete a BDA work assignment.
     */
    public function destroy(BdaWorkAssignment $task)
    {
        $task->delete();
        return back()->with('success', 'Work assignment deleted successfully.');
    }
}
