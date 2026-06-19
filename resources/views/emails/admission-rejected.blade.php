<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Status Update</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: #e31e24; }
        .content { padding: 40px; }
        .logo-wrapper { text-align: left; margin-bottom: 24px; }
        .logo-wrapper img { height: 60px; width: auto; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 0 0; }
        .body-text { font-size: 16px; color: #334155; line-height: 1.6; }
        .card { margin: 32px 0; background-color: #fef2f2; border-radius: 12px; padding: 24px; border: 1px solid #fee2e2; }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table td { padding: 8px 0; font-size: 14px; }
        .stats-label { color: #b91c1c; font-weight: 600; width: 40%; }
        .stats-value { color: #7f1d1d; font-weight: 700; text-align: right; }
        .badge { background-color: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
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
                    <img src="{{ asset('images/icon.png') }}" alt="StudyFlow Classes Logo">
                </div>
                <div class="header">
                    <h1>Status Update</h1>
                    <p>StudyFlow Classes – Admission Department</p>
                </div>
                <p class="body-text">
                    Dear <strong>{{ $studentName }}</strong>,
                </p>
                <p class="body-text">
                    Thank you for your interest in StudyFlow Classes. After reviewing your admission enquiry for <strong>{{ $className }}</strong>, we regret to inform you that we are unable to approve your admission request at this time.
                </p>
                
                <div class="card">
                    <table class="stats-table">
                        <tr>
                            <td class="stats-label">REASON</td>
                            <td class="stats-value">Admission Not Approved</td>
                        </tr>
                        <tr>
                            <td class="stats-label">STATUS</td>
                            <td class="stats-value"><span class="badge">Rejected</span></td>
                        </tr>
                    </table>
                </div>

                <p class="body-text" style="font-size: 15px; color: #475569;">
                    If you have any further questions or would like to discuss this decision, please feel free to reach out to our office.
                </p>
                
                <div class="footer">
                    <p style="margin: 0;">This is an automated message from StudyFlow Classes CRM.</p>
                    <p style="margin: 4px 0;">&copy; {{ date('Y') }} StudyFlow Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
