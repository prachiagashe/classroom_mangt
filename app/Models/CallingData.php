<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallingData extends Model
{
    use HasFactory;

    protected $table = 'calling_data';

    protected $fillable = [
        'sr_no',
        'school_name',
        'student_name',
        'mobile_no',
        'response',
        'call_status',
        'visit_branch',
        'follow_up',
        'follow_up_date',
    ];

    protected $casts = [
        'visit_branch' => 'boolean',
        'follow_up' => 'boolean',
        'follow_up_date' => 'datetime',
    ];

    public function getStudentNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : $value;
    }
}
