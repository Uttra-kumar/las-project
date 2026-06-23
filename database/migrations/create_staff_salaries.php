<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->string('month_name');
            $table->date('payment_date');
            $table->unsignedBigInteger('staff_id');
            $table->decimal('salary', 10, 2)->default(0);
            $table->decimal('amount', 10, 2)->default(0);
            $table->decimal('pf', 10, 2)->default(0);
            $table->decimal('esic', 10, 2)->default(0);
            $table->decimal('other', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->string('salary_type')->default('bank');
            $table->string('remarks')->nullable();
            $table->enum('status', ['paid', 'hold'])->default('hold');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('staff_id')->references('id')->on('other_staff')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unique(['staff_id', 'month_name']);
            $table->index(['session_id', 'month_name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_salaries');
    }
};