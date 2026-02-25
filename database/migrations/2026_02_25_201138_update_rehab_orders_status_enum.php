<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_rehab_orders_status_enum.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, modify the enum to include new statuses
        DB::statement("ALTER TABLE rehab_orders MODIFY COLUMN status ENUM(
            'draft',
            'sent_to_bed_manager',
            'bed_selected',
            'sent_to_cashier',
            'paid',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        // Revert to original enum
        DB::statement("ALTER TABLE rehab_orders MODIFY COLUMN status ENUM(
            'draft',
            'sent_to_cashier',
            'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }
};