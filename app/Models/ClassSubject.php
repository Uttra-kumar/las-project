<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    use HasFactory;
    
    protected $table = 'class_subjects';
    
    protected $fillable = [
        'session_id',
        'class_id',
        'subject_ids',
    ];
    
    protected $casts = [
        'subject_ids' => 'array',
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function subjects()
    {
        return Subject::whereIn('id', $this->subject_ids ?? [])->get();
    }
}