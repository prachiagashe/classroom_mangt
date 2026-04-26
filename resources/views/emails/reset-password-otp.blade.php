<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="color: #333333; text-align: center;">Password Reset Request</h2>
        <p style="color: #666666; font-size: 16px; line-height: 1.5;">
            We received a request to reset your password. Use the OTP below to complete the process.
        </p>
        <div style="background-color: #f8f9fa; border: 1px dashed #cccccc; padding: 20px; text-align: center; margin: 30px 0; border-radius: 4px;">
            <h1 style="margin: 0; color: #0056b3; font-size: 32px; letter-spacing: 5px;">{{ $otp }}</h1>
        </div>
        <p style="color: #666666; font-size: 14px; text-align: center;">
            This OTP is valid for 10 minutes. If you did not request a password reset, please ignore this email.
        </p>
    </div>
</body>
</html>
