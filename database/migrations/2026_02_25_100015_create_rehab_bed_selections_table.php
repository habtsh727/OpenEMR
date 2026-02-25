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
        Schema::create('rehab_bed_selections', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('rehab_encounter_id')
                ->constrained()
                ->cascadeOnDelete();
                
            $table->foreignId('rehab_order_id')
                ->constrained()
                ->cascadeOnDelete();
                
            $table->foreignId('bed_class_id')
                ->constrained('bed_classes')
                ->cascadeOnDelete();
                
            $table->foreignId('bed_id')
                ->constrained('beds')
                ->cascadeOnDelete();
                
            $table->integer('duration_days');
            $table->decimal('price_per_day', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('currency', 10)->default('ETB');
            
            $table->foreignId('selected_by')
                ->constrained('users')
                ->cascadeOnDelete();
                
            $table->timestamp('selected_at');
            
            $table->enum('status', [
                'pending',
                'selected',
                'cancelled'
            ])->default('selected');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_bed_selections');
    }
};
