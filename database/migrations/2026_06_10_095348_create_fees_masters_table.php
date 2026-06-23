<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees_masters', function (Blueprint $table) {
            $table->id();
            $table->string('master_id', 10)->unique();
            $table->string('session_id', 10);
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('fees_type_id');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->foreign('fees_type_id')->references('id')->on('fees_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees_masters');
    }
};