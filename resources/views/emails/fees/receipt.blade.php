<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 40px 0; color: #374151; line-height: 1.6;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); overflow: hidden;">
        
        <div style="background-color: #1e3a8a; color: white; padding: 30px; text-align: center;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 600; letter-spacing: 0.5px;">Payment Receipt</h1>
        </div>

        <div style="padding: 30px;">
            <p>Dear <strong>{{ ucwords(strtolower($enquiry->first_name . ' ' . $enquiry->surname)) }}</strong>,</p>
            <p>Thank you for your payment. We have successfully received your fee installment. Here are your transaction details:</p>

            <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;">
                <div style="font-size: 13px; color: #059669; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Amount Paid</div>
                <div style="font-size: 32px; color: #065f46; font-weight: 700;">₹{{ number_format($payment->amount, 2) }}</div>
            </div>

            <div style="background-color: #f8fafc; border-radius: 8px; padding: 20px; margin-bottom: 25px; border: 1px solid #e2e8f0;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Student Name</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ ucwords(strtolower($enquiry->first_name . ' ' . $enquiry->surname)) }}</div>
                        </td>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Class</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ $enquiry->class }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Installment No.</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ $installmentNumber ?? 'N/A' }}</div>
                        </td>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Payment Date</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Payment Mode</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ ucfirst($payment->payment_mode) }}</div>
                        </td>
                        <td style="padding-bottom: 15px; width: 50%;">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Transaction ID</div>
                            <div style="font-size: 16px; font-weight: 500; color: #0f172a;">{{ $payment->transaction_id ?? 'N/A' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div style="font-size: 13px; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Remaining Balance</div>
                            <div style="font-size: 16px; font-weight: 500; color: {{ $remainingBalance > 0 ? '#b91c1c' : '#15803d' }}">
                                ₹{{ number_format($remainingBalance, 2) }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <p style="text-align: center; margin-top: 30px; font-size: 15px;">
                If you have any questions about this receipt, please contact the administration office.
            </p>
        </div>

        <div style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px; text-align: center; font-size: 14px; color: #64748b;">
            <p>This is an automatically generated receipt. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name', 'Institute') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
