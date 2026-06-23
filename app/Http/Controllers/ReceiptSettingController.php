<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ReceiptSetting;
use Illuminate\Http\Request;

class ReceiptSettingController extends Controller
{
    public function index()
    {
        $setting = ReceiptSetting::first();
        return view('control-panel.receipt-setting', compact('setting'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string|max:10',
            'year' => 'required|string|max:10',
            'last_receipt_no' => 'required|integer|min:0',
        ]);
        
        ReceiptSetting::create([
            'prefix' => strtoupper($request->prefix),
            'year' => $request->year,
            'last_receipt_no' => $request->last_receipt_no,
            'receipt_format' => $request->prefix . '-' . $request->year . '-XX',
        ]);
        
        return response()->json(['success' => 'Receipt settings saved successfully!']);
    }
    
    public function update(Request $request, $id)
    {
        $setting = ReceiptSetting::findOrFail($id);
        
        $request->validate([
            'prefix' => 'required|string|max:10',
            'year' => 'required|string|max:10',
            'last_receipt_no' => 'required|integer|min:0',
        ]);
        
        $setting->update([
            'prefix' => strtoupper($request->prefix),
            'year' => $request->year,
            'last_receipt_no' => $request->last_receipt_no,
            'receipt_format' => strtoupper($request->prefix) . '-' . $request->year . '-XX',
        ]);
        
        return response()->json(['success' => 'Receipt settings updated successfully!']);
    }
    
    // Preview next receipt number
    public function preview(Request $request)
    {
        $prefix = strtoupper($request->prefix);
        $year = $request->year;
        $lastNo = $request->last_receipt_no;
        
        $nextNo = $lastNo + 1;
        $formattedNo = str_pad($nextNo, 2, '0', STR_PAD_LEFT);
        $nextReceipt = $prefix . '-' . $year . '-' . $formattedNo;
        
        return response()->json([
            'next_receipt' => $nextReceipt,
            'next_number' => $nextNo
        ]);
    }
}