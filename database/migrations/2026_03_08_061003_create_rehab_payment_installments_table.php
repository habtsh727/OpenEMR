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
        Schema::create('rehab_payment_installments', function (Blueprint $table) {
          $table->id();
            
            $table->foreignId('rehab_order_id')
                ->constrained()
                ->cascadeOnDelete();
                
            $table->integer('installment_number');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->date('paid_date')->nullable();
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('status', [
                'pending',
                'partial',
                'paid',
                'overdue',
                'cancelled'
            ])->default('pending');
            
            // For tracking who created/updated
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
                
            $table->timestamps();
            $table->softDeletes();
            
            // Index for faster queries
            $table->index(['rehab_order_id', 'status', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_payment_installments');
    }
};
