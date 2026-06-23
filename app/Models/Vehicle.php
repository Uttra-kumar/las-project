<?php

namespace App\Models;

use App\Models\User;
use App\Models\SchoolSession;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $table = 'vehicles';
    
    protected $fillable = [
        'session_id',
        'vehicle_name',
        'color',
        'capacity',
        'route',
        'driver',
        'helper',
        'registration_no',
        'insurance_date',
        'expiry_date',
        'type',
        'status',
        'remarks',
        'created_by'
    ];
    
    protected $casts = [
        'insurance_date' => 'date',
        'expiry_date' => 'date',
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}