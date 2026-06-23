<?php

namespace App\Helpers;

use App\Models\SchoolSession;
use Illuminate\Support\Facades\Session;

class SessionHelper
{
    /**
     * Get current selected session from session storage
     */
    public static function getCurrentSession()
    {
        $sessionId = Session::get('current_session_id');
        
        if ($sessionId) {
            $session = SchoolSession::where('session_id', $sessionId)
                ->where('status', 'active')
                ->first();
            if ($session) {
                return $session;
            }
        }
        
        // Return first active session as default
        return SchoolSession::where('status', 'active')->first();
    }
    
    /**
     * Get current session name
     */
    public static function getCurrentSessionName()
    {
        $session = self::getCurrentSession();
        return $session ? $session->session_name : 'No Active Session';
    }
    
    /**
     * Get current session ID
     */
    public static function getCurrentSessionId()
    {
        $session = self::getCurrentSession();
        return $session ? $session->session_id : null;
    }
    
    /**
     * Get all ACTIVE sessions only (for dropdown)
     */
    public static function getActiveSessions()
    {
        return SchoolSession::where('status', 'active')->orderBy('id', 'desc')->get();
    }
    
    /**
     * Get all sessions (for admin panel)
     */
    public static function getAllSessions()
    {
        return SchoolSession::orderBy('id', 'desc')->get();
    }
    
    /**
     * Set current session
     */
    public static function setCurrentSession($sessionId)
    {
        $session = SchoolSession::where('session_id', $sessionId)
            ->where('status', 'active')
            ->first();
            
        if ($session) {
            Session::put('current_session_id', $session->session_id);
            return true;
        }
        return false;
    }
}