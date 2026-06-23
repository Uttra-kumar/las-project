<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinanceGroup extends Model
{
    protected $table = 'finance_groups';
    
    protected $fillable = [
        'group_name',
        'group_code',
        'description',
        'status',
        'parent_group_id',
        'created_by'
    ];
    
    public function parent()
    {
        return $this->belongsTo(FinanceGroup::class, 'parent_group_id');
    }
    
    public function children()
    {
        return $this->hasMany(FinanceGroup::class, 'parent_group_id');
    }
    
    public function ledgers()
    {
        return $this->hasMany(FinanceLedger::class, 'group_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}