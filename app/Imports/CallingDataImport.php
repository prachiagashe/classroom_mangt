<?php

namespace App\Imports;

use App\Models\CallingData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CallingDataImport implements ToModel, WithStartRow, WithValidation, SkipsEmptyRows, WithCalculatedFormulas
{
    private $schoolName = null;
    private $startRow = 3; // Always start from row 3 (skip school name and headers)
    private $currentSrNo = 1;

    /**
     * Extract school name from first row before processing data
     */
    public function beforeImport($event)
    {
        $sheet = $event->getReader()->getActiveSheet();
        
        // Get school name from first row, column A (index 0)
        $schoolNameCell = $sheet->getCell('A1');
        $this->schoolName = trim($schoolNameCell->getValue());
        
        // Get the last Sr No from database for auto-increment
        $lastSrNo = CallingData::max('sr_no') ?? 0;
        $this->currentSrNo = $lastSrNo + 1;
        
        \Log::info('Extracted school name: ' . $this->schoolName);
        \Log::info('Starting Sr No from: ' . $this->currentSrNo);
    }

    /**
     * Start reading from row 3 (skip school name and header rows)
     */
    public function startRow(): int
    {
        return $this->startRow;
    }

    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        \Log::info('Processing row at index ' . ($this->startRow) . ':', $row);
        
        // Column-based mapping (ignore Excel Sr No)
        // Column A (0) → Sr No (IGNORE)
        // Column B (1) → Student Name  
        // Column C (2) → Mobile Number
        
        $studentName = isset($row[1]) ? trim($row[1]) : null;
        $contactNo = isset($row[2]) ? trim($row[2]) : null;
        
        // Extract mobile number from "Mobile: 9623451982" format
        $mobileNo = null;
        if ($contactNo) {
            $mobileNo = preg_replace('/[^0-9]/', '', $contactNo);
            \Log::info("Mobile extracted: '$contactNo' → '$mobileNo'");
        }
        
        \Log::info("Processed data - Student: '$studentName', Mobile: '$mobileNo', School: '{$this->schoolName}'");
        
        // Skip invalid rows - if student_name OR mobile empty
        if (empty($studentName) && empty($mobileNo)) {
            \Log::warning('Skipping row: no student name and no mobile number');
            return null;
        }
        
        // Create record with auto-incremented Sr No
        $callingData = new CallingData([
            'sr_no' => $this->currentSrNo++,
            'school_name' => $this->schoolName, // Never use "Unknown School"
            'student_name' => $studentName ?: '',
            'mobile_no' => $mobileNo ?: '',
            'response' => null,
            'call_status' => null,
            'visit_branch' => false,
            'follow_up' => false,
        ]);
        
        \Log::info('Created CallingData record:', $callingData->toArray());
        
        return $callingData;
    }

    /**
     * Validation rules for the import.
     */
    public function rules(): array
    {
        return [
            // No strict validation - handled in model() method
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages(): array
    {
        return [
            // No strict validation messages
        ];
    }

    /**
     * Prepare data for validation
     */
    public function prepareForValidation($data, $index)
    {
        \Log::info("Preparing data for validation at row " . ($index + $this->startRow) . ":", $data);
        
        // Trim all string values
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = trim($value);
            }
        }
        
        return $data;
    }
}
