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
        Schema::table('rehab_orders', function (Blueprint $table) {
            //
             // Add payment_method column if it doesn't exist
            if (!Schema::hasColumn('rehab_orders', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_amount');
            }
            
            // Add paid_at column if it doesn't exist
            if (!Schema::hasColumn('rehab_orders', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rehab_orders', function (Blueprint $table) {
            //
                        $table->dropColumn(['payment_method', 'paid_at']);
        });
    }
};
