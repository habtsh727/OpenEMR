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
        Schema::create('doctor_consultations', function (Blueprint $table) {
              $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('users')->cascadeOnDelete();
            $table->longText('history')->nullable();
            $table->longText('current_complaints')->nullable();
            $table->longText('physical_exam')->nullable();
            $table->longText('assessment_options')->nullable();
            $table->text('assessment_notes')->nullable();
            $table->longText('lab_orders')->nullable();
            $table->longText('imaging_orders')->nullable();
            $table->longText('medications')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('plan')->nullable();
            $table->string('disposition')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_consultations');
    }
};
