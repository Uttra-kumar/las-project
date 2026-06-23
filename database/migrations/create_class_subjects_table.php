<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 10);
            $table->unsignedBigInteger('class_id');
            $table->json('subject_ids');  // JSON column to store all subjects
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->unique(['session_id', 'class_id'], 'unique_class_session');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_subjects');
    }
};