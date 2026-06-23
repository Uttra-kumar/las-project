<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassSubject;
use App\Models\TeacherSubjectAllocation;
use App\Models\Stream;
use App\Models\SchoolSession;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherSubjectAllocationController extends Controller
{
   public function index(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
    $teachers = Teacher::where('session_id', $currentSessionId)
        ->where('is_deleted', 0)
        ->orderBy('full_name', 'asc')
        ->get();
    
    $selectedClass = $request->class_id;
    $selectedStream = $request->stream_id;
    $subjects = collect();
    $allocations = [];
    $streams = [];
    $isStreamClass = false;
    
    // ✅ FIX: Get subjects from class_subjects table
    if ($selectedClass) {
        $classSubject = ClassSubject::where('session_id', $currentSessionId)
            ->where('class_id', $selectedClass)
            ->first();

        if ($classSubject && $classSubject->subject_ids) {
            // ✅ FIX: Check if it's already an array
            if (is_array($classSubject->subject_ids)) {
                $subjectIds = $classSubject->subject_ids;
            } else {
                $subjectIds = json_decode($classSubject->subject_ids, true);
            }
            
            $subjects = Subject::whereIn('id', $subjectIds)
                ->where('status', 'active')
                ->orderBy('subject_name', 'asc')
                ->get();
        }
    }
    
    if ($selectedClass) {
        $class = Classes::find($selectedClass);
        if ($class) {
            $isStreamClass = $class->is_stream == 1;
            
            if ($isStreamClass) {
                $streams = Stream::where('class_id', $selectedClass)
                    ->where('status', 'active')
                    ->orderBy('stream_name', 'asc')
                    ->get();
                
                if ($selectedStream) {
                    $allocations = TeacherSubjectAllocation::with(['subject', 'teacher'])
                        ->where('session_id', $currentSessionId)
                        ->where('class_id', $selectedClass)
                        ->where('stream_id', $selectedStream)
                        ->orderBy('id', 'asc')
                        ->get();
                } else {
                    $allocations = collect();
                }
            } else {
                $allocations = TeacherSubjectAllocation::with(['subject', 'teacher'])
                    ->where('session_id', $currentSessionId)
                    ->where('class_id', $selectedClass)
                    ->whereNull('stream_id')
                    ->orderBy('id', 'asc')
                    ->get();
            }
        }
    }
    
    $previousSessions = SchoolSession::where('session_id', '!=', $currentSessionId)
        ->orderBy('id', 'desc')
        ->get();
    
    return view('academics.teacher-subject-allocation', compact(
        'classes', 'teachers', 'subjects', 'allocations', 
        'selectedClass', 'selectedStream', 'currentSession', 
        'previousSessions', 'streams', 'isStreamClass'
    ));
}
    
  public function store(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'subject_id' => 'required|exists:subjects,id',
        'teacher_id' => 'required|exists:teachers,id',
    ]);
    
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $class = Classes::find($request->class_id);
    $streamId = ($class->is_stream == 1) ? $request->stream_id : null;
    
    // ✅ DUPLICATE CHECK - More strict
    $exists = TeacherSubjectAllocation::where('session_id', $currentSessionId)
        ->where('class_id', $request->class_id)
        ->where(function($q) use ($streamId) {
            if ($streamId !== null) {
                $q->where('stream_id', $streamId);
            } else {
                $q->whereNull('stream_id');
            }
        })
        ->where('subject_id', $request->subject_id)
        ->exists();
    
    if ($exists) {
        return response()->json([
            'error' => 'This subject is already allocated for this class/stream!'
        ], 400);
    }
    
    TeacherSubjectAllocation::create([
        'session_id' => $currentSessionId,
        'class_id' => $request->class_id,
        'stream_id' => $streamId,
        'subject_id' => $request->subject_id,
        'teacher_id' => $request->teacher_id,
    ]);
    
    return response()->json(['success' => 'Subject allocated successfully!']);
}


    public function update(Request $request, $id)
{
    $request->validate([
        'teacher_id' => 'required|exists:teachers,id',
    ]);
    
    $allocation = TeacherSubjectAllocation::findOrFail($id);
    $allocation->update([
        'teacher_id' => $request->teacher_id,
    ]);
    
    return response()->json(['success' => 'Teacher updated successfully!']);
}

public function getAllocation($id)
{
    $allocation = TeacherSubjectAllocation::findOrFail($id);
    return response()->json($allocation);
}
    
    public function destroy($id)
    {
        $allocation = TeacherSubjectAllocation::findOrFail($id);
        $allocation->delete();
        
        return response()->json(['success' => 'Allocation removed successfully!']);
    }
    
    public function copyFromPrevious(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'from_session' => 'required|string',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $class = Classes::find($request->class_id);
        $streamId = $class->is_stream == 1 ? $request->stream_id : null;
        
        // Get previous allocations
        $previousAllocations = TeacherSubjectAllocation::where('session_id', $request->from_session)
            ->where('class_id', $request->class_id)
            ->when($streamId, function($q) use ($streamId) {
                $q->where('stream_id', $streamId);
            })
            ->when(!$streamId, function($q) {
                $q->whereNull('stream_id');
            })
            ->get();
        
        if ($previousAllocations->isEmpty()) {
            return response()->json(['error' => 'No allocations found in previous session!'], 404);
        }
        
        DB::beginTransaction();
        try {
            $copiedCount = 0;
            $skippedCount = 0;
            
            foreach ($previousAllocations as $prev) {
                // Check if already exists in current session
                $exists = TeacherSubjectAllocation::where('session_id', $currentSessionId)
                    ->where('class_id', $request->class_id)
                    ->where('stream_id', $streamId)
                    ->where('subject_id', $prev->subject_id)
                    ->exists();
                
                if (!$exists) {
                    TeacherSubjectAllocation::create([
                        'session_id' => $currentSessionId,
                        'class_id' => $request->class_id,
                        'stream_id' => $streamId,  // ✅ Copy with stream_id
                        'subject_id' => $prev->subject_id,
                        'teacher_id' => $prev->teacher_id,
                    ]);
                    $copiedCount++;
                } else {
                    $skippedCount++;
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Copied {$copiedCount} allocations! Skipped {$skippedCount} (already exist).",
                'copied' => $copiedCount,
                'skipped' => $skippedCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
}