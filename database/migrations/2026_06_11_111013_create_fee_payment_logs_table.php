<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fee_payment_id');
            $table->enum('action', ['created', 'edited', 'deleted']);
            $table->json('old_data')->nullable();
            $table->json('new_data')->nullable();
            $table->unsignedBigInteger('changed_by');
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('fee_payment_id')->references('id')->on('fee_payments');
            $table->foreign('changed_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payment_logs');
    }
};