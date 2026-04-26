<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'employee_code',
        'attendance_date',
        'status'
    ];

    /**
     * Get the employee that owns the attendance record.
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_code', 'employee_code');
    }
}
//teachers attendence