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
        Schema::create('cupping_therapies', function (Blueprint $table) {
            $table->id();

            // Link to patient encounter
            $table->foreignId('encounter_id')
                ->constrained()
                ->cascadeOnDelete();

            // Notes for therapy
            $table->text('notes')->nullable();

            // Amounts
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);

            // Workflow status
            $table->enum('status', [
                'pending',           // created but not processed
                'order_placed',      // doctor has placed the cupping order
                'payment_done',      // cashier received payment
                'sent_to_cupping',   // sent to cupping therapy department
                'completed',         // therapy completed
                'cancelled'          // cancelled
            ])->default('pending');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_therapies');
    }
};
