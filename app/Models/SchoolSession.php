<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\SessionHelper;

class SchoolSession extends Model
{
    use HasFactory;
    
    protected $table = 'sessions_table';
    
    protected $fillable = [
        'session_id',
        'session_name',
        'status',
    ];
    
    // Get currently active session (Global)
    public static function getActiveSession()
    {
        return self::where('status', 'active')->first();
    }
    
    // Get current session name helper
    public static function currentName()
    {
        $session = self::getActiveSession();
        return $session ? $session->session_name : 'No Active Session';
    }
    
    // Get current session ID helper
    public static function currentId()
    {
        $session = self::getActiveSession();
        return $session ? $session->session_id : null;
    }
    public function session()
{
    return $this->belongsTo(SchoolSession::class, 'session_id', 'session_id');
}
}