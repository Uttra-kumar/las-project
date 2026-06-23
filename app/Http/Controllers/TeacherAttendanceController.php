<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherAttendanceController extends Controller
{
    public function index(Request $request)
    {

        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        // Get selected date (default today)
        $selectedDate = $request->date ?? date('Y-m-d');
        
        // Get all active teachers for current session
        $teachers = Teacher::where('status', 1)
            ->orderBy('full_name')
            ->get();
        
        // Get attendance for selected date (single row)
        $attendance = TeacherAttendance::where('session_id', $sessionId)
            ->whereDate('attendance_date', $selectedDate)
            ->first();
        
        // Prepare attendance data with status
        $attendanceData = [];
        $presentCount = 0;
        $absentCount = 0;
        $leaveCount = 0;
        
        // Get all teacher IDs with their status from attendance
        $statusMap = $attendance ? $attendance->getAllTeacherStatus() : [];
        
        foreach ($teachers as $teacher) {
            $status = $statusMap[$teacher->id] ?? '';
            
            $attendanceData[] = [
                'teacher_id' => $teacher->id,
                'teacher_name' => $teacher->full_name,
                'teacher_id_number' => $teacher->teacher_id,
                'status' => $status,
            ];
            
            if ($status == 'present') $presentCount++;
            elseif ($status == 'leave') $leaveCount++;
            else $absentCount++;
        }
        
        // Get month dates for calendar
        $monthYear = $request->month_year ?? date('Y-m');
        $calendarData = $this->getCalendarData($sessionId, $monthYear);
        
        return view('teacher.attendance', compact(
            'teachers',
            'attendanceData',
            'selectedDate',
            'presentCount',
            'absentCount',
            'leaveCount',
            'calendarData',
            'monthYear',
            'currentSession',
            'attendance'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        $date = $request->date;
        $day = date('l', strtotime($date));
        
        // Separate teachers by status
        $present = [];
        $absent = [];
        $leave = [];
        
        foreach ($request->attendance as $teacherId => $status) {
            if ($status == 'present') {
                $present[] = $teacherId;
            } elseif ($status == 'leave') {
                $leave[] = $teacherId;
            } else {
                $absent[] = $teacherId;
            }
        }
        
        // Get total teachers count
        $totalTeachers = Teacher::where('session_id', $sessionId)
            ->where('is_deleted', 0)
            ->count();
        
        DB::beginTransaction();
        try {
            // Check if attendance already exists for this date
            $attendance = TeacherAttendance::where('session_id', $sessionId)
                ->whereDate('attendance_date', $date)
                ->first();
            
            if ($attendance) {
                // Update existing
                $attendance->update([
                    'present_teachers' => $present,
                    'absent_teachers' => $absent,
                    'leave_teachers' => $leave,
                    'total_present' => count($present),
                    'total_absent' => count($absent),
                    'total_leave' => count($leave),
                    'total_teachers' => $totalTeachers,
                    'day' => $day,
                    'remarks' => $request->remarks ?? null,
                    'created_by' => Auth::id(),
                ]);
            } else {
                // Create new
                TeacherAttendance::create([
                    'session_id' => $sessionId,
                    'attendance_date' => $date,
                    'day' => $day,
                    'present_teachers' => $present,
                    'absent_teachers' => $absent,
                    'leave_teachers' => $leave,
                    'total_present' => count($present),
                    'total_absent' => count($absent),
                    'total_leave' => count($leave),
                    'total_teachers' => $totalTeachers,
                    'remarks' => $request->remarks ?? null,
                    'created_by' => Auth::id(),
                ]);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully!',
                'present' => count($present),
                'absent' => count($absent),
                'leave' => count($leave),
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function getCalendarData($sessionId, $monthYear)
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
        $start = $date->copy()->startOfMonth();
        $end = $date->copy()->endOfMonth();
        
        // Get attendance for each day
        $attendanceRecords = TeacherAttendance::where('session_id', $sessionId)
            ->whereBetween('attendance_date', [$start, $end])
            ->get()
            ->keyBy(function($item) {
                return $item->attendance_date->format('Y-m-d');
            });
        
        $calendarData = [];
        $current = $start->copy();
        
        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            $record = $attendanceRecords->get($dateStr);
            
            $calendarData[] = [
                'date' => $dateStr,
                'day' => $current->format('d'),
                'is_today' => $dateStr == date('Y-m-d'),
                'is_weekend' => $current->isWeekend(),
                'present' => $record ? $record->total_present : 0,
                'absent' => $record ? $record->total_absent : 0,
                'leave' => $record ? $record->total_leave : 0,
                'has_attendance' => $record ? true : false,
                'total_teachers' => $record ? $record->total_teachers : 0,
            ];
            
            $current->addDay();
        }
        
        return $calendarData;
    }

       
public function report(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        // Get filters
        $fromDate = $request->from_date ?? date('Y-m-01');
        $toDate = $request->to_date ?? date('Y-m-d');
        $teacherId = $request->teacher_id ?? null;
        $reportType = $request->report_type ?? 'summary';
        
        // ✅ CALCULATE TOTAL DAYS IN SELECTED DURATION
        $start = \Carbon\Carbon::parse($fromDate);  // ← Backslash (\) ke saath
        $end = \Carbon\Carbon::parse($toDate);
        $totalDaysInDuration = $start->diffInDays($end) + 1; // +1 for inclusive
        
        // Get all teachers for filter dropdown
        $teachers = Teacher::where('status', 1)
            ->orderBy('full_name')
            ->get();
        
        // Build query
        $query = TeacherAttendance::where('session_id', $sessionId)
            ->whereBetween('attendance_date', [$fromDate, $toDate]);
        
        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        
        // ============================================
        // SUMMARY REPORT - Teacher wise totals
        // ============================================
        $summaryData = [];
        $allTeacherIds = [];
        
        foreach ($attendances as $attendance) {
            $allStatus = $attendance->getAllTeacherStatus();
            foreach ($allStatus as $tId => $status) {
                if (!isset($allTeacherIds[$tId])) {
                    $allTeacherIds[$tId] = [
                        'present' => 0,
                        'absent' => 0,
                        'leave' => 0,
                        'total' => 0,
                    ];
                }
                $allTeacherIds[$tId][$status]++;
                $allTeacherIds[$tId]['total']++;
            }
        }
        
        // If teacher filter applied, filter summary
        if ($teacherId) {
            $allTeacherIds = array_filter($allTeacherIds, function($key) use ($teacherId) {
                return $key == $teacherId;
            }, ARRAY_FILTER_USE_KEY);
        }
        
        // Build summary data with teacher details
        foreach ($allTeacherIds as $tId => $stats) {
            $teacher = Teacher::find($tId);
            if ($teacher) {
                $summaryData[] = [
                    'teacher_id' => $teacher->teacher_id,
                    'teacher_name' => $teacher->full_name,
                    'present' => $stats['present'],
                    'absent' => $stats['absent'],
                    'leave' => $stats['leave'],
                    // ✅ TOTAL DAYS = Selected Duration (19, 30, 31 etc.)
                    'total_days' => $totalDaysInDuration,
                    'working_days' => $stats['total'], // Actual days attendance marked
                    'attendance_percentage' => $stats['total'] > 0 
                        ? round(($stats['present'] / $stats['total']) * 100, 2) 
                        : 0,
                ];
            }
        }
        
        // Sort by name
        usort($summaryData, function($a, $b) {
            return strcmp($a['teacher_name'], $b['teacher_name']);
        });
        
        // ============================================
        // DETAILED REPORT - All records
        // ============================================
        $detailedData = [];
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalLeave = 0;
        
        foreach ($attendances as $attendance) {
            $allStatus = $attendance->getAllTeacherStatus();
            
            if ($teacherId) {
                $status = $allStatus[$teacherId] ?? null;
                if ($status) {
                    $teacher = Teacher::find($teacherId);
                    $detailedData[] = [
                        'date' => $attendance->attendance_date->format('d-m-Y'),
                        'day' => $attendance->day,
                        'teacher_name' => $teacher->full_name ?? 'N/A',
                        'teacher_id' => $teacher->teacher_id ?? 'N/A',
                        'status' => ucfirst($status),
                    ];
                    if ($status == 'present') $totalPresent++;
                    elseif ($status == 'absent') $totalAbsent++;
                    elseif ($status == 'leave') $totalLeave++;
                }
            } else {
                foreach ($allStatus as $tId => $status) {
                    $teacher = Teacher::find($tId);
                    if ($teacher) {
                        $detailedData[] = [
                            'date' => $attendance->attendance_date->format('d-m-Y'),
                            'day' => $attendance->day,
                            'teacher_name' => $teacher->full_name,
                            'teacher_id' => $teacher->teacher_id,
                            'status' => ucfirst($status),
                        ];
                        if ($status == 'present') $totalPresent++;
                        elseif ($status == 'absent') $totalAbsent++;
                        elseif ($status == 'leave') $totalLeave++;
                    }
                }
            }
        }
        
        // Summary totals
        $summaryTotals = [
            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'total_leave' => $totalLeave,
            'total_days' => $attendances->count(),
            'total_records' => count($detailedData),
            'total_days_in_duration' => $totalDaysInDuration, // ✅ Send to view
        ];
        
        return view('teacher.attendance_report', compact(
            'summaryData',
            'detailedData',
            'summaryTotals',
            'teachers',
            'fromDate',
            'toDate',
            'teacherId',
            'reportType',
            'currentSession',
            'totalDaysInDuration' // ✅ Pass to view
        ));
    }
    
    /**
     * Export CSV
     */
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $fromDate = $request->from_date ?? date('Y-m-01');
        $toDate = $request->to_date ?? date('Y-m-d');
        $teacherId = $request->teacher_id ?? null;
        $reportType = $request->report_type ?? 'summary';
        
        // ✅ CALCULATE TOTAL DAYS
        $start = Carbon::parse($fromDate);
        $end = Carbon::parse($toDate);
        $totalDaysInDuration = $start->diffInDays($end) + 1;
        
        $query = TeacherAttendance::where('session_id', $sessionId)
            ->whereBetween('attendance_date', [$fromDate, $toDate]);
        
        $attendances = $query->orderBy('attendance_date', 'desc')->get();
        
        // Prepare CSV data
        $csvData = [];
        
        if ($reportType == 'summary') {
            // Summary Report
            $csvData[] = ['Teacher ID', 'Teacher Name', 'Present', 'Absent', 'Leave', 'Total Days', 'Working Days', 'Attendance %'];
            
            $allTeacherIds = [];
            foreach ($attendances as $attendance) {
                $allStatus = $attendance->getAllTeacherStatus();
                foreach ($allStatus as $tId => $status) {
                    if (!isset($allTeacherIds[$tId])) {
                        $allTeacherIds[$tId] = ['present' => 0, 'absent' => 0, 'leave' => 0, 'total' => 0];
                    }
                    $allTeacherIds[$tId][$status]++;
                    $allTeacherIds[$tId]['total']++;
                }
            }
            
            if ($teacherId) {
                $allTeacherIds = array_filter($allTeacherIds, function($key) use ($teacherId) {
                    return $key == $teacherId;
                }, ARRAY_FILTER_USE_KEY);
            }
            
            foreach ($allTeacherIds as $tId => $stats) {
                $teacher = Teacher::find($tId);
                if ($teacher) {
                    $percentage = $stats['total'] > 0 ? round(($stats['present'] / $stats['total']) * 100, 2) : 0;
                    $csvData[] = [
                        $teacher->teacher_id,
                        $teacher->full_name,
                        $stats['present'],
                        $stats['absent'],
                        $stats['leave'],
                        $totalDaysInDuration, // ✅ Total Days in selected period
                        $stats['total'], // Working days
                        $percentage . '%',
                    ];
                }
            }
        } else {
            // Detailed Report
            $csvData[] = ['Date', 'Day', 'Teacher ID', 'Teacher Name', 'Status'];
            
            foreach ($attendances as $attendance) {
                $allStatus = $attendance->getAllTeacherStatus();
                
                if ($teacherId) {
                    $status = $allStatus[$teacherId] ?? null;
                    if ($status) {
                        $teacher = Teacher::find($teacherId);
                        $csvData[] = [
                            $attendance->attendance_date->format('d-m-Y'),
                            $attendance->day,
                            $teacher->teacher_id ?? 'N/A',
                            $teacher->full_name ?? 'N/A',
                            ucfirst($status),
                        ];
                    }
                } else {
                    foreach ($allStatus as $tId => $status) {
                        $teacher = Teacher::find($tId);
                        if ($teacher) {
                            $csvData[] = [
                                $attendance->attendance_date->format('d-m-Y'),
                                $attendance->day,
                                $teacher->teacher_id,
                                $teacher->full_name,
                                ucfirst($status),
                            ];
                        }
                    }
                }
            }
        }
        
        // Generate CSV
        $filename = 'attendance_report_' . $reportType . '_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        foreach ($csvData as $row) {
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