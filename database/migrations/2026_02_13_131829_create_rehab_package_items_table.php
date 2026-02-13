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
        Schema::create('rehab_package_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rehab_package_id')
                ->constrained()
                ->cascadeOnDelete();

            // polymorphic relation
            $table->morphs('itemable');

            // type for UI control
            $table->enum('item_type', [
                'standard_medication',
                'custom_medication',
                'service',
                'bed'
            ]);

            // doctor fields
            $table->string('dosage')->nullable();
            $table->foreignId('frequency_id')
                ->nullable()
                ->constrained('pharmacy_frequencies')
                ->nullOnDelete();

            $table->string('duration')->nullable();
            $table->integer('bed_duration_days')->nullable();

            // billing
            $table->decimal('price', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_package_items');
    }
};
