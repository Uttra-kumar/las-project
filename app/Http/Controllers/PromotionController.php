<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\FeePayment;
use App\Models\StudentFees;
use App\Models\StudentStreamSelection;
use App\Models\SchoolSession;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        $sessions = SchoolSession::orderBy('id', 'desc')->get();
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        $selectedSession = $request->session_id ?? $currentSessionId;
        $selectedClass = $request->class_id;
        $students = [];
        
        if ($selectedSession && $selectedClass) {
            $students = Student::with(['class', 'stream'])
                ->where('session_id', $selectedSession)
                ->where('class_id', $selectedClass)
                ->where('is_deleted', 0)
                ->orderBy('full_name', 'asc')
                ->get();
        }
        
        return view('promotion.index', compact('sessions', 'classes', 'students', 'selectedSession', 'selectedClass', 'currentSession'));
    }
    
    public function promote(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'from_session' => 'required|string',
            'from_class' => 'required|exists:classes,id',
            'to_class' => 'required|exists:classes,id',
            'to_session' => 'required|string',
        ]);
        
        $fromSession = $request->from_session;
        $toSession = $request->to_session;
        $fromClass = $request->from_class;
        $toClass = $request->to_class;
        
        DB::beginTransaction();
        try {
            $promotedCount = 0;
            
            foreach ($request->student_ids as $studentId) {
                $oldStudent = Student::find($studentId);
                
                // Generate new Student ID for new session
                $randomNum = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
                $newStudentId = 'STU' . $randomNum . substr($toSession, -2);
                
                while (Student::where('student_id', $newStudentId)->exists()) {
                    $randomNum = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
                    $newStudentId = 'STU' . $randomNum . substr($toSession, -2);
                }
                
                // Handle image copy
                $newImagePath = null;
                if ($oldStudent->image) {
                    $oldImagePath = storage_path('app/public/' . $oldStudent->image);
                    if (file_exists($oldImagePath)) {
                        $ext = pathinfo($oldStudent->image, PATHINFO_EXTENSION);
                        $newImageName = 'promoted_' . time() . '_' . uniqid() . '.' . $ext;
                        $newImagePath = 'students/' . $newImageName;
                        copy($oldImagePath, storage_path('app/public/' . $newImagePath));
                    }
                }
                
                // Mark old student as promoted
                $oldStudent->update([
                    'is_promoted' => 1,
                ]);
                
                // Create new student in new session
                Student::create([
                    'student_id' => $newStudentId,
                    'session_id' => $toSession,
                    'full_name' => $oldStudent->full_name,
                    'father_name' => $oldStudent->father_name,
                    'mother_name' => $oldStudent->mother_name,
                    'dob' => $oldStudent->dob,
                    'gender' => $oldStudent->gender,
                    'category' => $oldStudent->category,
                    'mobile' => $oldStudent->mobile,
                    'image' => $newImagePath,
                    'address' => $oldStudent->address,
                    'city' => $oldStudent->city,
                    'state' => $oldStudent->state,
                    'pincode' => $oldStudent->pincode,
                    'class_id' => $toClass,
                    'is_stream' => $oldStudent->is_stream,
                    'stream_id' => $oldStudent->stream_id,
                    'is_promoted' => 0,
                    'is_hosteler' => $oldStudent->is_hosteler,
                    'is_deleted' => 0,
                    'created_by' => Auth::id(),
                ]);
                
                $promotedCount++;
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "{$promotedCount} students promoted successfully!",
                'promoted' => $promotedCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong: ' . $e->getMessage()], 500);
        }
    }
    
    public function history(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        
        $sessions = SchoolSession::orderBy('id', 'desc')->get();
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        $selectedSession = $request->session_id;
        $selectedClass = $request->class_id;
        
        $students = collect();
        
        if ($selectedSession && $selectedClass) {
            $students = Student::with(['class'])
                ->where('session_id', $selectedSession)
                ->where('class_id', $selectedClass)
                ->where('is_deleted', 0)
                ->orderBy('full_name', 'asc')
                ->paginate(20);
        }
        
        return view('promotion.history', compact('sessions', 'classes', 'students', 'selectedSession', 'selectedClass', 'currentSession'));
    }

public function revert(Request $request)
    {
        $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
        ]);

       

        DB::beginTransaction();
        try {
            $revertedCount = 0;

            foreach ($request->student_ids as $studentId) {
                $student = Student::find($studentId);

                if (!$student) {
                   
                    continue;
                }

                

                // ✅ CHECK IF STUDENT IS PROMOTED
                if ($student->is_promoted == 0) {
                    
                    continue;
                }
               
                FeePayment::where('student_id', $studentId)->delete();
                StudentStreamSelection::where('student_id', $studentId)->delete();
                StudentFees::where('student_id', $studentId)->delete();
                if ($student->image && Storage::disk('public')->exists($student->image)) {
                    Storage::disk('public')->delete($student->image);
                    
                }

                // ✅ 1e. Delete Student (Permanent)
                $student->delete();
               


               

                $revertedCount++;
               
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$revertedCount} student(s) reverted successfully!",
                'reverted' => $revertedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
           
            return response()->json([
                'success' => false,
                'error' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }

   
    
}