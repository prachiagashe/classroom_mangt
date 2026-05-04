<?php

namespace App\Imports;

use App\Models\Enquiry;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EnquiryImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Skip if enquiry_no already exists
        if (!empty($row['enquiry_no'])) {
            $exists = Enquiry::where('enquiry_no', $row['enquiry_no'])->exists();
            if ($exists) {
                Log::warning("Enquiry Import: Skipping duplicate enquiry_no: " . $row['enquiry_no']);
                return null;
            }
        }

        // Process Date fields
        $date = $this->transformDate($row['date'] ?? null);
        $dob = $this->transformDate($row['dob'] ?? null);

        // Process JSON/Array fields (foundation, course, source)
        // If these are comma-separated in Excel, we convert them to arrays
        $foundation = $this->transformToArray($row['foundation'] ?? null);
        $course = $this->transformToArray($row['course'] ?? null);
        $source = $this->transformToArray($row['source'] ?? null);

        return new Enquiry([
            'enquiry_no'           => $row['enquiry_no'] ?? null,
            'branch_code'          => $row['branch_code'] ?? null,
            'date'                 => $date,
            'first_name'           => $row['first_name'] ?? '',
            'middle_name'          => $row['middle_name'] ?? null,
            'surname'              => $row['surname'] ?? '',
            'class'                => $row['class'] ?? '',
            'school_time'          => $row['school_time'] ?? null,
            'last_year_percentage' => $row['last_year_percentage'] ?? null,
            'school_name'          => $row['school_name'] ?? null,
            'medium'               => $row['medium'] ?? null,
            'dob'                  => $dob,
            'gender'               => $row['gender'] ?? null,
            'father_occupation'    => $row['father_occupation'] ?? null,
            'parent_mobile'        => $row['parent_mobile'] ?? '',
            'student_mobile'       => $row['student_mobile'] ?? null,
            'whatsapp'             => $row['whatsapp'] ?? null,
            'email'                => $row['email'] ?? null,
            'address'              => $row['address'] ?? null,
            'sibling1'             => $row['sibling1'] ?? null,
            'sibling2'             => $row['sibling2'] ?? null,
            'foundation'           => $foundation,
            'course'               => $course,
            'source'               => $source,
            'counselling_points'   => $this->transformToArray($row['counselling_points'] ?? null),
            'important_notes'      => $this->transformToArray($row['important_notes'] ?? null),
            'reference1'           => $row['reference1'] ?? null,
            'reference2'           => $row['reference2'] ?? null,
            'remarks'              => $row['remarks'] ?? null,
            'parent_feedback'      => $row['parent_feedback'] ?? null,
            'total_fees'           => $row['total_fees'] ?? 0,
            'discount_fees'        => $row['discount_fees'] ?? 0,
            'final_fees'           => $row['final_fees'] ?? 0,
            'status'               => 'new',
        ]);
    }

    /**
     * Transform date string/number to Carbon date
     */
    private function transformDate($value)
    {
        if (empty($value)) return null;

        try {
            // Check if it's an Excel numeric date
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Transform comma-separated string to array
     */
    private function transformToArray($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        
        return array_map('trim', explode(',', $value));
    }

    public function rules(): array
    {
        return [
            'first_name'    => 'required',
            'surname'       => 'required',
            'class'         => 'required',
            'parent_mobile' => 'required',
        ];
    }
}
