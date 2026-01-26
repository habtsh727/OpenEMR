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
        Schema::table('lab_orders', function (Blueprint $table) {
            //
            $table->timestamp('verified_at')->nullable()->after('status');
            $table->foreignId('verified_by')->nullable()->after('verified_at')->constrained('users')->nullOnDelete();
            $table->text('verification_notes')->nullable()->after('verified_by');

            // Also add notes column if not exists
            if (!Schema::hasColumn('lab_orders', 'notes')) {
                $table->text('notes')->nullable()->after('priority');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lab_orders', function (Blueprint $table) {
            //
                $table->dropColumn(['verified_at', 'verified_by', 'verification_notes']);
            
            // Only drop notes if it was added in this migration
            if (Schema::hasColumn('lab_orders', 'notes') && !Schema::hasColumn('lab_orders', 'notes_before_migration')) {
                $table->dropColumn('notes');
            }
        });
    }
};
