<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

trait SendsEmployeeEmails
{
    /**
     * Send employment confirmation email.
     *
     * @param string $employeeName
     * @param string $email
     * @param string $employeeCode
     * @param string $designation
     * @return void
     */
    protected function sendEmploymentConfirmationEmail($employeeName, $email, $employeeCode, $designation)
    {
        $email = trim($email);
        $employeeName = ucwords(strtolower(trim($employeeName)));
        
        // Auto-fix common typos reported by user
        if (str_ends_with(strtolower($email), '@gmmail.com')) {
            $oldEmail = $email;
            $email = str_replace('@gmmail.com', '@gmail.com', strtolower($email));
            Log::info("Auto-corrected employee email typo: {$oldEmail} -> {$email}");
        }

        if (empty($email)) {
            Log::warning("Cannot send employment confirmation email: Email address is empty for employee {$employeeName}.");
            return;
        }

        Log::info("Attempting to send premium employment confirmation email to: {$email} for employee: {$employeeName}");

        try {
            $subject = "Employment Confirmation – Bansal Classes";
            
            Mail::send('emails.employee-confirmation', [
                'employeeName' => $employeeName,
                'employeeCode' => $employeeCode,
                'designation' => $designation,
                'department' => null,
                'joiningDate' => null,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Employment confirmation email sent successfully to {$email} for employee {$employeeName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send employment confirmation email to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Send salary payment notification email.
     *
     * @param string $employeeName
     * @param string $email
     * @param float $amount
     * @param string $date
     * @param string $period
     * @param float $bonus
     * @param float $deductions
     * @return void
     */
    protected function sendSalaryPaymentEmail($employeeName, $email, $amount, $date, $period, $bonus, $deductions)
    {
        $email = trim($email);
        $employeeName = ucwords(strtolower(trim($employeeName)));
        
        // Auto-fix common typos reported by user
        if (str_ends_with(strtolower($email), '@gmmail.com')) {
            $oldEmail = $email;
            $email = str_replace('@gmmail.com', '@gmail.com', strtolower($email));
            Log::info("Auto-corrected employee email typo (salary): {$oldEmail} -> {$email}");
        }

        if (empty($email)) {
            Log::warning("Cannot send salary payment email: Email address is empty for employee {$employeeName}.");
            return;
        }

        Log::info("Attempting to send premium salary payment email to: {$email} for employee: {$employeeName}");

        try {
            $subject = "Salary Disbursed – Bansal Classes";
            
            Mail::send('emails.salary-payment', [
                'employeeName' => $employeeName,
                'period' => $period,
                'amount' => $amount,
                'bonus' => $bonus,
                'deductions' => $deductions,
                'date' => $date,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Salary payment email sent successfully to {$email} for employee {$employeeName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send salary payment email to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Send leave status notification email.
     *
     * @param string $employeeName
     * @param string $email
     * @param string $status
     * @param string $startDate
     * @return void
     */
    protected function sendLeaveStatusEmail($employeeName, $email, $status, $startDate)
    {
        $email = trim($email);
        $employeeName = ucwords(strtolower(trim($employeeName)));
        $statusLabel = ucfirst($status);
        $formattedDate = date('d M Y', strtotime($startDate));
        
        if (empty($email)) {
            Log::warning("Cannot send leave status email: Email address is empty for employee {$employeeName}.");
            return;
        }

        Log::info("Attempting to send leave {$status} email to: {$email} for employee: {$employeeName}");

        try {
            $subject = "Leave Request {$statusLabel} – Bansal Classes";
            
            $view = $status === 'approved' ? 'emails.leave-approved' : 'emails.leave-rejected';
            
            Mail::send($view, [
                'employeeName' => $employeeName,
                'startDate' => $formattedDate,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Leave status email sent successfully to {$email} for employee {$employeeName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send leave status email to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Send teacher password setup email.
     *
     * @param string $employeeName
     * @param string $email
     * @param string $setupUrl
     * @return void
     */
    protected function sendTeacherPasswordSetupEmail($employeeName, $email, $setupUrl)
    {
        $email = trim($email);
        $employeeName = ucwords(strtolower(trim($employeeName)));
        
        if (empty($email)) {
            Log::warning("Cannot send teacher setup email: Email address is empty for employee {$employeeName}.");
            return;
        }

        Log::info("Attempting to send teacher password setup email to: {$email}");

        try {
            $subject = "Teacher Account Setup – Bansal Classes";
            
            Mail::send('emails.teacher-setup', [
                'employeeName' => $employeeName,
                'setupUrl' => $setupUrl,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Teacher password setup email sent to {$email}.");
        } catch (\Exception $e) {
            Log::error("Failed to send teacher setup email to {$email}: " . $e->getMessage());
        }
    }
}
