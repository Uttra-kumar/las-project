<?php
// app/Helpers/LicenseHelper.php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;
use App\Models\License;
use App\Models\LicenseHistory;

class LicenseHelper
{
    public static function encrypt($data)
    {
        if (empty($data)) {
            return null;
        }
        return Crypt::encryptString($data);
    }

    public static function decrypt($encryptedData)
    {
        if (empty($encryptedData)) {
            return null;
        }
        try {
            return Crypt::decryptString($encryptedData);
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function verifyLicense($inputSecretKey, $inputMasterKey)
    {
        $license = License::first();

        if (!$license) {
            return ['success' => false, 'message' => 'License not found'];
        }

        $storedSecretKey = $license->secret_key;
        $storedMasterKey = self::decrypt($license->master_key);

        if ($storedSecretKey !== $inputSecretKey) {
            return ['success' => false, 'message' => 'Invalid secret key'];
        }

        if ($storedMasterKey !== $inputMasterKey) {
            return ['success' => false, 'message' => 'Invalid master key'];
        }

        return ['success' => true, 'message' => 'License verified successfully'];
    }

    public static function isLicenseValid()
    {
        $license = License::first();
        
        if (!$license) {
            return false;
        }

        if ($license->status == 'inactive' || $license->status == 'expired') {
            return false;
        }

        $expiryDate = self::decrypt($license->expiry_date);
        
        if (!$expiryDate) {
            return false;
        }

        if (strtotime($expiryDate) < time()) {
            $license->status = 'expired';
            $license->save();

            // ✅ Add history entry
            self::addHistory($license->id, 'expired', null, $expiryDate, 'Expired');

            return false;
        }

        return true;
    }

    public static function getDaysLeft()
    {
        $license = License::first();
        if (!$license || $license->status != 'active') {
            return null;
        }

        $expiryDate = self::decrypt($license->expiry_date);
        if (!$expiryDate) {
            return null;
        }

        return ceil((strtotime($expiryDate) - time()) / 86400);
    }

    public static function getExpiryDate()
    {
        $license = License::first();
        if (!$license) {
            return null;
        }
        return self::decrypt($license->expiry_date);
    }

    /**
     * ✅ Add history entry
     */
    public static function addHistory($licenseId, $action, $oldExpiry = null, $newExpiry = null, $status = null)
    {
        return LicenseHistory::create([
            'license_id' => $licenseId,
            'action' => $action,
            'old_expiry_date' => $oldExpiry ? self::encrypt($oldExpiry) : null,
            'new_expiry_date' => $newExpiry ? self::encrypt($newExpiry) : null,
            'status' => $status,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_by' => auth()->id() ?? 1,
        ]);
    }
}