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
        Schema::create('prescription_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained()->onDelete('cascade');
            $table->foreignId('pharmacy_item_id')->constrained('pharmacy_items')->onDelete('cascade');

            // Prescription details
            $table->string('dosage')->nullable();
            $table->foreignId('frequency_id')->nullable()->constrained('pharmacy_frequencies')->onDelete('set null');
            $table->string('route')->nullable(); // Could also link to pharmacy_routes
            $table->integer('duration_days')->nullable();
            $table->integer('quantity')->nullable(); // Total quantity needed
            $table->text('instructions')->nullable();

            $table->boolean('is_custom')->default(false);
            $table->string('custom_name')->nullable();
            $table->string('custom_details')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('prescription_id');
            $table->index('pharmacy_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescription_items');
    }
};
