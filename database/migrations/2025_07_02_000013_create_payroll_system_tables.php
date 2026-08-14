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
        // 1. Salary Structures Table
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->unique()->constrained('employees')->onDelete('cascade');
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('hra', 12, 2)->default(0);
            $table->decimal('conveyance', 12, 2)->default(0);
            $table->decimal('allowances', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('incentives', 12, 2)->default(0);
            $table->decimal('pf_deduction', 12, 2)->default(0);
            $table->decimal('esi_deduction', 12, 2)->default(0);
            $table->decimal('pt_deduction', 12, 2)->default(0);
            $table->decimal('tds_deduction', 12, 2)->default(0);
            $table->decimal('other_deductions', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->date('effective_date');
            $table->timestamps();
        });

        // 2. Salary Revision History Table
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->decimal('previous_net_salary', 12, 2);
            $table->decimal('new_net_salary', 12, 2);
            $table->json('salary_components')->nullable();
            $table->date('effective_date');
            $table->string('reason', 255)->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // 3. Monthly Payroll Table
        Schema::create('monthly_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('month', 7); // Format: YYYY-MM (e.g. 2026-08)
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->float('paid_leave_days', 8, 1)->default(0);
            $table->float('unpaid_leave_days', 8, 1)->default(0);
            $table->integer('absent_days')->default(0);
            $table->decimal('late_deductions', 12, 2)->default(0);
            $table->decimal('leave_deductions', 12, 2)->default(0);
            $table->decimal('bonus_amount', 12, 2)->default(0);
            $table->decimal('gross_salary', 12, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('net_salary', 12, 2)->default(0);
            $table->enum('payment_status', ['Draft', 'Pending', 'Paid'])->default('Pending');
            $table->date('payment_date')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->unique(['employee_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_payrolls');
        Schema::dropIfExists('salary_histories');
        Schema::dropIfExists('salary_structures');
    }
};
