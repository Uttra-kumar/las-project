<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_id', 20)->unique();
            $table->string('session_id', 10);
            $table->string('full_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->enum('marital_status', ['Married', 'Unmarried'])->nullable();
            $table->string('mobile', 15);
            $table->string('email')->nullable();
            $table->integer('experience')->default(0);
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('block')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode', 10)->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('institute_name')->nullable();
            $table->year('passing_year')->nullable();
            $table->string('obtained_marks')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->enum('salary_type', ['bank', 'cash'])->default('bank');
            $table->decimal('salary', 10, 2)->default(0);
            $table->boolean('is_hosteler')->default(0);
            $table->date('registration_date')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};