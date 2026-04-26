<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_name',
        'day',
        'period_number',
        'subject_id',
        'start_time',
        'end_time',
        'published',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the subject associated with this timetable entry.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Scope to get timetable by class.
     */
    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    /**
     * Scope to get timetable by day.
     */
    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    /**
     * Scope to get only published timetables.
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    /**
     * Get formatted time range.
     */
    public function getTimeRangeAttribute()
    {
        if ($this->start_time && $this->end_time) {
            return $this->start_time . ' - ' . $this->end_time;
        }
        return null;
    }
}
