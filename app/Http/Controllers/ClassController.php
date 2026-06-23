<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    public function index()
    {
        $classes = Classes::orderBy('id', 'asc')->paginate(10);
        return view('academics.index', compact('classes'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255|unique:classes,class_name',
        ]);
        
        // Generate 4 digit random class_id
        $classId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Check if class_id already exists
        while (Classes::where('class_id', $classId)->exists()) {
            $classId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        Classes::create([
            'class_id' => $classId,
            'class_name' => $request->class_name,
            'status' => 'active',
        ]);
        
        return response()->json(['success' => 'Class created successfully!']);
    }
    
    public function edit($id)
    {
        $class = Classes::findOrFail($id);
        return response()->json($class);
    }
    
    public function update(Request $request, $id)
    {
        $class = Classes::findOrFail($id);
        
        $request->validate([
            'class_name' => 'required|string|max:255|unique:classes,class_name,'.$id,
        ]);
        
        $class->update([
            'class_name' => $request->class_name,
            'status' => $request->status,
        ]);
        
        return response()->json(['success' => 'Class updated successfully!']);
    }
    
    public function destroy($id)
    {
        $class = Classes::findOrFail($id);
        $class->delete();
        
        return response()->json(['success' => 'Class deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $classes = Classes::where('class_name', 'like', "%{$search}%")
            ->orWhere('class_id', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        // Return ONLY the table HTML, not full layout
        return view('academics.classes-table', compact('classes'))->render();
    }
}