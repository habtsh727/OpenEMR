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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->onDelete('cascade');
            $table->foreignId('lab_order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('medication_order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');

            $table->text('clinical_notes')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('advice')->nullable();

            // Prescription status
            $table->enum('status', ['active', 'dispensed', 'expired', 'cancelled'])->default('active');
            $table->date('valid_until')->nullable();

            // Signature/approval
            $table->boolean('is_signed')->default(false);
            $table->foreignId('signed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('signed_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['encounter_id', 'status']);
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
