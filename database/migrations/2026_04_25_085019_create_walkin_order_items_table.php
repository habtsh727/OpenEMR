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
        Schema::create('walkin_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('walkin_orders')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('pharmacy_items');
            $table->foreignId('batch_id')->constrained('pharmacy_batches');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['pending', 'dispensed'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walkin_order_items');
    }
};
