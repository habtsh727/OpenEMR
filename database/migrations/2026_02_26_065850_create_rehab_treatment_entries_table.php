<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_rehab_treatment_entries_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rehab_treatment_entries', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('rehab_encounter_id')
                ->constrained()
                ->cascadeOnDelete();
                
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
                
            $table->foreignId('treatment_type_id')
                ->constrained('rehab_treatment_types')
                ->cascadeOnDelete();
                
            $table->json('answers'); // Store all dynamic answers as JSON
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            $table->index(['rehab_encounter_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rehab_treatment_entries');
    }
};