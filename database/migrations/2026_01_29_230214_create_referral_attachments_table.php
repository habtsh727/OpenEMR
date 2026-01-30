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
        Schema::create('referral_attachments', function (Blueprint $table) {
            $table->id();

            // Link to referral
            $table->foreignId('referral_id')
                  ->constrained('referrals')
                  ->cascadeOnDelete();

            // Track who uploaded the file
            $table->foreignId('uploaded_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Attachment info
            $table->enum(column: 'attachment_type', ['lab', 'imaging', 'report', 'other']); // Type of file
            $table->string('file_path');       // Stored file path in storage
            $table->string('file_name')->nullable(); // Original file name

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_attachments');
    }
};
