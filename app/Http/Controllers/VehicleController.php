<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Helpers\SessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Vehicle::query();
        
        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vehicle_name', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('route', 'like', "%{$search}%")
                  ->orWhere('driver', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $vehicles = $query->orderBy('id', 'desc')->paginate(15);
        
        $types = ['renter', 'owned'];
        $statuses = ['active', 'inactive'];
        
        // Get single vehicle for view/edit
        $vehicle = null;
        if ($request->has('view')) {
            $vehicle = Vehicle::find($request->view);
        }
        if ($request->has('edit')) {
            $vehicle = Vehicle::find($request->edit);
        }
        
        return view('management.vehicle.index', compact(
            'vehicles',
            'types',
            'statuses',
            'vehicle',
            'currentSession'
        ));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'registration_no' => 'required|string|max:50|unique:vehicles',
            'color' => 'nullable|string|max:50',
            'capacity' => 'nullable|string|max:50',
            'route' => 'nullable|string|max:255',
            'driver' => 'nullable|string|max:100',
            'helper' => 'nullable|string|max:100',
            'insurance_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:insurance_date',
            'type' => 'required|in:renter,owned',
            'status' => 'required|in:active,inactive',
        ]);
        
        $currentSession = SessionHelper::getCurrentSession();
        
        Vehicle::create([
            'session_id' => $currentSession->session_id,
            'vehicle_name' => $request->vehicle_name,
            'color' => $request->color,
            'capacity' => $request->capacity,
            'route' => $request->route,
            'driver' => $request->driver,
            'helper' => $request->helper,
            'registration_no' => $request->registration_no,
            'insurance_date' => $request->insurance_date,
            'expiry_date' => $request->expiry_date,
            'type' => $request->type,
            'status' => $request->status,
            'remarks' => $request->remarks,
            'created_by' => Auth::id(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle registered successfully!'
        ]);
    }
    
    public function show($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json([
            'success' => true,
            'vehicle' => $vehicle
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);
        
        $request->validate([
            'vehicle_name' => 'required|string|max:255',
            'registration_no' => 'required|string|max:50|unique:vehicles,registration_no,' . $id,
            'color' => 'nullable|string|max:50',
            'capacity' => 'nullable|string|max:50',
            'route' => 'nullable|string|max:255',
            'driver' => 'nullable|string|max:100',
            'helper' => 'nullable|string|max:100',
            'insurance_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:insurance_date',
            'type' => 'required|in:renter,owned',
            'status' => 'required|in:active,inactive',
        ]);
        
        $vehicle->update([
            'vehicle_name' => $request->vehicle_name,
            'color' => $request->color,
            'capacity' => $request->capacity,
            'route' => $request->route,
            'driver' => $request->driver,
            'helper' => $request->helper,
            'registration_no' => $request->registration_no,
            'insurance_date' => $request->insurance_date,
            'expiry_date' => $request->expiry_date,
            'type' => $request->type,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully!'
        ]);
    }
    
    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully!'
        ]);
    }
    
    public function exportCSV(Request $request)
    {
        $currentSession = SessionHelper::getCurrentSession();
        $sessionId = $currentSession->session_id;
        
        $query = Vehicle::where('session_id', $sessionId);
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('vehicle_name', 'like', "%{$search}%")
                  ->orWhere('registration_no', 'like', "%{$search}%")
                  ->orWhere('route', 'like', "%{$search}%");
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        $vehicles = $query->orderBy('id', 'desc')->get();
        
        $filename = 'vehicles_' . date('Y-m-d') . '.csv';
        $handle = fopen('php://output', 'w');
        
        fputcsv($handle, ['S.No', 'Vehicle Name', 'Registration No', 'Color', 'Capacity', 'Route', 'Driver', 'Helper', 'Insurance Date', 'Expiry Date', 'Type', 'Status']);
        
        foreach ($vehicles as $index => $vehicle) {
            fputcsv($handle, [
                $index + 1,
                $vehicle->vehicle_name,
                $vehicle->registration_no,
                $vehicle->color ?? '-',
                $vehicle->capacity ?? '-',
                $vehicle->route ?? '-',
                $vehicle->driver ?? '-',
                $vehicle->helper ?? '-',
                $vehicle->insurance_date ? date('d-m-Y', strtotime($vehicle->insurance_date)) : '-',
                $vehicle->expiry_date ? date('d-m-Y', strtotime($vehicle->expiry_date)) : '-',
                ucfirst($vehicle->type),
                ucfirst($vehicle->status),
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