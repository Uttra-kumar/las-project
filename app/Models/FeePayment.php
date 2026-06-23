<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'receipt_no', 'session_id', 'student_id', 'class_id', 'fees_type_id',
        'payment_date', 'amount', 'discount', 'fine', 'paid_amount',
        'payment_mode', 'remarks', 'status', 'created_by', 'is_edited', 'is_deleted'
    ];
    
    // Scope for active records only
    public function scopeActive($query)
    {
        return $query->where('is_deleted', 0);
    }
    
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
    public function logs()
    {
        return $this->hasMany(FeePaymentLog::class, 'fee_payment_id');
    }
    public function session()
{
    return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
}
}