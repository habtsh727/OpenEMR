<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        //
         // Fix orders that have status='paid' but payment_status='partial'
        DB::table('rehab_orders')
            ->where('status', 'paid')
            ->where('payment_status', 'partial')
            ->update(['status' => 'sent_to_cashier']);
            
        // Fix orders that have status='paid' but payment_status='pending'
        DB::table('rehab_orders')
            ->where('status', 'paid')
            ->where('payment_status', 'pending')
            ->update(['status' => 'sent_to_cashier']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
