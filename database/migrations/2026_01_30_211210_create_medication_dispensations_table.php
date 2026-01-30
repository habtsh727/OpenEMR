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
        Schema::create('medication_dispensations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('medication_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('pharmacist_id')->constrained('users')->nullOnDelete();

            $table->enum('status', ['pending', 'approved', 'dispensed'])->default('pending');
            $table->timestamp('dispensed_at')->nullable();
            $table->boolean('stock_updated')->default(false);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medication_dispensations');
    }
};
