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
        Schema::create('pharmacy_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('generic_name')->nullable();
            $table->foreignId('category_id')->constrained('pharmacy_categories');
            $table->foreignId('unit_id')->constrained('pharmacy_units');
            $table->foreignId('route_id')->constrained('pharmacy_routes');
            $table->string('strength'); // 500mg
            $table->boolean('is_prescription_required')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacy_items');
    }
};
