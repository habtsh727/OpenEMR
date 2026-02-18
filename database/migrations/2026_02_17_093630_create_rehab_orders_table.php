<?php

use App\Models\RehabEncounter;
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
        Schema::create('rehab_orders', function (Blueprint $table) {
             $table->id();

            $table->foreignId('rehab_encounter_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->decimal('total_amount', 12, 2)->default(0);

            $table->enum('status', [
                'draft',
                'sent_to_cashier',
                'cancelled'
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_orders');
    }
};
