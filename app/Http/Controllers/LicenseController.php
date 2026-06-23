<?php
// app/Http/Controllers/LicenseController.php

namespace App\Http\Controllers;

use App\Models\License;
use App\Models\LicenseHistory;
use App\Helpers\LicenseHelper;
use Illuminate\Http\Request;

class LicenseController extends Controller
{
    public function index()
    {
        $license = License::first();

        if (!$license) {
            $license = License::create([
                'secret_key' => 'MY-CUSTOM-SECRET-KEY-123',
                'status' => 'inactive'
            ]);
        }

        $secretKey = $license->secret_key;
        $masterKey = LicenseHelper::decrypt($license->master_key);
        $expiryDate = LicenseHelper::decrypt($license->expiry_date);

        return view('license.index', compact('license', 'secretKey', 'masterKey', 'expiryDate'));
    }

    public function activate(Request $request)
    {
        $request->validate([
            'secret_key' => 'required|string',
            'master_key' => 'required|string|min:10',
            'expiry_date' => 'required|date|after:today',
        ]);

        $license = License::first();

        if (!$license) {
            return response()->json(['success' => false, 'message' => 'License not found']);
        }

        $verify = LicenseHelper::verifyLicense($request->secret_key, $request->master_key);

        if (!$verify['success']) {
            return response()->json(['success' => false, 'message' => $verify['message']]);
        }

        // ✅ Save old expiry for history
        $oldExpiry = LicenseHelper::decrypt($license->expiry_date);

        $license->master_key = LicenseHelper::encrypt($request->master_key);
        $license->expiry_date = LicenseHelper::encrypt($request->expiry_date);
        $license->status = 'active';
        $license->activated_at = now();
        $license->save();

        // ✅ Add history entry
        LicenseHelper::addHistory(
            $license->id,
            'activated',
            $oldExpiry,
            $request->expiry_date,
            'Active'
        );

        return response()->json([
            'success' => true,
            'message' => 'License activated successfully!'
        ]);
    }

    public function renew(Request $request)
    {
        $request->validate([
            'secret_key' => 'required|string',
            'master_key' => 'required|string|min:10',
            'expiry_date' => 'required|date|after:today',
        ]);

        $license = License::first();

        if (!$license) {
            return response()->json(['success' => false, 'message' => 'License not found']);
        }

        $verify = LicenseHelper::verifyLicense($request->secret_key, $request->master_key);

        if (!$verify['success']) {
            return response()->json(['success' => false, 'message' => $verify['message']]);
        }

        // ✅ Save old expiry for history
        $oldExpiry = LicenseHelper::decrypt($license->expiry_date);

        $license->expiry_date = LicenseHelper::encrypt($request->expiry_date);
        $license->status = 'active';
        $license->activated_at = now();
        $license->save();

        // ✅ Add history entry
        LicenseHelper::addHistory(
            $license->id,
            'renewed',
            $oldExpiry,
            $request->expiry_date,
            'Active'
        );

        return response()->json([
            'success' => true,
            'message' => 'License renewed successfully!'
        ]);
    }

    public function updateSecret(Request $request)
    {
        $request->validate([
            'secret_key' => 'required|string|min:10',
        ]);

        $license = License::first();

        if (!$license) {
            return response()->json(['success' => false, 'message' => 'License not found']);
        }

        $license->secret_key = $request->secret_key;
        $license->save();

        return response()->json([
            'success' => true,
            'message' => 'Secret key updated successfully!',
            'secret_key' => $request->secret_key
        ]);
    }

    /**
     * ✅ License History Page (Control Panel)
     */
    public function history()
    {
        $license = License::first();
        
        if (!$license) {
            return redirect()->route('license.index')->with('error', 'License not found');
        }

        $history = LicenseHistory::where('license_id', $license->id)
            ->with('user')
            ->orderBy('id', 'desc')
            ->paginate(20);

        $currentExpiry = LicenseHelper::getExpiryDate();
        $daysLeft = LicenseHelper::getDaysLeft();

        return view('control-panel.license-history', compact('history', 'license', 'currentExpiry', 'daysLeft'));
    }
}