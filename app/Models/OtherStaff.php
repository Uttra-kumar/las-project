<?php

namespace App\Models;

use App\Models\User;
use App\Models\SchoolSession;
use Illuminate\Database\Eloquent\Model;

class OtherStaff extends Model
{
    protected $table = 'other_staff';
    
    protected $fillable = [
        'session_id',
        'emp_id',
        'full_name',
        'father_name',
        'mother_name',
        'dob',
        'gender',
        'marital_status',
        'mobile',
        'email',
        'experience',
        'department',
        'address',
        'city',
        'block',
        'district',
        'state',
        'pincode',
        'highest_qualification',
        'bank_name',
        'account_no',
        'ifsc_code',
        'salary_type',
        'salary',
        'registration_date',
        'image',
        'is_deleted',
        'status',
        'created_by'
    ];
    
    protected $casts = [
        'dob' => 'date',
        'registration_date' => 'date',
        'salary' => 'decimal:2',
    ];
    
    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('is_deleted', 0);
    }
    
    public function scopeDepartment($query, $dept)
    {
        return $query->where('department', $dept);
    }
    
    // Accessors
    public function getDepartmentNameAttribute()
    {
        $departments = [
            'guard' => 'Guard',
            'housekeeping' => 'Housekeeping',
            'driver' => 'Driver',
            'other' => 'Other'
        ];
        return $departments[$this->department] ?? $this->department;
    }
    
    public function getStatusBadgeAttribute()
    {
        if ($this->status == 'active') {
            return '<span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Active</span>';
        }
        return '<span class="badge badge-danger"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Inactive</span>';
    }
}