<?php

namespace App\Http\Controllers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Student;
use App\Models\SchoolSession;
use App\Models\Classes;
use App\Models\Stream;
use App\Models\StudentStreamSelection;
use App\Models\ClassSubject;
use App\Models\Subject;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class StudentListController extends Controller
{
    public function index(Request $request)
    {
        // Get current active session
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Query only students from current active session
        $query = Student::with(['class', 'creator', 'session'])
            ->where('is_deleted', 0)
            ->where('session_id', $currentSessionId);  // Only current session students
        
        // Apply filters
        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('type') && $request->type != 'all') {
            $isHosteler = $request->type == 'hosteler' ? 1 : 0;
            $query->where('is_hosteler', $isHosteler);
        }
        
        $students = $query->orderBy('id', 'desc')->paginate(10);
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        
        // For AJAX requests, return only the table partial
        if ($request->ajax()) {
            return view('student.table', compact('students'))->render();
        }
        
        return view('student.index', compact('students', 'classes', 'currentSession'));
    }
    
public function show($id)
{
    $currentSession = SessionHelper::getCurrentSession();
    $currentSessionId = $currentSession ? $currentSession->session_id : null;
    
    $student = Student::with(['class', 'creator', 'session', 'stream'])
        ->where('id', $id)
        ->where('is_deleted', 0)
        ->where('session_id', $currentSessionId)
        ->firstOrFail();
    
    // Generate QR Code with link to student details page
    $qrData = route('student.qr.data', $student->id);
    
    // Use API to generate QR
    $qrCode = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($qrData);
    
    $subjectsList = [];
    if ($student->is_stream == 1 && $student->stream_id) {
        $stream = Stream::find($student->stream_id);
        if ($stream) {
            $subjectIds = is_string($stream->subjects) ? json_decode($stream->subjects, true) : $stream->subjects;
            $subjectsList = Subject::whereIn('id', $subjectIds)->orderBy('subject_name')->get();
        }
    } else {
        $classSubject = ClassSubject::where('class_id', $student->class_id)
            ->where('session_id', $currentSessionId)
            ->first();
        if ($classSubject) {
            $subjectIds = is_string($classSubject->subject_ids) ? json_decode($classSubject->subject_ids, true) : $classSubject->subject_ids;
            $subjectsList = Subject::whereIn('id', $subjectIds)->orderBy('subject_name')->get();
        }
    }
    
    return view('student.profile', compact('student', 'subjectsList', 'qrCode'));
}
    
    // ✅ QR Code Generator using GD Library (No imagick)
    private function generateQRCode($student)
    {
        // Simple QR Code using HTML + CSS (No external library)
        $qrData = [
            'student_id' => $student->student_id,
            'name' => $student->full_name,
            'class' => $student->class->class_name ?? 'N/A',
            'father' => $student->father_name ?? 'N/A',
            'mobile' => $student->mobile,
            'session' => $student->session->session_name ?? 'N/A'
        ];
        
        // Generate QR as SVG using simple API
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/';
        $qrParams = [
            'size' => '200x200',
            'data' => json_encode($qrData),
            'format' => 'png',
            'bgcolor' => 'ffffff',
            'color' => '1a1a2e',
            'margin' => '10'
        ];
        
        $qrImageUrl = $qrUrl . '?' . http_build_query($qrParams);
        
        // Return image URL
        return $qrImageUrl;
    }


    public function qrData($id)
{
    $student = Student::with(['class', 'session'])->findOrFail($id);
    return view('student.qr-data', compact('student'));
}
    
    public function edit($id)
    {
        // Get current active session
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Find student only if belongs to current active session
        $student = Student::where('id', $id)
            ->where('is_deleted', 0)
            ->where('session_id', $currentSessionId)
            ->firstOrFail();
            
        $classes = Classes::where('status', 'active')->get();
        return view('student.edit', compact('student', 'classes'));
    }
    
    
    
    public function destroy($id)
    {
        // Get current active session
        $currentSession = SessionHelper::getCurrentSession();
        $currentSessionId = $currentSession ? $currentSession->session_id : null;
        
        // Find student only if belongs to current active session
        $student = Student::where('id', $id)
            ->where('is_deleted', 0)
            ->where('session_id', $currentSessionId)
            ->firstOrFail();
            
        $student->update(['is_deleted' => 1]);
        
        return response()->json(['success' => 'Student deleted successfully!']);
    }

    
  public function update(Request $request, $id)
{
    $student = Student::findOrFail($id);
    
    $request->validate([
        'full_name' => 'required|string|max:255',
        'mobile' => 'required|string|max:15',
        'address' => 'required|string',
        'city' => 'required|string|max:100',
        'state' => 'required|string|max:100',
        'pincode' => 'required|string|max:10',
        'class_id' => 'required|exists:classes,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);
    
    // Handle image upload
    $imagePath = $student->image;
    if ($request->hasFile('image')) {
        if ($student->image && Storage::disk('public')->exists($student->image)) {
            Storage::disk('public')->delete($student->image);
        }
        $image = $request->file('image');
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('students', $filename, 'public');
    }
    
    $student->update([
        'full_name' => $request->full_name,
        'father_name' => $request->father_name,
        'mother_name' => $request->mother_name,
        'dob' => $request->dob,
        'gender' => $request->gender,
        'category' => $request->category,
        'mobile' => $request->mobile,
        'image' => $imagePath,
        'address' => $request->address,
        'city' => $request->city,
        'state' => $request->state,
        'pincode' => $request->pincode,
        'class_id' => $request->class_id,
        'previous_institute' => $request->previous_institute,
        'previous_class' => $request->previous_class,
        'previous_result' => $request->previous_result,
        'previous_marks' => $request->previous_marks,
        'is_promoted' => $request->is_promoted ? 1 : 0,
        'is_hosteler' => $request->is_hosteler ? 1 : 0,
    ]);
    
    return response()->json([
        'success' => true,
        'message' => 'Student updated successfully!'
    ]);
}
} 