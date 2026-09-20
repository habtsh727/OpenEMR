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
        Schema::create('encounter_examinations', function (Blueprint $table) {
              $table->id();
            $table->foreignId('encounter_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('examination_template_id')
                ->constrained()
                ->cascadeOnDelete();
            // Stored value (dynamic)
            $table->text('value')->nullable();
            // One exam per encounter
            $table->unique(
                ['encounter_id', 'examination_template_id'],
                'enc_exam_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_examinations');
    }
};
