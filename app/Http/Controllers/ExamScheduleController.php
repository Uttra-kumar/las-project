<?php

namespace App\Http\Controllers;

use App\Models\ExamSchedule;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\Subject;
use App\Models\TeacherSubjectAllocation;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamScheduleController extends Controller
{
    // ✅ Exam Titles List
    private $examTitles = [
        'Monthly Test',
        'Quarterly Exam',
        'Half Yearly Exam',
        'Pre-Final Exam',
        'Final Exam'
    ];

    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $classes = Classes::where('status', 'active')->orderBy('class_name')->get();
        $streams = collect();
        $subjects = collect();
        
        $classId = $request->class_id;
        $streamId = $request->stream_id;
        $examTitle = $request->exam_title;
        
        // Get streams if class selected
        if ($classId) {
            $streams = Stream::where('class_id', $classId)->where('status', 'active')->get();
        }
        
        // Get subjects for dropdown
        if ($classId) {
            $query = TeacherSubjectAllocation::where('session_id', $sessionId)
                ->where('class_id', $classId);
            
            if ($streamId) {
                $query->where('stream_id', $streamId);
            } else {
                $query->whereNull('stream_id');
            }
            
            $allocations = $query->with(['subject', 'teacher'])->get();
            
            foreach ($allocations as $allocation) {
                if ($allocation->subject) {
                    $subjects->push((object)[
                        'id' => $allocation->subject_id,
                        'subject_name' => $allocation->subject->subject_name,
                        'teacher_id' => $allocation->teacher_id,
                        'teacher_name' => $allocation->teacher->full_name ?? 'N/A',
                    ]);
                }
            }
        }
        
        // Get existing schedule
        $schedule = null;
        $scheduleData = [];
        
        if ($classId && $examTitle) {
            $schedule = ExamSchedule::where('session_id', $sessionId)
                ->where('class_id', $classId)
                ->where('stream_id', $streamId)
                ->where('exam_title', $examTitle)
                ->first();
            
            if ($schedule) {
                $scheduleData = $schedule->schedule_data ?? [];
            }
        }
        
        return view('exam.schedule', compact(
            'classes',
            'streams',
            'subjects',
            'classId',
            'streamId',
            'examTitle',
            'scheduleData',
            'schedule',
            'currentSession'
        ));
    }
    
    // ✅ GET STREAMS - AJAX
    public function getStreams(Request $request)
    {
        $classId = $request->class_id;
        
        if (!$classId) {
            return response()->json([
                'success' => false,
                'streams' => []
            ]);
        }
        
        $streams = Stream::where('class_id', $classId)
            ->where('status', 'active')
            ->orderBy('stream_name')
            ->get();
        
        return response()->json([
            'success' => true,
            'streams' => $streams
        ]);
    }
    
    // ✅ GET SUBJECTS - AJAX
    public function getSubjects(Request $request)
    {
        $classId = $request->class_id;
        $streamId = $request->stream_id;
        $sessionId = SessionHelper::getCurrentSessionId();
        
        if (!$classId) {
            return response()->json([
                'success' => false,
                'subjects' => []
            ]);
        }
        
        $query = TeacherSubjectAllocation::where('session_id', $sessionId)
            ->where('class_id', $classId);
        
        if ($streamId) {
            $query->where('stream_id', $streamId);
        } else {
            $query->whereNull('stream_id');
        }
        
        $allocations = $query->with(['subject', 'teacher'])->get();
        
        $subjects = [];
        
        foreach ($allocations as $allocation) {
            if ($allocation->subject) {
                $subjects[] = [
                    'subject_id' => $allocation->subject_id,
                    'subject_name' => $allocation->subject->subject_name ?? 'N/A',
                    'teacher_id' => $allocation->teacher_id,
                    'teacher_name' => $allocation->teacher->full_name ?? 'N/A',
                ];
            }
        }
        
        if (empty($subjects) && $streamId) {
            $stream = Stream::find($streamId);
            if ($stream && $stream->subjects) {
                $subjectIds = $stream->subjects;
                $subjectList = Subject::whereIn('id', $subjectIds)->get();
                
                foreach ($subjectList as $subject) {
                    $subjects[] = [
                        'subject_id' => $subject->id,
                        'subject_name' => $subject->subject_name,
                        'teacher_id' => null,
                        'teacher_name' => 'Teacher not assigned',
                    ];
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'subjects' => $subjects
        ]);
    }
    
    public function store(Request $request)
{
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'exam_title' => 'required|string|max:255',
        'schedule_data' => 'nullable|array',
    ]);
    
    // ✅ Debug - Check what's coming
    \Log::info('=== Exam Schedule Store ===');
    \Log::info('Class ID: ' . $request->class_id);
    \Log::info('Exam Title: ' . $request->exam_title);
    \Log::info('Schedule Data: ', $request->schedule_data ?? []);
    
    $currentSession = SessionHelper::getCurrentSession();
    $sessionId = $currentSession->session_id;
    
    $schedule = ExamSchedule::where('session_id', $sessionId)
        ->where('class_id', $request->class_id)
        ->where('stream_id', $request->stream_id)
        ->where('exam_title', $request->exam_title)
        ->first();
    
    $scheduleData = $request->schedule_data ?? [];
    
    // ✅ Filter empty rows - Check properly
    $filteredData = array_filter($scheduleData, function($row) {
        return !empty($row['date']) && !empty($row['subject_id']);
    });
    
    \Log::info('Filtered Data Count: ' . count($filteredData));
    
    $data = [
        'session_id' => $sessionId,
        'class_id' => $request->class_id,
        'stream_id' => $request->stream_id,
        'exam_title' => $request->exam_title,
        'schedule_data' => array_values($filteredData),
        'remarks' => $request->remarks,
        'created_by' => Auth::id(),
    ];
    
    if ($schedule) {
        $schedule->update($data);
    } else {
        ExamSchedule::create($data);
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Exam schedule saved successfully!'
    ]);
}
    
    public function destroy($id)
    {
        $schedule = ExamSchedule::findOrFail($id);
        $schedule->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Exam schedule deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $sessionId = $currentSession->session_id;
    
    $query = ExamSchedule::where('session_id', $sessionId)
        ->with(['class', 'stream']);
    
    if ($request->filled('class_id')) {
        $query->where('class_id', $request->class_id);
    }
    
    $schedules = $query->orderBy('id', 'desc')->get();
    
    $filename = 'exam_schedule_' . date('Y-m-d') . '.csv';
    $handle = fopen('php://output', 'w');
    
    // ✅ Headers with Start Time & End Time
    fputcsv($handle, ['S.No', 'Class', 'Stream', 'Exam Title', 'Date', 'Start Time', 'End Time', 'Subject', 'Teacher']);
    
    foreach ($schedules as $index => $schedule) {
        $scheduleData = $schedule->schedule_data ?? [];
        foreach ($scheduleData as $row) {
            fputcsv($handle, [
                $index + 1,
                $schedule->class->class_name ?? 'N/A',
                $schedule->stream->stream_name ?? 'No Stream',
                $schedule->exam_title,
                $row['date'] ?? '',
                $row['start_time'] ?? '',
                $row['end_time'] ?? '',
                $row['subject_name'] ?? '',
                $row['teacher_name'] ?? '',
            ]);
        }
    }
    
    fclose($handle);
    
    return response()->stream(
        function() use ($handle) {},
        200,
        [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]
    );
}
}