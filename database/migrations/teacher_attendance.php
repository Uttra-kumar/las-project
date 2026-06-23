<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            $table->string('session_id');
            $table->date('attendance_date');
            $table->string('day')->nullable();
            
            // ✅ JSON columns for attendance data
            $table->json('present_teachers')->nullable();
            $table->json('absent_teachers')->nullable();
            $table->json('leave_teachers')->nullable();
            
            // ✅ Summary stats
            $table->integer('total_present')->default(0);
            $table->integer('total_absent')->default(0);
            $table->integer('total_leave')->default(0);
            $table->integer('total_teachers')->default(0);
            
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Unique constraint - one record per day
            $table->unique(['session_id', 'attendance_date']);
            
            // Indexes
            $table->index(['session_id', 'attendance_date']);
            $table->index('attendance_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('teacher_attendances');
    }
};