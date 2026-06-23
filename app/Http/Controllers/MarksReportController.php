<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\TeacherSubjectAllocation;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarksReportController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $user = Auth::user();
        $isAdmin = $user->role == 'admin';
        
        $classes = Classes::where('status', 'active')->orderBy('class_name')->get();
        $streams = collect();
        $exams = collect();
        
        $selectedClass = $request->class_id;
        $selectedStream = $request->stream_id;
        $selectedExam = $request->exam_title;
        
        // Get streams if class selected
        if ($selectedClass) {
            $class = Classes::find($selectedClass);
            if ($class && $class->is_stream == 1) {
                $streams = Stream::where('class_id', $selectedClass)
                    ->where('status', 'active')
                    ->orderBy('stream_name')
                    ->get();
            }
        }
        
        // Get exams for this class
        if ($selectedClass) {
            $exams = Mark::where('session_id', $sessionId)
                ->where('class_id', $selectedClass)
                ->when($selectedStream, function($q) use ($selectedStream) {
                    return $q->where('stream_id', $selectedStream);
                })
                ->distinct()
                ->pluck('exam_title');
        }
        
        // Get report data
        $reportData = [];
        $subjects = collect();
        $grandTotal = [];
        
        if ($selectedClass && $selectedExam) {
            // Get subjects for this class
            $query = TeacherSubjectAllocation::where('session_id', $sessionId)
                ->where('class_id', $selectedClass);
            
            if ($selectedStream) {
                $query->where('stream_id', $selectedStream);
            } else {
                $query->whereNull('stream_id');
            }
            
            $allocations = $query->with(['subject'])->get();
            $subjectIds = $allocations->pluck('subject_id')->unique();
            $subjects = Subject::whereIn('id', $subjectIds)
                ->where('status', 'active')
                ->orderBy('subject_name')
                ->get();
            
            // Get students
            $students = Student::where('session_id', $sessionId)
                ->where('class_id', $selectedClass)
                ->where('is_deleted', 0);
            
            if ($selectedStream) {
                $students->where('stream_id', $selectedStream);
            }
            
            $students = $students->orderBy('full_name')->get();
            
            // Get marks
            $marks = Mark::where('session_id', $sessionId)
                ->where('class_id', $selectedClass)
                ->where('stream_id', $selectedStream)
                ->where('exam_title', $selectedExam)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy('student_id');
            
            foreach ($students as $student) {
                $mark = $marks->get($student->id);
                $row = [
                    'student' => $student,
                    'marks' => $mark,
                    'subject_marks' => [],
                ];
                
                $totalObtained = 0;
                $totalMax = 0;
                
                foreach ($subjects as $subject) {
                    $subjectData = $mark && $mark->marks_data ? ($mark->marks_data[$subject->id] ?? null) : null;
                    $obtained = $subjectData['obtained'] ?? '-';
                    $max = $subjectData['max'] ?? '-';
                    
                    $row['subject_marks'][$subject->id] = [
                        'obtained' => $obtained,
                        'max' => $max,
                    ];
                    
                    if ($obtained !== '-') {
                        $totalObtained += (float) $obtained;
                        $totalMax += (float) $max;
                    }
                }
                
                $row['total_obtained'] = $totalObtained;
                $row['total_max'] = $totalMax;
                $row['percentage'] = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                $row['status'] = $mark ? $mark->status : 'pending';
                
                $reportData[] = $row;
            }
            
            // Calculate grand total
            $grandTotal = [
                'total_obtained' => 0,
                'total_max' => 0,
            ];
            
            foreach ($reportData as $row) {
                $grandTotal['total_obtained'] += $row['total_obtained'];
                $grandTotal['total_max'] += $row['total_max'];
            }
            
            $grandTotal['percentage'] = $grandTotal['total_max'] > 0 
                ? round(($grandTotal['total_obtained'] / $grandTotal['total_max']) * 100, 2) 
                : 0;
        }
        
        return view('marks.report', compact(
            'classes',
            'streams',
            'exams',
            'subjects',
            'reportData',
            'grandTotal',
            'selectedClass',
            'selectedStream',
            'selectedExam',
            'currentSession',
            'isAdmin'
        ));
    }
    
    public function exportCSV(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $sessionId = $currentSession->session_id;
    
    $selectedClass = $request->class_id;
    $selectedStream = $request->stream_id;
    $selectedExam = $request->exam_title;
    
    // Get subjects
    $query = TeacherSubjectAllocation::where('session_id', $sessionId)
        ->where('class_id', $selectedClass);
    
    if ($selectedStream) {
        $query->where('stream_id', $selectedStream);
    } else {
        $query->whereNull('stream_id');
    }
    
    $allocations = $query->with(['subject'])->get();
    $subjectIds = $allocations->pluck('subject_id')->unique();
    $subjects = Subject::whereIn('id', $subjectIds)
        ->where('status', 'active')
        ->orderBy('subject_name')
        ->get();
    
    // Get students
    $students = Student::where('session_id', $sessionId)
        ->where('class_id', $selectedClass)
        ->where('is_deleted', 0);
    
    if ($selectedStream) {
        $students->where('stream_id', $selectedStream);
    }
    
    $students = $students->orderBy('full_name')->get();
    
    // Get marks
    $marks = Mark::where('session_id', $sessionId)
        ->where('class_id', $selectedClass)
        ->where('stream_id', $selectedStream)
        ->where('exam_title', $selectedExam)
        ->whereIn('student_id', $students->pluck('id'))
        ->get()
        ->keyBy('student_id');
    
    $filename = 'marks_report_' . date('Y-m-d') . '.csv';
    $handle = fopen('php://output', 'w');
    
    // ✅ Headers - S.No, Student Name, Subjects, Obtain, Total, %
    $headers = ['S.No', 'Student Name'];
    foreach ($subjects as $subject) {
        $headers[] = strtoupper($subject->subject_name);
    }
    $headers[] = 'Obtain';
    $headers[] = 'Total';
    $headers[] = '%';
    fputcsv($handle, $headers);
    
    $index = 1;
    
    foreach ($students as $student) {
        $mark = $marks->get($student->id);
        $row = [$index++, $student->full_name];
        
        $totalObtained = 0;
        $totalMax = 0;
        
        foreach ($subjects as $subject) {
            $subjectData = $mark && $mark->marks_data ? ($mark->marks_data[$subject->id] ?? null) : null;
            $obtained = $subjectData['obtained'] ?? 0;
            $max = $subjectData['max'] ?? 0;
            
            $row[] = $obtained;
            
            $totalObtained += (float) $obtained;
            $totalMax += (float) $max;
        }
        
        $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        $row[] = $totalObtained;
        $row[] = $totalMax;
        $row[] = $percentage . '%';
        
        fputcsv($handle, $row);
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