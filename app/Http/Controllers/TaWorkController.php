<?php

namespace App\Http\Controllers;

use App\Models\TaWorkAssignment;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class TaWorkController extends Controller
{
    /**
     * Display listing of Talent Acquisition work assignments.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isLead = $user->hasRole('ta-team-lead') || $user->hasRole('super-admin') || $user->hasRole('admin');

        $query = TaWorkAssignment::has('assignee')->with(['assigner', 'assignee']);

        if (!$isLead) {
            // Standard TA Employee sees only their own work assignments
            $query->where('assigned_to', $user->id);
        } else {
            // Lead can filter by employee
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

        // Get list of TA employees for Lead's assignment dropdown
        $taEmployees = User::where(function ($q) {
            $q->whereHas('roles', function ($rq) {
                $rq->whereIn('slug', ['talent-acquisition', 'ta-team-lead']);
            })->orWhere('department', 'Talent')
              ->orWhere('department', 'Recruitment');
        })->where('status', 'active')->get();

        return view('ta.work.index', compact('assignments', 'taEmployees', 'isLead'));
    }

    /**
     * Assign new job requisition / work to a TA employee (Team Lead / Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'assigned_date' => 'required|date',
            'job_title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'budget' => 'required|string|max:255',
            'duration' => 'nullable|string|max:255',
            'job_description' => 'required|string',
            'target_profiles' => 'required|integer|min:1',
            'lead_notes' => 'nullable|string',
        ]);

        $assignment = TaWorkAssignment::create([
            'assigned_by' => auth()->id(),
            'assigned_to' => $validated['assigned_to'],
            'assigned_date' => $validated['assigned_date'],
            'job_title' => $validated['job_title'],
            'location' => $validated['location'],
            'experience' => $validated['experience'],
            'budget' => $validated['budget'],
            'duration' => $validated['duration'] ?? 'Full Time',
            'job_description' => $validated['job_description'],
            'target_profiles' => $validated['target_profiles'],
            'profiles_sourced' => 0,
            'status' => 'Pending',
            'lead_notes' => $validated['lead_notes'] ?? null,
        ]);

        $assignee = User::find($validated['assigned_to']);
        ActivityLogger::log('TA Job Requirement Assigned', "Assigned job '{$assignment->job_title}' to TA Employee {$assignee->name}", TaWorkAssignment::class, $assignment->id);

        return back()->with('success', "Job Requirement '{$assignment->job_title}' assigned successfully to {$assignee->name}.");
    }

    /**
     * Display specific TA work details (Job Specs & Description).
     */
    public function show(TaWorkAssignment $task)
    {
        $user = auth()->user();
        $isLead = $user->hasRole('ta-team-lead') || $user->hasRole('super-admin') || $user->hasRole('admin');

        if (!$isLead && $task->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access to TA work assignment.');
        }

        $task->load(['assigner', 'assignee']);

        return view('ta.work.show', compact('task', 'isLead'));
    }

    /**
     * TA Employee updates profiles sourced, work status, and employee notes.
     */
    public function updateEmployee(Request $request, TaWorkAssignment $task)
    {
        $user = auth()->user();
        if ($task->assigned_to !== $user->id && !$user->hasRole('super-admin') && !$user->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Done',
            'profiles_sourced' => 'required|integer|min:0',
            'employee_notes' => 'nullable|string',
        ]);

        $task->update([
            'status' => $validated['status'],
            'profiles_sourced' => $validated['profiles_sourced'],
            'employee_notes' => $validated['employee_notes'] ?? null,
        ]);

        ActivityLogger::log('TA Work Updated by Employee', "Updated status to {$validated['status']} & sourced {$validated['profiles_sourced']} profiles for '{$task->job_title}'", TaWorkAssignment::class, $task->id);

        return back()->with('success', 'Your sourcing progress, work status, and report notes have been updated.');
    }

    /**
     * TA Team Lead / Admin updates status, target profiles, and lead review notes.
     */
    public function updateLead(Request $request, TaWorkAssignment $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,In Progress,Done',
            'target_profiles' => 'required|integer|min:1',
            'lead_notes' => 'nullable|string',
        ]);

        $task->update([
            'status' => $validated['status'],
            'target_profiles' => $validated['target_profiles'],
            'lead_notes' => $validated['lead_notes'] ?? null,
        ]);

        ActivityLogger::log('TA Work Reviewed by Lead', "Updated status to {$validated['status']} and added notes for '{$task->job_title}'", TaWorkAssignment::class, $task->id);

        return back()->with('success', 'Job assignment status and review notes updated.');
    }

    /**
     * Delete a TA work assignment.
     */
    public function destroy(TaWorkAssignment $task)
    {
        $task->delete();
        return back()->with('success', 'TA Job assignment deleted successfully.');
    }
}
