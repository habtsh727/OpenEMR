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
        Schema::create('pharmacy_stock_transactions', function (Blueprint $table) {
            $table->id();
          // Item information
            $table->foreignId('pharmacy_item_id')
                  ->constrained('pharmacy_items')
                  ->onDelete('cascade');
                  
            $table->foreignId('batch_id')
                  ->nullable()
                  ->constrained('pharmacy_batches')
                  ->onDelete('set null');
            
            // Transaction details
            $table->enum('transaction_type', [
                'purchase', 
                'adjustment', 
                'dispense', 
                'return', 
                'damage',
                'transfer_in',
                'transfer_out'
            ]);
            
            $table->integer('quantity'); // Positive for incoming, negative for outgoing
            
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            
            // Reference to related document
            $table->string('reference_type')->nullable(); // e.g., MedicationOrder, PurchaseOrder, Adjustment
            $table->unsignedBigInteger('reference_id')->nullable();
            
            // Additional information
            $table->text('notes')->nullable();
            
            // User who performed the transaction
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['reference_type', 'reference_id']);
            $table->index('transaction_type');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_stock_transactions');
    }
};
