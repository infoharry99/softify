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
        Schema::create('finance_requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('vendor_name');
            $table->string('vendor_location')->nullable();
            $table->string('company_name')->nullable();
            $table->integer('selected_candidates_count')->default(0);
            $table->decimal('budget', 12, 2)->default(0.00);
            $table->date('date');
            $table->decimal('remaining_payment', 12, 2)->default(0.00);
            $table->enum('status', ['No Update', 'In Progress', 'Closed'])->default('No Update');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance_requirements');
    }
};
