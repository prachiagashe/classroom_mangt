<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Disbursed</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: #10b981; }
        .content { padding: 40px; }
        .logo-wrapper { text-align: center; margin-bottom: 24px; }
        .logo-wrapper img { height: 60px; width: auto; }
        .header { text-align: center; margin-bottom: 32px; }
        .icon-circle { background-color: #ecfdf5; width: 64px; height: 64px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin: 0 auto 24px auto; }
        .icon-circle span { color: #10b981; font-size: 32px; }
        .header h1 { color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 0 0; }
        .body-text { font-size: 16px; color: #334155; line-height: 1.6; }
        .card { margin: 32px 0; background-color: #0f172a; border-radius: 12px; padding: 24px; color: white; }
        .card-label { font-size: 13px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px; }
        .amount-text { font-size: 36px; font-weight: 700; margin-bottom: 24px; }
        .stats-table { width: 100%; color: #94a3b8; font-size: 14px; border-collapse: collapse; }
        .stats-table td { padding: 4px 0; }
        .stats-table .divider td { padding-top: 16px; color: white; }
        .footer { text-align: center; margin-top: 40px; color: #94a3b8; font-size: 12px; border-top: 1px solid #f1f5f9; padding-top: 32px; }
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 10px; margin: 10px auto; }
            .content { padding: 24px; }
            .header h1 { font-size: 24px; }
            .amount-text { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="top-bar"></div>
            <div class="content">
                <div class="logo-wrapper">
                    <img src="{{ asset('images/icon.png') }}" alt="Bansal Classes Logo">
                </div>
                <div class="header">
                    <div class="icon-circle">
                        <span>₹</span>
                    </div>
                    <h1>Salary Disbursed</h1>
                    <p>Payment for {{ $period }}</p>
                </div>
                <p class="body-text">
                    Dear <strong>{{ $employeeName }}</strong>,
                </p>
                <p class="body-text">
                    We are pleased to inform you that your salary for <strong>{{ $period }}</strong> has been successfully processed and transferred.
                </p>
                
                <div class="card">
                    <div class="card-label">Net Amount Paid</div>
                    <div class="amount-text">₹{{ number_format($amount, 2) }}</div>
                    
                    <div style="border-top: 1px solid #1e293b; padding-top: 20px;">
                        <table class="stats-table">
                            <tr>
                                <td>BONUS</td>
                                <td style="text-align: right; color: #10b981; font-weight: 600;">+ ₹{{ number_format($bonus, 2) }}</td>
                            </tr>
                            <tr>
                                <td>DEDUCTIONS</td>
                                <td style="text-align: right; color: #ef4444; font-weight: 600;">- ₹{{ number_format($deductions, 2) }}</td>
                            </tr>
                            <tr class="divider">
                                <td>PAYMENT DATE</td>
                                <td style="text-align: right;">{{ date('d M Y', strtotime($date)) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <p class="body-text" style="text-align: center; font-size: 15px; color: #475569;">
                    Thank you for your continued dedication and valuable contributions to the team.
                </p>
                
                <div class="footer">
                    <p style="margin: 0;">This is an automated financial notification from Bansal Classes CRM.</p>
                    <p style="margin: 4px 0;">&copy; {{ date('Y') }} Bansal Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
