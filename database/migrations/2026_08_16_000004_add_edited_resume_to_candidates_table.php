<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('candidates', 'edited_resume')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->string('edited_resume', 255)->nullable()->after('resume');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('candidates', 'edited_resume')) {
            Schema::table('candidates', function (Blueprint $table) {
                $table->dropColumn('edited_resume');
            });
        }
    }
};
