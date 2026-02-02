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
        Schema::create('bed_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // General, VIP, VVIP
            $table->string('code')->unique(); // GEN, VIP, VVIP
            $table->text('description')->nullable();
            $table->decimal('price_per_day', 10, 2);
            $table->string('currency', 10)->default('ETB');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_classes');
    }
};
