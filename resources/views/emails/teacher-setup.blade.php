<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Account Setup</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: linear-gradient(90deg, #6366f1 0%, #a855f7 100%); }
        .content { padding: 40px; }
        .logo-wrapper { text-align: left; margin-bottom: 24px; }
        .logo-wrapper img { height: 60px; width: auto; }
        .header h1 { color: #0f172a; font-size: 24px; font-weight: 700; margin: 0; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 24px 0; }
        .body-text { font-size: 16px; color: #334155; line-height: 1.6; }
        .btn-container { text-align: center; margin: 32px 0; }
        .btn { background-color: #6366f1; color: white !important; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-block; }
        .fallback { font-size: 14px; color: #64748b; line-height: 1.6; text-align: center; }
        .fallback a { color: #6366f1; }
        .footer { margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px; text-align: center; color: #94a3b8; font-size: 12px; }
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 10px; margin: 10px auto; }
            .content { padding: 24px; }
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
                    <h1>Welcome, Professor!</h1>
                    <p>Teacher Portal Onboarding</p>
                </div>
                
                <p class="body-text">
                    Hello <strong>{{ $employeeName }}</strong>, your teacher account has been successfully created. You can now access our academic portal to manage your classes and subjects.
                </p>
                
                <p class="body-text">
                    To get started, please set up your secure password by clicking the button below:
                </p>

                <div class="btn-container">
                    <a href="{{ $setupUrl }}" class="btn">Set Up My Password</a>
                </div>

                <p class="fallback">
                    If the button doesn't work, copy and paste this link into your browser:<br>
                    <a href="{{ $setupUrl }}">{{ $setupUrl }}</a>
                </p>

                <div class="footer">
                    <p>&copy; {{ date('Y') }} StudyFlow Classes. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
