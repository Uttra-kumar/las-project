<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // No constructor with middleware - Laravel 12 compatible
    
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);
        return view('control-panel.users', compact('users'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|string|max:15|unique:users,mobile',
            'role' => 'required|in:admin,teacher,accountant',
            'password' => 'required|min:6',
        ]);
        
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'status' => 1,
            'password' => Hash::make($request->password),
        ]);
        
        return response()->json(['success' => 'User created successfully!']);
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$id,
            'mobile' => 'required|string|max:15|unique:users,mobile,'.$id,
            'role' => 'required|in:admin,teacher,accountant',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'role' => $request->role,
        ]);
        
        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }
        
        return response()->json(['success' => 'User updated successfully!']);
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Don't allow deleting own account
        if ($user->id == auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account!'], 400);
        }
        
        $user->delete();
        
        return response()->json(['success' => 'User deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('mobile', 'like', "%{$search}%")
            ->orWhere('role', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('control-panel.users-table', compact('users'))->render();
    }
}