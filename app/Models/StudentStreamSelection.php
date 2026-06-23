<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentStreamSelection extends Model
{
    use HasFactory;
    
    protected $table = 'student_stream_selections';
    
    protected $fillable = [
        'student_id',
        'session_id',
        'class_id',
        'stream_id',
        'status',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
    
    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }
}