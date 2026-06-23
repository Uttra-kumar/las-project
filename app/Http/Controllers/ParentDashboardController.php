<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeePayment;
use App\Models\Mark;
use App\Models\SchoolSetting;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\Notice;
use App\Models\SchoolSession;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentDashboardController extends Controller
{
    public function index()
    {
        // ✅ Get student from session
        $studentId = session('student_id');
        $studentName = session('student_name');
        
        if (!$studentId) {
            return redirect()->route('parent.login')->with('error', 'Please login first.');
        }
        
        $student = Student::find($studentId);
        if (!$student) {
            return redirect()->route('parent.login')->with('error', 'Student not found.');
        }
        
        // ✅ USE STUDENT'S OWN SESSION
        $sessionId = $student->session_id;
        
        // ✅ Get session details
        $session = SchoolSession::where('session_id', $sessionId)->first();
        $sessionName = $session ? $session->session_name : 'N/A';
        
        // ✅ Get classes for dropdown
        $classes = Classes::where('status', 'active')
            ->orderBy('class_name')
            ->get();

        // ✅ Get ONLY PUBLISHED Notices from DATABASE (status = 1)
        $notices = Notice::where('session_id', $sessionId)
            ->where('status', '1')  // ✅ Only Published
            ->orderBy('id', 'desc')
            ->limit(5)  // ✅ Show latest 5 notices
            ->get()
            ->map(function($notice) {
                return [
                    'date' => $notice->notice_date ? date('d-m-Y', strtotime($notice->notice_date)) : '-',
                    'title' => $notice->title,
                    'description' => $notice->description,
                    'id' => $notice->id,
                ];
            });

        // ✅ Get latest marks for this student (only this session)
        $latestExam = Mark::where('session_id', $sessionId)
            ->where('student_id', $studentId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->first();
        
        $marksData = [];
        $totalObtained = 0;
        $totalMax = 0;
        $percentage = 0;
        
        if ($latestExam && $latestExam->marks_data) {
            foreach ($latestExam->marks_data as $subjectId => $data) {
                $subject = Subject::find($subjectId);
                $marksData[] = [
                    'subject_name' => $subject->subject_name ?? 'N/A',
                    'obtained' => $data['obtained'] ?? 0,
                    'max' => $data['max'] ?? 0,
                    'percentage' => $data['max'] > 0 
                        ? round(($data['obtained'] / $data['max']) * 100, 2) 
                        : 0,
                ];
                $totalObtained += $data['obtained'] ?? 0;
                $totalMax += $data['max'] ?? 0;
            }
            $percentage = $totalMax > 0 ? round(($totalObtained / $totalMax) * 100, 2) : 0;
        }
        
        // ✅ Get fee payments (only this session)
        $feePayments = FeePayment::where('session_id', $sessionId)
            ->where('student_id', $studentId)
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // ✅ Get total due fees (only this session)
        $totalDueFees = FeePayment::where('session_id', $sessionId)
            ->where('student_id', $studentId)
            ->where('status', '!=', 'paid')
            ->sum('amount');
        
        // ✅ Upcoming events (dummy data)
        $events = [
            ['date' => now()->addDays(5)->format('d-m-Y'), 'title' => 'Science Exhibition'],
            ['date' => now()->addDays(10)->format('d-m-Y'), 'title' => 'Parent-Teacher Meeting'],
            ['date' => now()->addDays(15)->format('d-m-Y'), 'title' => 'Annual Sports Day'],
        ];
        
        // ✅ Get School Settings
        $school = SchoolSetting::first();
        
        return view('parent.dashboard', compact(
            'student',
            'school',
            'classes',
            'marksData',
            'feePayments',
            'totalDueFees',
            'notices',        // ✅ Database notices
            'events',
            'percentage',
            'totalObtained',
            'totalMax',
            'latestExam',
            'sessionId',
            'sessionName'
        ));
    }
}