<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_payment_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('action', ['edited'])->default('edited');
            $table->unsignedBigInteger('fees_type_id');
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->unsignedBigInteger('edited_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edit_logs');
    }
};