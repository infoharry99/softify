<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Candidate;

class MigrateOldStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidates:migrate-old-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate candidate data & resume file paths from old Student model to Candidate model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Old System Candidate Data & Resume File Migration...');

        if (!Schema::hasTable('students')) {
            $this->error('The "students" table does not exist in the database.');
            return 1;
        }

        $oldStudents = DB::table('students')->get();
        $total = count($oldStudents);
        $validUserIds = \App\Models\User::pluck('id')->toArray();
        $fallbackHrId = $validUserIds[0] ?? 1;

        $migratedCount = 0;
        $updatedCount = 0;

        foreach ($oldStudents as $std) {
            $email = trim($std->email);
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            $hrId = in_array((int)($std->hr_id ?? 0), $validUserIds) ? (int)$std->hr_id : $fallbackHrId;

            // Map Job Type Enum
            $jobType = match ($std->job_type ?? '') {
                'Onsite' => 'Full Time',
                'Remote' => 'Remote',
                'Hybrid' => 'Hybrid',
                'Part Time' => 'Part Time',
                'Contract' => 'Contract',
                default => 'Remote',
            };

            // Map Notice Period Enum
            $rawNotice = $std->notice_period ?? '';
            $noticePeriod = match (true) {
                str_contains($rawNotice, 'Immediate') => 'Immediate',
                str_contains($rawNotice, '15') => '15 Days',
                str_contains($rawNotice, '30') => '30 Days',
                str_contains($rawNotice, '60') => '60 Days',
                str_contains($rawNotice, '90') => '90 Days',
                default => 'Immediate',
            };

            $currentCtc = is_numeric($std->current_ctc) ? (float)$std->current_ctc : null;
            $expectedCtc = is_numeric($std->expected_ctc) ? (float)$std->expected_ctc : null;
            $experience = is_numeric($std->experience) ? (float)$std->experience : 0.0;

            // Auto Download Original Resume from old platform (sale.talentifyy.com) if not present locally
            $resumePath = $std->resume ?? null;
            if ($resumePath && !\Illuminate\Support\Facades\Storage::disk('public')->exists($resumePath)) {
                $oldUrl = "https://sale.talentifyy.com/storage/" . ltrim($resumePath, '/');
                try {
                    $res = \Illuminate\Support\Facades\Http::timeout(15)->get($oldUrl);
                    if ($res->successful()) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($resumePath, $res->body());
                    }
                } catch (\Exception $e) {}
            }

            // Auto Download Company Edited Copy Resume from old platform (sale.talentifyy.com) if not present locally
            $editedResumePath = $std->company_resume ?? null;
            if ($editedResumePath && !\Illuminate\Support\Facades\Storage::disk('public')->exists($editedResumePath)) {
                $oldUrl = "https://sale.talentifyy.com/storage/" . ltrim($editedResumePath, '/');
                try {
                    $res = \Illuminate\Support\Facades\Http::timeout(15)->get($oldUrl);
                    if ($res->successful()) {
                        \Illuminate\Support\Facades\Storage::disk('public')->put($editedResumePath, $res->body());
                    }
                } catch (\Exception $e) {}
            }

            $data = [
                'hr_id' => $hrId,
                'company_name' => 'General',
                'job_title' => null,
                'name' => trim($std->name),
                'phone' => trim($std->phone),
                'location' => trim($std->location),
                'skills' => trim($std->skills),
                'experience' => $experience,
                'job_type' => $jobType,
                'notice_period' => $noticePeriod,
                'current_ctc' => $currentCtc,
                'expected_ctc' => $expectedCtc,
                'status' => 'Applied',
                'resume' => $resumePath,
                'edited_resume' => $editedResumePath,
                'note' => $std->note ?? null,
                'is_highlighted' => (bool)($std->is_highlighted ?? false),
                'created_at' => $std->created_at ?? now(),
                'updated_at' => $std->updated_at ?? now(),
            ];

            $candidate = Candidate::where('email', $email)->first();
            if ($candidate) {
                $candidate->update($data);
                $updatedCount++;
            } else {
                Candidate::create(array_merge(['email' => $email], $data));
                $migratedCount++;
            }
        }

        $this->info("✓ Success! Migration completed. Created: {$migratedCount}, Updated: {$updatedCount}. Total Candidates in new system: " . Candidate::count());
        return 0;
    }
}
