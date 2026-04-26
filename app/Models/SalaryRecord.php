<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryRecord extends Model
{
    protected $fillable = [
        'employee_code',
        'month',
        'year',
        'basic_salary',
        'deduction_amount',
        'bonus_amount',
        'net_salary',
        'paid_amount',
        'payment_status',
        'payment_date',
        'payment_method',
        'remarks',
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'deduction_amount' => 'decimal:2',
        'bonus_amount' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee\Employee::class, 'employee_code', 'employee_code');
    }
}
