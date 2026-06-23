<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\StudentStreamSelection;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StreamAllocationController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        $streams = Stream::where('status', 'active')->orderBy('stream_name', 'asc')->get();
        
        $selectedClass = $request->class_id;
        $selectedStream = $request->stream_id;
        $students = [];
        
        if ($selectedClass) {
            // Get students of selected class who are not yet assigned stream (is_stream = 0)
            $query = Student::with('class')
                ->where('session_id', $currentSessionId)
                ->where('class_id', $selectedClass)
                ->where('is_deleted', 0)
                ->where('is_stream', 0);  // Only students without stream
                
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            }
            
            $students = $query->orderBy('full_name', 'asc')->get();
        }
        
        // Get already allocated students for display
        $allocatedStudents = [];
        if ($selectedClass && $selectedStream) {
            $allocatedStudents = Student::with('class')
                ->where('session_id', $currentSessionId)
                ->where('class_id', $selectedClass)
                ->where('stream_id', $selectedStream)
                ->where('is_stream', 1)
                ->where('is_deleted', 0)
                ->orderBy('full_name', 'asc')
                ->get();
        }
        
        return view('academics.stream-allocation', compact('classes', 'streams', 'students', 'allocatedStudents', 'selectedClass', 'selectedStream', 'currentSession'));
    }
    
    public function assignStreams(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'stream_id' => 'required|exists:streams,id',
            'class_id' => 'required|exists:classes,id',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        $stream = Stream::find($request->stream_id);
        
        DB::beginTransaction();
        try {
            $assignedCount = 0;
            $alreadyAssigned = 0;
            
            foreach ($request->student_ids as $studentId) {
                $student = Student::find($studentId);
                
                // Check if already assigned
                if ($student->is_stream == 1) {
                    $alreadyAssigned++;
                    continue;
                }
                
                // Update student table
                $student->update([
                    'is_stream' => 1,
                    'stream_id' => $request->stream_id,
                ]);
                
                // Save in student_stream_selections table
                StudentStreamSelection::create([
                    'student_id' => $studentId,
                    'session_id' => $currentSessionId,
                    'class_id' => $request->class_id,
                    'stream_id' => $request->stream_id,
                    'status' => 'active',
                ]);
                
                $assignedCount++;
            }
            
            DB::commit();
            
            $message = "{$assignedCount} students assigned to {$stream->stream_name} stream.";
            if ($alreadyAssigned > 0) {
                $message .= " {$alreadyAssigned} students were already assigned.";
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'assigned' => $assignedCount,
                'already' => $alreadyAssigned
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    
    public function removeStream(Request $request)
{
    $request->validate([
        'student_ids' => 'required|array',
        'student_ids.*' => 'exists:students,id',
    ]);
    
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    DB::beginTransaction();
    try {
        $removedCount = 0;
        
        foreach ($request->student_ids as $studentId) {
            $student = Student::find($studentId);
            
            if ($student->is_stream == 1) {
                // Update student table
                $student->update([
                    'is_stream' => 0,
                    'stream_id' => null,
                ]);
                
                // ✅ DELETE from student_stream_selections instead of update
                StudentStreamSelection::where('student_id', $studentId)
                    ->where('session_id', $currentSessionId)
                    ->delete();  // ← DELETE, not update
                
                $removedCount++;
            }
        }
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => "{$removedCount} students removed from stream.",
            'removed' => $removedCount
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
    }
}
    
    
    
    public function exportCsv(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $selectedClass = $request->class_id;
        $selectedStream = $request->stream_id;
        
        $query = Student::with('class')
            ->where('session_id', $currentSessionId)
            ->where('class_id', $selectedClass)
            ->where('is_deleted', 0);
            
        if ($selectedStream) {
            $query->where('stream_id', $selectedStream);
        }
        
        $students = $query->orderBy('full_name', 'asc')->get();
        
        $filename = "stream_allocation_" . date('Y-m-d_H-i-s') . ".csv";
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($students) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, ['S.No', 'Student ID', 'Student Name', 'Father Name', 'Class', 'Stream', 'Status']);
            
            foreach ($students as $index => $student) {
                $streamName = $student->stream ? $student->stream->stream_name : 'Not Assigned';
                $status = $student->is_stream == 1 ? 'Assigned' : 'Not Assigned';
                
                fputcsv($file, [
                    $index + 1,
                    $student->student_id,
                    $student->full_name,
                    $student->father_name ?? '-',
                    $student->class->class_name ?? 'N/A',
                    $streamName,
                    $status
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}