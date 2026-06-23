<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'stream_id',
        'class_id',
        'stream_name',
        'subjects',
        'status',
    ];
    
    protected $casts = [
        'subjects' => 'array',
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function getSubjectsListAttribute()
    {
        return Subject::whereIn('id', $this->subjects ?? [])->get();
    }
}