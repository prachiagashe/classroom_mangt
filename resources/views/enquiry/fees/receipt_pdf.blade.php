<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Payment Receipt - {{ $enquiry->first_name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif; /* DejaVu Sans is bundled with DOMPDF and supports ₹ */
            color: #333;
            margin: 0;
            padding: 10px 20px; /* Reduced padding */
            font-size: 13px; /* Slightly smaller text to fit 1 page */
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        .header-table {
            width: 100%;
            margin-bottom: 15px;
            border-bottom: 2px dashed #ccc;
            padding-bottom: 10px;
        }
        
        .logo-container {
            width: 50%;
            vertical-align: middle;
        }
        
        .logo {
            max-width: 150px;
            max-height: 60px; /* Smaller logo */
        }
        
        .receipt-title {
            width: 50%;
            text-align: right;
            vertical-align: middle;
        }
        
        .receipt-title h2 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a8a;
        }
        
        .meta-info {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .meta-info td {
            width: 50%;
            padding: 2px 0;
        }
        
        .greeting {
            margin-bottom: 15px;
        }
        
        .amount-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .amount-box .label {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        
        .amount-box .value {
            font-size: 24px;
            font-weight: bold;
            color: #065f46;
        }
        
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .details-table th, .details-table td {
            padding: 8px;
            border-bottom: 1px solid #e2e8f0;
            text-align: left;
        }
        
        .details-table th {
            width: 40%;
            color: #64748b;
            font-weight: normal;
        }
        
        .details-table td {
            width: 60%;
            font-weight: bold;
        }
        
        .summary-box {
            width: 100%;
            border-top: 2px dashed #ccc;
            border-bottom: 2px dashed #ccc;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        
        .summary-table {
            width: 100%;
        }
        
        .summary-table td {
            padding: 3px 0;
        }
        
        .summary-table td:nth-child(1) {
            width: 70%;
            text-align: right;
            color: #64748b;
        }
        
        .summary-table td:nth-child(2) {
            width: 30%;
            text-align: right;
            font-weight: bold;
            padding-left: 20px;
        }
        
        .total-row td {
            font-size: 14px;
            padding-top: 5px;
            color: #b91c1c;
        }
        
        .footer {
            text-align: center;
            color: #64748b;
            font-size: 11px;
            margin-top: 20px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    
    <table class="header-table">
        <tr>
            <td class="logo-container">
                @php
                    // Using direct server path for DomPDF to accurately render local images
                    $logoPath = public_path('images/icon.png');
                    if(file_exists($logoPath)) {
                        $logoData = base64_encode(file_get_contents($logoPath));
                        $logoSrc = 'data:image/png;base64,' . $logoData;
                    } else {
                        $logoSrc = '';
                    }
                @endphp
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="Bansal Classes" class="logo">
                @else
                    <h1 style="margin:0; font-size:20px; color:#1e3a8a;">Bansal Classes</h1>
                @endif
            </td>
            <td class="receipt-title">
                <h2>Payment Receipt</h2>
            </td>
        </tr>
    </table>
    
    <table class="meta-info">
        <tr>
            <td><strong>Receipt No:</strong> {{ $receiptNo }}</td>
            <td class="text-right"><strong>Date:</strong> {{ date('d M Y') }}</td>
        </tr>
    </table>
    
    <div class="greeting">
        Dear <strong>{{ ucwords(strtolower($enquiry->first_name . ' ' . $enquiry->surname)) }}</strong>,<br><br>
        Thank you for your payment. We have successfully received your fee installment. Here are your transaction details:
    </div>
    
    <div class="amount-box">
        <div class="label">Amount Paid</div>
        <div class="value">₹{{ number_format($payment->amount, 2) }}</div>
    </div>
    
    <table class="details-table">
        <tr>
            <th>Student Name:</th>
            <td>{{ ucwords(strtolower($enquiry->first_name . ' ' . $enquiry->surname)) }}</td>
        </tr>
        <tr>
            <th>Class:</th>
            <td>{{ $enquiry->class }}</td>
        </tr>
        <tr>
            <th>Installment:</th>
            <td>{{ $installmentNumber }} / {{ $admission->installment_count ?? 1 }}</td>
        </tr>
        <tr>
            <th>Payment Date:</th>
            <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
        </tr>
        <tr>
            <th>Payment Mode:</th>
            <td>{{ ucfirst($payment->payment_mode) }}</td>
        </tr>
        <tr>
            <th>Transaction ID:</th>
            <td>{{ $payment->transaction_id ?? 'N/A' }}</td>
        </tr>
    </table>
    
    <div class="summary-box">
        <table class="summary-table">
            <tr>
                <td>Total Fee:</td>
                <td>₹{{ number_format($finalFees, 2) }}</td>
            </tr>
            <tr>
                <td>Paid Amount:</td>
                <td>₹{{ number_format($totalPaidSoFar, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Remaining Balance:</td>
                <td>₹{{ number_format($remainingBalance, 2) }}</td>
            </tr>
        </table>
    </div>
    
    <div class="footer">
        <p style="margin-bottom: 10px;">If you have any questions about this receipt, please contact the administration office.</p>
        
        <p><strong>Bansal Classes</strong><br>
        Pune<br>
        Phone: +91 95450 90432</p>
        
        <p style="margin-top:10px; opacity:0.7;">This is an automatically generated receipt.<br>
        &copy; {{ date('Y') }} {{ config('app.name', 'Institute') }}. All rights reserved.</p>
    </div>

</body>
</html>
