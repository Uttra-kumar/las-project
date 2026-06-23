<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolSettingController extends Controller
{
    public function index()
    {
        $setting = SchoolSetting::first();
        return view('control-panel.settings', compact('setting'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'udic' => 'required|string|max:50|unique:school_settings,udic',
            'license' => 'required|string|max:100|unique:school_settings,license',
            'logo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $logo1Path = null;
        $logo2Path = null;
        
        if ($request->hasFile('logo_1')) {
            $logo1Path = $request->file('logo_1')->store('logos', 'public');
        }
        
        if ($request->hasFile('logo_2')) {
            $logo2Path = $request->file('logo_2')->store('logos', 'public');
        }
        
        SchoolSetting::create([
            'school_name' => $request->school_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'udic' => $request->udic,
            'license' => $request->license,
            'logo_1' => $logo1Path,
            'logo_2' => $logo2Path,
        ]);
        
        return response()->json(['success' => 'School settings created successfully!']);
    }
    
    public function update(Request $request, $id)
    {
        $setting = SchoolSetting::findOrFail($id);
        
        $request->validate([
            'school_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'udic' => 'required|string|max:50|unique:school_settings,udic,'.$id,
            'license' => 'required|string|max:100|unique:school_settings,license,'.$id,
            'logo_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'logo_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = [
            'school_name' => $request->school_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'address' => $request->address,
            'udic' => $request->udic,
            'license' => $request->license,
        ];
        
        if ($request->hasFile('logo_1')) {
            // Delete old logo
            if ($setting->logo_1) {
                Storage::disk('public')->delete($setting->logo_1);
            }
            $data['logo_1'] = $request->file('logo_1')->store('logos', 'public');
        }
        
        if ($request->hasFile('logo_2')) {
            // Delete old logo
            if ($setting->logo_2) {
                Storage::disk('public')->delete($setting->logo_2);
            }
            $data['logo_2'] = $request->file('logo_2')->store('logos', 'public');
        }
        
        $setting->update($data);
        
        return response()->json(['success' => 'School settings updated successfully!']);
    }
    
    public function show()
    {
        $setting = SchoolSetting::first();
        return response()->json($setting);
    }
}