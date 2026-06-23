<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Classes;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ParentAuthController extends Controller
{
    // ✅ Show Parent Login Form
    public function showLoginForm()
    {
        $classes = Classes::where('status', 'active')
            ->orderBy('class_name')
            ->get();
            
        return view('auth.parent-login', compact('classes'));
    }

    // ✅ Handle Parent Login
    public function login(Request $request)
    {
        
        $request->validate([
            'mobile' => 'required|digits:10',
            'class_id' => 'required|exists:classes,id',
            'dob' => 'required|date',
        ]);

        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
     
        // ✅ Check in students table
        $student = Student::where(function($query) use ($request) {
                $query->where('mobile', $request->mobile)
                    ->orWhere('mobile', $request->mobile);
                    
            })
            ->where('class_id', $request->class_id)
            ->where('dob', $request->dob)
            ->where('is_deleted', 0)
            ->first();

        if (!$student) {
            return back()->withErrors([
                'mobile' => 'No student found with these credentials.',
            ])->withInput();
        }

        // ✅ Check if student has parent (mobile exists)
        if (!$student->father_mobile && !$student->mother_mobile && !$student->mobile) {
            return back()->withErrors([
                'mobile' => 'No parent mobile found for this student.',
            ])->withInput();
        }

        // ✅ Store student info in session (instead of creating user)
        session([
            'parent_logged_in' => true,
            'student_id' => $student->id,
            'student_name' => $student->full_name,
            'student_class' => $student->class_id,
            'student_mobile' => $student->mobile,
            'parent_mobile' => $request->mobile,
        ]);

        return redirect()->route('parent.dashboard')->with('success', 'Welcome ' . $student->full_name . '!');
    }

    // ✅ Parent Logout
    public function logout()
    {
        session()->forget([
            'parent_logged_in',
            'student_id',
            'student_name',
            'student_class',
            'student_mobile',
            'parent_mobile'
        ]);

        return redirect()->route('parent.login')->with('success', 'Logged out successfully!');
    }
}