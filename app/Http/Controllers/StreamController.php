<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    public function getStreamsByClass(Request $request)
    {
        $classId = $request->class_id;
        
        if (!$classId) {
            return response()->json([
                'success' => false,
                'message' => 'Class ID is required'
            ]);
        }
        
        $streams = Stream::where('class_id', $classId)
            ->where('status', 'active')
            ->orderBy('stream_name')
            ->get();
        
        return response()->json([
            'success' => true,
            'streams' => $streams
        ]);
    }
}