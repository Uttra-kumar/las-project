<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ExamSchedule;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class ParentExamController extends Controller
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
        
        // ✅ Get exam schedules
        $examSchedules = ExamSchedule::where('session_id', $sessionId)
            ->where('class_id', $student->class_id)
            ->when($student->is_stream == 1 && $student->stream_id, function($q) use ($student) {
                return $q->where('stream_id', $student->stream_id);
            })
            ->when(!($student->is_stream == 1 && $student->stream_id), function($q) {
                return $q->whereNull('stream_id');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        // ✅ Group by exam_title and create date-wise data
        $groupedSchedules = $examSchedules->groupBy('exam_title');
        
        $processedSchedules = [];
        
        foreach ($groupedSchedules as $examTitle => $schedulesGroup) {
            $dateWiseData = [];
            
            foreach ($schedulesGroup as $schedule) {
                $scheduleData = $schedule->schedule_data ?? [];
                
                if (!empty($scheduleData)) {
                    foreach ($scheduleData as $data) {
                        if (isset($data['date']) && isset($data['subject_name'])) {
                            $dateWiseData[] = [
                                'date' => $data['date'],
                                'start_time' => $data['start_time'] ?? '09:00',
                                'end_time' => $data['end_time'] ?? '12:00',
                                'subject_id' => $data['subject_id'] ?? null,
                                'subject_name' => $data['subject_name'],
                                'teacher_name' => $data['teacher_name'] ?? 'N/A',
                            ];
                        }
                    }
                }
            }
            
            // ✅ Sort by date
            usort($dateWiseData, function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });
            
            $processedSchedules[] = [
                'exam_title' => $examTitle,
                'date_wise_data' => $dateWiseData,
                'total_days' => count($dateWiseData),
                'total_subjects' => count(array_unique(array_column($dateWiseData, 'subject_name'))),
            ];
        }
        
        // ✅ Sort by first date (newest first)
        usort($processedSchedules, function($a, $b) {
            if (empty($a['date_wise_data']) || empty($b['date_wise_data'])) {
                return 0;
            }
            $dateA = $a['date_wise_data'][0]['date'] ?? '';
            $dateB = $b['date_wise_data'][0]['date'] ?? '';
            return strtotime($dateB) - strtotime($dateA);
        });
        
        return view('parent.exam', compact('student', 'examSchedules', 'processedSchedules'));
    }
}