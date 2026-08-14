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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hr_id');
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 255);
            $table->string('current_ctc', 255)->nullable();
            $table->string('expected_ctc', 255)->nullable();
            $table->longText('skills');
            $table->string('location', 255);
            $table->string('job_type', 255);
            $table->integer('experience');
            $table->string('notice_period', 255)->nullable();
            $table->text('note')->nullable();
            $table->string('resume', 255);
            $table->text('company_resume')->nullable();
            $table->string('last_updated_by', 255)->nullable();
            $table->boolean('is_highlighted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
