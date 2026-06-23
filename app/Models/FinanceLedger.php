<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceLedger extends Model
{
    protected $table = 'finance_ledgers';
    
    protected $fillable = [
        'ledger_name',
        'ledger_code',
        'group_id',
        'mobile',
        'email',
        'address',
        'gst_no',
        'opening_balance',
        'balance_type',
        'status',
        'remarks',
        'created_by'
    ];
    
    public function group()
    {
        return $this->belongsTo(FinanceGroup::class, 'group_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}