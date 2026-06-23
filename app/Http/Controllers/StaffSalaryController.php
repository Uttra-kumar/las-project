<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Models\StaffSalary;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffSalaryController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = StaffSalary::where('session_id', $sessionId);

         if ($request->filled('staff_id')) {
        $query->where('staff_id', $request->staff_id);
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
            $allStaff = OtherStaff::where('session_id', $sessionId)->where('is_deleted', 0)->where('status', 'active')
            ->orderBy('full_name')->get();
        $salaries = $query->with('staff')->orderBy('id', 'desc')->paginate(15);
        
        $grandTotal = StaffSalary::where('session_id', $sessionId)
         ->when($request->filled('staff_id'), function($q) use ($request) {
            return $q->where('staff_id', $request->staff_id);
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
        
        $months = StaffSalary::where('session_id', $sessionId)
            ->distinct()
            ->pluck('month_name');
        
        return view('finance.staff_salary.index', compact(
            'salaries',
            'grandTotal',
            'months',
            'currentSession',
            'allStaff'
        ));
    }
    
    public function create()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $staff = OtherStaff::where('session_id', $sessionId)
            ->where('is_deleted', 0)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();
        
        $months = $this->getMonths();
        
        return view('finance.staff_salary.create', compact('staff', 'months', 'currentSession'));
    }
    
    public function getStaffData(Request $request)
    {
        $staffId = $request->staff_id;
        $month = $request->month;
        
        if (!$staffId || !$month) {
            return response()->json(['success' => false, 'message' => 'Missing parameters']);
        }
        
        $staff = OtherStaff::find($staffId);
        if (!$staff) {
            return response()->json(['success' => false, 'message' => 'Staff not found']);
        }
        
        $daysInMonth = date('t', strtotime($month));
        $salaryPerDay = $daysInMonth > 0 ? $staff->salary / $daysInMonth : 0;
        $amount = $staff->salary;
        
        $pf = round($amount * 0.12, 2);
        $esic = round($amount * 0.0075, 2);
        $other = 0;
        $netSalary = round($amount - $pf - $esic - $other, 2);
        
        return response()->json([
            'success' => true,
            'data' => [
                'staff_name' => $staff->full_name,
                'staff_id' => $staff->id,
                'emp_id' => $staff->emp_id,
                'salary' => $staff->salary,
                'salary_type' => $staff->salary_type ?? 'bank',
                'department' => $staff->department,
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
            'staff_id' => 'required|exists:other_staff,id',
            'month' => 'required',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:bank,cash',
            'status' => 'required|in:paid,hold',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        $exists = StaffSalary::where('staff_id', $request->staff_id)
            ->where('month_name', $request->month)
            ->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Salary already exists for this staff member this month!'
            ], 422);
        }
        
        $staff = OtherStaff::find($request->staff_id);
        
        StaffSalary::create([
            'session_id' => $currentSession->session_id,
            'month_name' => $request->month,
            'payment_date' => $request->payment_date,
            'staff_id' => $request->staff_id,
            'salary' => $staff->salary ?? 0,
            'amount' => $request->amount,
            'pf' => $request->pf ?? 0,
            'esic' => $request->esic ?? 0,
            'other' => $request->other ?? 0,
            'net_salary' => $request->net_salary,
            'salary_type' => $request->salary_type,
            'remarks' => $request->remarks,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Staff salary created successfully!'
        ]);
    }
    
    public function edit($id)
    {
        $salary = StaffSalary::with('staff')->findOrFail($id);
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $staff = OtherStaff::where('session_id', $sessionId)
            ->where('is_deleted', 0)
            ->where('status', 'active')
            ->orderBy('full_name')
            ->get();
        
        $months = $this->getMonths();
        
        return view('finance.staff_salary.edit', compact('salary', 'staff', 'months', 'currentSession'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'salary_type' => 'required|in:bank,cash',
            'status' => 'required|in:paid,hold',
        ]);
        
        $salary = StaffSalary::findOrFail($id);
        $salary->update([
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'pf' => $request->pf ?? 0,
            'esic' => $request->esic ?? 0,
            'other' => $request->other ?? 0,
            'net_salary' => $request->net_salary,
            'salary_type' => $request->salary_type,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Staff salary updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $salary = StaffSalary::findOrFail($id);
        $salary->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Staff salary deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = StaffSalary::where('session_id', $sessionId)->with('staff');
             if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
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
        
        $filename = 'staff_salary_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['S.No', 'Emp ID', 'Staff Name', 'Department', 'Month', 'Salary', 'Amount', 'PF', 'ESIC', 'Other', 'Net Salary', 'Type', 'Status', 'Payment Date']);
        
        foreach ($salaries as $index => $salary) {
            fputcsv($handle, [
                $index + 1,
                $salary->staff->emp_id ?? 'N/A',
                $salary->staff->full_name ?? 'N/A',
                ucfirst($salary->staff->department ?? 'N/A'),
                $salary->month_name,
                $salary->salary,
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