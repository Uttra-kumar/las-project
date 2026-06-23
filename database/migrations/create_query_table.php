<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mobile');
            $table->string('email');
            $table->string('subject');
            $table->text('message');
            $table->text('remarks')->nullable();
            $table->enum('status', ['query', 'solve'])->default('query');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queries');
    }
};