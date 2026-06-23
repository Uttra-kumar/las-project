<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->id();
            $table->string('stream_id', 10)->unique();
            $table->unsignedBigInteger('class_id');
            $table->string('stream_name');
            $table->json('subjects'); // Array of subject IDs
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
           
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('streams');
    }
};