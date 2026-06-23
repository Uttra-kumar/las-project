<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeePayment;
use App\Models\ClassSubject;
use App\Models\FeesMaster;
use App\Models\Subject;
use App\Models\SchoolSession;
use App\Models\TeacherSubjectAllocation;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParentFeesController extends Controller
{
    public function index()
    {
        // ✅ Get student from session
        $studentId = session('student_id');
        
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
        
        // ✅ Get fee masters for this student's class
        $feeMasters = FeesMaster::where('session_id', $sessionId)
            ->where('class_id', $student->class_id)
            ->where('status', 'active')
            ->get();
        
        $feeCards = [];
        
        foreach ($feeMasters as $master) {
            // Get transactions for this fee type
            $transactions = FeePayment::where('session_id', $sessionId)
                ->where('student_id', $studentId)
                ->where('fees_type_id', $master->fees_type_id)
                ->get();
            
            $totalPaid = $transactions->sum('paid_amount');
            $totalDiscount = $transactions->sum('discount');
            $totalFine = $transactions->sum('fine');
            
            $feeCards[] = [
                'id' => $master->fees_type_id,
                'name' => $master->feesType->name ?? 'N/A',
                'icon' => 'fa-receipt',
                'total_amount' => $master->amount,
                'total_paid' => $totalPaid,
                'total_discount' => $totalDiscount,
                'total_fine' => $totalFine,
                'remaining' => $master->amount - $totalPaid - $totalDiscount - $totalFine,
                'transactions' => $transactions,
            ];
        }
        
        return view('parent.fees', compact('student', 'feeCards', 'sessionName'));
    }



   public function show($id)
    {
        $student = Student::findOrFail($id);
        
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        // ✅ GET SUBJECTS FROM CLASS_SUBJECTS TABLE
        $classSubject = ClassSubject::where('session_id', $sessionId)
            ->where('class_id', $student->class_id)
            ->first();
        
        $subjectIds = [];
        
        if ($classSubject && $classSubject->subject_ids) {
            // ✅ subject_ids already array because of cast
            if (is_array($classSubject->subject_ids)) {
                $subjectIds = $classSubject->subject_ids;
            } else {
                $subjectIds = json_decode($classSubject->subject_ids, true) ?? [];
            }
        }
        
        // ✅ Get subjects from IDs
        $subjectsList = Subject::whereIn('id', $subjectIds)
            ->where('status', 'active')
            ->orderBy('subject_name')
            ->get();
        
        return view('parent.profile', compact('student', 'subjectsList'));
    }

}