<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditLog extends Model
{
    use HasFactory;
    
    protected $table = 'edit_logs';
    
    protected $fillable = [
        'fee_payment_id',
        'student_id',
        'action',
        'fees_type_id',
        'old_data',
        'new_data',
        'edited_by',
        'remarks',
    ];
    
    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
    
    public function feesType()
    {
        return $this->belongsTo(FeesType::class, 'fees_type_id');
    }
    
    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }
}