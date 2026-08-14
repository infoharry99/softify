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
        Schema::create('employee_tracking', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('last_heartbeat', 255)->nullable();
            $table->timestamp('login_time')->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->timestamp('last_activity')->nullable();
            $table->boolean('is_online')->default(false);
            $table->boolean('system_active')->default(true);
            $table->boolean('internet_active')->default(true);
            $table->json('session_data')->nullable();
            $table->integer('total_work_minutes')->default(0);
            $table->date('work_date');
            $table->string('ip_address', 255)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_tracking');
    }
};
