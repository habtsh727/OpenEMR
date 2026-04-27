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
        Schema::create('walkin_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->enum('status', [
                'pending_payment',  // Waiting for cashier payment
                'paid',              // Payment received, waiting for dispensing
                'dispensed',         // Medication given to customer
                'cancelled'          // Order cancelled
            ])->default('pending_payment');
            $table->decimal('total_amount', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Pharmacist who created order
            $table->foreignId('dispensed_by')->nullable()->constrained('users'); // Pharmacist who dispensed
            $table->foreignId('paid_by')->nullable()->constrained('users'); // Cashier who collected payment
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('dispensed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin_orders');
    }
};
