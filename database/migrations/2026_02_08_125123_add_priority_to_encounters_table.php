<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('encounters', 'priority')) {
            Schema::table('encounters', function (Blueprint $table) {
                $table->enum('priority', ['low', 'medium', 'high', 'critical'])
                    ->nullable()
                    ->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('encounters', 'priority')) {
            Schema::table('encounters', function (Blueprint $table) {
                $table->dropColumn('priority');
            });
        }
    }
};