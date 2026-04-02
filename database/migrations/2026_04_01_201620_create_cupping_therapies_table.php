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

            // Link to encounter
            $table->foreignId('encounter_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Doctor who ordered
            $table->foreignId('doctor_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // 🔥 NEW: session date
            $table->date('treatment_date');

            // Notes
            $table->text('notes')->nullable();

            // Amounts
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2)->default(0);

            // Workflow status
            $table->enum('status', [
                'pending',
                'ordered',
                'payment_partial',
                'payment_completed',
                'sent_to_cupping',
                'completed',
                'cancelled'
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
