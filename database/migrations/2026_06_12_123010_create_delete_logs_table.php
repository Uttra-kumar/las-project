<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delete_logs', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_no', 50);
            $table->string('session_id', 10);
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('class_id');
            $table->unsignedBigInteger('fees_type_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('fine', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2);
            $table->enum('payment_mode', ['cash', 'upi', 'cheque', 'card', 'online']);
            $table->text('remarks')->nullable();
            $table->enum('status', ['paid', 'partial', 'pending']);
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('deleted_by');
            $table->text('delete_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delete_logs');
    }
};