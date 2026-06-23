<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeesType;
use App\Models\SchoolSession;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class FeesTypeController extends Controller
{
    public function index()
    {
        // Get current active session
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Get all sessions for dropdown
        $allSessions = SchoolSession::orderBy('id', 'desc')->get();
        
        // Filter by current session
        $feesTypes = FeesType::where('session_id', $currentSessionId)
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('fees.index', compact('feesTypes', 'currentSession', 'allSessions'));
    }
    
    // Get fees types by session (AJAX)
    public function getBySession(Request $request)
    {
        $sessionId = $request->session_id;
        $feesTypes = FeesType::where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $feesTypes
        ]);
    }
    
    // Copy fees types from previous session to current session
    public function copyFromPrevious(Request $request)
    {
        $previousSessionId = $request->previous_session_id;
        $currentSession = SessionHelper::getCurrentSession();
        
        if (!$currentSession) {
            return response()->json(['error' => 'No active session found!'], 400);
        }
        
        // Get all fees types from previous session
        $previousFeesTypes = FeesType::where('session_id', $previousSessionId)->get();
        
        if ($previousFeesTypes->isEmpty()) {
            return response()->json(['error' => 'No fees types found in previous session!'], 400);
        }
        
        $copiedCount = 0;
        $skippedCount = 0;
        
        foreach ($previousFeesTypes as $previous) {
            // Check if already exists in current session
            $exists = FeesType::where('session_id', $currentSession->session_id)
                ->where('name', $previous->name)
                ->exists();
            
            if (!$exists) {
                // Generate new 4 digit random fee_type_id
                $feeTypeId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                while (FeesType::where('fee_type_id', $feeTypeId)->exists()) {
                    $feeTypeId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                // Create new fees type for current session
                FeesType::create([
                    'fee_type_id' => $feeTypeId,
                    'session_id' => $currentSession->session_id,
                    'name' => $previous->name,
                    'status' => 'active',
                ]);
                $copiedCount++;
            } else {
                $skippedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Copied {$copiedCount} fees types to current session. Skipped {$skippedCount} (already exists).",
            'copied' => $copiedCount,
            'skipped' => $skippedCount
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        if (!$currentSession) {
            return response()->json(['error' => 'No active session found!'], 400);
        }
        
        // Check if fee type already exists for this session
        $exists = FeesType::where('session_id', $currentSession->session_id)
            ->where('name', $request->name)
            ->exists();
            
        if ($exists) {
            return response()->json(['error' => 'Fee type already exists for this session!'], 400);
        }
        
        // Generate 4 digit random fee_type_id
        $feeTypeId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        while (FeesType::where('fee_type_id', $feeTypeId)->exists()) {
            $feeTypeId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        FeesType::create([
            'fee_type_id' => $feeTypeId,
            'session_id' => $currentSession->session_id,
            'name' => $request->name,
            'status' => 'active',
        ]);
        
        return response()->json(['success' => 'Fee type created successfully!']);
    }
    
    public function edit($id)
    {
        $feeType = FeesType::findOrFail($id);
        return response()->json($feeType);
    }
    
    public function update(Request $request, $id)
    {
        $feeType = FeesType::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        // Check if name already exists for this session (excluding current)
        $exists = FeesType::where('session_id', $feeType->session_id)
            ->where('name', $request->name)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return response()->json(['error' => 'Fee type already exists for this session!'], 400);
        }
        
        $feeType->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);
        
        return response()->json(['success' => 'Fee type updated successfully!']);
    }
    
    public function destroy($id)
    {
        $feeType = FeesType::findOrFail($id);
        $feeType->delete();
        
        return response()->json(['success' => 'Fee type deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $feesTypes = FeesType::where('session_id', $currentSessionId)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('fee_type_id', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('fees.fees-table', compact('feesTypes'))->render();
    }
    
    public function view($id)
    {
        $feeType = FeesType::findOrFail($id);
        return response()->json($feeType);
    }
}