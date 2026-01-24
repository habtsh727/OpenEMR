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
        Schema::create('encounter_chief_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('chief_complaint_template_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('duration')->nullable();  
            $table->enum('severity', ['mild', 'moderate', 'severe'])->nullable();
            $table->text('notes')->nullable();
            $table->unique(
                ['encounter_id', 'chief_complaint_template_id'],
                'enc_cc_unique'
            );
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounter_chief_complaints');
    }
};
