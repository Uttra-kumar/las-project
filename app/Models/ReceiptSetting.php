<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptSetting extends Model
{
    use HasFactory;
    
    protected $table = 'receipt_settings';
    
    protected $fillable = [
        'prefix',
        'year',
        'last_receipt_no',
        'receipt_format',
    ];
    
    // Generate next receipt number
    public static function getNextReceiptNumber()
    {
        $setting = self::first();
        if (!$setting) {
            return 'REC-0000-01';
        }
        
        $nextNo = $setting->last_receipt_no + 1;
        $formattedNo = str_pad($nextNo, 2, '0', STR_PAD_LEFT);
        $receiptNo = $setting->prefix . '-' . $setting->year . '-' . $formattedNo;
        
        return $receiptNo;
    }
    
    // Update last receipt number after use
    public static function incrementReceiptNo()
    {
        $setting = self::first();
        if ($setting) {
            $setting->increment('last_receipt_no');
        }
    }
}