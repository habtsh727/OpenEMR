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
        Schema::create('imaging_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('encounter_id')->constrained('encounters')->onDelete('cascade');
            $table->string('order_type')->default('imaging'); // imaging, lab, prescription, etc.
            $table->foreignId('imaging_type_id')->nullable()->constrained('imaging_types');
            $table->foreignId('body_part_id')->nullable()->constrained('body_parts');
            $table->foreignId('ordered_by')->constrained('users');
            $table->decimal('amount', 10, 2);
            $table->enum('priority', ['routine', 'urgent'])->default('routine');
            $table->text('clinical_notes')->nullable();
            $table->enum('status', ['pending', 'paid', 'in_progress', 'completed', 'cancelled', 'rejected'])->default('pending');
            $table->timestamp('order_date')->useCurrent();
            $table->timestamp('completed_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['status']);
            $table->index('encounter_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_orders');
    }
};
