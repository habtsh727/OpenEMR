<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
          // Update rehab_orders status enum to include 'paid'
        DB::statement("ALTER TABLE rehab_orders MODIFY COLUMN status ENUM(
            'draft',
            'sent_to_cashier',
            'paid',
            'in_progress',
            'completed',
            'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
         DB::statement("ALTER TABLE rehab_orders MODIFY COLUMN status ENUM(
            'draft',
            'sent_to_cashier',
            'cancelled'
        ) NOT NULL DEFAULT 'draft'");
    }
};
