<?php

use App\Models\CardPayment;
use App\Models\Patient;
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
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Patient::class);
            $table->foreignIdFor(User::class, 'doctor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'triage_by')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(CardPayment::class)->nullable()->constrained()->nullOnDelete();
            $table->string('processed_by')->nullable();
            $table->enum('status', ['pending', 'triaged', 'doctor_assigned','in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encounters');
    }
};
