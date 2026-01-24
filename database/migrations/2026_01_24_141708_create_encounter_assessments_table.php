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
        Schema::create('encounter_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('assessment_template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Allow doctor to type custom diagnosis
            $table->string('custom_diagnosis')->nullable();

            // Primary / Secondary / Differential
            $table->enum('type', ['primary', 'secondary', 'differential'])
                ->default('primary');

            // Provisional / Confirmed
            $table->enum('certainty', ['provisional', 'confirmed'])
                ->default('provisional');

            $table->text('notes')->nullable();


            // Prevent duplicate diagnosis for one encounter
            $table->unique(
                ['encounter_id', 'assessment_template_id'],
                'enc_assess_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_assessments');
    }
};
