<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            $table->string('school_name');
            $table->string('mobile', 15);
            $table->string('email');
            $table->text('address');
            $table->string('udic', 50)->unique(); // UDIC code
            $table->string('license', 100)->unique(); // License key
            $table->string('logo_1')->nullable(); // Logo path
            $table->string('logo_2')->nullable(); // Logo path
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};