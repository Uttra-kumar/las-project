<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FeesMaster;
use App\Models\Classes;
use App\Models\FeesType;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class FeesMasterController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Get all sessions for dropdown
        $allSessions = \App\Models\SchoolSession::orderBy('id', 'desc')->get();
        
        $query = FeesMaster::with(['class', 'feesType'])
            ->where('session_id', $currentSessionId);
        
        // Apply filters
        if ($request->has('class_id') && $request->class_id) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->has('fees_type_id') && $request->fees_type_id) {
            $query->where('fees_type_id', $request->fees_type_id);
        }
        
        $feesMasters = $query->orderBy('id', 'desc')->paginate(10);
        
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        $feesTypes = FeesType::where('session_id', $currentSessionId)
            ->where('status', 'active')
            ->orderBy('name', 'asc')
            ->get();
            
        return view('fees.master', compact('feesMasters', 'classes', 'feesTypes', 'currentSession', 'allSessions'));
    }
    
    // Get fees masters by session (AJAX)
    public function getBySession(Request $request)
    {
        $sessionId = $request->session_id;
        $feesMasters = FeesMaster::with(['class', 'feesType'])
            ->where('session_id', $sessionId)
            ->orderBy('id', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $feesMasters
        ]);
    }
    
    // Copy fees masters from previous session to current session
    public function copyFromPrevious(Request $request)
    {
        $previousSessionId = $request->previous_session_id;
        $currentSession = SessionHelper::getCurrentSession();
        
        if (!$currentSession) {
            return response()->json(['error' => 'No active session found!'], 400);
        }
        
        // Get all fees masters from previous session
        $previousFeesMasters = FeesMaster::where('session_id', $previousSessionId)->get();
        
        if ($previousFeesMasters->isEmpty()) {
            return response()->json(['error' => 'No fees structures found in previous session!'], 400);
        }
        
        $copiedCount = 0;
        $skippedCount = 0;
        
        foreach ($previousFeesMasters as $previous) {
            // Check if already exists in current session (same class + same fee type)
            $exists = FeesMaster::where('session_id', $currentSession->session_id)
                ->where('class_id', $previous->class_id)
                ->where('fees_type_id', $previous->fees_type_id)
                ->exists();
            
            if (!$exists) {
                // Generate new 4 digit random master_id
                $masterId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                while (FeesMaster::where('master_id', $masterId)->exists()) {
                    $masterId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
                }
                
                // Create new fees master for current session
                FeesMaster::create([
                    'master_id' => $masterId,
                    'session_id' => $currentSession->session_id,
                    'class_id' => $previous->class_id,
                    'fees_type_id' => $previous->fees_type_id,
                    'amount' => $previous->amount,
                    'status' => 'active',
                ]);
                $copiedCount++;
            } else {
                $skippedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Copied {$copiedCount} fee structures to current session. Skipped {$skippedCount} (already exists).",
            'copied' => $copiedCount,
            'skipped' => $skippedCount
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fees_type_id' => 'required|exists:fees_types,id',
            'amount' => 'required|numeric|min:0',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        if (!$currentSession) {
            return response()->json(['error' => 'No active session found!'], 400);
        }
        
        // Check if already exists
        $exists = FeesMaster::where('session_id', $currentSession->session_id)
            ->where('class_id', $request->class_id)
            ->where('fees_type_id', $request->fees_type_id)
            ->exists();
            
        if ($exists) {
            return response()->json(['error' => 'Fee structure already exists for this class and fee type!'], 400);
        }
        
        // Generate 4 digit random master_id
        $masterId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        while (FeesMaster::where('master_id', $masterId)->exists()) {
            $masterId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        FeesMaster::create([
            'master_id' => $masterId,
            'session_id' => $currentSession->session_id,
            'class_id' => $request->class_id,
            'fees_type_id' => $request->fees_type_id,
            'amount' => $request->amount,
            'status' => 'active',
        ]);
        
        return response()->json(['success' => 'Fee structure created successfully!']);
    }
    
    public function edit($id)
    {
        $feeMaster = FeesMaster::with(['class', 'feesType'])->findOrFail($id);
        return response()->json($feeMaster);
    }
    
    public function update(Request $request, $id)
    {
        $feeMaster = FeesMaster::findOrFail($id);
        
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'fees_type_id' => 'required|exists:fees_types,id',
            'amount' => 'required|numeric|min:0',
        ]);
        
        // Check if already exists (excluding current)
        $exists = FeesMaster::where('session_id', $feeMaster->session_id)
            ->where('class_id', $request->class_id)
            ->where('fees_type_id', $request->fees_type_id)
            ->where('id', '!=', $id)
            ->exists();
            
        if ($exists) {
            return response()->json(['error' => 'Fee structure already exists for this class and fee type!'], 400);
        }
        
        $feeMaster->update([
            'class_id' => $request->class_id,
            'fees_type_id' => $request->fees_type_id,
            'amount' => $request->amount,
            'status' => $request->status,
        ]);
        
        return response()->json(['success' => 'Fee structure updated successfully!']);
    }
    
    public function destroy($id)
    {
        $feeMaster = FeesMaster::findOrFail($id);
        $feeMaster->delete();
        
        return response()->json(['success' => 'Fee structure deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $feesMasters = FeesMaster::with(['class', 'feesType'])
            ->where('session_id', $currentSessionId)
            ->where(function($query) use ($search) {
                $query->where('master_id', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhereHas('class', function($q) use ($search) {
                        $q->where('class_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('feesType', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('fees.master-table', compact('feesMasters'))->render();
    }
    
    public function view($id)
    {
        $feeMaster = FeesMaster::with(['class', 'feesType', 'session'])->findOrFail($id);
        return response()->json($feeMaster);
    }
}