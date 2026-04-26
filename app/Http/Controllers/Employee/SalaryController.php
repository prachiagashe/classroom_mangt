<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\SalaryRecord;
use App\Models\Employee\Employee;
use App\Traits\SendsEmployeeEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    use SendsEmployeeEmails;
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('n'));
        $selectedYear = $request->get('year', date('Y'));
        
        // Get all active employees
        $employees = Employee::where('status', 'Active')->get();
        
        // Get salary records for selected month/year
        $salaryRecords = SalaryRecord::where('month', $selectedMonth)
            ->where('year', $selectedYear)
            ->get()
            ->keyBy('employee_code');
        
        // Combine employees with their salary records
        $employeesWithSalary = $employees->map(function($employee) use ($salaryRecords) {
            $salaryRecord = $salaryRecords->get($employee->employee_code);
            
            return [
                'employee' => $employee,
                'salary_record' => $salaryRecord,
                'status' => $salaryRecord ? $salaryRecord->payment_status : 'not_generated'
            ];
        });
            
        return view('employee.salary.index', compact('employeesWithSalary', 'selectedMonth', 'selectedYear'));
    }

    public function generateSalary(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2030',
        ]);

        $month = $request->month;
        $year = $request->year;

        // Get all active employees
        $employees = Employee::where('status', 'Active')->get();

        foreach ($employees as $employee) {
            // Check if salary record already exists
            $existingRecord = SalaryRecord::where('employee_code', $employee->employee_code)
                ->where('month', $month)
                ->where('year', $year)
                ->first();

            if (!$existingRecord) {
                // Create new salary record
                SalaryRecord::create([
                    'employee_code' => $employee->employee_code,
                    'month' => $month,
                    'year' => $year,
                    'basic_salary' => $employee->basic_salary,
                    'deduction_amount' => 0,
                    'bonus_amount' => 0,
                    'net_salary' => $employee->basic_salary,
                    'paid_amount' => 0,
                    'payment_status' => 'pending',
                    'payment_date' => null,
                    'payment_method' => null,
                    'remarks' => null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Salary records generated successfully for ' . date('F Y', mktime(0, 0, 0, $month, 1, $year)));
    }

    public function paySalary($id)
    {
        $salaryRecord = SalaryRecord::with('employee')->findOrFail($id);
        return view('employee.salary.pay', compact('salaryRecord'));
    }

    public function updatePayment(Request $request, $id)
    {
        $request->validate([
            'deduction_amount' => 'nullable|numeric|min:0',
            'bonus_amount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:1',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:bank_transfer,cash,upi,cheque',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $salaryRecord = SalaryRecord::findOrFail($id);

        // Calculate net salary
        $netSalary = $salaryRecord->basic_salary - $request->deduction_amount + $request->bonus_amount;

        // Determine payment status
        $paymentStatus = 'pending';
        if ($request->paid_amount > 0) {
            if ($request->paid_amount >= $netSalary) {
                $paymentStatus = 'paid';
            } else {
                $paymentStatus = 'partial';
            }
        }

        // Update salary record
        $salaryRecord->update([
            'deduction_amount' => $request->deduction_amount,
            'bonus_amount' => $request->bonus_amount,
            'net_salary' => $netSalary,
            'paid_amount' => $request->paid_amount,
            'payment_status' => $paymentStatus,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'remarks' => $request->remarks,
        ]);

        // Send salary payment notification email
        $salaryRecord->load('employee');
        if ($salaryRecord->employee) {
            $period = date('F Y', mktime(0, 0, 0, $salaryRecord->month, 1, $salaryRecord->year));
            $this->sendSalaryPaymentEmail(
                $salaryRecord->employee->first_name . ' ' . $salaryRecord->employee->last_name,
                $salaryRecord->employee->email,
                $salaryRecord->paid_amount,
                $salaryRecord->payment_date,
                $period,
                $salaryRecord->bonus_amount,
                $salaryRecord->deduction_amount
            );
        }

        return redirect()->route('salary.index')->with('success', 'Salary payment updated successfully!');
    }

    public function history($employee_code)
    {
        $employee = Employee::where('employee_code', $employee_code)->firstOrFail();

        $salaryRecords = SalaryRecord::where('employee_code', $employee_code)
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('employee.salary.history', compact('employee', 'salaryRecords'));
    }
}
