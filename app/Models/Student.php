<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    
    protected $table = 'students';
    
    protected $fillable = [
        'student_id',
        'session_id',
        'full_name',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'category',
        'mobile',
        'image',
        'address',
        'city',
        'state',
        'pincode',
        'class_id',
        'is_stream',
        'stream_id',
        'previous_institute',
        'previous_class',
        'previous_result',
        'previous_marks',
        'is_promoted',
        'is_hosteler',
        'is_deleted',
        'created_by',
    ];
    
    // Relation with Class
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    // Relation with SchoolSession - CORRECT
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
    
    // Relation with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
     public function feePayments()
    {
        return $this->hasMany(FeePayment::class, 'student_id');
    }
    public function stream()
{
    return $this->belongsTo(Stream::class, 'stream_id');
}
}