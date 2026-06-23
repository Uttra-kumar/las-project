<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Stream;
use App\Models\Classes;
use App\Models\Subject;
use Illuminate\Http\Request;

class StreamManageController extends Controller
{
    public function index()
    {
        $streams = Stream::with('class')->orderBy('id', 'desc')->paginate(10);
        $classes = Classes::where('status', 'active')->orderBy('class_name', 'asc')->get();
        $subjects = Subject::where('status', 'active')->orderBy('subject_name', 'asc')->get();
        
        return view('academics.manage-stream', compact('streams', 'classes', 'subjects'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'stream_name' => 'required|string|max:255',
            'subjects' => 'required|array',
            'subjects.*' => 'exists:subjects,id',
        ]);
        
        // Generate 4 digit random stream_id
        $streamId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        while (Stream::where('stream_id', $streamId)->exists()) {
            $streamId = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        Stream::create([
            'stream_id' => $streamId,
            'class_id' => $request->class_id,
            'stream_name' => $request->stream_name,
            'subjects' => $request->subjects,
            'status' => 'active',
        ]);
        
        return response()->json(['success' => 'Stream created successfully!']);
    }
    
   public function edit($id)
{
    $stream = Stream::findOrFail($id);
    
    // Ensure subjects is always array
    if (is_string($stream->subjects)) {
        $stream->subjects = json_decode($stream->subjects, true);
    }
    
    // If null, set as empty array
    if (!$stream->subjects) {
        $stream->subjects = [];
    }
    
    return response()->json($stream);
}

public function update(Request $request, $id)
{
    $stream = Stream::findOrFail($id);
    
    $request->validate([
        'class_id' => 'required|exists:classes,id',
        'stream_name' => 'required|string|max:255',
        'subjects' => 'required|array',
        'subjects.*' => 'exists:subjects,id',
        'status' => 'required|in:active,inactive',
    ]);
    
    $stream->update([
        'class_id' => $request->class_id,
        'stream_name' => $request->stream_name,
        'subjects' => $request->subjects, // This will be automatically JSON encoded
        'status' => $request->status,
    ]);
    
    return response()->json(['success' => 'Stream updated successfully!']);
}
    public function destroy($id)
    {
        $stream = Stream::findOrFail($id);
        $stream->delete();
        
        return response()->json(['success' => 'Stream deleted successfully!']);
    }
    
    public function search(Request $request)
    {
        $search = $request->get('search');
        $streams = Stream::with('class')
            ->where('stream_name', 'like', "%{$search}%")
            ->orWhere('stream_id', 'like', "%{$search}%")
            ->orderBy('id', 'desc')
            ->paginate(10);
        
        return view('academics.stream-table', compact('streams'))->render();
    }
}