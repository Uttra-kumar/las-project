<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('id', 'desc')->paginate(10);
        return view('academics.subjects', compact('subjects'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name',
        ]);
        
        // Generate 4 digit random subject_id
        $subjectId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        
        while (Subject::where('subject_id', $subjectId)->exists()) {
            $subjectId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        Subject::create([
            'subject_id' => $subjectId,
            'subject_name' => $request->subject_name,
            'status' => 'active',
        ]);
        
        return response()->json(['success' => 'Subject created successfully!']);
    }
    
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        return response()->json($subject);
    }
    
    public function update(Request $request, $id)
    {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'subject_name' => 'required|string|max:255|unique:subjects,subject_name,'.$id,
        ]);
        
        $subject->update([
            'subject_name' => $request->subject_name,
            'status' => $request->status,
        ]);
        
        return response()->json(['success' => 'Subject updated successfully!']);
    }
    
    public function destroy($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();
        
        return response()->json(['success' => 'Subject deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $subjects = Subject::where('subject_name', 'like', "%{$search}%")
            ->orWhere('subject_id', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('academics.subjects-table', compact('subjects'))->render();
    }
}