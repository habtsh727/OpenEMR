<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_treatment_dates_to_rehab_encounters.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rehab_encounters', function (Blueprint $table) {
            $table->timestamp('treatment_started_at')->nullable()->after('status');
            $table->timestamp('completed_at')->nullable()->after('treatment_started_at');
        });
    }

    public function down(): void
    {
        Schema::table('rehab_encounters', function (Blueprint $table) {
            $table->dropColumn(['treatment_started_at', 'completed_at']);
        });
    }
};