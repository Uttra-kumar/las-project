<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('other_staff', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('emp_id')->unique();
            $table->string('full_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('dob')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('marital_status')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('experience')->default(0);
            $table->enum('department', ['guard', 'housekeeping', 'driver', 'other'])->default('other');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('block')->nullable();
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('pincode')->nullable();
            $table->string('highest_qualification')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->enum('salary_type', ['bank', 'cash'])->default('bank');
            $table->decimal('salary', 10, 2)->default(0);
            $table->date('registration_date')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'department', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('other_staff');
    }
};