<?php

namespace App\Models;

use App\Models\OtherStaff;
use App\Models\User;
use App\Models\SchoolSession;
use Illuminate\Database\Eloquent\Model;

class StaffSalary extends Model
{
    protected $table = 'staff_salaries';
    
    protected $fillable = [
        'session_id',
        'month_name',
        'payment_date',
        'staff_id',
        'salary',
        'amount',
        'pf',
        'esic',
        'other',
        'net_salary',
        'salary_type',
        'remarks',
        'status',
        'created_by'
    ];
    
    protected $casts = [
        'payment_date' => 'date',
        'salary' => 'decimal:2',
        'amount' => 'decimal:2',
        'pf' => 'decimal:2',
        'esic' => 'decimal:2',
        'other' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];
    
    public function staff()
    {
        return $this->belongsTo(OtherStaff::class, 'staff_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function scopeSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
    
    public function scopeMonth($query, $month)
    {
        return $query->where('month_name', $month);
    }
}