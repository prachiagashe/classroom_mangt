<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DoubtSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'teacher_id',
        'class_name',
        'session_date',
        'start_time',
        'end_time',
        'description',
        'status'
    ];

    protected $casts = [
        'session_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('session_date', '>=', now()->format('Y-m-d'));
    }

    public function scopeForClass($query, $className)
    {
        return $query->whereHas('subject', function ($q) use ($className) {
            $q->where('class_name', $className);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFormattedDateAttribute()
    {
        return $this->session_date->format('M d, Y');
    }

    public function getFormattedTimeAttribute()
    {
        try {
            // Check if start_time contains a space (full datetime)
            if (strpos($this->start_time, ' ') !== false) {
                $startTime = Carbon::parse($this->start_time);
            } else {
                $startTime = Carbon::parse($this->session_date . ' ' . $this->start_time);
            }
            
            // Check if end_time contains a space (full datetime)
            if (strpos($this->end_time, ' ') !== false) {
                $endTime = Carbon::parse($this->end_time);
            } else {
                $endTime = Carbon::parse($this->session_date . ' ' . $this->end_time);
            }
            
            return $startTime->format('h:i A') . ' - ' . $endTime->format('h:i A');
        } catch (\Exception $e) {
            // Fallback to original format if parsing fails
            return $this->start_time . ' - ' . $this->end_time;
        }
    }

    public function getFormattedDateTimeAttribute()
    {
        try {
            // Check if start_time contains a space (full datetime)
            if (strpos($this->start_time, ' ') !== false) {
                $startTime = Carbon::parse($this->start_time);
            } else {
                $startTime = Carbon::parse($this->session_date . ' ' . $this->start_time);
            }
            
            return $this->session_date->format('M d, Y') . ' at ' . $startTime->format('h:i A');
        } catch (\Exception $e) {
            // Fallback to original format if parsing fails
            return $this->session_date->format('M d, Y') . ' at ' . $this->start_time;
        }
    }
}
