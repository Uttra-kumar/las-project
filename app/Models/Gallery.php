<?php

namespace App\Models;

use App\Models\User;
use App\Models\SchoolSession;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';
    
    protected $fillable = [
        'session_id',
        'title',
        'description',
        'gallery_date',
        'image',
        'status',
        'remarks',
        'created_by'
    ];
    
    protected $casts = [
        'gallery_date' => 'date',
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
    
    public function isPublished()
    {
        return $this->status == '1';
    }
    
    public function scopePublished($query)
    {
        return $query->where('status', '1');
    }
    
    public function scopeUnpublished($query)
    {
        return $query->where('status', '2');
    }
}