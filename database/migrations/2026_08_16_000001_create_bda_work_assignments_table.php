<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bda_work_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->date('assigned_date');
            $table->string('title')->default('Daily BDA Target & Schedule');
            $table->enum('status', ['Pending', 'In Progress', 'Done'])->default('Pending');

            // KPI Targets (Assigned by Team Lead)
            $table->integer('target_new_companies')->default(20);
            $table->integer('target_linkedin_requests')->default(30);
            $table->integer('target_emails')->default(30);
            $table->integer('target_cold_calls')->default(35);
            $table->integer('target_followups')->default(15);
            $table->integer('target_meetings')->default(3);

            // Achieved KPI Actuals (Logged by BDA Employee)
            $table->integer('achieved_new_companies')->default(0);
            $table->integer('achieved_linkedin_requests')->default(0);
            $table->integer('achieved_emails')->default(0);
            $table->integer('achieved_cold_calls')->default(0);
            $table->integer('achieved_followups')->default(0);
            $table->integer('achieved_meetings')->default(0);

            // Notes
            $table->text('employee_notes')->nullable();
            $table->text('lead_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bda_work_assignments');
    }
};
