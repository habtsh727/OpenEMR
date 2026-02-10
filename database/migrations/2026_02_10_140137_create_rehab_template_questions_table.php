<?php

use App\Models\RehabQuestionnaireTemplate;
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
        Schema::create('rehab_template_questions', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(RehabQuestionnaireTemplate::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('question');

            $table->enum('type', [
                'boolean',
                'checkbox',
                'text',
                'textarea',
                'number',
                'datetime',
                'select'
            ]);

            $table->json('options')->nullable(); // checkbox + select

            $table->boolean('is_required')->default(false);

            $table->integer('order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rehab_template_questions');
    }
};
