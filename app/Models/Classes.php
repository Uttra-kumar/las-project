<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    use HasFactory;
    
    protected $table = 'classes';
    
    protected $fillable = [
        'class_id',
        'class_name',
        'is_stream',
        'status',
    ];
    public function students()
    {
        return $this->hasMany(Student::class, 'class_id');
    }
    
}