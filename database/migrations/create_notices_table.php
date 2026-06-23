<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->date('notice_date');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['1', '2'])->default('1'); // 1 = Published, 2 = Unpublished
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
};