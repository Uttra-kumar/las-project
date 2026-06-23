<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('student_id', 20)->unique();
            $table->string('session_id', 10);
            $table->string('full_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->date('dob');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('category', 20); // ST, SC, General, Others
            $table->string('mobile', 15);
            $table->string('image')->nullable();
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('pincode', 10);
            $table->unsignedBigInteger('class_id');
            $table->string('previous_institute');
            $table->string('previous_class');
            $table->enum('previous_result', ['Pass', 'Fail', 'Supply']);
            $table->string('previous_marks');
            $table->boolean('is_promoted')->default(0);
            $table->boolean('is_hosteler')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();
            
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};