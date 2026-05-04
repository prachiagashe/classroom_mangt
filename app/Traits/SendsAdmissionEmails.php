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
            $subject = "Admission Confirmation – Bansal Classes";
            
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
                        <div style='height: 8px; background: linear-gradient(90deg, #e31e24 0%, #ff5e62 100%);'></div>
                        
                        <div class='content' style='padding: 40px;'>
                            <!-- Logo Section -->
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto; display: block;'>
                            </div>

                            <!-- Header Section -->
                            <div style='text-align: center; margin-bottom: 32px;'>
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Admission Confirmed</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Bansal Classes – Excellence through Education</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$studentName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                We are absolutely thrilled to welcome you to <strong>Bansal Classes</strong>. Your admission request has been successfully processed and confirmed.
                            </p>

                            <!-- Information Card -->
                            <div style='margin: 32px 0; background-color: #f1f5f9; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;'>
                                <table class='stats-table' style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>STUDENT NAME</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$studentName}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>CLASS</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$className}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>STATUS</td>
                                        <td style='padding: 8px 0; text-align: right;'>
                                            <span style='background-color: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;'>Confirmed</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style='text-align: center; font-size: 15px; color: #475569; line-height: 1.6;'>
                                We are honored to be part of your academic journey and look forward to helping you reach your full potential.
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
            $subject = "Admission Status Update – Bansal Classes";
            
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
                        <!-- Red Accent Bar -->
                        <div style='height: 8px; background: #e31e24;'></div>
                        
                        <div class='content' style='padding: 40px;'>
                            <!-- Logo Section -->
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto; display: block;'>
                            </div>

                            <!-- Header Section -->
                            <div style='text-align: center; margin-bottom: 32px;'>
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Status Update</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Bansal Classes – Admission Department</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$studentName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Thank you for your interest in Bansal Classes. After reviewing your admission enquiry for <strong>{$className}</strong>, we regret to inform you that we are unable to approve your admission request at this time.
                            </p>

                            <!-- Information Card -->
                            <div style='margin: 32px 0; background-color: #fef2f2; border-radius: 12px; padding: 24px; border: 1px solid #fee2e2;'>
                                <table class='stats-table' style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #b91c1c; font-size: 14px; font-weight: 600;'>REASON</td>
                                        <td style='padding: 8px 0; color: #7f1d1d; font-size: 14px; font-weight: 700; text-align: right;'>Admission Not Approved</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #b91c1c; font-size: 14px; font-weight: 600;'>STATUS</td>
                                        <td style='padding: 8px 0; text-align: right;'>
                                            <span style='background-color: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;'>Rejected</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style='font-size: 15px; color: #475569; line-height: 1.6;'>
                                If you have any further questions or would like to discuss this decision, please feel free to reach out to our office.
                            </p>
                            
                            <!-- Footer -->
                            <div style='text-align: center; margin-top: 40px; color: #94a3b8; font-size: 12px; border-top: 1px solid #f1f5f9; padding-top: 32px;'>
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
            $subject = "Your Student Account Credentials – Bansal Classes";
            
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
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Welcome to Bansal Classes</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Your Student Portal Access is Ready</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$studentName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Your student account has been successfully created. You can now log in to the Bansal Classes Student Portal using the credentials provided below:
                            </p>

                            <!-- Credentials Card -->
                            <div style='margin: 32px 0; background-color: #f8fafc; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;'>
                                <table style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>EMAIL</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$email}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>PASSWORD</td>
                                        <td style='padding: 8px 0; color: #7c3aed; font-size: 14px; font-weight: 700; text-align: right;'><code>{$password}</code></td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Action Button -->
                            <div style='text-align: center; margin: 40px 0;'>
                                <a href='{$loginUrl}' style='background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%); color: white; padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 16px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2), 0 2px 4px -1px rgba(79, 70, 229, 0.1);'>
                                    Login to Portal
                                </a>
                            </div>

                            <p style='font-size: 14px; color: #ef4444; line-height: 1.6; text-align: center; background-color: #fef2f2; padding: 12px; border-radius: 8px;'>
                                <strong>Note:</strong> For security reasons, you will be required to change your password upon your first login.
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

            Log::info("Student login credentials email sent successfully to {$email} for student {$studentName}.");
        } catch (\Exception $e) {
            Log::error("Failed to send student login credentials email to {$email}: " . $e->getMessage());
        }
    }

}
