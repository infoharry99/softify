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
        if (!Schema::hasTable('candidate_saved_searches')) {
            Schema::create('candidate_saved_searches', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->string('name', 255);
                $table->text('search_query')->nullable();
                $table->boolean('boolean_enabled')->default(false);
                $table->json('filters')->nullable();
                $table->unsignedInteger('results_count')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_saved_searches');
    }
};
