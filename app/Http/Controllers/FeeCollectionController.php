<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Models\FeesType;
use App\Models\FeesMaster;
use App\Models\FeePayment;
use App\Models\EditLog;
use App\Models\DeleteLog;
use App\Models\ReceiptSetting;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FeeCollectionController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $selectedStudent = $request->student_id;
        $student = null;
        $feeCards = [];
        
        if ($selectedStudent) {
            $student = Student::with('class')->find($selectedStudent);
            
            if ($student) {
                $feeTypes = FeesType::where('session_id', $currentSessionId)
                    ->where('status', 'active')
                    ->get();
                
                $payments = FeePayment::where('student_id', $selectedStudent)
                    ->where('session_id', $currentSessionId)
                    ->where('is_deleted', 0)
                    ->get();
                
                foreach ($feeTypes as $feeType) {
                    $feesMaster = FeesMaster::where('class_id', $student->class_id)
                        ->where('fees_type_id', $feeType->id)
                        ->where('session_id', $currentSessionId)
                        ->where('status', 'active')
                        ->first();
                    
                    if ($feesMaster) {
                        $typePayments = $payments->where('fees_type_id', $feeType->id);
                        $totalPaid = $typePayments->sum('paid_amount');
                        $totalDiscount = $typePayments->sum('discount');
                        $totalFine = $typePayments->sum('fine');
                        $totalAmount = $feesMaster->amount;
                        $remaining = $totalAmount - $totalPaid;
                        
                        $feeCards[] = [
                            'id' => $feeType->id,
                            'name' => $feeType->name,
                            'master_id' => $feesMaster->master_id,
                            'total_amount' => $totalAmount,
                            'total_paid' => $totalPaid,
                            'total_discount' => $totalDiscount,
                            'total_fine' => $totalFine,
                            'remaining' => $remaining > 0 ? $remaining : 0,
                            'transactions' => $typePayments->sortByDesc('payment_date')->values(),
                            'icon' => $this->getIconForFeeType($feeType->name)
                        ];
                    }
                }
            }
        }
        
        $receiptSetting = ReceiptSetting::first();
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        return view('fees.collection', compact('student', 'feeCards', 'receiptSetting', 'classes', 'currentSession'));
    }
    
    private function getIconForFeeType($name)
    {
        $name = strtolower($name);
        if (str_contains($name, 'course')) return 'fa-book-open';
        if (str_contains($name, 'hostel')) return 'fa-building';
        if (str_contains($name, 'exam')) return 'fa-file-alt';
        if (str_contains($name, 'library')) return 'fa-book';
        return 'fa-tag';
    }
    
    public function collectFee(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'fees_type_id' => 'required|exists:fees_types,id',
            'payment_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|in:cash,upi,cheque,card,online',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $student = Student::find($request->student_id);
        
        $feesMaster = FeesMaster::where('class_id', $student->class_id)
            ->where('fees_type_id', $request->fees_type_id)
            ->where('session_id', $currentSession->session_id)
            ->first();
        
        if (!$feesMaster) {
            return response()->json(['error' => 'Fee structure not found!'], 400);
        }
        
        $totalPaidSoFar = FeePayment::where('student_id', $request->student_id)
            ->where('fees_type_id', $request->fees_type_id)
            ->where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->sum('paid_amount');
        
        $newTotalPaid = $totalPaidSoFar + $request->paid_amount;
        
        if ($newTotalPaid > $feesMaster->amount) {
            $maxAllowed = $feesMaster->amount - $totalPaidSoFar;
            return response()->json([
                'error' => "Payment exceeds total amount! Maximum allowed: ₹" . number_format($maxAllowed, 2)
            ], 400);
        }
        
        $receiptSetting = ReceiptSetting::first();
        if (!$receiptSetting) {
            return response()->json(['error' => 'Receipt setting not configured!'], 400);
        }
        
        $nextNo = $receiptSetting->last_receipt_no + 1;
        $formattedNo = str_pad($nextNo, 2, '0', STR_PAD_LEFT);
        $receiptNo = $receiptSetting->prefix . '-' . $receiptSetting->year . '-' . $formattedNo;
        
        DB::beginTransaction();
        try {
            $payment = FeePayment::create([
                'receipt_no' => $receiptNo,
                'session_id' => $currentSession->session_id,
                'student_id' => $request->student_id,
                'class_id' => $student->class_id,
                'fees_type_id' => $request->fees_type_id,
                'payment_date' => $request->payment_date,
                'amount' => $feesMaster->amount,
                'discount' => $request->discount ?? 0,
                'fine' => $request->fine ?? 0,
                'paid_amount' => $request->paid_amount,
                'payment_mode' => $request->payment_mode,
                'remarks' => $request->remarks,
                'status' => ($newTotalPaid >= $feesMaster->amount) ? 'paid' : 'partial',
                'created_by' => Auth::id(),
                'is_edited' => 0,
                'is_deleted' => 0,
            ]);
            
            $receiptSetting->increment('last_receipt_no');
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Fee collected successfully!',
                'receipt_no' => $receiptNo,
                'payment' => $payment
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    
    public function editPayment($id)
    {
        $payment = FeePayment::findOrFail($id);
        return response()->json($payment);
    }
    
    public function updatePayment(Request $request, $id)
    {
        $payment = FeePayment::findOrFail($id);
        $oldData = $payment->toArray();
        
        $request->validate([
            'payment_date' => 'required|date',
            'paid_amount' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,upi,cheque,card,online',
        ]);
        
        $feesMaster = FeesMaster::where('class_id', $payment->class_id)
            ->where('fees_type_id', $payment->fees_type_id)
            ->first();
        
        $otherPaymentsTotal = FeePayment::where('student_id', $payment->student_id)
            ->where('fees_type_id', $payment->fees_type_id)
            ->where('id', '!=', $id)
            ->where('is_deleted', 0)
            ->sum('paid_amount');
        
        $newTotalPaid = $otherPaymentsTotal + $request->paid_amount;
        
        if ($newTotalPaid > $feesMaster->amount) {
            return response()->json(['error' => 'Payment exceeds total amount!'], 400);
        }
        
        DB::beginTransaction();
        try {
            $payment->update([
                'payment_date' => $request->payment_date,
                'discount' => $request->discount ?? 0,
                'fine' => $request->fine ?? 0,
                'paid_amount' => $request->paid_amount,
                'payment_mode' => $request->payment_mode,
                'remarks' => $request->remarks,
                'is_edited' => 1,
            ]);
            
            // Store in edit_logs table
            EditLog::create([
                'fee_payment_id' => $payment->id,
                'student_id' => $payment->student_id,
                'action' => 'edited',
                'fees_type_id' => $payment->fees_type_id,
                'old_data' => $oldData,
                'new_data' => $payment->toArray(),
                'edited_by' => Auth::id(),
                'remarks' => $request->remarks ?? 'Payment details updated',
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
    
    public function deletePayment(Request $request, $id)
    {
        $payment = FeePayment::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Store in delete_logs table
            DeleteLog::create([
                'receipt_no' => $payment->receipt_no,
                'session_id' => $payment->session_id,
                'student_id' => $payment->student_id,
                'class_id' => $payment->class_id,
                'fees_type_id' => $payment->fees_type_id,
                'payment_date' => $payment->payment_date,
                'amount' => $payment->amount,
                'discount' => $payment->discount,
                'fine' => $payment->fine,
                'paid_amount' => $payment->paid_amount,
                'payment_mode' => $payment->payment_mode,
                'remarks' => $payment->remarks,
                'status' => $payment->status,
                'created_by' => $payment->created_by,
                'deleted_by' => Auth::id(),
                'delete_reason' => $request->delete_reason ?? 'Payment record deleted',
            ]);
            
            // Soft delete in original table
            $payment->update([
                'is_deleted' => 1,
                'is_edited' => 1,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment deleted successfully!'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong!'], 500);
        }
    }
    
    public function printReceipt($id)
    {
        $payment = FeePayment::with(['student', 'class', 'feesType'])->findOrFail($id);
        return view('fees.print-receipt', compact('payment'));
    }
    
    public function printStatement($studentId)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $student = Student::with('class')->findOrFail($studentId);
        $payments = FeePayment::where('student_id', $studentId)
            ->where('session_id', $currentSession->session_id)
            ->where('is_deleted', 0)
            ->orderBy('payment_date', 'asc')
            ->get();
        
        return view('fees.print-statement', compact('student', 'payments'));
    }
    
    public function studentList(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        $query = Student::with('class')
            ->where('session_id', $currentSessionId)
            ->where('is_deleted', 0);
        
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        
        $students = $query->orderBy('full_name', 'asc')->paginate(15);
        
        return view('fees.student-list', compact('students', 'classes', 'currentSession'));
    }
    
    public function editLogs(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        
        $query = EditLog::with(['student', 'feesType', 'editor'])
            ->orderBy('id', 'desc');
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->paginate(20);
        
        // Return to control-panel folder
        return view('control-panel.edit-logs', compact('logs', 'currentSession'));
    }
    
   public function editLogDetails($id)
{
    try {
        $log = EditLog::with(['student', 'feesType', 'editor'])->find($id);
        
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        
        return response()->json($log);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function deleteLogDetails($id)
{
    try {
        $log = DeleteLog::with(['student', 'class', 'feesType', 'creator', 'deleter'])->find($id);
        
        if (!$log) {
            return response()->json(['error' => 'Log not found'], 404);
        }
        
        return response()->json($log);
        
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
    
    public function deleteLogs(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        
        $query = DeleteLog::with(['student', 'class', 'feesType', 'creator', 'deleter'])
            ->orderBy('id', 'desc');
        
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        
        $logs = $query->paginate(20);
        
        // Return to control-panel folder (consistent with editLogs)
        return view('control-panel.delete-logs', compact('logs', 'currentSession'));
    }

public function restorePayment($id)
{
    DB::beginTransaction();
    try {
        // Find the delete log record
        $deleteLog = DeleteLog::findOrFail($id);
        
        // Check if original payment exists
        $payment = FeePayment::where('receipt_no', $deleteLog->receipt_no)
            ->where('student_id', $deleteLog->student_id)
            ->first();
        
        if ($payment) {
            // Restore the payment
            $payment->update([
                'is_deleted' => 0,
                'is_edited' => 1,
            ]);
        } else {
            // If payment doesn't exist, create new one from delete log
            FeePayment::create([
                'receipt_no' => $deleteLog->receipt_no,
                'session_id' => $deleteLog->session_id,
                'student_id' => $deleteLog->student_id,
                'class_id' => $deleteLog->class_id,
                'fees_type_id' => $deleteLog->fees_type_id,
                'payment_date' => $deleteLog->payment_date,
                'amount' => $deleteLog->amount,
                'discount' => $deleteLog->discount,
                'fine' => $deleteLog->fine,
                'paid_amount' => $deleteLog->paid_amount,
                'payment_mode' => $deleteLog->payment_mode,
                'remarks' => $deleteLog->remarks,
                'status' => $deleteLog->status,
                'created_by' => $deleteLog->created_by,
                'is_edited' => 1,
                'is_deleted' => 0,
            ]);
        }
        
        // Delete from delete_logs table
        $deleteLog->delete();
        
        DB::commit();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment restored successfully!'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}

    
}