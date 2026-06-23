<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_groups', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->string('group_code')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('parent_group_id')->nullable(); // For sub-groups
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('parent_group_id')->references('id')->on('finance_groups')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unique('group_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_groups');
    }
};