<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $fillable = [
        'employee_code',
        'leave_type',
        'start_date',
        'end_date',
        'reason',
        'attachment',
        'status',
        'admin_remark'
    ];

    /**
     * Get the employee that owns the leave request.
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_code', 'employee_code');
    }
}
