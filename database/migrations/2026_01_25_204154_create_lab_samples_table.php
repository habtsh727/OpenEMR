<?php

// use App\Livewire\Admin\Users;
use App\Models\LabSample;
use App\Models\User;
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
        Schema::create('lab_samples', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lab_order_id')
                ->constrained('lab_orders')
                ->cascadeOnDelete();
                
            $table->string('sample_type'); // blood, urine
            $table->string('status')->default('pending');
            // pending | collected | accepted | rejected

            $table->timestamp('collected_at')->nullable();
            $table->foreignId('collected_by')->nullable()->constrained('users');

            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_samples');
    }
};
