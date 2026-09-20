<?php

use App\Models\Encounter;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Encounter::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->string('order_type');
            // lab | medication | imaging | service | bed | referral

            $table->string('status')->default('pending');
            // pending | in_progress | completed | cancelled

            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
