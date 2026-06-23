<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'teacher_id', 'session_id', 'full_name', 'father_name', 'mother_name',
        'dob', 'gender', 'marital_status', 'mobile', 'email', 'experience',
        'address', 'city', 'block', 'district', 'state', 'pincode',
        'highest_qualification', 'institute_name', 'passing_year', 'obtained_marks',
        'bank_name', 'account_no', 'ifsc_code', 'salary_type', 'salary',
        'is_hosteler', 'registration_date', 'image', 'is_deleted','status', 'created_by'
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
}