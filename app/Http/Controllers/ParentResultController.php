<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Mark;
use App\Models\Subject;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class ParentResultController extends Controller
{
    public function index()
    {
        $studentId = session('student_id');
        
        if (!$studentId) {
            return redirect()->route('parent.login')->with('error', 'Please login first.');
        }
        
        $student = Student::find($studentId);
        if (!$student) {
            return redirect()->route('parent.login')->with('error', 'Student not found.');
        }
        
        $sessionId = $student->session_id;
        
        // ✅ Get results (approved marks)
        $results = Mark::where('session_id', $sessionId)
            ->where('student_id', $studentId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('exam_title');
        
        // ✅ Prepare result data
        $resultData = [];
        foreach ($results as $examTitle => $marks) {
            $subjectMarks = [];
            $totalObtained = 0;
            $totalMax = 0;
            
            foreach ($marks as $mark) {
                if ($mark->marks_data) {
                    foreach ($mark->marks_data as $subjectId => $data) {
                        $subject = Subject::find($subjectId);
                        $subjectMarks[] = [
                            'subject_name' => $subject->subject_name ?? 'N/A',
                            'obtained' => $data['obtained'] ?? 0,
                            'max' => $data['max'] ?? 0,
                            'percentage' => $data['max'] > 0 ? round(($data['obtained'] / $data['max']) * 100, 2) : 0,
                        ];
                        $totalObtained += $data['obtained'] ?? 0;
                        $totalMax += $data['max'] ?? 0;
                    }
                }
            }
            
            $resultData[] = [
                'exam_title' => $examTitle,
                'exam_date' => $marks->first()->exam_date ?? null,
                'subjects' => $subjectMarks,
                'total_obtained' => $totalObtained,
                'total_max' => $totalMax,
                'percentage' => $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0,
                'status' => $marks->first()->status ?? 'pending',
            ];
        }
        
        return view('parent.result', compact('student', 'resultData'));
    }
}