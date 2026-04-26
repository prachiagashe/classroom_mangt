<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'class_name',
        'course_type',
        'description',
        'teacher_name',
        'teacher_email',
        'teacher_id',
        'credits',
        'is_active',
        'color',
        'schedule',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(Employee::class, 'teacher_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByClass($query, $className)
    {
        return $query->where('class_name', $className);
    }

    public function scopeByCourseType($query, $courseType)
    {
        return $query->where('course_type', $courseType);
    }

    public function scopeByClassAndCourse($query, $className, $courseType)
    {
        return $query->where('class_name', $className)->where('course_type', $courseType);
    }
}
