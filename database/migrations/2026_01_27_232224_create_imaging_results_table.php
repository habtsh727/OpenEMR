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
        Schema::create('imaging_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('imaging_order_id')->unique()->constrained('imaging_orders')->onDelete('cascade');
            $table->foreignId('radiologist_id')->constrained('users');
            $table->text('report');
            $table->json('images')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'rejected'])->default('pending');
            $table->timestamp('reported_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imaging_results');
    }
};
