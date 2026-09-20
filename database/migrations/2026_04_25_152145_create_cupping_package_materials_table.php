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
        Schema::create('cupping_package_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('cupping_packages')->cascadeOnDelete();
            $table->foreignId('pharmacy_item_id')->constrained('pharmacy_items')->cascadeOnDelete();
            $table->integer('quantity_required')->default(1);
            $table->decimal('unit_price_snapshot', 10, 2)->default(0);
            $table->decimal('total_material_cost', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_package_materials');
    }
};
