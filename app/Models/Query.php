<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $table = 'queries';
    
    protected $fillable = [
        'name',
        'mobile',
        'email',
        'subject',
        'message',
        'remarks',
        'status'
    ];
}