<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Gallery::where('session_id', $sessionId);
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $galleries = $query->orderBy('id', 'desc')->paginate(12);
        
        $gallery = null;
        if ($request->has('view')) {
            $gallery = Gallery::find($request->view);
        }
        if ($request->has('edit')) {
            $gallery = Gallery::find($request->edit);
        }
        
        return view('control-panel.gallery', compact('galleries', 'gallery', 'currentSession'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'gallery_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:1,2',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('gallery', $filename, 'public');
        }
        
        Gallery::create([
            'session_id' => $currentSession->session_id,
            'title' => $request->title,
            'description' => $request->description,
            'gallery_date' => $request->gallery_date,
            'image' => $imagePath,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Gallery image added successfully!'
        ]);
    }
    
    public function show($id)
    {
        $gallery = Gallery::findOrFail($id);
        return response()->json([
            'success' => true,
            'gallery' => $gallery
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'gallery_date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'status' => 'required|in:1,2',
        ]);
        
        $imagePath = $gallery->image;
        if ($request->hasFile('image')) {
            if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
                Storage::disk('public')->delete($gallery->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('gallery', $filename, 'public');
        }
        
        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'gallery_date' => $request->gallery_date,
            'image' => $imagePath,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Gallery image updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        
        if ($gallery->image && Storage::disk('public')->exists($gallery->image)) {
            Storage::disk('public')->delete($gallery->image);
        }
        
        $gallery->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Gallery image deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Gallery::where('session_id', $sessionId);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $galleries = $query->orderBy('id', 'desc')->get();
        
        $filename = 'gallery_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['S.No', 'Title', 'Description', 'Date', 'Status', 'Image', 'Created At']);
        
        foreach ($galleries as $index => $gallery) {
            fputcsv($handle, [
                $index + 1,
                $gallery->title,
                $gallery->description ?? '-',
                $gallery->gallery_date ? date('d-m-Y', strtotime($gallery->gallery_date)) : '-',
                $gallery->status == '1' ? 'Published' : 'Unpublished',
                $gallery->image ?? '-',
                $gallery->created_at ? date('d-m-Y H:i:s', strtotime($gallery->created_at)) : '-',
            ]);
        }
        
        fclose($handle);
        
        return response()->stream(
            function() use ($handle) {},
            200,
            [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]
        );
    }
}