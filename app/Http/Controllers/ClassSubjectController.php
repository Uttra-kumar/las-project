<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\ClassSubject;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    public function index()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        $subjects = Subject::where('status', 'active')->orderBy('subject_name', 'asc')->get();
        
        // Get assigned subjects (one row per class)
        $assignedData = ClassSubject::where('session_id', $currentSessionId)
            ->get()
            ->keyBy('class_id');
        
        return view('academics.class-subject', compact('classes', 'subjects', 'assignedData', 'currentSession'));
    }
    
    public function assign(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Update or create (single row per class)
        ClassSubject::updateOrCreate(
            [
                'session_id' => $currentSessionId,
                'class_id' => $request->class_id,
            ],
            [
                'subject_ids' => $request->subject_ids,
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Subjects assigned successfully!'
        ]);
    }
    
    public function getSubjects($classId)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $assigned = ClassSubject::where('session_id', $currentSessionId)
            ->where('class_id', $classId)
            ->first();
        
        return response()->json([
            'assigned' => $assigned ? $assigned->subject_ids : []
        ]);
    }
}