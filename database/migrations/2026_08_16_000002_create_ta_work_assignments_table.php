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
        Schema::create('ta_work_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('cascade');
            $table->date('assigned_date');
            
            // Job Position Specs (From Screenshot)
            $table->string('job_title');
            $table->string('location')->default('Remote');
            $table->string('experience')->nullable();
            $table->string('budget')->nullable();
            $table->string('duration')->nullable();
            $table->text('job_description')->nullable();

            // Sourcing Targets & Progress
            $table->integer('target_profiles')->default(10);
            $table->integer('profiles_sourced')->default(0);

            // Status & Dual-side Notes
            $table->enum('status', ['Pending', 'In Progress', 'Done'])->default('Pending');
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
        Schema::dropIfExists('ta_work_assignments');
    }
};
