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
        Schema::create('cupping_therapy_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cupping_therapy_id')->constrained('cupping_therapies')->cascadeOnDelete();
            $table->foreignId('cupping_package_id')->constrained('cupping_packages')->cascadeOnDelete();
            $table->integer('session_number');

            // Snapshots to preserve historical data
            $table->string('package_name_snapshot');
            $table->decimal('package_price_snapshot', 10, 2);
            $table->json('treatment_snapshot')->nullable();
            $table->json('materials_snapshot')->nullable();

            $table->enum('status', [
                'pending',
                'paid',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_therapy_packages');
    }
};
