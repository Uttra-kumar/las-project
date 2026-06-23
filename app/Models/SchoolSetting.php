<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    use HasFactory;
    
    protected $table = 'school_settings';
    
    protected $fillable = [
        'school_name',
        'mobile',
        'email',
        'address',
        'udic',
        'license',
        'logo_1',
        'logo_2',
    ];
    
    // Get school name helper
    public static function getSchoolName()
    {
        $setting = self::first();
        return $setting ? $setting->school_name : 'School Management System';
    }
}