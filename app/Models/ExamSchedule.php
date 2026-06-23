<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $table = 'exam_schedules';
    
    protected $fillable = [
        'session_id',
        'class_id',
        'stream_id',
        'exam_title',
        'schedule_data',
        'remarks',
        'created_by'
    ];
    
    protected $casts = [
        'schedule_data' => 'array',
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}