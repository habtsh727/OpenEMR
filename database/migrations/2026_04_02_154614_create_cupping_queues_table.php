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
        Schema::create('cupping_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cupping_session_id')->constrained()->cascadeOnDelete();
            $table->enum('queue_type', ['payment', 'treatment']);
            $table->integer('position')->default(0);
            $table->enum('status', ['waiting', 'processing', 'completed'])->default('waiting');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_queues');
    }
};
