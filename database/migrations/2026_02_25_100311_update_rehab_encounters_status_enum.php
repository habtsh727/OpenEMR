<?php
// database/migrations/2026_02_25_xxxxxx_update_rehab_encounters_status_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rehab_encounters MODIFY COLUMN status ENUM(
            'pending_questionnaire',
            'questionnaire_in_progress',
            'submitted_to_doctor',
            'doctor_review',
            'sent_to_cashier',
            'bed_selected',
            'paid',
            'waiting_bed_selection',
            'treatment_in_progress',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending_questionnaire'");
    }

    public function down(): void
    {
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