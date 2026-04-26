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
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Welcome to the Team</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Bansal Classes – Official Onboarding</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$employeeName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Congratulations! It is our great pleasure to officially welcome you to <strong>Bansal Classes</strong>. We are excited to have you join our mission of academic excellence.
                            </p>

                            <!-- Information Card -->
                            <div style='margin: 32px 0; background-color: #f1f5f9; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;'>
                                <table class='stats-table' style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>EMPLOYEE ID</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$employeeCode}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>DESIGNATION</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$designation}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>STATUS</td>
                                        <td style='padding: 8px 0; text-align: right;'>
                                            <span style='background-color: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;'>Onboarded</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style='font-size: 15px; color: #475569; line-height: 1.6;'>
                                Our HR team will be in touch with you shortly to provide further details regarding your orientation. Welcome aboard!
                            </p>
                            
                            <!-- Brand Quote -->
                            <div style='text-align: center; margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px;'>
                                <p style='font-style: italic; color: #94a3b8; font-size: 14px; margin: 0;'>\"Preparing Students for Success in Life\"</p>
                            </div>

                            <!-- Footer -->
                            <div style='text-align: center; margin-top: 32px; color: #94a3b8; font-size: 12px;'>
                                <p style='margin: 0;'>This is an automated onboarding notification from Bansal Classes CRM.</p>
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
            
            $formattedAmount = number_format($amount, 2);
            $formattedBonus = number_format($bonus, 2);
            $formattedDeductions = number_format($deductions, 2);
            $formattedDate = date('d M Y', strtotime($date));

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
                        .amount-text { font-size: 28px !important; }
                        .stats-table td { padding: 6px 0 !important; font-size: 13px !important; }
                    }
                </style>
            </head>
            <body style='margin: 0; padding: 0; background-color: #f8fafc;'>
                <div class='wrapper' style='max-width: 600px; margin: 40px auto; padding: 20px;'>
                    <div class='container' style='background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;'>
                        <!-- Success Accent Bar -->
                        <div style='height: 8px; background: #10b981;'></div>
                        
                        <div class='content' style='padding: 40px;'>
                            <!-- Logo Section -->
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto; display: block;'>
                            </div>

                            <!-- Header Section -->
                            <div style='text-align: center; margin-bottom: 32px;'>
                                <div style='background-color: #ecfdf5; width: 64px; height: 64px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 24px;'>
                                    <span style='color: #10b981; font-size: 32px;'>₹</span>
                                </div>
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Salary Disbursed</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Payment for {$period}</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$employeeName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                We are pleased to inform you that your salary for <strong>{$period}</strong> has been successfully processed and transferred.
                            </p>

                            <!-- Financial Card -->
                            <div style='margin: 32px 0; background-color: #0f172a; border-radius: 12px; padding: 24px; color: white;'>
                                <div style='font-size: 13px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;'>Net Amount Paid</div>
                                <div class='amount-text' style='font-size: 36px; font-weight: 700; margin-bottom: 24px;'>₹{$formattedAmount}</div>
                                
                                <div style='border-top: 1px solid #1e293b; padding-top: 20px;'>
                                    <table class='stats-table' style='width: 100%; color: #94a3b8; font-size: 14px;'>
                                        <tr>
                                            <td style='padding: 4px 0;'>BONUS</td>
                                            <td style='padding: 4px 0; text-align: right; color: #10b981; font-weight: 600;'>+ ₹{$formattedBonus}</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 4px 0;'>DEDUCTIONS</td>
                                            <td style='padding: 4px 0; text-align: right; color: #ef4444; font-weight: 600;'>- ₹{$formattedDeductions}</td>
                                        </tr>
                                        <tr>
                                            <td style='padding: 16px 0 0 0;'>PAYMENT DATE</td>
                                            <td style='padding: 16px 0 0 0; text-align: right; color: white;'>{$formattedDate}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <p style='text-align: center; font-size: 15px; color: #475569; line-height: 1.6;'>
                                Thank you for your continued dedication and valuable contributions to the team.
                            </p>
                            
                            <!-- Footer -->
                            <div style='text-align: center; margin-top: 40px; color: #94a3b8; font-size: 12px; border-top: 1px solid #f1f5f9; padding-top: 32px;'>
                                <p style='margin: 0;'>This is an automated financial notification from Bansal Classes CRM.</p>
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
            $accentColor = $status === 'approved' ? '#10b981' : '#ef4444';
            
            $logoPath = public_path('images/icon.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }

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
                        <div style='height: 8px; background: {$accentColor};'></div>
                        
                        <div class='content' style='padding: 40px;'>
                            <!-- Logo Section -->
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto; display: block;'>
                            </div>

                            <div style='text-align: center; margin-bottom: 32px;'>
                                <h1 class='header-h1' style='color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em;'>Leave Request Update</h1>
                                <p style='color: #64748b; font-size: 16px; margin: 8px 0 0 0;'>Bansal Classes – Official Notification</p>
                            </div>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Dear <strong style='color: #0f172a;'>{$employeeName}</strong>,
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Your leave request starting from <strong>{$formattedDate}</strong> has been <strong style='color: {$accentColor};'>{$status}</strong> by the administration.
                            </p>

                            <div style='margin: 32px 0; background-color: #f1f5f9; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0;'>
                                <table class='stats-table' style='width: 100%; border-collapse: collapse;'>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>LEAVE START DATE</td>
                                        <td style='padding: 8px 0; color: #0f172a; font-size: 14px; font-weight: 700; text-align: right;'>{$formattedDate}</td>
                                    </tr>
                                    <tr>
                                        <td style='padding: 8px 0; color: #64748b; font-size: 14px; font-weight: 600;'>NEW STATUS</td>
                                        <td style='padding: 8px 0; text-align: right;'>
                                            <span style='background-color: " . ($status === 'approved' ? '#dcfce7' : '#fee2e2') . "; color: " . ($status === 'approved' ? '#166534' : '#991b1b') . "; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;'>{$statusLabel}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <p style='text-align: center; font-size: 15px; color: #475569; line-height: 1.6;'>
                                If you have any questions, please contact the HR department.
                            </p>
                            
                            <div style='text-align: center; margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px;'>
                                <p style='font-style: italic; color: #94a3b8; font-size: 14px; margin: 0;'>\"Preparing Students for Success in Life\"</p>
                            </div>

                            <div style='text-align: center; margin-top: 32px; color: #94a3b8; font-size: 12px;'>
                                <p style='margin: 0;'>This is an automated notification from Bansal Classes CRM.</p>
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
            
            $logoPath = public_path('images/icon.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }

            $html = "
            <!DOCTYPE html>
            <html>
            <head>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <style>
                    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
                    body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
                </style>
            </head>
            <body style='margin: 0; padding: 0; background-color: #f8fafc;'>
                <div style='max-width: 600px; margin: 40px auto; padding: 20px;'>
                    <div style='background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0;'>
                        <div style='height: 8px; background: linear-gradient(90deg, #6366f1 0%, #a855f7 100%);'></div>
                        <div style='padding: 40px;'>
                            <div style='text-align: left; margin-bottom: 24px;'>
                                <img src='{$logoBase64}' alt='Bansal Classes' style='height: 60px; width: auto;'>
                            </div>

                            <h1 style='color: #0f172a; font-size: 24px; font-weight: 700; margin: 0;'>Welcome, Professor!</h1>
                            <p style='color: #64748b; font-size: 16px; margin: 8px 0 24px 0;'>Teacher Portal Onboarding</p>

                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                Hello <strong>{$employeeName}</strong>, your teacher account has been successfully created. You can now access our academic portal to manage your classes and subjects.
                            </p>
                            
                            <p style='font-size: 16px; color: #334155; line-height: 1.6;'>
                                To get started, please set up your secure password by clicking the button below:
                            </p>

                            <div style='text-align: center; margin: 32px 0;'>
                                <a href='{$setupUrl}' style='background-color: #6366f1; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block;'>Set Up My Password</a>
                            </div>

                            <p style='font-size: 14px; color: #64748b; line-height: 1.6; text-align: center;'>
                                If the button doesn't work, copy and paste this link into your browser:<br>
                                <a href='{$setupUrl}' style='color: #6366f1;'>{$setupUrl}</a>
                            </p>

                            <div style='margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px; text-align: center; color: #94a3b8; font-size: 12px;'>
                                <p>&copy; " . date('Y') . " Bansal Classes. All rights reserved.</p>
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

            Log::info("Teacher password setup email sent to {$email}.");
        } catch (\Exception $e) {
            Log::error("Failed to send teacher setup email to {$email}: " . $e->getMessage());
        }
    }
}
