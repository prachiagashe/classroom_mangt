<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AcademicAssignmentController extends Controller
{
    /**
     * Handle academic assignment form submission.
     */
    public function store(Request $request)
    {
        Log::info('Academic Assignment Request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'assigned_classes' => 'required|array|min:1',
            'assigned_classes.*' => 'string|max:255',
            'assigned_subjects' => 'required|array|min:1',
            'assigned_subjects.*' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $employee = Employee::findOrFail($request->employee_id);
            
            // Convert arrays to comma-separated strings
            $classesString = implode(', ', $request->assigned_classes);
            $subjectsString = implode(', ', $request->assigned_subjects);
            
            // Update employee with academic assignments
            $employee->update([
                'assigned_classes' => $classesString,
                'assigned_subjects' => $subjectsString,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Academic assignments updated successfully!',
                'data' => [
                    'assigned_classes' => $classesString,
                    'assigned_subjects' => $subjectsString,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Academic Assignment Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating academic assignments: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get academic assignments for an employee.
     */
    public function show($employeeId)
    {
        $employee = Employee::findOrFail($employeeId);
        
        return response()->json([
            'success' => true,
            'data' => [
                'assigned_classes' => $employee->assigned_classes,
                'assigned_subjects' => $employee->assigned_subjects,
                'classes_array' => $employee->assigned_classes_array,
                'subjects_array' => $employee->assigned_subjects_array,
            ]
        ]);
    }
}
