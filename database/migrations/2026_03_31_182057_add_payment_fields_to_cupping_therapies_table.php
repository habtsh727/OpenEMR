<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cupping_therapies', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('cupping_therapies', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('final_amount');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'amount_paid')) {
                $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_reference');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('amount_paid');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_notes');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'paid_by')) {
                $table->foreignId('paid_by')->nullable()->constrained('users')->nullOnDelete()->after('paid_at');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'cancelled_by')) {
                $table->foreignId('cancelled_by')->nullable()->constrained('users')->nullOnDelete()->after('cancelled_at');
            }
            
            if (!Schema::hasColumn('cupping_therapies', 'cancellation_reason')) {
                $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cupping_therapies', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_reference',
                'amount_paid',
                'payment_notes',
                'paid_at',
                'paid_by',
                'cancelled_at',
                'cancelled_by',
                'cancellation_reason'
            ]);
        });
    }
};