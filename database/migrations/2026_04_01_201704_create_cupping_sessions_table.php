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
        Schema::create('cupping_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cupping_therapy_id')->constrained()->cascadeOnDelete();
            $table->integer('session_number');
            $table->date('session_date');
            $table->decimal('session_amount', 10, 2);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->enum('treatment_status', ['pending', 'in_queue', 'in_progress', 'completed'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('treatment_started_at')->nullable();
            $table->timestamp('treatment_completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_sessions');
    }
};
