<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CandidateImportController extends Controller
{
    /**
     * Bulk import candidates and automatically download & store resume files.
     * POST /api/v1/import-candidates
     */
    public function import(Request $request)
    {
        $candidates = $request->input('candidates', []);

        if (empty($candidates) && $request->has('name')) {
            $candidates = [$request->all()];
        }

        if (empty($candidates)) {
            return response()->json([
                'success' => false,
                'message' => 'No candidate data provided. Send a JSON array of candidates.'
            ], 400);
        }

        $importedCount = 0;
        $failedCount = 0;
        $errors = [];

        foreach ($candidates as $index => $data) {
            try {
                if (empty($data['email']) || empty($data['name'])) {
                    $errors[] = "Row {$index}: Missing required name or email.";
                    $failedCount++;
                    continue;
                }

                $email = trim($data['email']);
                $name = trim($data['name']);
                $phone = trim($data['phone'] ?? '9999999999');
                $location = trim($data['location'] ?? 'Remote');
                $skills = trim($data['skills'] ?? 'General');
                $experience = (float)($data['experience'] ?? 0);
                $jobType = in_array($data['job_type'] ?? '', ['Full Time', 'Part Time', 'Contract', 'Remote', 'Hybrid']) ? $data['job_type'] : 'Remote';
                $noticePeriod = in_array($data['notice_period'] ?? '', ['Immediate', '15 Days', '30 Days', '60 Days', '90 Days']) ? $data['notice_period'] : 'Immediate';
                $currentCtc = is_numeric($data['current_ctc'] ?? null) ? (float)$data['current_ctc'] : null;
                $expectedCtc = is_numeric($data['expected_ctc'] ?? null) ? (float)$data['expected_ctc'] : null;
                $status = in_array($data['status'] ?? '', ['Applied', 'Screening', 'Interview Scheduled', 'Offered', 'Hired', 'Rejected']) ? $data['status'] : 'Applied';
                $jobTitle = $data['job_title'] ?? null;
                $companyName = $data['company_name'] ?? 'General';
                $note = $data['note'] ?? null;

                // Handle Original Resume File Download / Store
                $resumePath = null;
                if (!empty($data['resume_url'])) {
                    $resumePath = $this->downloadAndSaveFile($data['resume_url'], 'candidate_resumes');
                } elseif (!empty($data['resume_base64'])) {
                    $resumePath = $this->saveBase64File($data['resume_base64'], 'candidate_resumes');
                } elseif (!empty($data['resume'])) {
                    $resumePath = $data['resume'];
                }

                // Handle Edited Copy Resume File Download / Store
                $editedResumePath = null;
                if (!empty($data['edited_resume_url']) || !empty($data['company_resume_url'])) {
                    $url = $data['edited_resume_url'] ?? $data['company_resume_url'];
                    $editedResumePath = $this->downloadAndSaveFile($url, 'candidate_edited_resumes');
                } elseif (!empty($data['edited_resume_base64']) || !empty($data['company_resume_base64'])) {
                    $base64 = $data['edited_resume_base64'] ?? $data['company_resume_base64'];
                    $editedResumePath = $this->saveBase64File($base64, 'candidate_edited_resumes');
                } elseif (!empty($data['edited_resume']) || !empty($data['company_resume'])) {
                    $editedResumePath = $data['edited_resume'] ?? $data['company_resume'];
                }

                $candidateData = [
                    'hr_id' => 1,
                    'company_name' => $companyName,
                    'job_title' => $jobTitle,
                    'name' => $name,
                    'phone' => $phone,
                    'location' => $location,
                    'skills' => $skills,
                    'experience' => $experience,
                    'job_type' => $jobType,
                    'notice_period' => $noticePeriod,
                    'current_ctc' => $currentCtc,
                    'expected_ctc' => $expectedCtc,
                    'status' => $status,
                    'note' => $note,
                    'is_highlighted' => (bool)($data['is_highlighted'] ?? false),
                ];

                if ($resumePath) {
                    $candidateData['resume'] = $resumePath;
                }
                if ($editedResumePath) {
                    $candidateData['edited_resume'] = $editedResumePath;
                }

                Candidate::updateOrCreate(
                    ['email' => $email],
                    $candidateData
                );

                $importedCount++;

            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Row {$index} ({$data['email']}): " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Import complete. Successfully imported {$importedCount} candidates. Failed: {$failedCount}.",
            'imported_count' => $importedCount,
            'failed_count' => $failedCount,
            'errors' => $errors,
        ]);
    }

    /**
     * Download file from URL and store in storage disk.
     */
    private function downloadAndSaveFile($fileUrl, $folder)
    {
        try {
            $response = Http::timeout(30)->get($fileUrl);
            if ($response->successful()) {
                $ext = pathinfo(parse_url($fileUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'pdf';
                $filename = Str::random(40) . '.' . $ext;
                $path = "{$folder}/{$filename}";
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            // Silently fallback or log
        }
        return null;
    }

    /**
     * Save base64 string as file in storage disk.
     */
    private function saveBase64File($base64String, $folder)
    {
        try {
            if (preg_match('/^data:application\/(pdf|msword|vnd\.openxmlformats-officedocument\.wordprocessingml\.document);base64,/', $base64String, $matches)) {
                $ext = $matches[1] === 'pdf' ? 'pdf' : 'docx';
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
            } else {
                $ext = 'pdf';
            }

            $binary = base64_decode($base64String);
            if ($binary) {
                $filename = Str::random(40) . '.' . $ext;
                $path = "{$folder}/{$filename}";
                Storage::disk('public')->put($path, $binary);
                return $path;
            }
        } catch (\Exception $e) {
            // Silently fallback or log
        }
        return null;
    }

    /**
     * Import candidate data + download resumes by pulling a remote API URL.
     * GET /run-candidate-import?api_url=https://old-system.com/api/candidates
     */
    public function importFromRemoteUrl(Request $request)
    {
        $apiUrl = $request->input('api_url');
        if (empty($apiUrl)) {
            return '<div style="font-family: system-ui, sans-serif; padding: 40px; background: #fff7ed; color: #c2410c; max-width: 650px; margin: 50px auto; border-radius: 16px; border: 1px solid #ffedd5;">' .
                   '<h2>📥 Automatic Remote Candidate & Resume Importer</h2>' .
                   '<p>Pass your old system API URL in the query parameter to automatically fetch all candidate profiles and download all PDF resume files into your system.</p>' .
                   '<form method="GET" action="/run-candidate-import">' .
                   '<input type="url" name="api_url" placeholder="https://old-system.com/api/candidates" required style="width: 80%; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 12px;"><br>' .
                   '<button type="submit" style="background: #00a884; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">Start Auto-Import →</button>' .
                   '</form>' .
                   '</div>';
        }

        try {
            $response = Http::timeout(60)->get($apiUrl);
            if (!$response->successful()) {
                return '<div style="color: #ef4444; padding: 30px; background: #fef2f2; border-radius: 12px; max-width: 600px; margin: 50px auto;">Failed to fetch remote API. HTTP Status: ' . $response->status() . '</div>';
            }

            $remoteData = $response->json();
            $candidatesList = $remoteData['candidates'] ?? $remoteData['data'] ?? $remoteData;

            $request->merge(['candidates' => $candidatesList]);
            $result = $this->import($request)->getData(true);

            return '<div style="font-family: system-ui, sans-serif; padding: 40px; background: #e6f7f3; color: #00a884; font-size: 1.1rem; border-radius: 16px; border: 2px solid #9ee5d4; max-width: 650px; margin: 50px auto; text-align: center;">' .
                   '✓ <strong>Import Completed!</strong><br><br>' .
                   'Successfully imported/updated <strong>' . ($result['imported_count'] ?? 0) . '</strong> candidates with resume files downloaded.<br>' .
                   'Failed: ' . ($result['failed_count'] ?? 0) . '<br><br>' .
                   '<a href="/admin/candidates" style="background: #00a884; color: #ffffff; padding: 12px 24px; text-decoration: none; border-radius: 10px; font-weight: 700; display: inline-block;">Go to Candidates Directory →</a>' .
                   '</div>';

        } catch (\Exception $e) {
            return '<div style="color: #ef4444; font-family: sans-serif; padding: 30px; background: #fef2f2; border: 1px solid #fca5a5; border-radius: 12px; max-width: 600px; margin: 50px auto;">' .
                   '<strong>Error running remote candidate import:</strong> ' . htmlspecialchars($e->getMessage()) .
                   '</div>';
        }
    }
}
