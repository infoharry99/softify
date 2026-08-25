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
        Schema::table('bda_works', function (Blueprint $table) {
            $table->text('file_url')->nullable()->after('description');
        });

        try {
            DB::statement("ALTER TABLE `bda_works` MODIFY `file_path` VARCHAR(255) NULL");
            DB::statement("ALTER TABLE `bda_works` MODIFY `file_name` VARCHAR(255) NULL");
        } catch (\Throwable $e) {
            // Ignore for SQLite
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bda_works', function (Blueprint $table) {
            $table->dropColumn('file_url');
        });
    }
};
