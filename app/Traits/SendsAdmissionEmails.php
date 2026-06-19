<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

trait SendsAdmissionEmails
{
    /**
     * Send admission confirmation email.
     *
     * @param string $studentName
     * @param string $email
     * @param string $className
     * @return void
     */
    protected function sendAdmissionConfirmationEmail($studentName, $email, $className)
    {
        $email = trim($email);
        $studentName = ucwords(strtolower(trim($studentName)));
        
        // Auto-fix common typos reported by user
        if (str_ends_with(strtolower($email), '@gmmail.com')) {
            $oldEmail = $email;
            $email = str_replace('@gmmail.com', '@gmail.com', strtolower($email));
            Log::info("Auto-corrected email typo: {$oldEmail} -> {$email}");
        }

        if (empty($email)) {
            Log::warning("Cannot send admission confirmation email: Email address is empty for student {$studentName}.");
            return;
        }

        Log::info("Attempting to send premium admission confirmation email to: {$email} for student: {$studentName}");

        try {
            $subject = "Admission Confirmation – StudyFlow Classes";
            
            Mail::send('emails.admission-confirmed', [
                'studentName' => $studentName,
                'className' => $className,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Admission confirmation email sent successfully to {$email} for student {$studentName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send admission confirmation email to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Send admission rejection email.
     *
     * @param string $studentName
     * @param string $email
     * @param string $className
     * @return void
     */
    protected function sendAdmissionRejectionEmail($studentName, $email, $className)
    {
        $email = trim($email);
        $studentName = ucwords(strtolower(trim($studentName)));
        
        // Auto-fix common typos reported by user
        if (str_ends_with(strtolower($email), '@gmmail.com')) {
            $oldEmail = $email;
            $email = str_replace('@gmmail.com', '@gmail.com', strtolower($email));
            Log::info("Auto-corrected email typo (rejection): {$oldEmail} -> {$email}");
        }

        if (empty($email)) {
            Log::warning("Cannot send admission rejection email: Email address is empty for student {$studentName}.");
            return;
        }

        Log::info("Attempting to send premium admission rejection email to: {$email} for student: {$studentName}");

        try {
            $subject = "Admission Status Update – StudyFlow Classes";
            
            Mail::send('emails.admission-rejected', [
                'studentName' => $studentName,
                'className' => $className,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Admission rejection email sent successfully to {$email} for student {$studentName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send admission rejection email to {$email}: " . $e->getMessage());
        }
    }

    /**
     * Send student login credentials email.
     *
     * @param string $studentName
     * @param string $email
     * @param string $password
     * @param string $loginUrl
     * @return void
     */
    protected function sendStudentLoginCredentialsEmail($studentName, $email, $password, $loginUrl)
    {
        $email = trim($email);
        $studentName = ucwords(strtolower(trim($studentName)));
        
        if (empty($email)) {
            Log::warning("Cannot send login credentials email: Email address is empty for student {$studentName}.");
            return;
        }

        Log::info("Attempting to send student login credentials email to: {$email} for student: {$studentName}");

        try {
            $subject = "Your Student Account Credentials – StudyFlow Classes";
            
            Mail::send('emails.student-credentials', [
                'studentName' => $studentName,
                'email' => $email,
                'password' => $password,
                'loginUrl' => $loginUrl,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Student login credentials email sent successfully to {$email} for student {$studentName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send student login credentials email to {$email}: " . $e->getMessage());
        }
    }

}
