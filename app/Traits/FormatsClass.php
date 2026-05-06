<?php

namespace App\Traits;

trait FormatsClass
{
    /**
     * Get the class value formatted with an ordinal suffix (e.g., 1st, 2nd, 10th).
     *
     * @return string
     */
    public function getFormattedClassAttribute()
    {
        $value = $this->class;
        
        if (empty($value)) {
            return '-';
        }

        // Handle comma-separated values (if stored as such)
        if (str_contains($value, ',')) {
            $classes = explode(',', $value);
            $formattedClasses = array_map(fn($c) => $this->formatSingleClass(trim($c)), $classes);
            return implode(', ', $formattedClasses);
        }

        return $this->formatSingleClass($value);
    }

    /**
     * Format a single class value.
     *
     * @param string $value
     * @return string
     */
    private function formatSingleClass($value)
    {
        // Normalize: Extract digits
        if (preg_match('/(\d+)/', $value, $matches)) {
            $number = (int) $matches[1];
            
            // Ordinal suffix logic
            // 11, 12, 13 always get 'th'
            if ($number % 100 >= 11 && $number % 100 <= 13) {
                $suffix = 'th';
            } else {
                switch ($number % 10) {
                    case 1:  $suffix = 'st'; break;
                    case 2:  $suffix = 'nd'; break;
                    case 3:  $suffix = 'rd'; break;
                    default: $suffix = 'th'; break;
                }
            }
            return $number . $suffix;
        }

        return $value ?: '-';
    }
}
