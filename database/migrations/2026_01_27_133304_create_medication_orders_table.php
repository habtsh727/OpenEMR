<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medication_orders', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_order_id')->nullable()->constrained()->onDelete('set null');
            
            // Status flow: pending → sent_to_cashier → paid → sent_to_pharmacy → dispensed → completed
            $table->enum('status', [
                'pending',           // Doctor created, not sent to cashier
                'sent_to_cashier',   // Sent for payment
                'paid',              // Payment completed
                'sent_to_pharmacy',  // Sent to pharmacy queue
                'dispensed',         // Pharmacy dispensed
                'completed',         // Patient received
                'cancelled'
            ])->default('pending');
            
            // Financial information
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            
            // Payment information
            $table->foreignId('cashier_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('paid_at')->nullable();
            
            // Pharmacy information
            $table->foreignId('pharmacist_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dispensed_at')->nullable();
            
            // Notes
            $table->text('clinical_notes')->nullable(); // Doctor's notes
            $table->text('pharmacy_notes')->nullable(); // Pharmacy notes
            
            $table->timestamps();
            
            // Indexes for performance (with shorter names)
            $table->index(['encounter_id', 'status'], 'idx_encounter_status');
            $table->index(['status', 'created_at'], 'idx_status_created');
            $table->index('cashier_id', 'idx_cashier');
            $table->index('pharmacist_id', 'idx_pharmacist');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medication_orders');
    }
};