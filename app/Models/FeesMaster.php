<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeesMaster extends Model
{
    use HasFactory;
    
    protected $table = 'fees_masters';
    
    protected $fillable = [
        'master_id',
        'session_id',
        'class_id',
        'fees_type_id',
        'amount',
        'status',
    ];
    
    // Relations
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    
    public function feesType()
    {
        return $this->belongsTo(FeesType::class, 'fees_type_id');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
}