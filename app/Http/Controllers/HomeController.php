<?php

namespace App\Http\Controllers;

use App\Models\SchoolSetting;
use App\Models\Notice;
use App\Models\Gallery;
use App\Models\Query;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $school = SchoolSetting::first();
        $currentSession = SessionHelper::getCurrentSession();
        $sessionName = $currentSession ? $currentSession->session_name : 'N/A';
        
        // ✅ Published Notices
        $notices = Notice::where('status', '1')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
        
        // ✅ Published Gallery Images
        $galleries = Gallery::where('status', '1')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();
        
        return view('home', compact('school', 'notices', 'galleries', 'sessionName'));
    }
    
    public function storeQuery(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        Query::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'query',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Your message has been sent successfully!'
        ]);
    }
}