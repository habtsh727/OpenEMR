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
        Schema::create('cupping_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cupping_therapy_id')
                ->constrained()
                ->cascadeOnDelete();

            // Report content
            $table->text('report_text');
            $table->text('notes')->nullable();

            // Cupping staff
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_reports');
    }
};
