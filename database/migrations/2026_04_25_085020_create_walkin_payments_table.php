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
        Schema::create('walkin_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('walkin_orders');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'mobile_money', 'card', 'bank_transfer']);
            $table->string('transaction_id')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->decimal('change_due', 10, 2)->default(0);
            $table->foreignId('collected_by')->constrained('users'); // Cashier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin_payments');
    }
};
