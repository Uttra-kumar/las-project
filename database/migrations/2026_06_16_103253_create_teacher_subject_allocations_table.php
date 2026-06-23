<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subject_allocations', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 10);
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('teacher_id');
            $table->timestamps();
            
            // Unique constraint to prevent duplicates
            $table->unique(['session_id', 'class_id', 'subject_id'], 'unique_allocation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_allocations');
    }
};