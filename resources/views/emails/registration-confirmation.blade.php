<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%); }
        .content { padding: 40px; }
        .logo-wrapper { text-align: left; margin-bottom: 24px; }
        .logo-wrapper img { height: 60px; width: auto; }
        .header { text-align: center; margin-bottom: 32px; }
        .icon-circle { display: inline-block; padding: 12px; background-color: #f5f3ff; border-radius: 12px; margin-bottom: 16px; }
        .icon-circle svg { width: 32px; height: 32px; color: #7c3aed; }
        .header h1 { color: #0f172a; font-size: 28px; font-weight: 700; margin: 0; letter-spacing: -0.025em; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 0 0; }
        .body-text { font-size: 16px; color: #334155; line-height: 1.6; }
        .card { margin: 32px 0; background-color: #f8fafc; border-radius: 12px; padding: 24px; border: 1px solid #e2e8f0; }
        .stats-table { width: 100%; border-collapse: collapse; }
        .stats-table td { padding: 10px 0; font-size: 14px; }
        .stats-label { color: #64748b; font-weight: 600; width: 40%; }
        .stats-value { color: #0f172a; font-weight: 700; text-align: right; }
        .badge { background-color: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; text-transform: uppercase; }
        .btn-container { text-align: center; margin: 40px 0; }
        .btn { background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%); color: white !important; padding: 14px 32px; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 16px; display: inline-block; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
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
                    <div class="icon-circle">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1>Registration Successful</h1>
                    <p>Welcome to Bansal Classes CRM</p>
                </div>
                <p class="body-text">
                    Dear <strong>{{ $userName }}</strong>,
                </p>
                <p class="body-text">
                    We are pleased to inform you that your account has been successfully created as a <strong>{{ $roleName }}</strong> in our system. You can now access your dashboard and manage your records.
                </p>
                
                <div class="card">
                    <table class="stats-table">
                        <tr>
                            <td class="stats-label">REGISTERED EMAIL</td>
                            <td class="stats-value">{{ $email }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">ACCOUNT ROLE</td>
                            <td class="stats-value">{{ $roleName }}</td>
                        </tr>
                        <tr>
                            <td class="stats-label">STATUS</td>
                            <td class="stats-value"><span class="badge">Active</span></td>
                        </tr>
                    </table>
                </div>

                <div class="btn-container">
                    <a href="{{ $loginUrl }}" class="btn">Login to Dashboard</a>
                </div>

                <p class="body-text" style="text-align: center; font-size: 15px; color: #475569;">
                    If you did not create this account, please contact our support team immediately.
                </p>
                
                <div class="footer">
                    <p style="margin: 0;">This is an automated message from Bansal Classes CRM.</p>
                    <p style="margin: 4px 0;">&copy; {{ date('Y') }} Bansal Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
