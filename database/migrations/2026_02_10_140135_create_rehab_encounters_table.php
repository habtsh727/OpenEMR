<?php

use App\Models\Encounter;
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
        Schema::create('rehab_encounters', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Encounter::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'questionnaire_filled_by')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('status', [
                'pending_questionnaire',
                'questionnaire_in_progress',
                'submitted_to_doctor',
                'doctor_review',
                'sent_to_cashier',
                'paid',
                'treatment_in_progress',
                'completed',
                'cancelled'
            ])->default('pending_questionnaire');

            $table->text('doctor_notes')->nullable();
            $table->text('rehab_notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_encounters');
    }
};
