<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassAttendance extends Model
{
    protected $table = 'class_attendence';

    protected $fillable = [
        'student_id',
        'class_name',
        'attendance_status',
        'attendance_date',
    ];

    public function student()
    {
        return $this->belongsTo(\App\Models\Admission::class, 'student_id');
    }
}
