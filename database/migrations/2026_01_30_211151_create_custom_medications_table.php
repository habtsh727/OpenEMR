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
        Schema::create('custom_medications', function (Blueprint $table) {
             $table->id();

            $table->string('name');
            $table->text('ingredients');
            $table->text('preparation_instructions')->nullable();

            $table->string('dosage');
            $table->foreignId('frequency_id')->constrained('pharmacy_frequencies');
            $table->string('duration');
            $table->text('instructions')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('base_price', 10, 2);
            // $table->foreignId('created_by')->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_medications');
    }
};
