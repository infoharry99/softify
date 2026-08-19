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

        // 1. Text Search (Name, Email, Phone, Location, Job Title, Company Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('job_title', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        // 2. Filter by Job Title
        if ($request->filled('job_title')) {
            $query->where(function ($q) use ($request) {
                $q->where('job_title', 'like', "%{$request->job_title}%")
                  ->orWhere('skills', 'like', "%{$request->job_title}%");
            });
        }

        // 3. Filter by Skills
        if ($request->filled('skill')) {
            $query->where('skills', 'like', "%{$request->skill}%");
        }

        // 4. Filter by Job Type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // 5. Filter by Notice Period
        if ($request->filled('notice_period')) {
            $query->where('notice_period', $request->notice_period);
        }

        // 6. Filter by Expected CTC
        if ($request->filled('expected_ctc')) {
            $query->where('expected_ctc', '<=', (float) $request->expected_ctc);
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
            'phone' => ['required', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'location' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'skills' => 'required|string|min:2',
            'experience' => 'required|numeric|min:0|max:50',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string|max:2000',
        ], $this->validationMessages());

        $validated['company_name'] = $validated['company_name'] ?? 'General';
        $validated['status'] = $validated['status'] ?? 'Applied';

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
            'phone' => ['required', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'location' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'skills' => 'required|string|min:2',
            'experience' => 'required|numeric|min:0|max:50',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Applied,Screening,Interview Scheduled,Offered,Hired,Rejected',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'note' => 'nullable|string|max:2000',
        ], $this->validationMessages());

        $validated['company_name'] = $validated['company_name'] ?? ($candidate->company_name ?? 'General');
        $validated['status'] = $validated['status'] ?? ($candidate->status ?? 'Applied');

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
     * Download original or edited copy resume.
     */
    public function downloadResume(Candidate $candidate, Request $request)
    {
        $type = $request->get('type', 'original');
        $fileKey = ($type === 'edited' && $candidate->edited_resume) ? $candidate->edited_resume : $candidate->resume;

        if (!$fileKey || !Storage::disk('public')->exists($fileKey)) {
            return back()->with('error', 'Requested resume file not found on server.');
        }

        $suffix = ($type === 'edited') ? '_Edited_Copy' : '_Original';
        return Storage::disk('public')->download($fileKey, "Resume_{$candidate->name}{$suffix}.pdf");
    }

    /**
     * Preview candidate original or edited resume in browser.
     */
    public function previewResume(Candidate $candidate, Request $request)
    {
        $type = $request->get('type', 'original');
        $fileKey = ($type === 'edited' && $candidate->edited_resume) ? $candidate->edited_resume : $candidate->resume;

        if (!$fileKey || !Storage::disk('public')->exists($fileKey)) {
            return back()->with('error', 'Requested resume file not found on server.');
        }

        $filePath = Storage::disk('public')->path($fileKey);
        $mimeType = Storage::disk('public')->mimeType($fileKey) ?? 'application/pdf';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="Resume_' . $candidate->name . '_' . $type . '.pdf"',
        ]);
    }

    /**
     * Upload or update edited copy resume (Original resume remains 100% untouched!).
     */
    public function uploadEditedResume(Request $request, Candidate $candidate)
    {
        $request->validate([
            'edited_resume_file' => 'required|file|mimes:pdf,doc,docx|max:5120',
        ]);

        if ($candidate->edited_resume && Storage::disk('public')->exists($candidate->edited_resume)) {
            Storage::disk('public')->delete($candidate->edited_resume);
        }

        $path = $request->file('edited_resume_file')->store('candidate_edited_resumes', 'public');
        $candidate->update([
            'edited_resume' => $path,
            'last_updated_by' => auth()->id(),
        ]);

        ActivityLogger::log('Candidate Edited Resume Uploaded', "Uploaded edited copy resume for candidate '{$candidate->name}'", Candidate::class, $candidate->id);

        return back()->with('success', "Edited copy resume updated successfully for {$candidate->name}. Original resume remains safe and untouched.");
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
            'phone.regex' => 'Please enter a valid 10-digit mobile number starting with 6, 7, 8, or 9 (e.g. 9876543210 or +919876543210).',
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
