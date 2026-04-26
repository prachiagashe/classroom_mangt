<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PTMSchedule extends Model
{
    protected $table = 'ptm_schedules';
    
    protected $fillable = [
        'class_name',
        'course_type',
        'meeting_date',
        'start_time',
        'end_time',
        'teacher_name',
        'meeting_mode',
        'meeting_link',
        'meeting_location',
        'description',
        'status',
    ];

    protected $casts = [
        'meeting_date' => 'date',
    ];

    public function getFormattedDateTimeAttribute()
    {
        return $this->meeting_date->format('d M Y') . ' - ' . date('h:i A', strtotime($this->start_time));
    }

    public function scopeForStudent($query, $studentClass, $studentCourseType = null)
    {
        // Normalize class name - remove suffixes like "th", "st", "nd", "rd"
        if ($studentClass) {
            $studentClass = preg_replace('/(\d+)(?:st|nd|rd|th)/i', '$1', $studentClass);
        }
        
        $query->where('class_name', $studentClass);
        
        if ($studentClass == '11' || $studentClass == '12') {
            $query->where('course_type', $studentCourseType);
        }
        
        return $query;
    }

    public function scopeUpcoming($query)
    {
        return $query->where('meeting_date', '>=', now())
                    ->where('status', 'scheduled')
                    ->orderBy('meeting_date', 'asc')
                    ->orderBy('start_time', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where(function($q) {
                    $q->where('meeting_date', '<', now())
                      ->orWhere('status', 'completed')
                      ->orWhere('status', 'cancelled');
                })
                ->orderBy('meeting_date', 'desc');
    }
}
