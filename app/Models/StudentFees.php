<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFees extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'student_id',
        'fees_master_id',
        'session_id',
        'fees_type_id',
        'amount',
        'status',
        'due_date',
        'paid_date',
        'remarks',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    
    public function feesMaster()
    {
        return $this->belongsTo(FeesMaster::class, 'fees_master_id');
    }
    
    public function feesType()
    {
        return $this->belongsTo(FeesType::class, 'fees_type_id');
    }
}