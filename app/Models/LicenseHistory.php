<?php
// app/Models/LicenseHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseHistory extends Model
{
    protected $fillable = [
        'license_id',
        'action',
        'old_expiry_date',
        'new_expiry_date',
        'status',
        'ip_address',
        'user_agent',
        'created_by'
    ];

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}