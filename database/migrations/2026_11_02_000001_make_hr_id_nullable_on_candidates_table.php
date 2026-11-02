<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('candidates') && Schema::hasColumn('candidates', 'hr_id')) {
            try {
                // MySQL / MariaDB direct statement
                DB::statement('ALTER TABLE `candidates` MODIFY `hr_id` BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // Driver fallback
                try {
                    Schema::table('candidates', function (Blueprint $table) {
                        $table->unsignedBigInteger('hr_id')->nullable()->change();
                    });
                } catch (\Throwable $ex) {
                    // Ignored if already nullable
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep nullable
    }
};
