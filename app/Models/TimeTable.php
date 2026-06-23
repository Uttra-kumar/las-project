<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'session_id',
        'class_id',
        'stream_id',  // Add this
        'periods',
    ];
    
    protected $casts = [
        'periods' => 'array',
    ];
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function stream()
    {
        return $this->belongsTo(Stream::class, 'stream_id');
    }
}