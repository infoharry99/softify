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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hr_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('company_name', 255)->nullable();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 50);
            $table->string('location', 255);
            $table->text('skills');
            $table->float('experience', 4, 1)->default(0);
            $table->enum('job_type', ['Full Time', 'Part Time', 'Contract', 'Remote', 'Hybrid'])->default('Full Time');
            $table->enum('notice_period', ['Immediate', '15 Days', '30 Days', '60 Days', '90 Days'])->default('Immediate');
            $table->decimal('current_ctc', 12, 2)->nullable();
            $table->decimal('expected_ctc', 12, 2)->nullable();
            $table->enum('status', ['Applied', 'Screening', 'Interview Scheduled', 'Offered', 'Hired', 'Rejected'])->default('Applied');
            $table->string('resume', 255)->nullable();
            $table->text('note')->nullable();
            $table->foreignId('last_updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
