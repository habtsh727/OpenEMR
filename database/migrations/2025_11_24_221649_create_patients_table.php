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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('card_number')->unique();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('mother_name')->nullable();
            $table->string('gender');
            $table->string('phone_number1');
            $table->string('phone_number2')->nullable();
            $table->string('emergency_person');
            $table->string('emergency_contact');
            $table->string('emergency_person_relationship');
            $table->string('region');
            $table->string('region_zone');
            $table->string('region_woreda');
            $table->date('date_of_birth');
            $table->date('last_visit_at')->nullable();
           $table->foreignId('created_by')->constrained('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
