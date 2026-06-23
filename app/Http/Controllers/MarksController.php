<?php

namespace App\Http\Controllers;

use App\Models\Mark;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectAllocation;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MarksController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $user = Auth::user();
        $teacher = null;
        $isAdmin = $user->role == 'admin';
        
        if (!$isAdmin) {
            $teacher = Teacher::where('email', $user->email)
                ->where('status', 1)
                ->first();
            
            if (!$teacher) {
                abort(403, 'You are not authorized to access this page.');
            }
        }
        
        $classes = Classes::where('status', 'active')->orderBy('class_name')->get();
        $streams = collect();
        $subjects = collect();
        $students = collect();
        $marksData = [];
        
        $selectedClass = $request->class_id;
        $selectedStream = $request->stream_id;
        $examTitle = $request->exam_title;
        
        if ($selectedClass) {
            $class = Classes::find($selectedClass);
            if ($class && $class->is_stream == 1) {
                $streams = Stream::where('class_id', $selectedClass)
                    ->where('status', 'active')
                    ->orderBy('stream_name')
                    ->get();
            }
        }
        
        if ($selectedClass && $examTitle) {
            $query = TeacherSubjectAllocation::where('session_id', $sessionId)
                ->where('class_id', $selectedClass);
            
            if ($selectedStream) {
                $query->where('stream_id', $selectedStream);
            } else {
                $query->whereNull('stream_id');
            }
            
            if (!$isAdmin && $teacher) {
                $query->where('teacher_id', $teacher->id);
            }
            
            $allocations = $query->with(['subject', 'teacher'])->get();
            $subjectIds = $allocations->pluck('subject_id')->unique();
            $subjects = Subject::whereIn('id', $subjectIds)
                ->where('status', 'active')
                ->orderBy('subject_name')
                ->get();
            
            $students = Student::where('session_id', $sessionId)
                ->where('class_id', $selectedClass)
                ->where('is_deleted', 0);
            
            if ($selectedStream) {
                $students->where('stream_id', $selectedStream);
            }
            
            $students = $students->orderBy('full_name')->get();
            
            if ($students->isNotEmpty()) {
                $existingMarks = Mark::where('session_id', $sessionId)
                    ->where('class_id', $selectedClass)
                    ->where('stream_id', $selectedStream)
                    ->where('exam_title', $examTitle)
                    ->whereIn('student_id', $students->pluck('id'))
                    ->get()
                    ->keyBy('student_id');
                
                foreach ($students as $student) {
                    $marksData[$student->id] = [
                        'student' => $student,
                        'marks' => $existingMarks->get($student->id),
                    ];
                }
            }
        }
        
        return view('marks.marks_entry', compact(
            'classes',
            'streams',
            'subjects',
            'students',
            'marksData',
            'selectedClass',
            'selectedStream',
            'examTitle',
            'isAdmin',
            'teacher',
            'currentSession'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'exam_title' => 'required|string|max:255',
            'marks' => 'required|array',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        $user = Auth::user();
        $isAdmin = $user->role == 'admin';
        
        $teacher = null;
        if (!$isAdmin) {
            $teacher = Teacher::where('email', $user->email)
                ->where('session_id', $sessionId)
                ->where('is_deleted', 0)
                ->first();
            
            if (!$teacher) {
                return response()->json(['success' => false, 'message' => 'Unauthorized!'], 403);
            }
        }
        
        DB::beginTransaction();
        try {
            foreach ($request->marks as $studentId => $subjectMarks) {
                $marksData = [];
                $totalObtained = 0;
                $totalMax = 0;
                
                foreach ($subjectMarks as $subjectId => $data) {
                    $obtained = $data['obtained'] ?? 0;
                    $max = $data['max'] ?? 100;
                    
                    $marksData[$subjectId] = [
                        'obtained' => (float) $obtained,
                        'max' => (float) $max,
                    ];
                    
                    $totalObtained += (float) $obtained;
                    $totalMax += (float) $max;
                }
                
                $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
                
                $mark = Mark::where('session_id', $sessionId)
                    ->where('class_id', $request->class_id)
                    ->where('stream_id', $request->stream_id)
                    ->where('exam_title', $request->exam_title)
                    ->where('student_id', $studentId)
                    ->first();
                
                $data = [
                    'session_id' => $sessionId,
                    'class_id' => $request->class_id,
                    'stream_id' => $request->stream_id,
                    'exam_title' => $request->exam_title,
                    'exam_date' => $request->exam_date ?? now(),
                    'student_id' => $studentId,
                    'marks_data' => $marksData,
                    'total_obtained' => $totalObtained,
                    'total_max' => $totalMax,
                    'percentage' => $percentage,
                    'remarks' => $request->remarks[$studentId] ?? null,
                    'created_by' => Auth::id(),
                ];
                
                if ($isAdmin) {
                    $data['status'] = 'approved';
                    $data['approved_by'] = Auth::id();
                    $data['approved_at'] = now();
                } else {
                    $data['teacher_id'] = $teacher->id;
                    $data['status'] = 'pending';
                }
                
                if ($mark) {
                    $mark->update($data);
                } else {
                    Mark::create($data);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Marks saved successfully!',
                'status' => $isAdmin ? 'approved' : 'pending'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function approve(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'exam_title' => 'required|string|max:255',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        Mark::where('session_id', $sessionId)
            ->where('class_id', $request->class_id)
            ->where('stream_id', $request->stream_id)
            ->where('exam_title', $request->exam_title)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All marks approved successfully!'
        ]);
    }
    
     public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $marks = Mark::where('session_id', $sessionId)
            ->when($request->class_id, function($q) use ($request) {
                return $q->where('class_id', $request->class_id);
            })
            ->when($request->exam_title, function($q) use ($request) {
                return $q->where('exam_title', $request->exam_title);
            })
            ->with(['student'])
            ->get();
        
        if ($marks->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No marks found!'], 404);
        }
        
        $filename = 'marks_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Headers
        $headers = ['S.No', 'Student Name', 'Roll No'];
        $subjectNames = [];
        
        foreach ($marks as $mark) {
            if ($mark->marks_data) {
                foreach ($mark->marks_data as $subjectId => $data) {
                    $subject = Subject::find($subjectId);
                    if ($subject && !in_array($subject->subject_name, $subjectNames)) {
                        $subjectNames[] = $subject->subject_name;
                    }
                }
            }
        }
        
        $headers = array_merge($headers, $subjectNames, ['Total Obtained', 'Total Max', 'Percentage', 'Status']);
        fputcsv($handle, $headers);
        
        $index = 1;
        foreach ($marks as $mark) {
            $row = [
                $index++,
                $mark->student->full_name ?? 'N/A',
                $mark->student->roll_no ?? '-',
            ];
            
            foreach ($subjectNames as $subjectName) {
                $found = false;
                if ($mark->marks_data) {
                    foreach ($mark->marks_data as $subjectId => $data) {
                        $subject = Subject::find($subjectId);
                        if ($subject && $subject->subject_name == $subjectName) {
                            $row[] = $data['obtained'] . '/' . $data['max'];
                            $found = true;
                            break;
                        }
                    }
                }
                if (!$found) {
                    $row[] = '-';
                }
            }
            
            $row[] = $mark->total_obtained ?? 0;
            $row[] = $mark->total_max ?? 0;
            $row[] = ($mark->percentage ?? 0) . '%';
            $row[] = ucfirst($mark->status ?? 'pending');
            
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