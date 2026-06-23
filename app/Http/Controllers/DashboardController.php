<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\FeePayment;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        // 1. Total Students (Current Session)
        $totalStudents = Student::where('session_id', $sessionId)->count();
        
        // 2. Total Classes
        $totalClasses = Classes::where('status', 'active')->count();
        
        // 3. Today's Collection
        $todayCollection = FeePayment::where('session_id', $sessionId)
            ->whereDate('payment_date', today())
            ->sum('paid_amount');
        
        // 4. This Month's Collection
        $monthCollection = FeePayment::where('session_id', $sessionId)
            ->whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('paid_amount');
        
        // 5. Total Teachers (Current Session)
        $totalTeachers = Teacher::all()->where('is_deleted', 0)->count();
        
        // 6. Total Staff (Non-Teaching - if you have staff table)
        // For now using teachers only, you can add staff table later
        $totalStaff = 0; // Replace with actual staff count
        
        // 7. Class-wise Student Data
        $classData = Classes::where('status', 'active')
            ->select('id', 'class_name')
            ->withCount(['students' => function($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            }])
            ->orderBy('class_name')
            ->get()
            ->map(function($class) {
                return [
                    'class_name' => $class->class_name,
                    'students' => $class->students_count,
                ];
            });
        
        // 8. Calendar Events (Exam dates, fee due dates - dummy for now)
        $calendarEvents = $this->getCalendarEvents();
        
        // 9. Dashboard Data
        $dashboardData = [
            'today_collection' => $todayCollection ?? 0,
            'month_collection' => $monthCollection ?? 0,
            'total_classes' => $totalClasses ?? 0,
            'total_students' => $totalStudents ?? 0,
            'total_teachers' => $totalTeachers ?? 0,
            'total_staff' => $totalStaff ?? 0,
            'calendar_events' => $calendarEvents,
        ];
        
        return view('dashboard', compact('dashboardData', 'classData'));
    }
    
    private function getCalendarEvents()
    {
        // You can fetch from exam table or fee due dates
        // For now, returning dummy events
        $events = [];
        
        // Add some sample events
        $today = now();
        
        
        return $events;
    }
}