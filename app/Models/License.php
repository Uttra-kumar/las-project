<?php
// app/Models/License.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = [
        'secret_key',
        'master_key',
        'expiry_date',
        'activated_at',
        'status'
    ];
}