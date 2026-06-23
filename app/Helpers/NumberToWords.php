<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $number = (int) $number;
        $words = '';
        $ones = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if ($number == 0) {
            return 'Zero';
        }
        
        if ($number < 20) {
            return $ones[$number];
        }
        
        if ($number < 100) {
            return $tens[floor($number / 10)] . ' ' . $ones[$number % 10];
        }
        
        if ($number < 1000) {
            return $ones[floor($number / 100)] . ' Hundred ' . self::convert($number % 100);
        }
        
        if ($number < 100000) {
            return self::convert(floor($number / 1000)) . ' Thousand ' . self::convert($number % 1000);
        }
        
        if ($number < 10000000) {
            return self::convert(floor($number / 100000)) . ' Lakh ' . self::convert($number % 100000);
        }
        
        return self::convert(floor($number / 10000000)) . ' Crore ' . self::convert($number % 10000000);
    }
}