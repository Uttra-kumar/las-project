<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finance_ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('ledger_name');
            $table->string('ledger_code')->nullable();
            $table->unsignedBigInteger('group_id');
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('gst_no')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->enum('balance_type', ['debit', 'credit'])->default('debit');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('group_id')->references('id')->on('finance_groups')->onDelete('restrict');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->unique('ledger_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('finance_ledgers');
    }
};