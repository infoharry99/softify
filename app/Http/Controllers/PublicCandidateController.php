<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PublicCandidateController extends Controller
{
    /**
     * Display the public candidate application form.
     */
    public function create(Request $request)
    {
        $source = strtolower(trim($request->input('source', 'direct')));
        $defaultJobTitle = trim($request->input('job', $request->input('role', '')));

        // Known popular job types and notice periods
        $jobTypes = ['Full Time', 'Part Time', 'Contract', 'Remote', 'Hybrid'];
        $noticePeriods = ['Immediate', '15 Days', '30 Days', '60 Days', '90 Days'];

        return view('careers.apply', compact('source', 'defaultJobTitle', 'jobTypes', 'noticePeriods'));
    }

    /**
     * Handle submission of the public candidate application form.
     */
    public function store(Request $request)
    {
        // 1. Anti-spam honeypot verification
        if ($request->filled('website_url_check') || $request->filled('bot_verify_token')) {
            return redirect()->route('careers.success')->with('name', 'Applicant');
        }

        // 2. Validate user input
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'string', 'regex:/^(\+91[\-\s]?)?[6789]\d{9}$/'],
            'location' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'skills' => 'required|string|min:2|max:1000',
            'experience' => 'required|numeric|min:0|max:50',
            'job_type' => 'required|in:Full Time,Part Time,Contract,Remote,Hybrid',
            'notice_period' => 'required|in:Immediate,15 Days,30 Days,60 Days,90 Days',
            'current_ctc' => 'nullable|numeric|min:0',
            'expected_ctc' => 'nullable|numeric|min:0',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'cover_note' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Please provide your full name.',
            'name.min' => 'Full name must have at least 2 characters.',
            'email.required' => 'Please provide your active email address.',
            'email.email' => 'Please provide a valid email format (e.g. name@domain.com).',
            'phone.required' => 'Please provide your 10-digit mobile number.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
            'location.required' => 'Please enter your current city or location.',
            'skills.required' => 'Please list your key skills or technical competencies.',
            'experience.required' => 'Please provide your total experience in years (enter 0 for Fresher).',
            'job_type.required' => 'Please select your preferred work mode.',
            'notice_period.required' => 'Please select your current notice period.',
            'resume_file.mimes' => 'Resume attachment must be in PDF, DOC, or DOCX format.',
            'resume_file.max' => 'Resume attachment cannot exceed 5MB.',
        ]);

        $source = strtolower(trim($request->input('source', 'direct')));
        $sourceLabel = match($source) {
            'linkedin' => 'LinkedIn Job Post / InMail',
            'whatsapp' => 'WhatsApp Sharing Link',
            'instagram' => 'Instagram Bio / Story Link',
            'telegram' => 'Telegram Channel',
            'facebook' => 'Facebook / Social',
            'email' => 'Direct Email Link',
            default => 'Public Career Form',
        };

        // 3. Handle Resume Upload
        $resumePath = null;
        if ($request->hasFile('resume_file')) {
            $resumePath = $request->file('resume_file')->store('candidate_resumes', 'public');
        }

        // 4. Check for existing candidate profile by email or phone
        $existing = Candidate::where('email', $validated['email'])
            ->orWhere('phone', $validated['phone'])
            ->first();

        // Build note string
        $noteParts = [];
        $noteParts[] = "Application Source: {$sourceLabel}";
        if (!empty($validated['linkedin_url'])) {
            $noteParts[] = "LinkedIn: " . $validated['linkedin_url'];
        }
        if (!empty($validated['portfolio_url'])) {
            $noteParts[] = "Portfolio/GitHub: " . $validated['portfolio_url'];
        }
        if (!empty($validated['cover_note'])) {
            $noteParts[] = "Applicant Note: " . $validated['cover_note'];
        }
        $formattedNote = implode(" | ", $noteParts);

        if ($existing) {
            // Update existing candidate profile with newest credentials
            $updateData = [
                'name' => $validated['name'],
                'location' => $validated['location'],
                'job_title' => $validated['job_title'] ?: $existing->job_title,
                'skills' => $validated['skills'],
                'experience' => $validated['experience'],
                'job_type' => $validated['job_type'],
                'notice_period' => $validated['notice_period'],
            ];

            if (isset($validated['current_ctc']) && $validated['current_ctc'] !== null) {
                $updateData['current_ctc'] = $validated['current_ctc'];
            }
            if (isset($validated['expected_ctc']) && $validated['expected_ctc'] !== null) {
                $updateData['expected_ctc'] = $validated['expected_ctc'];
            }

            if ($resumePath) {
                // Delete previous file if present
                if ($existing->resume && Storage::disk('public')->exists($existing->resume)) {
                    Storage::disk('public')->delete($existing->resume);
                }
                $updateData['resume'] = $resumePath;
            }

            // Append note history
            $newNote = trim(($existing->note ? $existing->note . "\n\n" : "") . "[" . now()->format('d M Y, h:i A') . "] " . $formattedNote);
            $updateData['note'] = Str::limit($newNote, 2000);
            $updateData['status'] = 'Applied'; // reset status to applied for HR review

            $existing->update($updateData);

            ActivityLogger::log(
                'Public Candidate Re-applied',
                "Candidate '{$existing->name}' updated their application via {$sourceLabel}",
                Candidate::class,
                $existing->id
            );

            return redirect()->route('careers.success')->with([
                'candidate_name' => $existing->name,
                'job_title' => $existing->job_title,
                'is_update' => true,
            ]);
        }

        // 5. Create new Candidate record
        $candidateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'location' => $validated['location'],
            'company_name' => 'Direct Applicant',
            'job_title' => $validated['job_title'] ?: 'Applicant',
            'skills' => $validated['skills'],
            'experience' => $validated['experience'],
            'job_type' => $validated['job_type'],
            'notice_period' => $validated['notice_period'],
            'current_ctc' => $validated['current_ctc'] ?? null,
            'expected_ctc' => $validated['expected_ctc'] ?? null,
            'status' => 'Applied',
            'resume' => $resumePath,
            'note' => $formattedNote,
            'hr_id' => null,
            'last_updated_by' => null,
        ];

        $candidate = Candidate::create($candidateData);

        ActivityLogger::log(
            'Public Candidate Applied',
            "New candidate '{$candidate->name}' applied via {$sourceLabel}",
            Candidate::class,
            $candidate->id
        );

        return redirect()->route('careers.success')->with([
            'candidate_name' => $candidate->name,
            'job_title' => $candidate->job_title,
            'is_update' => false,
        ]);
    }

    /**
     * Display thank-you / confirmation page after successful submission.
     */
    public function success()
    {
        $candidateName = session('candidate_name', 'Applicant');
        $jobTitle = session('job_title', '');
        $isUpdate = session('is_update', false);

        return view('careers.success', compact('candidateName', 'jobTitle', 'isUpdate'));
    }
}
