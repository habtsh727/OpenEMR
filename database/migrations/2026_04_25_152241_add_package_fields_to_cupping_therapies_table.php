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
        Schema::table('cupping_therapies', function (Blueprint $table) {
            //
            $table->foreignId('primary_package_id')->nullable()->after('doctor_id')->constrained('cupping_packages')->nullOnDelete();
            $table->integer('total_sessions')->default(1)->after('final_amount');
            $table->enum('payment_type', ['full', 'installment'])->default('full')->after('total_sessions');
            $table->integer('installment_count')->nullable()->after('payment_type');
            $table->date('next_payment_due')->nullable()->after('installment_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupping_therapies', function (Blueprint $table) {
            //
            $table->dropForeign(['primary_package_id']);
            $table->dropColumn([
                'primary_package_id',
                'total_sessions',
                'payment_type',
                'installment_count',
                'next_payment_due'
            ]);
        });
    }
};
