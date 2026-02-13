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

            $table->enum('item_type', [
                'standard_medication',
                'custom_medication',
                'service',
                'bed'
            ]);

            $table->unsignedBigInteger('item_id')->nullable();

            $table->string('item_name');

            $table->string('dosage')->nullable();
            $table->unsignedBigInteger('frequency_id')->nullable();
            $table->string('duration')->nullable();

            $table->integer('bed_duration_days')->nullable();

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
