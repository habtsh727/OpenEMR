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
        //
        Schema::table('cupping_sessions', function (Blueprint $table) {
            $table->foreignId('cupping_therapy_package_id')->nullable()->after('cupping_therapy_id')->constrained('cupping_therapy_packages')->nullOnDelete();
            $table->json('materials_consumed')->nullable()->after('treatment_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('cupping_sessions', function (Blueprint $table) {
            $table->dropForeign(['cupping_therapy_package_id']);
            $table->dropColumn(['cupping_therapy_package_id', 'materials_consumed']);
        });
    }
};
