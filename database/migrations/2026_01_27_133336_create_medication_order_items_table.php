<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_order_items', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('medication_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('pharmacy_item_id')->nullable()->constrained('pharmacy_items')->onDelete('set null');
            $table->foreignId('pharmacy_batch_id')->nullable()->constrained()->onDelete('set null');
            
            // Prescription details (optional)
            $table->string('dosage')->nullable(); // e.g., "1 tablet"
            $table->foreignId('frequency_id')->nullable()->constrained('pharmacy_frequencies')->onDelete('set null');
            $table->integer('duration_days')->nullable(); // e.g., 7 days
            $table->text('instructions')->nullable(); // e.g., "Take after food"
            
            // Order details
            $table->integer('quantity')->default(1);
            $table->integer('dispensed_quantity')->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            
            // Stock tracking
            $table->boolean('from_stock')->default(false);
            $table->boolean('is_custom')->default(false); // If doctor added custom medication
            $table->string('custom_name')->nullable(); // For custom medications
            $table->text('custom_instructions')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance (with shorter names)
            $table->index(['medication_order_id', 'pharmacy_item_id'], 'idx_order_item');
            $table->index('pharmacy_batch_id', 'idx_batch');
            $table->index('is_custom', 'idx_custom');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_order_items');
    }
};