<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    /**
     * Display candidate directory with rich multi-filter options.
     */
    public function index(Request $request)
    {
        $query = Candidate::with(['hr', 'updatedBy']);

        // 1. Text Search (Name, Email, Phone, Location, Company Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Skills
        if ($request->filled('skill')) {
            $query->where('skills', 'like', "%{$request->skill}%");
        }

        // 3. Filter by Experience Range
        if ($request->filled('min_exp')) {
            $query->where('experience', '>=', (float) $request->min_exp);
        }
        if ($request->filled('max_exp')) {
            $query->where('experience', '<=', (float) $request->max_exp);
        }

        // 4. Filter by Job Type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // 5. Filter by Notice Period
        if ($request->filled('notice_period')) {
            $query->where('notice_period', $request->notice_period);
        }

        // 6. Filter by Status / Pipeline Stage
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 7. Filter by Hiring Company
        if ($request->filled('company_name')) {
            $query->where('company_name', 'like', "%{$request->company_name}%");
        }

        // 8. Filter by Expected CTC Max
        if ($request->filled('max_expected_ctc')) {
            $query->where('expected_ctc', '<=', (float) $request->max_expected_ctc);
        }

        $candidates = $query->latest()->paginate(10)->withQueryString();

        // Get unique hiring companies list for multi-select dropdown filter
        $hiringCompanies = Candidate::whereNotNull('company_name')->pluck('company_name')->unique();

        return view('admin.candidates.index', compact('candidates', 'hiringCompanies'));
    }

    /**
     * Show form for adding a new candidate.
     */
    public function create()
    {
        return view('admin.candidates.create');
    }

    /**
     * Store candidate record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'skills' => 'required|string',
            'experience' => 'required|numeric|min:0',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('resume_file')) {
            $validated['resume'] = $request->file('resume_file')->store('candidate_resumes', 'public');
        }

        $validated['hr_id'] = auth()->id();
        $validated['last_updated_by'] = auth()->id();

        $candidate = Candidate::create($validated);

        ActivityLogger::log('Candidate Added', "Added new candidate '{$candidate->name}' for company '{$candidate->company_name}'", Candidate::class, $candidate->id);

        return redirect()->route('admin.candidates.index')
            ->with('success', "Candidate '{$candidate->name}' registered successfully.");
    }

    /**
     * Display candidate profile details.
     */
    public function show(Candidate $candidate)
    {
        $candidate->load(['hr', 'updatedBy']);
        return view('admin.candidates.show', compact('candidate'));
    }

    /**
     * Show edit candidate form.
     */
    public function edit(Candidate $candidate)
    {
        return view('admin.candidates.edit', compact('candidate'));
    }

    /**
     * Update candidate record.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'location' => 'required|string|max:255',
            'skills' => 'required|string',
            'experience' => 'required|numeric|min:0',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'company_name' => 'nullable|string|max:255',
            'status' => 'required|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string',
        ]);

        if ($request->hasFile('resume_file')) {
            if ($candidate->resume && Storage::disk('public')->exists($candidate->resume)) {
                Storage::disk('public')->delete($candidate->resume);
            }
            $validated['resume'] = $request->file('resume_file')->store('candidate_resumes', 'public');
        }

        $validated['last_updated_by'] = auth()->id();

        $candidate->update($validated);

        ActivityLogger::log('Candidate Updated', "Updated candidate profile for '{$candidate->name}'", Candidate::class, $candidate->id);

        return redirect()->route('admin.candidates.show', $candidate->id)
            ->with('success', "Candidate profile updated successfully.");
    }

    /**
     * Update candidate hiring status / pipeline stage.
     */
    public function updateStatus(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'status' => 'required|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
        ]);

        $candidate->update([
            'status' => $validated['status'],
            'last_updated_by' => auth()->id(),
        ]);

        ActivityLogger::log('Candidate Status Changed', "Changed status of candidate '{$candidate->name}' to '{$validated['status']}'", Candidate::class, $candidate->id);

        return back()->with('success', "Candidate stage updated to {$validated['status']}.");
    }

    /**
     * Download candidate resume.
     */
    public function downloadResume(Candidate $candidate)
    {
        if (!$candidate->resume || !Storage::disk('public')->exists($candidate->resume)) {
            return back()->with('error', 'Resume file not found on server.');
        }

        return Storage::disk('public')->download($candidate->resume, "Resume_{$candidate->name}.pdf");
    }

    /**
     * Delete candidate record.
     */
    public function destroy(Candidate $candidate)
    {
        if ($candidate->resume && Storage::disk('public')->exists($candidate->resume)) {
            Storage::disk('public')->delete($candidate->resume);
        }

        $name = $candidate->name;
        $candidate->delete();

        ActivityLogger::log('Candidate Deleted', "Deleted candidate '{$name}'");

        return redirect()->route('admin.candidates.index')
            ->with('success', "Candidate '{$name}' removed from system.");
    }
}
