<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherAttendance extends Model
{
    protected $table = 'teacher_attendances';
    
    protected $fillable = [
        'session_id',
        'attendance_date',
        'day',
        'present_teachers',
        'absent_teachers',
        'leave_teachers',
        'total_present',
        'total_absent',
        'total_leave',
        'total_teachers',
        'remarks',
        'created_by'
    ];
    
    protected $casts = [
        'attendance_date' => 'date',
        'present_teachers' => 'array',
        'absent_teachers' => 'array',
        'leave_teachers' => 'array',
    ];
    
    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Scopes
    public function scopeDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }
    
    public function scopeSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
    
    // Get all teacher IDs with their status
    public function getAllTeacherStatus()
    {
        $all = [];
        
        foreach (($this->present_teachers ?? []) as $id) {
            $all[$id] = 'present';
        }
        foreach (($this->absent_teachers ?? []) as $id) {
            $all[$id] = 'absent';
        }
        foreach (($this->leave_teachers ?? []) as $id) {
            $all[$id] = 'leave';
        }
        
        return $all;
    }
}