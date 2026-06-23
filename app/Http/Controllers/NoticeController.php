<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Notice::where('session_id', $sessionId);
        
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
        
        // Filter by date
        if ($request->filled('date_from')) {
            $query->whereDate('notice_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('notice_date', '<=', $request->date_to);
        }
        
        $notices = $query->orderBy('id', 'desc')->paginate(15);
        
        // Get single notice for view/edit
        $notice = null;
        if ($request->has('view')) {
            $notice = Notice::find($request->view);
        }
        if ($request->has('edit')) {
            $notice = Notice::find($request->edit);
        }
        
        return view('control-panel.notice', compact(
            'notices',
            'notice',
            'currentSession'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'notice_date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        Notice::create([
            'session_id' => $currentSession->session_id,
            'notice_date' => $request->notice_date,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notice created successfully!'
        ]);
    }
    
    public function show($id)
    {
        $notice = Notice::findOrFail($id);
        return response()->json([
            'success' => true,
            'notice' => $notice
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);
        
        $request->validate([
            'notice_date' => 'required|date',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:1,2',
        ]);
        
        $notice->update([
            'notice_date' => $request->notice_date,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Notice updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Notice deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Notice::where('session_id', $sessionId);
        
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
        
        $notices = $query->orderBy('id', 'desc')->get();
        
        $filename = 'notices_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['S.No', 'Date', 'Title', 'Description', 'Status', 'Created At']);
        
        foreach ($notices as $index => $notice) {
            fputcsv($handle, [
                $index + 1,
                $notice->notice_date ? date('d-m-Y', strtotime($notice->notice_date)) : '-',
                $notice->title,
                $notice->description ?? '-',
                $notice->status == '1' ? 'Published' : 'Unpublished',
                $notice->created_at ? date('d-m-Y H:i:s', strtotime($notice->created_at)) : '-',
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