<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSession;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SessionController extends Controller
{
    public function index()
    {
        // Show all sessions in admin panel (active + inactive)
        $sessions = SchoolSession::orderBy('id', 'desc')->paginate(10);
        return view('control-panel.session', compact('sessions'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'session_name' => 'required|string|max:255|unique:sessions_table,session_name',
        ]);
        
        $sessionId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        
        while (SchoolSession::where('session_id', $sessionId)->exists()) {
            $sessionId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        SchoolSession::create([
            'session_id' => $sessionId,
            'session_name' => $request->session_name,
            'status' => 'active', // New sessions are active by default
        ]);
        
        return response()->json(['success' => 'Session created successfully!']);
    }
    
    public function edit($id)
    {
        $session = SchoolSession::findOrFail($id);
        return response()->json($session);
    }
    
    public function update(Request $request, $id)
    {
        $session = SchoolSession::findOrFail($id);
        
        $request->validate([
            'session_name' => 'required|string|max:255|unique:sessions_table,session_name,'.$id,
            'status' => 'required|in:active,inactive',
        ]);
        
        $session->update([
            'session_name' => $request->session_name,
            'status' => $request->status,
        ]);
        
        return response()->json(['success' => 'Session updated successfully!']);
    }
    
    public function destroy($id)
    {
        $session = SchoolSession::findOrFail($id);
        
        // Check if this is the current selected session
        $currentSessionId = SessionHelper::getCurrentSessionId();
        if ($session->session_id == $currentSessionId) {
            return response()->json(['error' => 'Cannot delete currently selected session!'], 400);
        }
        
        $session->delete();
        
        return response()->json(['success' => 'Session deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $sessions = SchoolSession::where('session_name', 'like', "%{$search}%")
            ->orWhere('session_id', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('control-panel.session-table', compact('sessions'))->render();
    }

    public function changeSession(Request $request)
    {
        try {
            $request->validate([
                'session_id' => 'required|exists:sessions_table,session_id'
            ]);
            
            $session = SchoolSession::where('session_id', $request->session_id)
                ->where('status', 'active')
                ->first();
            
            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session is not active!'
                ], 400);
            }
            
            // Save to Laravel session
            Session::put('current_session_id', $session->session_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Session changed to ' . $session->session_name,
                'session_name' => $session->session_name,
                'session_id' => $session->session_id
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getSessionInfo()
    {
        try {
            $currentSession = SessionHelper::getCurrentSession();
            $activeSessions = SessionHelper::getActiveSessions();
            
            return response()->json([
                'success' => true,
                'current_session' => $currentSession,
                'active_sessions' => $activeSessions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}