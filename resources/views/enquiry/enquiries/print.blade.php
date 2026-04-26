<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bansal Classes - Enquiry Form</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        html, body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 13px;
            color: #2d2d2d;
            background: #f0f0f0;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .sheet {
            width: 210mm;
            margin: 0 auto;
            background: white;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 7.5mm 10.5mm 6mm 10.5mm;
            position: relative;
            background: #fff;
            page-break-after: always;
            break-after: page;
        }

        .page:last-child {
            page-break-after: auto;
            break-after: auto;
        }

        /* BRAND HEADER */
        .brand-box {
            border: 0.5px solid #c2c2c2;
            padding: 1.1mm 1.7mm 1.2mm 1.7mm;
        }

        .brand-main-row {
            display: flex;
            align-items: center;
        }

        .logo-square {
            width: 17mm;
            height: 17mm;
            border: 0.5px solid #737373;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 2.4mm;
            background: #dcdcdc;
            overflow: hidden;
            flex-shrink: 0;
        }

        .logo-inner {
            width: 11.2mm;
            height: 11.2mm;
            border: 1.5px solid #646464;
            border-radius: 2.4mm;
            text-transform: lowercase;
            font-size: 22px;
            line-height: 11mm;
            text-align: center;
            font-weight: 900;
            color: #333;
            font-family: Arial, sans-serif;
        }

        .brand-text {
            flex: 1;
            text-align: center;
        }

        .brand-line1 {
            font-size: 9px;
            font-weight: 700;
            margin-bottom: 0.5mm;
        }

        .brand-line2 {
            font-size: 28px;
            line-height: 1;
            letter-spacing: 0.5px;
            font-weight: 900;
            margin-bottom: 0.5mm;
        }

        .brand-line3 {
            font-size: 11px;
            letter-spacing: 5px;
            font-weight: 700;
            margin-bottom: 0.5mm;
        }

        .brand-line4 {
            display: flex;
            justify-content: space-between;
            padding: 0 10mm;
            font-size: 9px;
            font-style: italic;
            color: #525252;
        }

        .address-strip {
            margin-top: 2mm;
            background: #d7d7d7;
            text-align: center;
            font-size: 9px;
            font-weight: 700;
            padding: 1.2mm 1.2mm;
            line-height: 1.2;
        }

        /* META ROW */
        .meta-row {
            margin-top: 3mm;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .meta-cell {
            display: flex;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
        }

        .meta-label {
            margin-right: 1.5mm;
            white-space: nowrap;
        }

        .char-set {
            display: inline-flex;
            align-items: center;
        }

        .char-box {
            width: 5.5mm;
            height: 5.5mm;
            border: 0.5px solid #777;
            margin-right: -0.5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
        }

        .char-box.small { width: 4.8mm; }
        .char-box.phone { width: 5.5mm; height: 5.5mm; }
        .char-box.dob { width: 5mm; height: 5.5mm; }

        .char-gap { display: inline-block; width: 2mm; }
        .char-slash {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2mm;
            font-size: 11px;
            font-weight: 700;
        }

        .enquiry-pill {
            min-width: 35mm;
            height: 8mm;
            border-radius: 2.2mm;
            background: #6d7782;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3mm;
        }

        .branch-row {
            margin-top: 2mm;
            font-size: 11px;
            font-weight: 700;
        }

        .branch-value {
            font-weight: 700;
            margin-left: 1.5mm;
        }

        /* FORM LINES */
        .form-lines {
            margin-top: 2mm;
            padding-bottom: 4mm;
        }

        .line-row {
            display: flex;
            align-items: center;
            margin-top: 2mm;
            line-height: 3;
            font-size: 11px;
            flex-wrap: nowrap;
        }

        .line-row.tight { margin-top: 1.5mm; }

        .bullet {
            width: 2.5mm;
            height: 2.5mm;
            border: 0.5px solid #8a8a8a;
            margin-right: 2mm;
            flex: 0 0 auto;
        }

        .row-strong { font-weight: 700; }

        .line-field {
            display: inline-block;
            border-bottom: 0.5px solid #767676;
            height: 4mm;
            vertical-align: bottom;
            margin: 0 1.5mm 0 1mm;
            line-height: 3.8mm;
            overflow: hidden;
            white-space: nowrap;
            min-width: 5mm;
        }

        .line-field input {
            border: none;
            outline: none;
            width: 100%;
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            padding: 0;
            background: transparent;
            height: 100%;
        }

        .w-17 { width: 17mm; }
        .w-24 { width: 24mm; }
        .w-28 { width: 28mm; }
        .w-33 { width: 33mm; }
        .w-36 { width: 36mm; }
        .w-45 { width: 45mm; }
        .w-58 { width: 58mm; }
        .w-84 { width: 84mm; }
        .w-103 { width: 103mm; }

        .name-section {
            margin-top: 1.5mm;
            margin-left: 4mm;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .name-col { width: 56mm; }
        .name-col .line-field {
            margin: 0;
            width: 55mm;
            height: 4.5mm;
        }
        .name-caption {
            text-align: center;
            font-size: 9px;
            margin-top: 0.5mm;
            color: #595959;
        }

        .option-group {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            margin-left: 3mm;
        }

        .option-group.tight { margin-left: 2mm; }

        .box-check {
            width: 4.5mm;
            height: 4.5mm;
            border: 0.5px solid #7d7d7d;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-left: 1mm;
            vertical-align: middle;
            cursor: pointer;
            flex-shrink: 0;
            position: relative;
        }

        .box-check.checked::after {
            content: "✓";
            position: absolute;
            font-size: 8px;
            font-weight: 700;
            color: #2f2f2f;
        }

        .phone-set {
            display: inline-flex;
            margin-left: 1.5mm;
        }

        .indented { margin-left: 4mm; }

        .remarks-line {
            margin-left: 1mm;
            width: 64mm;
        }

        /* SIGNATURES */
        .signatures {
            margin-top: 10mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-cell {
            width: 56mm;
            text-align: center;
            border-top: 0.5px solid #7a7a7a;
            padding-top: 1.5mm;
            font-size: 10px;
            font-weight: 700;
        }

        /* PAGE 2 - COUNSELLING */
        .counselling-title {
            width: 51mm;
            margin: 0 auto 2.5mm auto;
            border: 0.5px solid #7b7b7b;
            border-radius: 3mm;
            text-align: center;
            font-size: 22px;
            font-weight: 500;
            line-height: 10mm;
            height: 10mm;
        }

        .counselling-box {
            border: 0.5px solid #7f7f7f;
            display: flex;
            min-height: 199mm;
        }

        .counselling-left {
            width: 52%;
            border-right: 0.5px solid #7f7f7f;
            padding: 3mm 4mm 3mm 4mm;
        }

        .left-ribbon {
            background: #595f68;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            line-height: 9mm;
            height: 9mm;
            text-align: center;
            transform: skewX(-10deg);
            margin-bottom: 2mm;
            letter-spacing: 0.1px;
            white-space: nowrap;
            padding: 0 2mm;
        }

        .left-ribbon span {
            display: block;
            transform: skewX(10deg);
        }

        .discussion-list { padding: 0 1mm; }

        .discussion-row {
            display: flex;
            align-items: center;
            margin-bottom: 1.8mm;
            font-size: 11px;
        }

        .big-check {
            width: 5mm;
            height: 5mm;
            border: 0.5px solid #7f7f7f;
            margin-right: 2.5mm;
            flex: 0 0 auto;
            cursor: pointer;
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .big-check.checked::after {
            content: "✓";
            font-size: 9px;
            font-weight: 700;
            color: #2f2f2f;
        }

        .counselling-right {
            flex: 1;
            padding: 3mm 3mm 3mm 3mm;
        }

        .feedback-title {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 2mm;
            line-height: 1.3;
        }

        .feedback-content {
            width: 100%;
            min-height: 160mm;
        }

        .feedback-content textarea {
            width: 100%;
            min-height: 160mm;
            border: none;
            outline: none;
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            resize: none;
            background: transparent;
        }

        /* IMPORTANT NOTICES */
        .important-title {
            margin-top: 3mm;
            font-size: 26px;
            line-height: 1.1;
            font-weight: 700;
        }

        .notice-list { margin-top: 1.5mm; }

        .notice-row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.8mm;
            font-size: 11px;
            line-height: 1.3;
        }

        .notice-check {
            width: 6mm;
            height: 5.5mm;
            border: 0.5px solid #7f7f7f;
            margin-right: 3mm;
            position: relative;
            flex: 0 0 auto;
            margin-top: 0.3mm;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .notice-check.checked::after {
            content: "✓";
            font-size: 9px;
            font-weight: 700;
            color: #2f2f2f;
        }

        /* PRINT BUTTON */
        .print-btn {
            position: fixed;
            top: 15px;
            right: 15px;
            background: #595f68;
            color: white;
            border: none;
            padding: 8px 20px;
            font-size: 13px;
            border-radius: 4px;
            cursor: pointer;
            font-family: Arial, sans-serif;
            z-index: 999;
        }
        .print-btn:hover { background: #404750; }

        @media print {
            html, body { background: white; }
            .print-btn { display: none; }
            .sheet { margin: 0; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Print</button>

<div class="sheet">

    <!-- PAGE 1: ENQUIRY FORM -->
    <section class="page page-one">

        <!-- Brand Header -->
        <div class="brand-box">
            <div class="brand-main-row">
                <div class="logo-square">
    <img 
        src="{{ asset('images/bansal-logo.png') }}" 
        alt="Bansal Logo"
        style="width: 100%; height: 100%; object-fit: contain;"
    >
</div>
                <div class="brand-text">
                    <div class="brand-line1">Rajasthan Kota's Pioneer Brand of India</div>
                    <div class="brand-line2">BANSAL CLASSES</div>
                    <div class="brand-line3">PRIVATE LIMITED</div>
                    <div class="brand-line4">
                        <span>Since : 1981</span>
                        <span>Ideal for Scholars</span>
                    </div>
                </div>
            </div>
            <div class="address-strip">BCPL, 2<sup>nd</sup> Floor, R. B. Ingle Plaza, Nanded-city, Pune. Mob. 8087758574, 9209936534</div>
        </div>

        <!-- Meta Row: Enq No / ENQUIRY FORM pill / Date -->
        <div class="meta-row">
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Enq No :</span>
                <span class="char-set" style="margin-left: 1.5mm;">
                    @php
                        $enqNo = $form['enquiry_no'];
                        for($i = 0; $i < strlen($enqNo); $i++) {
                            echo '<span class="char-box">' . $enqNo[$i] . '</span>';
                            if($i < strlen($enqNo) - 1) echo '<span class="char-gap"></span>';
                        }
                    @endphp
                </span>
            </div>

            <div class="enquiry-pill">ENQUIRY FORM</div>

            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Enquiry Date :</span>
                <span class="char-set" style="margin-left: 1.5mm;">
                    @php
                        $enqDate = $form['enquiry_date'];
                        for($i = 0; $i < strlen($enqDate); $i++) {
                            echo '<span class="char-box">' . $enqDate[$i] . '</span>';
                            if($i < 1 || $i == 3) echo '<span class="char-gap"></span>';
                        }
                    @endphp
                </span>
            </div>
        </div>

        <div class="branch-row">
            <span class="meta-label">Branch code :</span>
            <span class="branch-value">BCPL-NDCY</span>
        </div>

        <!-- Form Lines -->
        <div class="form-lines">

            <!-- Full Name -->
            <div class="line-row">
                <span class="bullet"></span>
                <span class="row-strong">Full Name of the applicant</span>
            </div>

            <div class="name-section">
                <div class="name-col">
                    <span class="line-field"><input type="text" value="{{ $form['first_name'] }}" readonly></span>
                    <div class="name-caption">First Name</div>
                </div>
                <div class="name-col">
                    <span class="line-field"><input type="text" value="{{ $form['middle_name'] }}" readonly></span>
                    <div class="name-caption">Middle Name</div>
                </div>
                <div class="name-col">
                    <span class="line-field"><input type="text" value="{{ $form['surname'] }}" readonly></span>
                    <div class="name-caption">Surname</div>
                </div>
            </div>

            <!-- Class / School Time / Last Year % -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Class:</span>
                <span class="line-field w-24"><input type="text" value="{{ $form['class'] }}" readonly></span>
                <span>College/School Time :</span>
                <span class="line-field w-45"><input type="text" value="{{ $form['college_time'] }}" readonly></span>
                <span>Last Year % :</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['last_year_percentage'] }}" readonly></span>
            </div>

            <!-- College/School Name -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>College/School Name :</span>
                <span class="line-field w-103"><input type="text" value="{{ $form['college_name'] }}" readonly></span>
            </div>

            <!-- Medium -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Medium :</span>
                <span class="option-group">Semi Medium <span class="box-check {{ $form['checks']['semi_medium'] ? 'checked' : '' }}"></span></span>
                <span class="option-group">English Medium <span class="box-check {{ $form['checks']['english_medium'] ? 'checked' : '' }}"></span></span>
                <span class="option-group tight">CBSE <span class="box-check {{ $form['checks']['cbse'] ? 'checked' : '' }}"></span></span>
                <span class="option-group tight">ICSE <span class="box-check {{ $form['checks']['icse'] ? 'checked' : '' }}"></span></span>
            </div>

            <!-- Date of Birth -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Date Of Birth</span>
                <span style="margin-left: 1.5mm;">:</span>
                <span class="char-set" style="margin-left: 1.5mm;">
                    @for($i = 0; $i < 8; $i++)
                        <span class="char-box dob">{{ $form['dob_digits'][$i] ?? '' }}</span>
                        @if($i < 1 || $i == 3)
                            <span class="char-gap"></span>
                        @endif
                    @endfor
                </span>
            </div>

            <!-- Mobile No (Parent's) -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Mobile No (Parent's)</span>
                <span style="margin-left: 1mm;">:</span>
                <span class="phone-set">
                    @php
                        $parentMobile = $form['parent_mobile'];
                        for ($i = 0; $i < 10; $i++) {
                            echo '<span class="char-box phone">' . (isset($parentMobile[$i]) ? $parentMobile[$i] : '') . '</span>';
                        }
                    @endphp
                </span>
            </div>

            <!-- Mobile No (Student's) -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Mobile No (Student's)</span>
                <span style="margin-left: 1mm;">:</span>
                <span class="phone-set">
                    @php
                        $studentMobile = $form['student_mobile'];
                        for ($i = 0; $i < 10; $i++) {
                            echo '<span class="char-box phone">' . (isset($studentMobile[$i]) ? $studentMobile[$i] : '') . '</span>';
                        }
                    @endphp
                </span>
            </div>

            <!-- Whatsapp No -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Whatsapp No</span>
                <span style="margin-left: 4mm;">:</span>
                <span class="phone-set">
                    @php
                        $whatsappMobile = $form['whatsapp_mobile'];
                        for ($i = 0; $i < 10; $i++) {
                            echo '<span class="char-box phone">' . (isset($whatsappMobile[$i]) ? $whatsappMobile[$i] : '') . '</span>';
                        }
                    @endphp
                </span>
            </div>

            <!-- Father's Occupation -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Father's Occupation :</span>
                <span class="line-field w-103"><input type="text" value="{{ $form['father_occupation'] }}" readonly></span>
            </div>

            <!-- Address -->
            <div class="line-row tight">
                <span class="bullet"></span>
                <span>Add :</span>
                <span class="line-field w-103"><input type="text" value="{{ $form['address_line_1'] }}" readonly></span>
            </div>

            <div class="line-row tight indented">
                <span class="line-field w-103"><input type="text" value="{{ $form['address_line_2'] }}" readonly></span>
            </div>

            <!-- Foundation -->
            <div class="line-row" style="margin-top: 3mm;">
                <span class="bullet"></span>
                <span><strong>Foundation :</strong></span>
                <span class="option-group">Scholarship <span class="box-check {{ $form['checks']['scholarship'] ? 'checked' : '' }}"></span></span>
                <span class="option-group">Dr. Homibhabha <span class="box-check {{ $form['checks']['dr_homibhabha'] ? 'checked' : '' }}"></span></span>
                <span class="option-group">Olympaid <span class="box-check {{ $form['checks']['olympaid'] ? 'checked' : '' }}"></span></span>
                <span class="option-group">MTSE <span class="box-check {{ $form['checks']['mtse'] ? 'checked' : '' }}"></span></span>
            </div>

            <!-- MIIT / NEET / JEE etc. -->
            <div class="line-row tight">
                <span class="bullet"></span>
                 <span><strong>Courses : </strong></span>
                <!-- <span> MIIT <span class="box-check" onclick="toggleCheck(this)" style="margin-left:1mm;"></span></span> -->
                <span style="margin-left: 2mm;">NEET <span class="box-check {{ $form['checks']['neet'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2mm;">JEE <span class="box-check {{ $form['checks']['jee'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2mm;">MHT-CET <span class="box-check {{ $form['checks']['mht_cet'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2mm;">REPT <span class="box-check {{ $form['checks']['rept'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2mm;">TEST SERIES <span class="box-check {{ $form['checks']['test_series'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2mm;">CRASH C. <span class="box-check {{ $form['checks']['crash_course'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
            </div>

            <!-- Sibling 1 -->
            <div class="line-row" style="margin-top: 2.5mm;">
                <span class="bullet"></span>
                <span><strong>Sibling</strong></span>
                <span style="margin-left: 1.5mm;">1) :</span>
                <span class="line-field w-58"><input type="text" value="{{ $form['siblings'][0]['name'] ?? '' }}" readonly></span>
                <span>Class</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['siblings'][0]['class'] ?? '' }}" readonly></span>
                <span>Medium</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['siblings'][0]['medium'] ?? '' }}" readonly></span>
            </div>

            <div class="line-row tight indented">
                <span>2) :</span>
                <span class="line-field w-58"><input type="text" value="{{ $form['siblings'][1]['name'] ?? '' }}" readonly></span>
                <span>Class</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['siblings'][1]['class'] ?? '' }}" readonly></span>
                <span>Medium</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['siblings'][1]['medium'] ?? '' }}" readonly></span>
            </div>

            <!-- Reference 1 -->
            <div class="line-row" style="margin-top: 2mm;">
                <span class="bullet"></span>
                <span><strong>Reference</strong></span>
                <span style="margin-left: 1.5mm;">1) :</span>
                <span class="line-field w-58"><input type="text" value="{{ $form['references'][0]['name'] ?? '' }}" readonly></span>
                <span>Class</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['references'][0]['class'] ?? '' }}" readonly></span>
                <span>Medium</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['references'][0]['medium'] ?? '' }}" readonly></span>
            </div>

            <div class="line-row tight indented">
                <span style="margin-left: 1.5mm;">2) :</span>
                <span class="line-field w-58"><input type="text" value="{{ $form['references'][1]['name'] ?? '' }}" readonly></span>
                <span>Class</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['references'][1]['class'] ?? '' }}" readonly></span>
                <span>Medium</span>
                <span class="line-field w-17"><input type="text" value="{{ $form['references'][1]['medium'] ?? '' }}" readonly></span>
            </div>

            <!-- How do you know about us -->
            <div class="line-row" style="margin-top: 2.5mm;">
                <span class="bullet"></span>
                <span><strong>How do you about us</strong></span>
            </div>

            <div class="line-row tight indented">
                <span>Boost/TSE Exam <span class="box-check {{ $form['checks']['boost_tse_exam'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Paper Advt <span class="box-check {{ $form['checks']['paper_advt'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">TV Advt <span class="box-check {{ $form['checks']['tv_advt'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Student's Ref <span class="box-check {{ $form['checks']['students_ref'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Employee Ref <span class="box-check {{ $form['checks']['employee_ref'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Other Ref <span class="box-check {{ $form['checks']['other_ref'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
            </div>

            <!-- Remarks -->
            <div class="line-row" style="margin-top: 2.5mm;">
                <span class="bullet"></span>
                <span><strong>Remarks</strong></span>
                <span style="margin-left: 2.5mm;">Hot <span class="box-check {{ $form['checks']['hot'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Warm <span class="box-check {{ $form['checks']['warm'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <span style="margin-left: 2.5mm;">Cold <span class="box-check {{ $form['checks']['cold'] ? 'checked' : '' }}" style="margin-left:1mm;"></span></span>
                <!-- <span class="line-field remarks-line"><input type="text" value="{{ $form['parent_feedback'] }}" readonly></span> -->
            </div>

        </div><!-- /form-lines -->

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-cell">Parent's Sign</div>
            <div class="signature-cell">Student's Sign</div>
            <div class="signature-cell">Counsellor Name &amp; Sign</div>
        </div>

    </section>

    <!-- PAGE 2: COUNSELLING -->
    <section class="page page-two">

        <div class="counselling-title">COUNSELLING</div>

        <div class="counselling-box">
            <!-- LEFT: Points to discuss -->
            <div class="counselling-left">
                <div class="left-ribbon"><span>Points to be discussed/done</span></div>
                <div class="discussion-list">
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Welcome To Parents And Students</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Introduce Yourself And Bansal's History</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Ask Std And Brief</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Needs Of Classes For Particular Std</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Why Bansal Is Best ?</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Syllabus/academic Planner :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Faculty Team :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Day Care Schedule :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Double Session :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Dppp,modules :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Study Materials, Bag, T-shirt :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Fortnightly Test (15 Days ) :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Subjective Test :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Major Test :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Result :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Class Room &amp; Infrastructure :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Parents Teacher Meeting :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Amenities :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Security :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Cctv :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Gate Pass :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Batch Coordinator For Girls :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Fees :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Total Fees :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Discount Fees :</span></div>
                    <div class="discussion-row"><span class="big-check" onclick="toggleCheck(this)"></span><span>Final Fees :</span></div>
                </div>
            </div>

            <!-- RIGHT: Parent's Feedback -->
            <div class="counselling-right">
                <div class="feedback-title">Parent's Feedback /<br>conversation</div>
                <div class="feedback-content">
                    {{ $form['parent_feedback'] ?? '' }}
                </div>
            </div>
        </div>

        <!-- Important Notices -->
        <div class="important-title">महत्वाच्या सूचना :-</div>
        <div class="notice-list">
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>सर्व अँडमिशनसाठी 30% (स्टडी मटेरियल फीस) व 70% (ट्युशन फीस) हि पद्धत लागू होईल.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>सर्व अँडमिशनसाठी स्कॉलरशिप/डिस्काउंट हि पद्धत 70% फीस (ट्युशन फीस) वरती लागू होईल.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>उर्वरित 30% फीस (स्टडी मटेरियल फीस) बरती कोणत्याही स्कॉलरशिप/डिस्काउंट लागू होणार नाही.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>ठरलेल्या फीसपेक्षा किमान कमी 15% फीस भरल्यानंतरच विद्यार्थ्यांना वर्गामध्ये बसण्यास परवानगी असेल.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>पाचवी ते दहावीतील विद्यार्थ्यांना अँडमिशनसाठी वेळीच 30% फीस (स्टडी मटेरियल फीस) भरावी लागेल.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>एखादा विद्यार्थ्यांना 70% पेक्षा जास्त स्कॉलरशिप देण्यासाठी रिजनल ऑफिसची परवानगी बंधनकारक असेल.</span>
            </div>
            <div class="notice-row">
                <span class="notice-check" onclick="toggleCheck(this)"></span>
                <span>(11वी व 12 वी नीट/जेईई 2- years) या दोन वर्षांकरिता प्रवेश घेताना एकूण दोन्ही वर्षांच्या फीच्या 15% रक्कम भरणे बंधनकारक असेल.</span>
            </div>
        </div>

    </section>

</div>

<script>
    function toggleCheck(el) {
        el.classList.toggle('checked');
    }
</script>
</body>
</html>