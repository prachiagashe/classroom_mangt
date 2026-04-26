<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAttendence extends Model
{
    protected $table = 'student_attendence';
    
    protected $fillable = [
        'roll_no',
        'name',
        'month',
        'day_1', 'day_2', 'day_3', 'day_4', 'day_5',
        'day_6', 'day_7', 'day_8', 'day_9', 'day_10',
        'day_11', 'day_12', 'day_13', 'day_14', 'day_15',
        'day_16', 'day_17', 'day_18', 'day_19', 'day_20',
        'day_21', 'day_22', 'day_23', 'day_24', 'day_25',
        'day_26', 'day_27', 'day_28', 'day_29', 'day_30', 'day_31',
        'total_p',
        'total_a',
        'percentage'
    ];

    /**
     * Calculate attendance statistics
     */
    public function calculateStatistics()
    {
        $present = 0;
        $absent = 0;
        
        for ($i = 1; $i <= 31; $i++) {
            $dayColumn = "day_{$i}";
            $status = $this->$dayColumn;
            
            if ($status === 'P') {
                $present++;
            } elseif ($status === 'A') {
                $absent++;
            }
        }
        
        $total = $present + $absent;
        $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        
        $this->total_p = $present;
        $this->total_a = $absent;
        $this->percentage = $percentage;
        
        $this->save();
    }
}
