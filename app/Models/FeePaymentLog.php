<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePaymentLog extends Model
{
    use HasFactory;
    
    protected $table = 'fee_payment_logs';
    
    protected $fillable = [
        'fee_payment_id',
        'action',
        'old_data',
        'new_data',
        'changed_by',
        'remarks',
    ];
    
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
    
    // Relation with FeePayment
    public function payment()
    {
        return $this->belongsTo(FeePayment::class, 'fee_payment_id');
    }
    
    // Relation with User who changed
    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}