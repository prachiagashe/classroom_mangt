<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Updated</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; }
        .wrapper { width: 100%; max-width: 600px; margin: 40px auto; padding: 20px; box-sizing: border-box; }
        .container { background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; }
        .top-bar { height: 8px; background: linear-gradient(90deg, #3b82f6 0%, #2563eb 100%); }
        .content { padding: 40px; margin-bottom: 30px; }
        .logo-wrapper { text-align: left; margin-bottom: 24px; }
        .logo-wrapper img { height: 60px; width: auto; }
        .header { text-align: center; margin-bottom: 32px; }
        .header h1 { color: #0f172a; font-size: 24px; margin: 0; }
        .header p { color: #64748b; font-size: 16px; margin: 8px 0 0 0; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .info-item { background-color: #f8f9fa; padding: 15px; border-radius: 8px; border-left: 4px solid #007bff; }
        .info-label { font-weight: bold; color: #6c757d; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; }
        .info-value { color: #333; font-size: 16px; margin: 0; }
        .payment-info { background-color: #e8f5e8; border-left: 4px solid #28a745; }
        .installment-info { background-color: #fff3cd; border-left: 4px solid #ffc107; }
        .footer { text-align: center; border-top: 1px solid #e9ecef; padding-top: 20px; color: #6c757d; font-size: 14px; margin-top: 20px;}
        .badge { display: inline-block; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background-color: #d4edda; color: #155724; }
        .badge-installment { background-color: #fff3cd; color: #856404; }
        .badge-pending { background-color: #f8d7da; color: #721c24; }
        @media only screen and (max-width: 600px) {
            .wrapper { padding: 10px; margin: 10px auto; }
            .content { padding: 24px; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="top-bar"></div>
            <div class="content">
                <!-- Logo Section -->
                <div class="logo-wrapper">
                    <img src="{{ asset('images/icon.png') }}" alt="Bansal Classes Logo">
                </div>

                <div class="header">
                    <h1>Admission Details Updated</h1>
                    <p>Your admission information has been successfully updated in our system.</p>
                </div>

        <div class="content">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Student Name</div>
                    <div class="info-value">{{ $admission->student_name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Class</div>
                    <div class="info-value">{{ $admission->class }}</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Roll Number</div>
                    <div class="info-value">{{ $admission->roll_number ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $admission->email }}</div>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Contact</div>
                    <div class="info-value">{{ $admission->contact ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Fee Status</div>
                    <div class="info-value">
                        @if($admission->payment_mode == 'installment')
                            <span class="badge badge-installment">{{ $admission->fee_status }}</span>
                        @elseif($admission->payment_mode == 'cash' || $admission->payment_mode == 'online')
                            <span class="badge badge-paid">Paid</span>
                        @else
                            <span class="badge badge-pending">{{ $admission->fee_status }}</span>
                        @endif
                    </div>
                </div>
            </div>

            @if($admission->payment_mode == 'installment')
                <div class="info-item payment-info">
                    <div class="info-label">Payment Mode</div>
                    <div class="info-value">Installment Plan</div>
                </div>

                <div class="info-grid">
                    <div class="info-item installment-info">
                        <div class="info-label">Installment Amount</div>
                        <div class="info-value">₹{{ number_format($admission->installment_amount, 2) }}</div>
                    </div>
                    <div class="info-item installment-info">
                        <div class="info-label">Number of Installments</div>
                        <div class="info-value">{{ $admission->installment_count }}</div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-item installment-info">
                        <div class="info-label">Installment Duration</div>
                        <div class="info-value">{{ ucfirst($admission->installment_type) }}</div>
                    </div>
                    <div class="info-item installment-info">
                        <div class="info-label">Start Date</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($admission->installment_start_date)->format('d M Y') }}</div>
                    </div>
                </div>
            @else
                <div class="info-item payment-info">
                    <div class="info-label">Payment Mode</div>
                    <div class="info-value">{{ ucfirst($admission->payment_mode) }}</div>
                </div>
            @endif

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Total Fees</div>
                    <div class="info-value">₹{{ number_format($admission->total_fee, 2) }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Paid Amount</div>
                    <div class="info-value">₹{{ number_format($admission->paid_amount, 2) }}</div>
                </div>
            </div>

            @if($admission->remarks)
                <div class="info-item">
                    <div class="info-label">Remarks</div>
                    <div class="info-value">{{ $admission->remarks }}</div>
                </div>
            @endif
        </div>

            <div class="footer">
                <p>This is an automated notification from {{ config('app.name', 'Bansal Classes') }}.</p>
                <p>If you have any questions, please contact our administration.</p>
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Bansal Classes') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>
