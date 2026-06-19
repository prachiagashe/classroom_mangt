<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Registration Successful</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: linear-gradient(90deg, #1d4ed8 0%, #3b82f6 100%); }
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
        .btn-container { text-align: center; margin: 32px 0; }
        .btn { background-color: #1d4ed8; color: white !important; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; }
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
                    <h1>Registration Successful</h1>
                    <p>Welcome to StudyFlow Classes</p>
                </div>
                <p class="body-text">
                    Dear <strong>{{ $teacherName }}</strong>,
                </p>
                <p class="body-text">
                    Your teacher account has been registered successfully. We are excited to have you onboard with StudyFlow Classes.
                </p>
                
                <div class="card">
                    <table class="stats-table">
                        <tr>
                            <td class="stats-label">REGISTERED EMAIL</td>
                            <td class="stats-value">{{ $email }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">DEPARTMENT</td>
                            <td class="stats-value">{{ $department }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">ASSIGNED CLASSES</td>
                            <td class="stats-value">{{ $assignedClasses }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">ASSIGNED SUBJECTS</td>
                            <td class="stats-value">{{ $assignedSubjects }}</td>
                        </tr>
                    </table>
                </div>

                <div class="btn-container">
                    <a href="{{ url('/teacher/login') }}" class="btn">Login to Dashboard</a>
                </div>

                <p class="body-text" style="font-size: 14px; color: #64748b; text-align: center;">
                    If you did not request this registration or have any questions, please contact the administration immediately.
                </p>
                
                <div class="footer">
                    <p style="margin: 0;">This is an automated message, please do not reply to this email.</p>
                    <p style="margin: 4px 0;">&copy; {{ date('Y') }} StudyFlow Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
