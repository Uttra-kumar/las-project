<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $table = 'marks';
    
    protected $fillable = [
        'session_id',
        'class_id',
        'stream_id',
        'exam_title',
        'exam_date',
        'student_id',
        'marks_data',
        'total_obtained',
        'total_max',
        'percentage',
        'grade',
        'remarks',
        'status',
        'created_by',
        'approved_by',
        'approved_at'
    ];
    
    protected $casts = [
        'exam_date' => 'date',
        'marks_data' => 'array',
        'total_obtained' => 'decimal:2',
        'total_max' => 'decimal:2',
        'percentage' => 'decimal:2',
        'approved_at' => 'datetime',
    ];
    
    // Relationships
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    
    // ✅ YEH RELATIONSHIP ADD KAREIN
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    // Helper methods
    public function getMark($subjectId)
    {
        return $this->marks_data[$subjectId] ?? null;
    }
    
    public function getObtained($subjectId)
    {
        return $this->marks_data[$subjectId]['obtained'] ?? null;
    }
    
    public function getMax($subjectId)
    {
        return $this->marks_data[$subjectId]['max'] ?? null;
    }
}