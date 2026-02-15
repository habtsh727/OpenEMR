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
        Schema::create('rehab_package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rehab_package_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('item_type', [
                'standard_medication',
                'custom_medication',
                'service',
                'bed'
            ]);
            $table->string('item_name');

            // Medication fields
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable(); // Changed from frequency_id to string for custom entry
            $table->string('duration')->nullable();
            $table->string('quantity')->nullable();
            $table->text('instructions')->nullable();

            // Bed field
            $table->integer('bed_duration_days')->nullable();

            // Common fields
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_package_items');
    }
};
