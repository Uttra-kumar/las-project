<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('marks', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->string('exam_title');
            $table->date('exam_date')->nullable();
            $table->unsignedBigInteger('student_id');
            $table->json('marks_data')->nullable(); // ✅ {subject_id: {obtained: xx, max: yy}}
            $table->decimal('total_obtained', 8, 2)->default(0);
            $table->decimal('total_max', 8, 2)->default(0);
            $table->decimal('percentage', 8, 2)->default(0);
            $table->string('grade')->nullable();
            $table->text('remarks')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
            
            $table->unique(['session_id', 'class_id', 'stream_id', 'exam_title', 'student_id']);
            $table->index(['session_id', 'class_id', 'exam_title']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('marks');
    }
};