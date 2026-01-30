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
        Schema::create('referrals', function (Blueprint $table) {
              $table->id();

            // Link to the encounter
            $table->foreignId('encounter_id')
                  ->constrained('encounters')
                  ->cascadeOnDelete();

            // Destination hospital / higher-level clinic
            $table->string('facility_name');          // Name of hospital/clinic
            $table->string('facility_type')->nullable(); // e.g., hospital, clinic, tertiary center
            $table->string('facility_location')->nullable();

            // Clinical info
            $table->enum('urgency', ['routine', 'urgent', 'emergency'])->default('routine');
            $table->string('reason');                 // Short reason for referral
            $table->text('clinical_summary');        // Summary of condition, labs, findings

            // Workflow
            $table->enum('status', ['created', 'sent', 'completed', 'cancelled'])->default('created');
            $table->timestamp('referred_at')->useCurrent(); // When referral was created

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
