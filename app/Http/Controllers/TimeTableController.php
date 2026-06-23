<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\TimeTable;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassSubject;
use App\Models\TeacherSubjectAllocation;
use App\Models\Stream;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class TimeTableController extends Controller
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
        $timeTable = null;
        $streams = [];
        $isStreamClass = false;
        $allocations = collect();
        
        if ($selectedClass) {
            $class = Classes::find($selectedClass);
            $isStreamClass = $class->is_stream == 1;
            
            if ($isStreamClass) {
                // Get streams for this class
                $streams = Stream::where('class_id', $selectedClass)
                    ->where('status', 'active')
                    ->orderBy('stream_name', 'asc')
                    ->get();
                
                if ($selectedStream) {
                    // Get allocations for this stream
                    $allocations = TeacherSubjectAllocation::with(['subject', 'teacher'])
                        ->where('session_id', $currentSessionId)
                        ->where('class_id', $selectedClass)
                        ->where('stream_id', $selectedStream)
                        ->get();
                    
                    // Get time table for this class + stream
                    $timeTable = TimeTable::where('session_id', $currentSessionId)
                        ->where('class_id', $selectedClass)
                        ->where('stream_id', $selectedStream)
                        ->first();
                }
            } else {
                // Non-stream class
                $allocations = TeacherSubjectAllocation::with(['subject', 'teacher'])
                    ->where('session_id', $currentSessionId)
                    ->where('class_id', $selectedClass)
                    ->whereNull('stream_id')
                    ->get();
                
                $timeTable = TimeTable::where('session_id', $currentSessionId)
                    ->where('class_id', $selectedClass)
                    ->whereNull('stream_id')
                    ->first();
            }
        }
        
        // Default time slots
        $defaultPeriods = [
            ['time' => '08:00 - 08:45', 'allocation_id' => '', 'is_break' => false],
            ['time' => '08:45 - 09:30', 'allocation_id' => '', 'is_break' => false],
            ['time' => '09:30 - 09:45', 'allocation_id' => '', 'subject_name' => 'BREAK', 'is_break' => true],
            ['time' => '09:45 - 10:30', 'allocation_id' => '', 'is_break' => false],
            ['time' => '10:30 - 11:15', 'allocation_id' => '', 'is_break' => false],
            ['time' => '11:15 - 11:30', 'allocation_id' => '', 'subject_name' => 'LUNCH BREAK', 'is_break' => true],
            ['time' => '11:30 - 12:15', 'allocation_id' => '', 'is_break' => false],
            ['time' => '12:15 - 13:00', 'allocation_id' => '', 'is_break' => false],
        ];
        
        $periods = $timeTable ? $timeTable->periods : $defaultPeriods;
        
        // Previous sessions for copy
        $previousSessions = \App\Models\SchoolSession::where('session_id', '!=', $currentSessionId)
            ->orderBy('id', 'desc')
            ->get();
        
        return view('academics.time-table', compact(
            'classes', 'teachers', 'selectedClass', 'selectedStream',
            'periods', 'currentSession', 'previousSessions',
            'streams', 'isStreamClass', 'allocations'
        ));
    }
    
    public function save(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'periods' => 'required|array',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $class = Classes::find($request->class_id);
        $streamId = ($class->is_stream == 1) ? $request->stream_id : null;
        
        TimeTable::updateOrCreate(
            [
                'session_id' => $currentSessionId,
                'class_id' => $request->class_id,
                'stream_id' => $streamId,
            ],
            [
                'periods' => $request->periods,
            ]
        );
        
        return response()->json(['success' => 'Time table saved successfully!']);
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
        $streamId = ($class->is_stream == 1) ? $request->stream_id : null;
        
        $previousTimeTable = TimeTable::where('session_id', $request->from_session)
            ->where('class_id', $request->class_id)
            ->when($streamId, function($q) use ($streamId) {
                $q->where('stream_id', $streamId);
            })
            ->when(!$streamId, function($q) {
                $q->whereNull('stream_id');
            })
            ->first();
        
        if (!$previousTimeTable) {
            return response()->json(['error' => 'No time table found in previous session'], 404);
        }
        
        TimeTable::updateOrCreate(
            [
                'session_id' => $currentSessionId,
                'class_id' => $request->class_id,
                'stream_id' => $streamId,
            ],
            [
                'periods' => $previousTimeTable->periods,
            ]
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Time table copied from previous session!',
            'periods' => $previousTimeTable->periods
        ]);
    }
}