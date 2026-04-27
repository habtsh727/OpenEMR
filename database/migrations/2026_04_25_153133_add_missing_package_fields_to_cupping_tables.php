<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get existing columns in cupping_therapies
        $therapyColumns = DB::select("SHOW COLUMNS FROM cupping_therapies");
        $existingTherapyColumns = array_column($therapyColumns, 'Field');

        // Add missing columns to cupping_therapies
        Schema::table('cupping_therapies', function (Blueprint $table) use ($existingTherapyColumns) {
            if (!in_array('primary_package_id', $existingTherapyColumns)) {
                $table->foreignId('primary_package_id')->nullable()->after('doctor_id')->constrained('cupping_packages')->nullOnDelete();
            }

            // Note: total_sessions already exists, so skip it

            if (!in_array('payment_type', $existingTherapyColumns)) {
                $table->enum('payment_type', ['full', 'installment'])->default('full')->after('total_sessions');
            }

            if (!in_array('installment_count', $existingTherapyColumns)) {
                $table->integer('installment_count')->nullable()->after('payment_type');
            }

            if (!in_array('next_payment_due', $existingTherapyColumns)) {
                $table->date('next_payment_due')->nullable()->after('installment_count');
            }
        });

        // Get existing columns in cupping_sessions
        $sessionColumns = DB::select("SHOW COLUMNS FROM cupping_sessions");
        $existingSessionColumns = array_column($sessionColumns, 'Field');

        // Add missing columns to cupping_sessions
        Schema::table('cupping_sessions', function (Blueprint $table) use ($existingSessionColumns) {
            if (!in_array('cupping_therapy_package_id', $existingSessionColumns)) {
                $table->foreignId('cupping_therapy_package_id')->nullable()->after('cupping_therapy_id')->constrained('cupping_therapy_packages')->nullOnDelete();
            }

            if (!in_array('materials_consumed', $existingSessionColumns)) {
                $table->json('materials_consumed')->nullable()->after('treatment_completed_at');
            }
        });

        // Get existing columns in cupping_payments
        $paymentColumns = DB::select("SHOW COLUMNS FROM cupping_payments");
        $existingPaymentColumns = array_column($paymentColumns, 'Field');

        // Add missing columns to cupping_payments
        Schema::table('cupping_payments', function (Blueprint $table) use ($existingPaymentColumns) {
            if (!in_array('cupping_therapy_package_id', $existingPaymentColumns)) {
                $table->foreignId('cupping_therapy_package_id')->nullable()->after('cupping_therapy_id')->constrained('cupping_therapy_packages')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        // Remove added columns if needed
        Schema::table('cupping_therapies', function (Blueprint $table) {
            $columns = ['primary_package_id', 'payment_type', 'installment_count', 'next_payment_due'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('cupping_therapies', $column)) {
                    if ($column === 'primary_package_id') {
                        $table->dropForeign(['primary_package_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('cupping_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('cupping_sessions', 'cupping_therapy_package_id')) {
                $table->dropForeign(['cupping_therapy_package_id']);
                $table->dropColumn('cupping_therapy_package_id');
            }

            if (Schema::hasColumn('cupping_sessions', 'materials_consumed')) {
                $table->dropColumn('materials_consumed');
            }
        });

        Schema::table('cupping_payments', function (Blueprint $table) {
            if (Schema::hasColumn('cupping_payments', 'cupping_therapy_package_id')) {
                $table->dropForeign(['cupping_therapy_package_id']);
                $table->dropColumn('cupping_therapy_package_id');
            }
        });
    }
};
