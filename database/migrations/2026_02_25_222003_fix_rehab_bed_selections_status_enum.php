<?php
// database/migrations/2026_02_25_xxxxxx_fix_rehab_bed_selections_status_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE rehab_bed_selections MODIFY COLUMN status ENUM(
            'pending',
            'selected',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rehab_bed_selections MODIFY COLUMN status ENUM(
            'pending',
            'selected',
            'cancelled'
        ) NOT NULL DEFAULT 'pending'");
    }
};