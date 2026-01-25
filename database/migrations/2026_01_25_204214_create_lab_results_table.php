<?php

use App\Models\LabOrder;
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
        Schema::create('lab_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LabOrder::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('parameter'); // Hb, WBC
            $table->string('value');
            $table->string('unit')->nullable();
            $table->string('reference_range')->nullable();

            $table->string('flag')->default('normal');
            // normal | abnormal | critical
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_results');
    }
};
