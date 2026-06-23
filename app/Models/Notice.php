<?php

namespace App\Models;

use App\Models\User;
use App\Models\SchoolSession;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notices';
    
    protected $fillable = [
        'session_id',
        'notice_date',
        'title',
        'description',
        'status',
        'remarks',
        'created_by'
    ];
    
    protected $casts = [
        'notice_date' => 'date',
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function session()
    {
        return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
    }
    
    // ✅ Status Helpers
    public function isPublished()
    {
        return $this->status == '1';
    }
    
    public function isUnpublished()
    {
        return $this->status == '2';
    }
    
    public function getStatusLabelAttribute()
    {
        if ($this->status == '1') {
            return '<span class="badge badge-success"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Published</span>';
        }
        return '<span class="badge badge-warning"><i class="fas fa-circle" style="font-size:0.4rem;"></i> Unpublished</span>';
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