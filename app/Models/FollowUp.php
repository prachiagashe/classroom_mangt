<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'enquiry_id',
        'student_name',
        'followup_date',
        'followup_time',
        'type',
        'followup_type',
        'next_followup_date',
        'notes',
        'status',
        'remarks'
    ];

    public function enquiry()
    {
        return $this->belongsTo(Enquiry::class);
    }

    public function getStudentNameAttribute($value)
{
    return ucwords(strtolower($value));
}

}
