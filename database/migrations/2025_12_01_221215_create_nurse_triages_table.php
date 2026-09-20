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
        Schema::create('nurse_triages', function (Blueprint $table) {
            $table->id();
            $table->string('bp_systolic');
            $table->string('bp_diastolic');
            $table->string('temperature');
            $table->string('pulse');
            $table->string('spo2');
            $table->string('priority');
            $table->string('processed_by');
            $table->foreignId('patient_id')->constrained('patients');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nurse_triages');
    }
};
