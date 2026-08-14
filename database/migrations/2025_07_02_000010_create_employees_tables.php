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
        // 1. Employees Table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('employee_code', 50)->unique();
            $table->unsignedBigInteger('reporting_manager_id')->nullable();
            $table->foreign('reporting_manager_id')->references('id')->on('employees')->onDelete('set null');
            $table->timestamps();
        });

        // 2. Employee Profiles Table
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_phone', 50)->nullable();
            $table->timestamps();
        });

        // 3. Employee Joining Details Table
        Schema::create('employee_joining_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->onDelete('cascade');
            $table->date('joining_date');
            $table->enum('employment_type', ['Full Time', 'Part Time', 'Contract', 'Intern', 'Freelancer'])->default('Full Time');
            $table->enum('employment_status', ['Active', 'Probation', 'Notice Period', 'Resigned', 'Terminated', 'Inactive'])->default('Active');
            $table->date('probation_end_date')->nullable();
            $table->date('confirmation_date')->nullable();
            $table->integer('notice_period_days')->default(30);
            $table->string('work_location', 150)->default('Office');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 4. Employee Documents Table
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('document_name', 150);
            $table->enum('document_type', ['Offer Letter', 'Joining Letter', 'Appointment Letter', 'Salary Letter', 'Experience Letter', 'Other']);
            $table->string('file_path', 255);
            $table->string('version', 20)->default('1.0');
            $table->enum('status', ['Active', 'Archived'])->default('Active');
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_joining_details');
        Schema::dropIfExists('employee_profiles');
        Schema::dropIfExists('employees');
    }
};
