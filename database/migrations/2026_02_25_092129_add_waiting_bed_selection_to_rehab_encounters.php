<?php
// database/migrations/2026_02_25_xxxxxx_add_waiting_bed_selection_to_rehab_encounters.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modify the ENUM column to include waiting_bed_selection
        DB::statement("ALTER TABLE rehab_encounters MODIFY COLUMN status ENUM(
            'pending_questionnaire',
            'questionnaire_in_progress',
            'submitted_to_doctor',
            'doctor_review',
            'sent_to_cashier',
            'paid',
            'waiting_bed_selection',
            'treatment_in_progress',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending_questionnaire'");
    }

    public function down(): void
    {
        // Revert back to original ENUM
        DB::statement("ALTER TABLE rehab_encounters MODIFY COLUMN status ENUM(
            'pending_questionnaire',
            'questionnaire_in_progress',
            'submitted_to_doctor',
            'doctor_review',
            'sent_to_cashier',
            'paid',
            'treatment_in_progress',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending_questionnaire'");
    }
};