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

        DB::table('students')->orderBy('id')->chunk(500, function ($oldStudents) use ($validUserIds, $fallbackHrId, &$migratedCount) {
            $insertBatch = [];

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

                DB::table('candidates')->updateOrInsert(
                    ['email' => $email],
                    [
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
                        'resume' => $std->resume ?? null,
                        'edited_resume' => $std->company_resume ?? null,
                        'note' => $std->note ?? null,
                        'is_highlighted' => (bool)($std->is_highlighted ?? false),
                        'created_at' => $std->created_at ?? now(),
                        'updated_at' => $std->updated_at ?? now(),
                    ]
                );

                $migratedCount++;
            }
        });

        Schema::dropIfExists('students');

        $this->info("✓ Success! Migration completed. Processed {$migratedCount} candidates into candidates table. Temporary students table dropped. Total Candidates in new system: " . Candidate::count());
        return 0;
    }
}
