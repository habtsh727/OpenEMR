<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            
            // Patient and Doctor
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();
            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Appointment Details
            $table->enum('visit_type', [
                'consultation',
                'follow-up',
                'lab_review',
                'rehab_milestone',
                'emergency',
                'other'
            ])->default('consultation');
            
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->string('time_slot')->nullable(); // e.g., "09:00-09:30"
            
            // Status
            $table->enum('status', [
                'scheduled',
                'completed',
                'missed',
                'cancelled',
                'rescheduled',
                'requested'
            ])->default('scheduled');
            
            // Notes and References
            $table->text('additional_notes')->nullable();
            $table->string('reschedule_reason')->nullable();
            
            // Payment
            $table->enum('payment_status', [
                'unpaid',
                'partially_paid',
                'paid',
                'waived'
            ])->default('unpaid');
            $table->decimal('payment_amount', 10, 2)->nullable();
            
            // Related Orders (Lab, Medication, Rehab)
            $table->string('related_order_type')->nullable(); // medication, lab, rehab, none
            $table->unsignedBigInteger('related_order_id')->nullable();
            
            // Encounter Link (created on check-in)
            $table->foreignId('encounter_id')
                ->nullable()
                ->constrained('encounters')
                ->nullOnDelete();
            
            // Tracking
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamp('reminder_sent_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for performance
            $table->index(['appointment_date', 'status']);
            $table->index('doctor_id');
            $table->index('patient_id');
            $table->index(['appointment_date', 'appointment_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};