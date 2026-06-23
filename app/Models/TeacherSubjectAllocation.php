<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubjectAllocation extends Model
{
    use HasFactory;
    
    protected $table = 'teacher_subject_allocations';
    
    protected $fillable = [
        'session_id',
        'class_id',
        'stream_id',  // Add this
        'subject_id',
        'teacher_id',
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}