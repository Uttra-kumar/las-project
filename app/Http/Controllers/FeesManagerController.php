<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Models\FeesMaster;
use App\Models\FeesType;
use App\Models\StudentFees;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class FeesManagerController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Get all classes for current session
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        // Selected class
        $selectedClass = $request->class_id;
        $students = [];
        $feesMasters = [];
        
        if ($selectedClass) {
            // Get students of selected class (current session only)
            $students = Student::where('class_id', $selectedClass)
                ->where('session_id', $currentSessionId)
                ->where('is_deleted', 0)
                ->orderBy('full_name', 'asc')
                ->get();
            
            // Get fees masters for this class
            $feesMasters = FeesMaster::with(['feesType'])
                ->where('class_id', $selectedClass)
                ->where('session_id', $currentSessionId)
                ->where('status', 'active')
                ->get();
        }
        
        return view('fees.manager', compact('classes', 'students', 'feesMasters', 'selectedClass', 'currentSession'));
    }
    
    // Assign fees to students
    public function assignFees(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'fees_master_id' => 'required|exists:fees_masters,id',
            'amount' => 'required|numeric|min:0',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $feesMaster = FeesMaster::find($request->fees_master_id);
        $assignedCount = 0;
        
        foreach ($request->student_ids as $studentId) {
            // Check if already assigned
            $exists = StudentFees::where('student_id', $studentId)
                ->where('fees_master_id', $request->fees_master_id)
                ->where('session_id', $currentSessionId)
                ->exists();
            
            if (!$exists) {
                StudentFees::create([
                    'student_id' => $studentId,
                    'fees_master_id' => $request->fees_master_id,
                    'session_id' => $currentSessionId,
                    'fees_type_id' => $feesMaster->fees_type_id,
                    'amount' => $request->amount,
                    'status' => 'pending',
                    'due_date' => now()->addDays(30),
                ]);
                $assignedCount++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "Fees assigned to {$assignedCount} students successfully!",
            'assigned' => $assignedCount
        ]);
    }
    
    // Get fees master details for display
    public function getFeesMasterDetails($id)
    {
        $feesMaster = FeesMaster::with(['feesType', 'class'])->findOrFail($id);
        return response()->json($feesMaster);
    }

// Get assigned fees for a class
public function getAssignedFees(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $assigned = StudentFees::with(['student'])
        ->where('session_id', $currentSessionId)
        ->where('fees_master_id', $request->fees_type_id)
        ->whereHas('student', function($q) use ($request) {
            $q->where('class_id', $request->class_id)->where('is_deleted', 0);
        })
        ->get();
    
    return response()->json($assigned);
}

// Remove assigned fees from students
public function removeFees(Request $request)
{
    $request->validate([
        'student_ids' => 'required|array',
        'fees_master_id' => 'required|exists:fees_masters,id',
    ]);
    
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    $feesMaster = FeesMaster::find($request->fees_master_id);
    
    $removedCount = StudentFees::where('session_id', $currentSessionId)
        ->where('fees_master_id', $request->fees_master_id)
        ->whereIn('student_id', $request->student_ids)
        ->delete();
    
    return response()->json([
        'success' => true,
        'message' => "Fees removed from {$removedCount} student(s)!",
        'removed' => $removedCount
    ]);
}
    
}