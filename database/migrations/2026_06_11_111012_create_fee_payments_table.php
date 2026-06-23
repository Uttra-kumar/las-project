<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_payments', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 50)->unique();
            $table->string('session_id', 10);
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('fees_type_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->enum('payment_mode', ['cash', 'upi', 'cheque', 'card', 'online'])->default('cash');
            $table->text('remarks')->nullable();
            $table->enum('status', ['paid', 'partial', 'pending'])->default('pending');
            $table->unsignedBigInteger('created_by');
            $table->boolean('is_edited')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();
            
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('fees_type_id')->references('id')->on('fees_types');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_payments');
    }
};