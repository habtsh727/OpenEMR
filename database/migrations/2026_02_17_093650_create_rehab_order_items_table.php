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
        Schema::create('rehab_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('rehab_order_package_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('item_type', [
                'standard_medication',
                'custom_medication',
                'service',
                'bed'
            ]);

            $table->string('item_name');

            // Medication fields
            $table->string('dosage')->nullable();
            $table->string('frequency')->nullable();
            $table->string('duration')->nullable();
            $table->string('quantity')->nullable();

            // Bed
            $table->integer('bed_duration_days')->nullable();

            // Pricing snapshot
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_order_items');
    }
};
