<?php

namespace App\Http\Controllers;

use App\Models\FeePayment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\FeesType;
use App\Models\StudentFees;
use App\Models\FeesMaster;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    
    // Daily Collection Report
    public function dailyCollection(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $fromDate = $request->from_date ?? date('Y-m-01');
        $toDate = $request->to_date ?? date('Y-m-d');
        
        // Get all transactions in date range
        $transactions = FeePayment::with(['student', 'feesType', 'class'])
            ->where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->orderBy('payment_date', 'desc')
            ->get();
        
        // Group by date
        $groupedByDate = $transactions->groupBy('payment_date');
        
        // Calculate totals
        $totalCash = $transactions->where('payment_mode', 'cash')->sum('paid_amount');
        $totalUpi = $transactions->where('payment_mode', 'upi')->sum('paid_amount');
        $totalCheque = $transactions->where('payment_mode', 'cheque')->sum('paid_amount');
        $totalCard = $transactions->where('payment_mode', 'card')->sum('paid_amount');
        $totalOnline = $transactions->where('payment_mode', 'online')->sum('paid_amount');
        $grandTotal = $transactions->sum('paid_amount');
        
        $stats = [
            'cash' => $totalCash,
            'upi' => $totalUpi,
            'cheque' => $totalCheque,
            'card' => $totalCard,
            'online' => $totalOnline,
            'total' => $grandTotal,
            'transactions' => $transactions->count()
        ];
        
        return view('reports.daily_collection', compact('groupedByDate', 'fromDate', 'toDate', 
            'currentSession', 'stats', 'transactions'));
    }
    
    // Export CSV - Date wise summary
    public function exportCsv(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $fromDate = $request->from_date ?? date('Y-m-01');
        $toDate = $request->to_date ?? date('Y-m-d');
        
        // Get all transactions in date range
        $transactions = FeePayment::where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->whereBetween('payment_date', [$fromDate, $toDate])
            ->get();
        
        // Group by date
        $groupedByDate = $transactions->groupBy('payment_date');
        
        // Create CSV file
        $filename = "daily_collection_summary_{$fromDate}_to_{$toDate}.csv";
        $handle = fopen('php://temp', 'w');
        
        // Add headers - Date wise summary
        fputcsv($handle, ['Date', 'Total Transactions', 'Cash (₹)', 'UPI (₹)', 'Cheque (₹)', 'Card (₹)', 'Total (₹)']);
        
        $totalCash = 0;
        $totalUpi = 0;
        $totalCheque = 0;
        $totalCard = 0;
        $overallTotal = 0;
        $totalTransactions = 0;
        
        // Add data rows - date wise summary
        foreach ($groupedByDate as $date => $txns) {
            $dayCash = $txns->where('payment_mode', 'cash')->sum('paid_amount');
            $dayUpi = $txns->where('payment_mode', 'upi')->sum('paid_amount');
            $dayCheque = $txns->where('payment_mode', 'cheque')->sum('paid_amount');
            $dayCard = $txns->where('payment_mode', 'card')->sum('paid_amount');
            $dayTotal = $dayCash + $dayUpi + $dayCheque + $dayCard;
            $dayCount = $txns->count();
            
            $totalCash += $dayCash;
            $totalUpi += $dayUpi;
            $totalCheque += $dayCheque;
            $totalCard += $dayCard;
            $overallTotal += $dayTotal;
            $totalTransactions += $dayCount;
            
            fputcsv($handle, [
                date('d-m-Y', strtotime($date)),
                $dayCount,
                number_format($dayCash, 2),
                number_format($dayUpi, 2),
                number_format($dayCheque, 2),
                number_format($dayCard, 2),
                number_format($dayTotal, 2)
            ]);
        }
        
        // Add empty row
        fputcsv($handle, []);
        
        // Add grand total row
        fputcsv($handle, ['GRAND TOTAL', $totalTransactions, 
            number_format($totalCash, 2), 
            number_format($totalUpi, 2), 
            number_format($totalCheque, 2), 
            number_format($totalCard, 2), 
            number_format($overallTotal, 2)]);
        
        // Get content and output
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
    
    // Get transactions for a specific date (AJAX for modal)
    public function getDateTransactions(Request $request)
    {
        $date = $request->date;
        $currentSession = SessionHelper::getCurrentSession();
        
        $transactions = FeePayment::with(['student', 'feesType'])
            ->where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->whereDate('payment_date', $date)
            ->get();
        
        return response()->json($transactions);
    }

// Transaction Details Report
public function transactionDetails(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
    $users = \App\Models\User::where('role', 'admin')->orWhere('role', 'accountant')->get();
    
    $fromDate = $request->from_date ?? date('Y-m-01');
    $toDate = $request->to_date ?? date('Y-m-d');
    $selectedClass = $request->class_id ?? '';
    $selectedMode = $request->mode ?? '';
    $selectedUser = $request->user_id ?? '';
    
    $query = FeePayment::with(['student', 'feesType', 'class', 'creator'])
        ->where('session_id', $currentSession->session_id)
        ->where('is_deleted', 0)
        ->whereBetween('payment_date', [$fromDate, $toDate]);
    
    if ($selectedClass) {
        $query->where('class_id', $selectedClass);
    }
    
    if ($selectedMode) {
        $query->where('payment_mode', $selectedMode);
    }
    
    if ($selectedUser) {
        $query->where('created_by', $selectedUser);
    }
    
    $transactions = $query->orderBy('payment_date', 'desc')->get();
    
    $totalAmount = $transactions->sum('amount');
    $totalDiscount = $transactions->sum('discount');
    $totalFine = $transactions->sum('fine');
    $totalPaid = $transactions->sum('paid_amount');
    
    return view('reports.transaction_details', compact('transactions', 'fromDate', 'toDate', 
        'currentSession', 'classes', 'users', 'selectedClass', 'selectedMode', 'selectedUser',
        'totalAmount', 'totalDiscount', 'totalFine', 'totalPaid'));
}

// Export CSV for Transaction Details
public function exportTransactionsCsv(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $fromDate = $request->from_date ?? date('Y-m-01');
    $toDate = $request->to_date ?? date('Y-m-d');
    
    $query = FeePayment::with(['student', 'feesType', 'class', 'creator'])
        ->where('session_id', $currentSession->session_id)
        ->where('is_deleted', 0)
        ->whereBetween('payment_date', [$fromDate, $toDate]);
    
    if ($request->class_id) {
        $query->where('class_id', $request->class_id);
    }
    
    if ($request->mode) {
        $query->where('payment_mode', $request->mode);
    }
    
    if ($request->user_id) {
        $query->where('created_by', $request->user_id);
    }
    
    $transactions = $query->orderBy('payment_date', 'desc')->get();
    
    $filename = "transaction_details_{$fromDate}_to_{$toDate}.csv";
    $handle = fopen('php://temp', 'w');
    
    // Add UTF-8 BOM for Excel
    fputs($handle, "\xEF\xBB\xBF");
    
    // Headers
    fputcsv($handle, ['S.No', 'Date', 'Receipt No', 'Class', 'Student Name', 'Father Name', 
        'Fee Type', 'Amount (₹)', 'Discount (₹)', 'Fine (₹)', 'Paid Amount (₹)', 'Mode', 'User']);
    
    // Data rows
    foreach ($transactions as $index => $t) {
        fputcsv($handle, [
            $index + 1,
            date('d-m-Y', strtotime($t->payment_date)),
            $t->receipt_no,
            $t->class->class_name ?? 'N/A',
            $t->student->full_name ?? 'N/A',
            $t->student->father_name ?? 'N/A',
            $t->feesType->name ?? 'N/A',
            number_format($t->amount, 2),
            number_format($t->discount, 2),
            number_format($t->fine, 2),
            number_format($t->paid_amount, 2),
            strtoupper($t->payment_mode),
            $t->creator->name ?? 'N/A'
        ]);
    }
    
    // Add summary
    fputcsv($handle, []);
    fputcsv($handle, ['SUMMARY', '', '', '', '', '', '', '', '', '', '', '']);
    fputcsv($handle, ['Total Amount', '', '', '', '', '', '', number_format($transactions->sum('amount'), 2), '', '', '', '']);
    fputcsv($handle, ['Total Discount', '', '', '', '', '', '', number_format($transactions->sum('discount'), 2), '', '', '', '']);
    fputcsv($handle, ['Total Fine', '', '', '', '', '', '', number_format($transactions->sum('fine'), 2), '', '', '', '']);
    fputcsv($handle, ['Total Paid', '', '', '', '', '', '', number_format($transactions->sum('paid_amount'), 2), '', '', '', '']);
    
    rewind($handle);
    $content = stream_get_contents($handle);
    fclose($handle);
    
    return response($content)
        ->header('Content-Type', 'text/csv; charset=UTF-8')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
}


// Due Report
// Due Report - WITHOUT Date Filter
public function dueReport(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
    $feeTypes = FeesType::where('session_id', $currentSessionId)->where('status', 'active')->get();
    
    // NO DATE FILTER - Sirf class aur fee type ke filters
    $selectedClass = $request->class_id ?? '';
    $selectedFeeType = $request->fee_type_id ?? '';
    
    // Get all students for current session
    $students = Student::with(['class'])
        ->where('session_id', $currentSessionId)
        ->where('is_deleted', 0)
        ->get();
    
    // Apply class filter
    if ($selectedClass) {
        $students = $students->where('class_id', $selectedClass);
    }
    
    $dueRecords = [];
    $totalAmount = 0;
    $totalPaid = 0;
    $totalDiscount = 0;
    $totalDue = 0;
    
    foreach ($students as $student) {
        // Get all fee masters for this student's class
        $feeMasters = FeesMaster::with(['feesType'])
            ->where('class_id', $student->class_id)
            ->where('session_id', $currentSessionId)
            ->where('status', 'active')
            ->get();
        
        // Apply fee type filter
        if ($selectedFeeType) {
            $feeMasters = $feeMasters->where('fees_type_id', $selectedFeeType);
        }
        
        foreach ($feeMasters as $fm) {
            // Get ALL payments for this student and fee type (NO DATE FILTER)
            $payments = FeePayment::where('student_id', $student->id)
                ->where('fees_type_id', $fm->fees_type_id)
                ->where('session_id', $currentSessionId)
                ->where('is_deleted', 0)
                ->get();  // ← NO whereBetween on payment_date
            
            $totalPaidAmount = $payments->sum('paid_amount');
            $totalDiscountAmount = $payments->sum('discount');
            $due = $fm->amount - $totalPaidAmount - $totalDiscountAmount;
            
            // Only show if due > 0
            if ($due > 0) {
                $dueRecords[] = [
                    'student' => $student,
                    'class' => $student->class,
                    'fee_type' => $fm->feesType,
                    'total_fees' => $fm->amount,
                    'paid' => $totalPaidAmount,
                    'discount' => $totalDiscountAmount,
                    'due' => $due,
                ];
                
                $totalAmount += $fm->amount;
                $totalPaid += $totalPaidAmount;
                $totalDiscount += $totalDiscountAmount;
                $totalDue += $due;
            }
        }
    }
    
    return view('reports.due_report', compact('dueRecords', 'classes', 'feeTypes', 
        'selectedClass', 'selectedFeeType', 'currentSession',
        'totalAmount', 'totalPaid', 'totalDiscount', 'totalDue'));
}

// Export Due Report CSV
public function exportDueCsv(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $selectedClass = $request->class_id ?? '';
    $selectedFeeType = $request->fee_type_id ?? '';
    
    // Get all students for current session
    $students = Student::with(['class'])
        ->where('session_id', $currentSessionId)
        ->where('is_deleted', 0)
        ->get();
    
    if ($selectedClass) {
        $students = $students->where('class_id', $selectedClass);
    }
    
    $dueRecords = [];
    
    foreach ($students as $student) {
        $feeMasters = FeesMaster::with(['feesType'])
            ->where('class_id', $student->class_id)
            ->where('session_id', $currentSessionId)
            ->where('status', 'active')
            ->get()
            ->unique('fees_type_id');
        
        if ($selectedFeeType) {
            $feeMasters = $feeMasters->where('fees_type_id', $selectedFeeType);
        }
        
        foreach ($feeMasters as $fm) {
            $payments = FeePayment::where('student_id', $student->id)
                ->where('fees_type_id', $fm->fees_type_id)
                ->where('session_id', $currentSessionId)
                ->where('is_deleted', 0)
                ->get();
            
            $totalPaidAmount = $payments->sum('paid_amount');
            $totalDiscountAmount = $payments->sum('discount');
            $due = $fm->amount - $totalPaidAmount - $totalDiscountAmount;
            
            if ($due > 0) {
                $dueRecords[] = [
                    'class_name' => $student->class->class_name ?? 'N/A',
                    'student_name' => $student->full_name,
                    'father_name' => $student->father_name ?? '-',
                    'fee_type' => $fm->feesType->name ?? 'N/A',
                    'amount' => $fm->amount,
                    'paid' => $totalPaidAmount,
                    'discount' => $totalDiscountAmount,
                    'due' => $due
                ];
            }
        }
    }
    
    // Create CSV file
    $filename = "due_report_" . date('Y-m-d_H-i-s') . ".csv";
    
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $callback = function() use ($dueRecords) {
        $file = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fputs($file, "\xEF\xBB\xBF");
        
        // Add headers
        fputcsv($file, ['S.No', 'Class', 'Student Name', 'Father Name', 'Fee Type', 
            'Total Amount (₹)', 'Paid (₹)', 'Discount (₹)', 'Due (₹)']);
        
        // Add data rows
        foreach ($dueRecords as $index => $record) {
            fputcsv($file, [
                $index + 1,
                $record['class_name'],
                $record['student_name'],
                $record['father_name'],
                $record['fee_type'],
                number_format($record['amount'], 2),
                number_format($record['paid'], 2),
                number_format($record['discount'], 2),
                number_format($record['due'], 2)
            ]);
        }
        
        // Add summary
        if (count($dueRecords) > 0) {
            fputcsv($file, []);
            fputcsv($file, ['SUMMARY', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Total Due Amount', '', '', '', '', '', '', '', 
                number_format(collect($dueRecords)->sum('due'), 2)]);
            fputcsv($file, ['Total Records', count($dueRecords), '', '', '', '', '', '', '']);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}



// Student Report
public function studentReport(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
    
    $selectedClass = $request->class_id ?? '';
    $selectedType = $request->type ?? '';
    $selectedStatus = $request->status_type ?? '';
    
    $students = collect(); // Empty by default
    
    // Only fetch if filter is applied
        if ($selectedClass || $selectedType || $selectedStatus) {
            $query = Student::with(['class'])
                ->where('session_id', $currentSessionId);
            
            if ($selectedClass) {
                $query->where('class_id', $selectedClass);
            }
            
            if ($selectedType && $selectedType != 'all') {
                $isHosteler = ($selectedType == 'hosteler') ? 1 : 0;
                $query->where('is_hosteler', $isHosteler);
            }
            
            // ✅ CORRECT Status filter (is_deleted)
            // '1' = Active (is_deleted = 0)
            // '0' = Inactive (is_deleted = 1)
            if ($selectedStatus == '1') {
                $query->where('is_deleted', 0);  // Active
            } elseif ($selectedStatus == '0') {
                $query->where('is_deleted', 1);  // Inactive
            } else {
                // Default: Show only active (not deleted)
                $query->where('is_deleted', 0);
            }
            
            $students = $query->orderBy('full_name', 'asc')->get();
        }
    
    return view('reports.student_report', compact('students', 'classes', 
        'selectedClass','selectedStatus', 'selectedType', 'currentSession'));
}

// Export Student Report CSV
public function exportStudentCsv(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $selectedClass = $request->class_id ?? '';
    $selectedType = $request->type ?? '';
    
    $query = Student::with(['class'])
        ->where('session_id', $currentSessionId)
        ->where('is_deleted', 0);
    
    if ($selectedClass) {
        $query->where('class_id', $selectedClass);
    }
    
    if ($selectedType && $selectedType != 'all') {
        $isHosteler = ($selectedType == 'hosteler') ? 1 : 0;
        $query->where('is_hosteler', $isHosteler);
    }
    
    $students = $query->orderBy('full_name', 'asc')->get();
    
    $filename = "student_report_" . date('Y-m-d_H-i-s') . ".csv";
    
    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];
    
    $callback = function() use ($students) {
        $file = fopen('php://output', 'w');
        
        // Add UTF-8 BOM for Excel
        fputs($file, "\xEF\xBB\xBF");
        
        // Add headers
        fputcsv($file, ['S.No', 'Class', 'Student Name', 'Father Name', 'DOB', 'Gender', 'Mobile', 'Type', 'Admission Date', 'Student ID']);
        
        // Add data rows
        foreach ($students as $index => $student) {
            fputcsv($file, [
                $index + 1,
                $student->class->class_name ?? 'N/A',
                $student->full_name,
                $student->father_name ?? '-',
                $student->dob ? date('d-m-Y', strtotime($student->dob)) : '-',
                $student->gender ?? '-',
                $student->mobile,
                $student->is_hosteler ? 'Hosteler' : 'Day Scholar',
                $student->created_at ? date('d-m-Y', strtotime($student->created_at)) : '-',
                $student->student_id
            ]);
        }
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}

     public function teacherReport(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $selectedStatus = $request->status;
        
        $query = Teacher::where('is_deleted', 0);
        
        // Apply status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_deleted', 0);
            } elseif ($request->status == 'inactive') {
                $query->where('is_deleted', 1);
            }
        } else {
            // Default: Show only active
            $query->where('is_deleted', 0);
        }
        
        $teachers = $query->orderBy('id', 'desc')->get();
        
        return view('reports.teachers_report', compact('teachers', 'currentSession', 'selectedStatus'));
    }

    public function teacherExportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        
        $query = Teacher::where('session_id', $currentSession->session_id);
        
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->where('is_deleted', 0);
            } elseif ($request->status == 'inactive') {
                $query->where('is_deleted', 1);
            }
        } else {
            $query->where('is_deleted', 0);
        }
        
        $teachers = $query->orderBy('id', 'desc')->get();
        
        // Generate CSV
        $filename = 'teachers_report_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        // Headers
        fputcsv($handle, [
            'S.No',
            'Teacher ID',
            'Teacher Name',
            'Father Name',
            'DOB',
            'Gender',
            'Mobile',
            'Email',
            'Qualification',
            'Experience',
            'Status'
        ]);
        
        // Data
        foreach ($teachers as $index => $teacher) {
            fputcsv($handle, [
                $index + 1,
                $teacher->teacher_id,
                $teacher->full_name,
                $teacher->father_name ?? '-',
                $teacher->dob ? date('d-m-Y', strtotime($teacher->dob)) : '-',
                $teacher->gender ?? '-',
                $teacher->mobile,
                $teacher->email ?? '-',
                $teacher->highest_qualification ?? '-',
                $teacher->experience ?? 0,
                $teacher->is_deleted == 0 ? 'Active' : 'Inactive'
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function() use ($handle) {
                // Already output
            },
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }







}