<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'designation',
        'department',
        'joining_date',
        'employment_type',
        'status',
        'basic_salary',
        'salary_type',
        'payment_method',
        'bank_name',
        'account_number',
        'IFSC_code',
        'salary',
        'education',
        'experience',
        'assigned_classes',
        'assigned_subjects',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'joining_date' => 'date',
        'education' => 'string',
        'experience' => 'string',
    ];

    public function getFirstNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : $value;
    }

    public function getMiddleNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : $value;
    }

    public function getLastNameAttribute($value)
    {
        return $value ? ucwords(strtolower($value)) : $value;
    }

    /**
     * Get the full name of the employee.
     */
    public function getFullNameAttribute(): string
    {
        $fullName = $this->first_name;
        
        if ($this->middle_name) {
            $fullName .= ' ' . $this->middle_name;
        }
        
        $fullName .= ' ' . $this->last_name;
        
        return $fullName;
    }

    /**
     * Generate unique employee code.
     */
    public static function generateEmployeeCode(): string
    {
        $lastEmployee = self::orderBy('id', 'desc')->first();
        $lastNumber = $lastEmployee ? (int) substr($lastEmployee->employee_code, -3) : 0;
        $newNumber = $lastNumber + 1;
        
        return 'EMP-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Format education entries into a single string.
     */
    public static function formatEducation(array $educationEntries): string
    {
        $formattedEducation = [];
        
        foreach ($educationEntries as $entry) {
            if (!empty($entry['degree']) && !empty($entry['institution'])) {
                $educationString = $entry['degree'];
                $educationString .= ' - ' . $entry['institution'];
                
                if (!empty($entry['year_of_passing'])) {
                    $educationString .= ' - ' . $entry['year_of_passing'];
                }
                
                if (!empty($entry['grade'])) {
                    $educationString .= ' - ' . $entry['grade'];
                }
                
                $formattedEducation[] = $educationString;
            }
        }
        
        return implode(', ', $formattedEducation);
    }

    /**
     * Format experience entries into a single string.
     */
    public static function formatExperience(array $experienceEntries): string
    {
        $formattedExperience = [];
        
        foreach ($experienceEntries as $entry) {
            if (!empty($entry['organization']) && !empty($entry['role'])) {
                $experienceString = $entry['organization'];
                $experienceString .= ' - ' . $entry['role'];
                
                if (!empty($entry['start_date']) && !empty($entry['end_date'])) {
                    $startDate = date('Y', strtotime($entry['start_date']));
                    $endDate = $entry['end_date'] === 'Present' ? 'Present' : date('Y', strtotime($entry['end_date']));
                    $experienceString .= ' - ' . $startDate . ' to ' . $endDate;
                }
                
                if (!empty($entry['total_experience'])) {
                    $experienceString .= ' (' . $entry['total_experience'] . ')';
                }
                
                $formattedExperience[] = $experienceString;
            }
        }
        
        return implode(', ', $formattedExperience);
    }

    /**
     * Get assigned classes as array.
     */
    public function getAssignedClassesArrayAttribute(): array
    {
        return $this->assigned_classes ? explode(', ', $this->assigned_classes) : [];
    }

    /**
     * Get assigned subjects as array.
     */
    public function getAssignedSubjectsArrayAttribute(): array
    {
        if (!$this->assigned_subjects) return [];

        $decoded = json_decode($this->assigned_subjects, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // Flatten the mapping back to a single array of unique subjects for compatibility
            $allSubjects = [];
            foreach ($decoded as $class => $subjects) {
                if (is_array($subjects)) {
                    $allSubjects = array_merge($allSubjects, $subjects);
                }
            }
            return array_values(array_unique($allSubjects));
        }

        return explode(', ', $this->assigned_subjects);
    }

    /**
     * Get assigned subject mapping as array (Class -> Subjects)
     */
    public function getSubjectMappingAttribute(): array
    {
        if (!$this->assigned_subjects) return [];
        $decoded = json_decode($this->assigned_subjects, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }
        return [];
    }

    /**
     * Get primary class (first class in the list).
     */
    public function getPrimaryClassAttribute(): ?string
    {
        $classes = $this->assigned_classes_array;
        return $classes[0] ?? null;
    }

    /**
     * Get salary records for employee.
     */
    public function salaryRecords()
    {
        return $this->hasMany(\App\Models\SalaryRecord::class, 'employee_code', 'employee_code');
    }

    /**
     * Get attendance records for employee.
     */
    public function attendance()
    {
        return $this->hasMany(\App\Models\AttendanceRecord::class, 'employee_code', 'employee_code');
    }
}