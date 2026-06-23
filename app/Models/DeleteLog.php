<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeleteLog extends Model
{
    use HasFactory;
    
    protected $table = 'delete_logs';
    
    protected $fillable = [
        'receipt_no',
        'session_id',
        'student_id',
        'class_id',
        'fees_type_id',
        'payment_date',
        'amount',
        'discount',
        'fine',
        'paid_amount',
        'payment_mode',
        'remarks',
        'status',
        'created_by',
        'deleted_by',
        'delete_reason',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function feesType()
    {
        return $this->belongsTo(FeesType::class, 'fees_type_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}