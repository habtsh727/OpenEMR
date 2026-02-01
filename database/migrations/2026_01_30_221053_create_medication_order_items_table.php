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
        Schema::create('medication_order_items', function (Blueprint $table) {

            $table->id();

            $table->foreignId('medication_order_id')->constrained()->cascadeOnDelete();

            // Standard drug
            $table->foreignId('drug_id')->nullable()->constrained('pharmacy_items')->nullOnDelete();

            // Custom medication
            $table->foreignId('custom_medication_id')->nullable()->constrained('custom_medications')->nullOnDelete();
            $table->enum('discount_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('discount_value', 10, 2)->nullable();
            // Order-specific overrides
            $table->string('dosage')->nullable();
            $table->foreignId('frequency_id')->nullable()->constrained('pharmacy_frequencies')->nullOnDelete();
            $table->string('duration')->nullable();
            $table->text('instructions')->nullable();

            $table->decimal('unit_price', 10, 2);
            $table->integer('quantity')->default(1);
            $table->decimal('total_price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_order_items');
    }
};
