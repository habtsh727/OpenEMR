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
                        $table->enum('payment_type', ['full', 'installment'])->default('full')->after('total_amount');
            $table->integer('installment_count')->nullable()->after('payment_type');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('installment_count');
            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'overdue'
            ])->default('pending')->after('paid_amount');
            $table->date('next_payment_due')->nullable()->after('payment_status');
            $table->boolean('payment_reminder_sent')->default(false)->after('next_payment_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rehab_orders', function (Blueprint $table) {
            //
            $table->dropColumn([
                'payment_type',
                'installment_count',
                'paid_amount',
                'payment_status',
                'next_payment_due',
                'payment_reminder_sent'
            ]);
        });
    }
};
