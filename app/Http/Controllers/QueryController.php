<?php

namespace App\Http\Controllers;

use App\Models\Query;
use Illuminate\Http\Request;

class QueryController extends Controller
{
    public function index(Request $request)
    {
        $query = Query::orderBy('id', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $queries = $query->paginate(15);
        
        return view('control-panel.query', compact('queries'));
    }
    
    public function show($id)
    {
        $query = Query::findOrFail($id);
        return response()->json([
            'success' => true,
            'query' => $query
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $query = Query::findOrFail($id);
        
        $query->update([
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Query updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $query = Query::findOrFail($id);
        $query->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Query deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $query = Query::orderBy('id', 'desc');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $queries = $query->get();
        
        $filename = 'queries_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['S.No', 'Date', 'Name', 'Mobile', 'Email', 'Subject', 'Message', 'Status']);
        
        foreach ($queries as $index => $q) {
            fputcsv($handle, [
                $index + 1,
                $q->created_at ? date('d-m-Y h:i A', strtotime($q->created_at)) : '-',
                $q->name,
                $q->mobile,
                $q->email,
                $q->subject,
                $q->message,
                ucfirst($q->status),
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