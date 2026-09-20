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
        Schema::create('consumables', function (Blueprint $table) {
            $table->id();
            
            // Basic Info
            $table->string('name'); // Oxygen Cylinder, Syringe 5ml
            $table->string('code')->unique(); // CON-OXY-001
            $table->string('category')->nullable(); 
            // oxygen, iv-fluid, disposable, dressing, balm

            // Unit & Measurement
            $table->string('unit'); 
            // pcs, ml, liter, cylinder, box

            // Stock
            $table->integer('current_stock')->default(0);
            $table->integer('minimum_stock')->default(0);

            // Costing (optional billing later)
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->boolean('billable')->default(false);

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumables');
    }
};
