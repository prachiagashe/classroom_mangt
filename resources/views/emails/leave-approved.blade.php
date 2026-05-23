<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Approved</title>
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
        .header h1 { color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 0 0; }
        .body-text { font-size: 16px; color: #334155; line-height: 1.6; }
        .card { margin: 32px 0; background-color: #f1f5f9; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table td { padding: 8px 0; font-size: 14px; }
        .stats-label { color: #64748b; font-weight: 600; width: 40%; }
        .stats-value { color: #0f172a; font-weight: 700; text-align: right; }
        .badge { background-color: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .footer { text-align: center; margin-top: 32px; color: #94a3b8; font-size: 12px; border-top: 1px solid #f1f5f9; padding-top: 32px; }
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 10px; margin: 10px auto; }
            .content { padding: 24px; }
            .header h1 { font-size: 24px; }
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
                    <h1>Leave Request Approved</h1>
                    <p>Bansal Classes – Official Notification</p>
                </div>
                <p class="body-text">
                    Dear <strong>{{ $employeeName }}</strong>,
                </p>
                <p class="body-text">
                    Your leave request has been approved successfully by the administration.
                </p>
                
                <div class="card">
                    <table class="stats-table">
                        <tr>
                            <td class="stats-label">LEAVE DATES</td>
                            <td class="stats-value">{{ $startDate }} to {{ $endDate ?? $startDate }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">LEAVE TYPE</td>
                            <td class="stats-value">{{ $leaveType ?? 'General Leave' }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">NEW STATUS</td>
                            <td class="stats-value"><span class="badge">Approved</span></td>
                        </tr>
                    </table>
                </div>

                <p class="body-text" style="text-align: center; font-size: 15px; color: #475569;">
                    Have a great day ahead!
                </p>
                
                <div class="footer">
                    <p style="margin: 0;">This is an automated notification from Bansal Classes CRM.</p>
                    <p style="margin: 4px 0;">&copy; {{ date('Y') }} Bansal Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
