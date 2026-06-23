<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('month_name'); // January 2026
            $table->date('payment_date');
            $table->unsignedBigInteger('teacher_id');
            
            // Attendance Summary
            $table->integer('total_present')->default(0);
            $table->integer('total_absent')->default(0);
            $table->integer('total_leave')->default(0);
            
            // Salary Details
            $table->decimal('salary', 10, 2)->default(0); // Monthly salary
            $table->decimal('amount', 10, 2)->default(0); // Salary for days worked
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('esic', 10, 2)->default(0);
            $table->decimal('other', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0); // amount - pf - esic - other
            
            $table->string('salary_type')->default('bank'); // bank / cash
            $table->string('remarks')->nullable();
            $table->enum('status', ['paid', 'hold'])->default('hold');
            
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('teacher_id')->references('id')->on('teachers')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Unique constraint
            $table->unique(['teacher_id', 'month_name']);
            
            // Indexes
            $table->index(['session_id', 'month_name']);
            $table->index('teacher_id');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_salaries');
    }
};