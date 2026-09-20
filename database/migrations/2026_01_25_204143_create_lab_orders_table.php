<?php

use App\Models\LabTest;
use App\Models\Order;
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
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(LabTest::class)
                ->constrained();

            $table->string('priority')->default('routine');
            // routine | urgent | stat

            $table->string('payment_status')->default('unpaid'); // unpaid | paid | refunded
            $table->foreignId('paid_by')->nullable()->constrained('users'); // cashier
            $table->timestamp('paid_at')->nullable();
            
            $table->string('status')->default('pending');
            // pending | sample_collected | processing | verified | reported | cancelled
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
