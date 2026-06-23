<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classes;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    public function index()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $teachers = Teacher::where('status',1)
            ->where('is_deleted', 0)
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('teacher.index', compact('teachers', 'currentSession'));
    }
    
    public function create()
    {
        $currentSession = SessionHelper::getCurrentSession();
        return view('teacher.register', compact('currentSession'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        // Generate Teacher ID: TCH + 6 digit random + session last 2 digits
        $randomNum = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        $teacherId = 'TCH' . $randomNum . substr($currentSession->session_id, -2);
        
        while (Teacher::where('teacher_id', $teacherId)->exists()) {
            $randomNum = str_pad(random_int(1, 999999), 6, '0', STR_PAD_LEFT);
            $teacherId = 'TCH' . $randomNum . substr($currentSession->session_id, -2);
        }
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('teachers', $filename, 'public');
        }
        
        Teacher::create([
            'teacher_id' => $teacherId,
            'session_id' => $currentSession->session_id,
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'experience' => $request->experience ?? 0,
            'address' => $request->address,
            'city' => $request->city,
            'block' => $request->block,
            'district' => $request->district,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'highest_qualification' => $request->highest_qualification,
            'institute_name' => $request->institute_name,
            'passing_year' => $request->passing_year,
            'obtained_marks' => $request->obtained_marks,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc_code' => $request->ifsc_code,
            'salary_type' => $request->salary_type ?? 'bank',
            'salary' => $request->salary ?? 0,
            'is_hosteler' => $request->has('is_hosteler') ? 1 : 0,
            'registration_date' => $request->registration_date,
            'image' => $imagePath,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Teacher registered successfully!',
            'teacher_id' => $teacherId
        ]);
    }
    
    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teacher.profile', compact('teacher'));
    }
    
    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teacher.edit', compact('teacher'));
    }
    
    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $imagePath = $teacher->image;
        if ($request->hasFile('image')) {
            if ($teacher->image && Storage::disk('public')->exists($teacher->image)) {
                Storage::disk('public')->delete($teacher->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('teachers', $filename, 'public');
        }
        
        $teacher->update([
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'experience' => $request->experience ?? 0,
            'address' => $request->address,
            'city' => $request->city,
            'block' => $request->block,
            'district' => $request->district,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'highest_qualification' => $request->highest_qualification,
            'institute_name' => $request->institute_name,
            'passing_year' => $request->passing_year,
            'obtained_marks' => $request->obtained_marks,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc_code' => $request->ifsc_code,
            'salary_type' => $request->salary_type ?? 'bank',
            'salary' => $request->salary ?? 0,
            'is_hosteler' => $request->input('is_hosteler', 0),
            'registration_date' => $request->registration_date,
            'image' => $imagePath,
            'status' => $request->status,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->update(['is_deleted' => 1]);
        
        return response()->json(['success' => 'Teacher deleted successfully!']);
    }
    
   public function list(Request $request)
{
    $currentSession = SessionHelper::getCurrentSession();

    $query = Teacher::where('is_deleted', 0);

    // Search Filter
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('full_name', 'like', "%{$search}%")
              ->orWhere('teacher_id', 'like', "%{$search}%")
              ->orWhere('mobile', 'like', "%{$search}%");
        });
    }

    // Status Filter
   // Status Filter
if ($request->filled('status')) {
    if ($request->status == 'active') {
        $query->where('status', 1);
    } elseif ($request->status == 'inactive') {
        $query->where('status', 0);
    }

} 

    $teachers = $query->orderBy('id', 'desc')->paginate(10);

    if ($request->ajax() || $request->has('ajax')) {
        return view('teacher.table', compact('teachers'))->render();
    }

    return view('teacher.index', compact('teachers', 'currentSession'));
}
}