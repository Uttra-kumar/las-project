<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('time_tables', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 10);
            $table->unsignedBigInteger('class_id');
            $table->string('day', 20); // Monday, Tuesday etc.
            $table->json('periods'); // JSON to store all periods
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_tables');
    }
};