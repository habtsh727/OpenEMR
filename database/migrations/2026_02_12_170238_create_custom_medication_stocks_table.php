<?php

use App\Models\CustomMedication;
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
        Schema::create('custom_medication_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomMedication::class)
                ->unique()
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('quantity', 10, 2)->default(0);

            $table->decimal('low_stock_alert', 10, 2)->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_medication_stocks');
    }
};
