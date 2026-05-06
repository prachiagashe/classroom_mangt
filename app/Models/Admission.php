<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Enquiry;

use App\Traits\FormatsClass;

class Admission extends Model
{
    use HasFactory, FormatsClass;

    protected $table = 'admissions';

    protected $fillable = [
        'enquiry_id',
        'student_name',
        'parent_name',
        'contact',
        'email',
        'class',
        'roll_number',
        'admission_date',
        'fee_status',
        'payment_mode',
        'installment_type',
        'installment_count',
        'installment_amount',
        'installment_start_date',
        'remarks',
        'total_fees',
        'discount_fees',
        'final_fees',
        'total_fee',
        'paid_amount',
        'address',
        'date_of_birth',
        'blood_group',
        'previous_school',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'date_of_birth'  => 'date',
        'total_fees'      => 'float',
        'discount_fees'   => 'float',
        'final_fees'      => 'float',
        'total_fee'       => 'float',
        'paid_amount'     => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class, 'enquiry_id');
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayment::class, 'admission_id');
    }

    public function payments()
    {
        return $this->hasMany(FeePayment::class, 'admission_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    // Automatically calculate balance
    public function getBalanceAttribute()
    {
        return $this->total_fee - $this->total_paid;
    }

    // Calculate total paid from feePayments table
    public function getTotalPaidAttribute()
    {
        return $this->feePayments()->sum('amount');
    }

    // Calculate pending amount
    public function getPendingAmountAttribute()
    {
        return $this->total_fee - $this->total_paid;
    }

    /*
    |--------------------------------------------------------------------------
    | Helper Methods
    |--------------------------------------------------------------------------
    */

    public function isPaid()
    {
        return $this->fee_status === 'paid';
    }

    public function isPending()
    {
        return $this->fee_status === 'pending';
    }

    public function isOverdue()
    {
        return $this->fee_status === 'overdue';
    }
}
