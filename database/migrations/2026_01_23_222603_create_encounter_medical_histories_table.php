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
        Schema::create('encounter_medical_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medical_history_template_id')->constrained()->cascadeOnDelete();
            $table->string('value')->nullable();
            $table->timestamps();

            $table->unique(
                ['encounter_id', 'medical_history_template_id'],
                'enc_med_hist_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_medical_histories');
    }
};
