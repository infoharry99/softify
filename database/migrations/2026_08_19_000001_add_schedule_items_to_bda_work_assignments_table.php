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
        if (Schema::hasTable('bda_work_assignments')) {
            Schema::table('bda_work_assignments', function (Blueprint $table) {
                if (!Schema::hasColumn('bda_work_assignments', 'schedule_items')) {
                    $table->json('schedule_items')->nullable()->after('lead_notes');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('bda_work_assignments')) {
            Schema::table('bda_work_assignments', function (Blueprint $table) {
                if (Schema::hasColumn('bda_work_assignments', 'schedule_items')) {
                    $table->dropColumn('schedule_items');
                }
            });
        }
    }
};
