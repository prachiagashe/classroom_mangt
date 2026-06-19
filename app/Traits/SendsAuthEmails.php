<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

trait SendsAuthEmails
{
    /**
     * Send registration confirmation email.
     *
     * @param string $userName
     * @param string $email
     * @param string $role
     * @return void
     */
    protected function sendRegistrationConfirmationEmail($userName, $email, $role)
    {
        $email = trim($email);
        $userName = ucwords(strtolower(trim($userName)));
        $roleName = ucfirst($role);
        
        // Auto-fix common typos
        if (str_ends_with(strtolower($email), '@gmmail.com')) {
            $oldEmail = $email;
            $email = str_replace('@gmmail.com', '@gmail.com', strtolower($email));
            Log::info("Auto-corrected registration email typo: {$oldEmail} -> {$email}");
        }

        if (empty($email)) {
            Log::warning("Cannot send registration confirmation email: Email address is empty for user {$userName}.");
            return;
        }

        Log::info("Attempting to send registration confirmation email to: {$email} for {$role}: {$userName}");

        try {
            $subject = "Account Created Successfully – StudyFlow Classes";
            $loginUrl = route('login');
            
            Mail::send('emails.registration-confirmation', [
                'userName' => $userName,
                'roleName' => $roleName,
                'email' => $email,
                'loginUrl' => $loginUrl,
            ], function ($mail) use ($email, $subject) {
                $mail->to($email)->subject($subject);
            });

            Log::info("Registration confirmation email sent successfully to {$email} for {$role} {$userName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send registration confirmation email to {$email}: " . $e->getMessage());
        }
    }
}
