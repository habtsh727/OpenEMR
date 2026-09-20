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
        Schema::create('service_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('service_id')->constrained('services');
            $table->decimal('original_amount', 10, 2); // price before discount
            $table->decimal('discount', 10, 2)->default(0); // optional discount
            $table->decimal('paid_amount', 10, 2)->default(0); // amount already paid
            $table->boolean('is_paid')->default(false);
            $table->integer('quantity')->default(1); // number of sessions, days, etc.
            $table->date('start_date')->nullable(); // optional for recurring
            $table->date('end_date')->nullable(); // optional for recurring
            $table->date('payment_date')->nullable(); // optional for recurring
             $table->enum('payment_type', ['cash', 'bank', 'telebirr', 'insurance'])->default('cash');
            $table->foreignId('processed_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_payments');
    }
};
