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
        Schema::create('cupping_package_treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('cupping_packages')->cascadeOnDelete();
            $table->foreignId('cupping_type_id')->constrained('cupping_types')->cascadeOnDelete();
            $table->foreignId('cupping_location_id')->constrained('cupping_locations')->cascadeOnDelete();
            $table->decimal('treatment_price', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupping_package_treatments');
    }
};
