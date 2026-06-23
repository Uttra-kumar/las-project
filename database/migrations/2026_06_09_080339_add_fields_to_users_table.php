<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns
            $table->string('mobile', 15)->unique()->nullable()->after('email');
            $table->enum('role', ['admin', 'teacher', 'student', 'parent'])->default('student')->after('mobile');
            $table->boolean('status')->default(1)->after('role'); // 1 = active, 0 = inactive
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop columns if migration rolls back
            $table->dropColumn(['mobile', 'role', 'status']);
        });
    }
};