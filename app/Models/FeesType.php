<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesType extends Model
{
    use HasFactory;
    
    protected $table = 'fees_types';
    
    protected $fillable = [
        'fee_type_id',
        'session_id',
        'name',
        'status',
    ];
    
    // Relation with session
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
}