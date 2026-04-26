<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Updated</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4f;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin-bottom: 30px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        .info-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-left: 4px solid #007bff;
        }
        .info-label {
            font-weight: bold;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-value {
            color: #333;
            font-size: 16px;
            margin: 0;
        }
        .payment-info {
            background-color: #e8f5e8;
            border-left: 4px solid #28a745;
        }
        .installment-info {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .footer {
            text-align: center;
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-installment {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-pending {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        @php
            $logoPath = public_path('images/icon.png');
            $logoBase64 = '';
            if (file_exists($logoPath)) {
                $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
            }
        @endphp
        <!-- Logo Section -->
        <div style="text-align: left; margin-bottom: 24px;">
            <img src="{{ $logoBase64 }}" alt="Bansal Classes" style="height: 60px; width: auto; display: block;">
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
            <p>This is an automated notification from {{ config('app.name') }}.</p>
            <p>If you have any questions, please contact our administration.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
