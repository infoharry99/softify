<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('candidates', 'job_title')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('job_title', 255)->nullable()->after('company_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('candidates', 'job_title')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->dropColumn('job_title');
            });
        }
    }
};
