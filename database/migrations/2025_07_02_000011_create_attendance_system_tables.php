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
        // 1. Attendance Table
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['Present', 'Absent', 'Half Day', 'Leave', 'Holiday', 'Week Off', 'Late', 'Early Logout', 'Missing Logout'])->default('Present');
            $table->timestamp('first_login_at')->nullable();
            $table->timestamp('last_logout_at')->nullable();
            $table->integer('total_working_minutes')->default(0);
            $table->integer('total_break_minutes')->default(0);
            $table->integer('effective_working_minutes')->default(0);
            $table->integer('late_minutes')->default(0);
            $table->integer('early_logout_minutes')->default(0);
            $table->boolean('is_admin_adjusted')->default(false);
            $table->text('admin_remarks')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']);
        });

        // 2. Attendance Sessions Table
        Schema::create('attendance_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendance')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->timestamp('login_at');
            $table->timestamp('logout_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->enum('status', ['Active', 'Logged Out', 'Auto Closed', 'Missing Logout', 'Admin Adjusted'])->default('Active');
            $table->string('ip_address', 50)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });

        // 3. Attendance Breaks Table
        Schema::create('attendance_breaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendance')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_minutes')->default(0);
            $table->boolean('is_exceeded')->default(false);
            $table->integer('exceeded_minutes')->default(0);
            $table->enum('status', ['Active', 'Ended'])->default('Active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_breaks');
        Schema::dropIfExists('attendance_sessions');
        Schema::dropIfExists('attendance');
    }
};
