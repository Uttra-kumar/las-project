<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classes;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        try {
            $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
            $currentSession = SessionHelper::getCurrentSession();
            return view('student.register', compact('classes', 'currentSession'));
        } catch (\Exception $e) {
            Log::error('Student registration page error: ' . $e->getMessage());
            abort(500, 'Error loading page: ' . $e->getMessage());
        }
    }
    
    public function store(Request $request)
    {
        try {
            \Log::info('Student store request received');
            // dd($request->all());
            // Validate
            $request->validate([
                'full_name' => 'required|string|max:255',
                'mobile' => 'required|string|max:15',
                'address' => 'required|string',
                'city' => 'required|string|max:100',
                'category' => 'required',
                'state' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
                'class_id' => 'required|exists:classes,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $currentSession = SessionHelper::getCurrentSession();
            if (!$currentSession) {
                return response()->json(['error' => 'No active session found!'], 400);
            }
            
            // Generate Student ID
            $randomNum = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
            $studentId = 'STU' . $randomNum . substr($currentSession->session_id, -2);
            
            while (Student::where('student_id', $studentId)->exists()) {
                $randomNum = str_pad(random_int(1, 99999999), 8, '0', STR_PAD_LEFT);
                $studentId = 'STU' . $randomNum . substr($currentSession->session_id, -2);
            }
            
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('students', $filename, 'public');
                \Log::info('Image uploaded: ' . $imagePath);
            }
            
            $student = Student::create([
                'student_id' => $studentId,
                'session_id' => $currentSession->session_id,
                'full_name' => $request->full_name,
                'father_name' => $request->father_name ?? null,
                'mother_name' => $request->mother_name ?? null,
                'dob' => $request->dob ?? null,
                'gender' => $request->gender ?? null,
                'category' => $request->category ?? null,
                'mobile' => $request->mobile,
                'image' => $imagePath,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'pincode' => $request->pincode,
                'class_id' => $request->class_id,
                'previous_institute' => $request->previous_institute ?? null,
                'previous_class' => $request->previous_class ?? null,
                'previous_result' => $request->previous_result ?? null,
                'previous_marks' => $request->previous_marks ?? null,
                'is_promoted' => $request->has('is_promoted') ? 0 : 1,
                'is_hosteler' => $request->has('is_hosteler') ? 0 : 1,
                'is_deleted' => 0,
                'created_by' => Auth::id(),
            ]);
            
            \Log::info('Student created successfully', ['student_id' => $studentId]);
            
            return response()->json([
                'success' => true,
                'message' => 'Student registered successfully!',
                'student_id' => $studentId
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            \Log::error('Student store error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Something went wrong: ' . $e->getMessage()
            ], 500);
        }
    }
}