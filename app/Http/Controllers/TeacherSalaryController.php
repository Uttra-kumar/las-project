<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherSalary;
use App\Models\TeacherAttendance;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherSalaryController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = TeacherSalary::where('session_id', $sessionId);
             if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
            }
        if ($request->filled('month')) {
            $query->where('month_name', $request->month);
        }
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
         $allTeacher = Teacher::where('session_id', $sessionId)->where('status', 1)->orderBy('full_name')->get();
        $salaries = $query->with('teacher')->orderBy('id', 'desc')->paginate(15);
        
        $grandTotal = TeacherSalary::where('session_id', $sessionId)
         ->when($request->filled('teacher_id'), function($q) use ($request) {
            return $q->where('teacher_id', $request->teacher_id);
        })
            ->when($request->filled('month'), function($q) use ($request) {
                return $q->where('month_name', $request->month);
            })
            ->select(
                DB::raw('SUM(net_salary) as total_net'),
                DB::raw('SUM(CASE WHEN salary_type = "cash" THEN net_salary ELSE 0 END) as total_cash'),
                DB::raw('SUM(CASE WHEN salary_type = "bank" THEN net_salary ELSE 0 END) as total_bank')
            )
            ->first();
        
        $months = TeacherSalary::where('session_id', $sessionId)
            ->distinct()
            ->pluck('month_name');
        
        return view('finance.salary.index', compact(
            'salaries',
            'grandTotal',
            'months',
            'allTeacher',
            'currentSession'
        ));
    }
    
    public function create()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $teachers = Teacher::where('status', 1)
            ->orderBy('full_name')
            ->get();
        
        $months = $this->getMonths();
        
        return view('finance.salary.create', compact('teachers', 'months', 'currentSession'));
    }
    
   public function getTeacherData(Request $request)
{
    $teacherId = $request->teacher_id;
    $month = $request->month;
    
    if (!$teacherId || !$month) {
        return response()->json(['success' => false, 'message' => 'Missing parameters']);
    }
    
    $teacher = Teacher::find($teacherId);
    if (!$teacher) {
        return response()->json(['success' => false, 'message' => 'Teacher not found']);
    }
    
    // Get attendance for the month
    $startDate = date('Y-m-01', strtotime($month));
    $endDate = date('Y-m-t', strtotime($month));
    
    $attendances = TeacherAttendance::whereBetween('attendance_date', [$startDate, $endDate])->get();
    
    $present = 0;
    $absent = 0;
    $leave = 0;
    
    foreach ($attendances as $attendance) {
        $presentTeachers = $attendance->present_teachers ?? [];
        $absentTeachers = $attendance->absent_teachers ?? [];
        $leaveTeachers = $attendance->leave_teachers ?? [];
        
        if (in_array($teacherId, $presentTeachers)) {
            $present++;
        } elseif (in_array($teacherId, $absentTeachers)) {
            $absent++;
        } elseif (in_array($teacherId, $leaveTeachers)) {
            $leave++;
        }
    }
    
    // ✅ CALCULATE SALARY
    $monthlySalary = $teacher->salary ?? 0;
    $daysInMonth = date('t', strtotime($month));
    $salaryPerDay = $daysInMonth > 0 ? $monthlySalary / $daysInMonth : 0;
    $amount = $present * $salaryPerDay;
    
    // ✅ DEFAULT DEDUCTIONS
    $pf = round($amount * 0.12, 2);
    $esic = round($amount * 0.0075, 2);
    $other = 0;
    $netSalary = round($amount - $pf - $esic - $other, 2);
    
    return response()->json([
        'success' => true,
        'data' => [
            'teacher_name' => $teacher->full_name,
            'teacher_id' => $teacher->id,
            'salary' => $monthlySalary,
            'salary_type' => $teacher->salary_type ?? 'bank',
            'total_present' => $present,
            'days_in_month' => $daysInMonth,
            'salary_per_day' => round($salaryPerDay, 2),
            'amount' => round($amount, 2),
            'pf' => $pf,
            'esic' => $esic,
            'other' => $other,
            'net_salary' => $netSalary,
        ]
    ]);
}
    
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'month' => 'required',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'pf' => 'required|numeric|min:0',
            'esic' => 'required|numeric|min:0',
            'other' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:bank,cash',
            'status' => 'required|in:paid,hold',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        $exists = TeacherSalary::where('teacher_id', $request->teacher_id)
            ->where('month_name', $request->month)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Salary already exists for this teacher and month!'
            ], 422);
        }
        
        $teacher = Teacher::find($request->teacher_id);
        
        TeacherSalary::create([
            'session_id' => $currentSession->session_id,
            'month_name' => $request->month,
            'payment_date' => $request->payment_date,
            'teacher_id' => $request->teacher_id,
            'total_present' => $request->total_present ?? 0,
            'salary' => $teacher->salary ?? 0,
            'amount' => $request->amount,
            'pf' => $request->pf,
            'esic' => $request->esic,
            'other' => $request->other,
            'net_salary' => $request->net_salary,
            'salary_type' => $request->salary_type,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Salary created successfully!'
        ]);
    }
    
    public function edit($id)
    {
        $salary = TeacherSalary::with('teacher')->findOrFail($id);
        $currentSession = SessionHelper::getCurrentSession();
        
        $teachers = Teacher::where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->orderBy('full_name')
            ->get();
        
        $months = $this->getMonths();
        
        return view('finance.salary.edit', compact('salary', 'teachers', 'months', 'currentSession'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'pf' => 'required|numeric|min:0',
            'esic' => 'required|numeric|min:0',
            'other' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:bank,cash',
            'status' => 'required|in:paid,hold',
        ]);
        
        $salary = TeacherSalary::findOrFail($id);
        $salary->update([
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'pf' => $request->pf,
            'esic' => $request->esic,
            'other' => $request->other,
            'net_salary' => $request->net_salary,
            'salary_type' => $request->salary_type,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Salary updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $salary = TeacherSalary::findOrFail($id);
        $salary->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Salary deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = TeacherSalary::where('session_id', $sessionId)->with('teacher');
            
                if ($request->filled('teacher_id')) {
                $query->where('teacher_id', $request->teacher_id);
            }
        if ($request->filled('month')) {
            $query->where('month_name', $request->month);
        }
        if ($request->filled('salary_type')) {
            $query->where('salary_type', $request->salary_type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $salaries = $query->orderBy('id', 'desc')->get();
        
        $filename = 'salary_report_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, [
            'S.No', 'Teacher ID', 'Teacher Name', 'Month', 'Salary',
            'Present', 'Amount', 'PF', 'ESIC',
            'Other', 'Net Salary', 'Type', 'Status', 'Payment Date'
        ]);
        
        foreach ($salaries as $index => $salary) {
            fputcsv($handle, [
                $index + 1,
                $salary->teacher->teacher_id ?? 'N/A',
                $salary->teacher->full_name ?? 'N/A',
                $salary->month_name,
                $salary->salary,
                $salary->total_present,
                $salary->amount,
                $salary->pf,
                $salary->esic,
                $salary->other,
                $salary->net_salary,
                ucfirst($salary->salary_type),
                ucfirst($salary->status),
                $salary->payment_date->format('d-m-Y'),
            ]);
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
    
    private function getMonths()
    {
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = now()->subMonths($i);
            $months[] = $date->format('F Y');
        }
        return $months;
    }
}