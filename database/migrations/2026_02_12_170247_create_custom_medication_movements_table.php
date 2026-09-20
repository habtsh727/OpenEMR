<?php

use App\Models\CustomMedication;
use App\Models\User;
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
        Schema::create('custom_medication_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CustomMedication::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('type', [
                'stock_in',
                'stock_out',
                'adjustment',
                'dispensed'
            ]);
            $table->decimal('quantity', 10, 2);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->foreignIdFor(User::class, 'performed_by')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->text('note')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_medication_movements');
    }
};
