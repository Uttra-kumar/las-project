<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('vehicle_name');
            $table->string('color')->nullable();
            $table->string('capacity')->nullable();
            $table->string('route')->nullable();
            $table->string('driver')->nullable();
            $table->string('helper')->nullable();
            $table->string('registration_no')->unique();
            $table->date('insurance_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('type', ['renter', 'owned'])->default('owned');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->index(['session_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
};