<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OtherStaff;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class OtherStaffController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = OtherStaff::where('is_deleted', 0);
        
        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('emp_id', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $staff = $query->orderBy('id', 'desc')->paginate(15);
        
        $departments = ['guard', 'housekeeping', 'driver', 'other'];
        
        return view('management.index', compact('staff', 'departments', 'currentSession'));
    }
    
    public function create()
    {
        $currentSession = SessionHelper::getCurrentSession();
        $departments = ['guard', 'housekeeping', 'driver', 'other'];
        $salaryTypes = ['bank', 'cash'];
        $genders = ['Male', 'Female', 'Other'];
        $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];
        
        return view('management.create', compact(
            'departments',
            'salaryTypes',
            'genders',
            'maritalStatuses',
            'currentSession'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'department' => 'required|in:guard,housekeeping,driver,other',
            'salary' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        // Generate Employee ID
        $deptCode = strtoupper(substr($request->department, 0, 3));
        $randomNum = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        $empId = 'EMP' . $deptCode . $randomNum;
        
        while (OtherStaff::where('emp_id', $empId)->exists()) {
            $randomNum = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $empId = 'EMP' . $deptCode . $randomNum;
        }
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('staff', $filename, 'public');
        }
        
        OtherStaff::create([
            'session_id' => $sessionId,
            'emp_id' => $empId,
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'experience' => $request->experience ?? 0,
            'department' => $request->department,
            'address' => $request->address,
            'city' => $request->city,
            'block' => $request->block,
            'district' => $request->district,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'highest_qualification' => $request->highest_qualification,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc_code' => $request->ifsc_code,
            'salary_type' => $request->salary_type ?? 'bank',
            'salary' => $request->salary ?? 0,
            'registration_date' => $request->registration_date ?? now(),
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Staff registered successfully!',
            'emp_id' => $empId
        ]);
    }
    
    public function edit($id)
    {
        $staff = OtherStaff::findOrFail($id);
        $departments = ['guard', 'housekeeping', 'driver', 'other'];
        $salaryTypes = ['bank', 'cash'];
        $genders = ['Male', 'Female', 'Other'];
        $maritalStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];
        
        return view('management.edit', compact('staff', 'departments', 'salaryTypes', 'genders', 'maritalStatuses'));
    }
    
    public function update(Request $request, $id)
    {
        $staff = OtherStaff::findOrFail($id);
        
        $request->validate([
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'nullable|email|max:255',
            'department' => 'required|in:guard,housekeeping,driver,other',
            'salary' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $imagePath = $staff->image;
        if ($request->hasFile('image')) {
            if ($staff->image && Storage::disk('public')->exists($staff->image)) {
                Storage::disk('public')->delete($staff->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('staff', $filename, 'public');
        }
        
        $staff->update([
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'marital_status' => $request->marital_status,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'experience' => $request->experience ?? 0,
            'department' => $request->department,
            'address' => $request->address,
            'city' => $request->city,
            'block' => $request->block,
            'district' => $request->district,
            'state' => $request->state,
            'pincode' => $request->pincode,
            'highest_qualification' => $request->highest_qualification,
            'bank_name' => $request->bank_name,
            'account_no' => $request->account_no,
            'ifsc_code' => $request->ifsc_code,
            'salary_type' => $request->salary_type ?? 'bank',
            'salary' => $request->salary ?? 0,
            'registration_date' => $request->registration_date,
            'image' => $imagePath,
            'status' => $request->status ?? 'active',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Staff updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $staff = OtherStaff::findOrFail($id);
        $staff->update(['is_deleted' => 1]);
        
        return response()->json([
            'success' => true,
            'message' => 'Staff deleted successfully!'
        ]);
    }
    
    public function show($id)
    {
        $staff = OtherStaff::findOrFail($id);
        return view('management.profile', compact('staff'));
    }
}