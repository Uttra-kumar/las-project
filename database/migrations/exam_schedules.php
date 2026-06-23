exam_schedules<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('stream_id')->nullable();
            $table->string('exam_title');
            $table->json('schedule_data')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('stream_id')->references('id')->on('streams')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            $table->unique(['session_id', 'class_id', 'stream_id', 'exam_title']);
            $table->index(['session_id', 'class_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_schedules');
    }
};