<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherSalary extends Model
{
    protected $table = 'teacher_salaries';
    
    protected $fillable = [
        'session_id',
        'month_name',
        'payment_date',
        'teacher_id',
        'total_present',
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
    
    // Relationships
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    // Scopes
    public function scopeSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }
    
    public function scopeMonth($query, $month)
    {
        return $query->where('month_name', $month);
    }
    
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}