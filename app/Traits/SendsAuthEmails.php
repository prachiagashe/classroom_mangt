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
            $subject = "Account Created Successfully – Bansal Classes";
            $loginUrl = route('login');

            $logoPath = public_path('images/icon.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
            
            // Premium HTML content
            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
                    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
                    @media only screen and (max-width: 620px) {
                        .wrapper { width: 100% !important; padding: 10px !important; margin: 10px auto !important; }
                        .container { border-radius: 12px !important; }
                        .content { padding: 24px !important; }
                        .header-h1 { font-size: 24px !important; }
                        .stats-table td { padding: 6px 0 !important; font-size: 13px !important; }
                    }
                </style>
            </head>
            <body style='margin: 0; padding: 0; background-color: #f8fafc;'>
                <div class='wrapper' style='max-width: 600px; margin: 40px auto; padding: 20px;'>
                    <div class='container' style='background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;'>
                        <!-- Top Accent Bar -->
                        <div style='height: 8px; background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);'></div>
                        
                        <div class='content' style='padding: 40px;'>
                            <!-- Logo Section -->
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto; display: block;'>
                            </div>

                            <!-- Header Section -->
                            <div style='text-align: center; margin-bottom: 32px;'>
                                <div style='display: inline-block; padding: 12px; background-color: #f5f3ff; border-radius: 12px; margin-bottom: 16px;'>
                                    <svg style='width: 32px; height: 32px; color: #7c3aed;' fill='none' stroke='currentColor' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'>
                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                                    </svg>
                                </div>
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Registration Successful</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Welcome to Bansal Classes CRM</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$userName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                We are pleased to inform you that your account has been successfully created as a <strong>{$roleName}</strong> in our system. You can now access your dashboard and manage your records.
                            </p>

                            <!-- Information Card -->
                            <div style='margin: 32px 0; background-color: #f8fafc; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;'>
                                <table class='stats-table' style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600;'>REGISTERED EMAIL</td>
                                        <td style='padding: 10px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$email}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600;'>ACCOUNT ROLE</td>
                                        <td style='padding: 10px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$roleName}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 10px 0; color: #64748b; font-size: 14px; font-weight: 600;'>STATUS</td>
                                        <td style='padding: 10px 0; text-align: right;'>
                                            <span style='background-color: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;'>Active</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Action Button -->
                            <div style='text-align: center; margin: 40px 0;'>
                                <a href='{$loginUrl}' style='background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 16px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);'>
                                    Login to Dashboard
                                </a>
                            </div>

                            <p style='font-size: 15px; color: #475569; line-height: 1.6; text-align: center;'>
                                If you did not create this account, please contact our support team immediately.
                            </p>
                            
                            <!-- Brand Quote -->
                            <div style='text-align: center; margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px;'>
                                <p style='font-style: italic; color: #94a3b8; font-size: 14px; margin: 0;'>\"Preparing Students for Success in Life\"</p>
                            </div>

                            <!-- Footer -->
                            <div style='text-align: center; margin-top: 32px; color: #94a3b8; font-size: 12px;'>
                                <p style='margin: 0;'>This is an automated message from Bansal Classes CRM.</p>
                                <p style='margin: 4px 0;'>&copy; " . date('Y') . " Bansal Classes. All rights reserved.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
            </html>";

            Mail::html($html, function ($mail) use ($email, $subject) {
                $mail->to($email)
                     ->subject($subject);
            });

            Log::info("Registration confirmation email sent successfully to {$email} for {$role} {$userName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send registration confirmation email to {$email}: " . $e->getMessage());
        }
    }
}
