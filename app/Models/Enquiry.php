<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    use HasFactory;

    protected $table = 'enquiries';

    protected $fillable = [
        'enquiry_no',
        'branch_code',
        'date',

        'first_name',
        'middle_name',
        'surname',

        'class',
        'school_time',
        'last_year_percentage',
        'school_name',
        'medium',
        'dob',
        'gender',
        
        'father_occupation',
        'parent_mobile',
        'student_mobile',
        'whatsapp',
        'email',
        'address',

        'foundation',
        'course',

        'sibling1',
        'sibling2',

        'reference1',
        'reference2',

        'source',
        'remarks',
        'parent_feedback',
        'counselling_points',
        'important_notes',

        'total_fees',
        'discount_fees',
        'final_fees',

        'status'
    ];

    /*
    |--------------------------------------------------------------------------
    | Type Casting
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'foundation' => 'array',
        'course' => 'array',
        'source' => 'array',
        'counselling_points' => 'array',
        'important_notes' => 'array',

        'dob' => 'date',
        'date' => 'date',

        'total_fees' => 'float',
        'discount_fees' => 'float',
        'final_fees' => 'float',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function admission()
    {
        return $this->hasOne(\App\Models\Admission::class, 'enquiry_id');
    }

    public function followUps()
    {
        return $this->hasMany(\App\Models\FollowUp::class, 'enquiry_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors (Optional but Professional)
    |--------------------------------------------------------------------------
    */

    public function getFormattedClassAttribute()
    {
        $value = $this->class;
        if (empty($value)) return '-';

        if (is_numeric($value)) {
            $number = (int) $value;
            // Handle special cases: 11, 12, 13 are always 'th'
            if ($number % 100 >= 11 && $number % 100 <= 13) {
                return $number . 'th';
            }
            // Standard ordinal rules
            switch ($number % 10) {
                case 1:  return $number . 'st';
                case 2:  return $number . 'nd';
                case 3:  return $number . 'rd';
                default: return $number . 'th';
            }
        }

        return $value;
    }
}
