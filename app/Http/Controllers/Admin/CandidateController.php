<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.candidates.partials.table', compact('candidates'))->render(),
                'total' => $candidates->total(),
                'first_item' => $candidates->firstItem() ?? 0,
                'last_item' => $candidates->lastItem() ?? 0,
            ]);
        }

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
     * Show Quick Candidate Entry form for Data Entry Operators.
     */
    public function quickCreate()
    {
        $todayCount = Candidate::whereDate('created_at', today())->count();
        $hiringCompanies = Candidate::whereNotNull('company_name')->pluck('company_name')->unique();

        return view('admin.candidates.quick_create', compact('todayCount', 'hiringCompanies'));
    }

    /**
     * Fast real-time check for duplicate email or phone number.
     */
    public function checkDuplicate(Request $request)
    {
        $email = $request->email;
        $phone = $request->phone;

        if (!$email && !$phone) {
            return response()->json(['exists' => false]);
        }

        $duplicate = Candidate::where(function ($q) use ($email, $phone) {
            if ($email) $q->where('email', $email);
            if ($phone) $q->orWhere('phone', $phone);
        })->first();

        if ($duplicate) {
            return response()->json([
                'exists' => true,
                'candidate' => [
                    'id' => $duplicate->id,
                    'name' => $duplicate->name,
                    'email' => $duplicate->email,
                    'phone' => $duplicate->phone,
                    'company_name' => $duplicate->company_name,
                    'show_url' => route('admin.candidates.show', $duplicate->id),
                ]
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Store candidate record with robust validation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255|unique:candidates,email',
            'phone' => 'required|string|min:7|max:20|regex:/^[0-9+\s\-()]{7,20}$/',
            'location' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'skills' => 'required|string|min:2',
            'experience' => 'required|numeric|min:0|max:50',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'status' => 'required|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string|max:2000',
        ], $this->validationMessages());

        if ($request->hasFile('resume_file')) {
            $validated['resume'] = $request->file('resume_file')->store('candidate_resumes', 'public');
        }

        $validated['hr_id'] = auth()->id();
        $validated['last_updated_by'] = auth()->id();

        $candidate = Candidate::create($validated);

        ActivityLogger::log('Candidate Added', "Added new candidate '{$candidate->name}' for company '{$candidate->company_name}'", Candidate::class, $candidate->id);

        if ($request->ajax()) {
            $todayCount = Candidate::whereDate('created_at', today())->count();
            return response()->json([
                'success' => true,
                'message' => "✓ Candidate '{$candidate->name}' saved successfully",
                'candidate' => $candidate,
                'today_count' => $todayCount,
            ]);
        }

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
     * Update candidate record with robust validation.
     */
    public function update(Request $request, Candidate $candidate)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('candidates')->ignore($candidate->id)],
            'phone' => 'required|string|min:7|max:20|regex:/^[0-9+\s\-()]{7,20}$/',
            'location' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'skills' => 'required|string|min:2',
            'experience' => 'required|numeric|min:0|max:50',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'status' => 'required|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string|max:2000',
        ], $this->validationMessages());

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
     * Preview candidate resume in browser.
     */
    public function previewResume(Candidate $candidate)
    {
        if (!$candidate->resume || !Storage::disk('public')->exists($candidate->resume)) {
            return back()->with('error', 'Resume file not found on server.');
        }

        $filePath = Storage::disk('public')->path($candidate->resume);
        $mimeType = Storage::disk('public')->mimeType($candidate->resume) ?? 'application/pdf';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="Resume_' . $candidate->name . '.pdf"',
        ]);
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

    /**
     * Human-readable custom validation messages.
     */
    private function validationMessages(): array
    {
        return [
            'name.required' => 'Candidate full name is required.',
            'name.min' => 'Candidate name must be at least 2 characters long.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address (e.g. candidate@example.com).',
            'email.unique' => 'A candidate with this email address already exists in the ATS system.',
            'phone.required' => 'Contact phone number is required.',
            'phone.regex' => 'Please enter a valid phone number (digits, +, -, and spaces allowed).',
            'location.required' => 'Candidate location / city is required.',
            'company_name.required' => 'Client hiring company name is required.',
            'skills.required' => 'Please specify key candidate skills (comma separated).',
            'experience.required' => 'Total years of experience is required.',
            'experience.numeric' => 'Experience must be a valid number.',
            'experience.min' => 'Experience cannot be negative.',
            'resume_file.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume_file.max' => 'Resume file size cannot exceed 5 MB.',
        ];
    }
}
