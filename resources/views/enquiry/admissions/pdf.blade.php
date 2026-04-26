<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Form - {{ $admission->student_name }}</title>
    <style>
        @media print {
            body { margin: 0; font-size: 10px; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.3;
            color: #000;
            margin: 10px;
        }
        
        .border { border: 1px solid #000; }
        .border-2 { border: 2px solid #000; }
        .border-dashed { border-style: dashed; }
        
        .p-2 { padding: 6px; }
        .p-3 { padding: 10px; }
        .p-4 { padding: 15px; }
        .p-6 { padding: 20px; }
        .px-2 { padding-left: 6px; padding-right: 6px; }
        .py-1 { padding-top: 3px; padding-bottom: 3px; }
        
        .mb-2 { margin-bottom: 6px; }
        .mb-3 { margin-bottom: 10px; }
        .mb-4 { margin-bottom: 15px; }
        .mb-6 { margin-bottom: 20px; }
        .mt-1 { margin-top: 3px; }
        .mt-2 { margin-top: 6px; }
        
        .text-xs { font-size: 9px; }
        .text-sm { font-size: 10px; }
        .text-base { font-size: 11px; }
        .text-lg { font-size: 13px; }
        .text-xl { font-size: 16px; }
        
        .font-bold { font-weight: bold; }
        .font-semibold { font-weight: 600; }
        .uppercase { text-transform: uppercase; }
        
        .text-center { text-align: center; }
        
        .table { width: 100%; border-collapse: collapse; }
        .table td, .table th { border: 1px solid #000; padding: 4px; text-align: center; font-size: 9px; }
        .table .text-left { text-align: left; }
        
        .page-break { page-break-after: always; }
        
        .w-32 { width: 128px; }
        .w-40 { width: 160px; }
        .h-8 { height: 32px; }
        .h-20 { height: 80px; }
        .h-32 { height: 128px; }
        
        .border-b { border-bottom: 1px solid #000; }
        .border-t { border-top: 1px solid #000; }
        
        .bg-gray { background-color: #f5f5f5; }
    </style>
</head>
<body>
    <!-- PAGE 1: ADMISSION FORM -->
    <div class="border-2 p-4" style="max-width: 800px; margin: 0 auto;">
        <!-- HEADER -->
        <table width="100%" style="margin-bottom: 15px;">
            <tr>
                <td colspan="2" class="text-center">
                    <h1 class="text-xl font-bold uppercase">BANSAL CLASSES PRIVATE LIMITED</h1>
                    <p class="text-sm">BCPL, 2nd Floor, R.B. Ingle Plaza, Nanded City, Pune</p>
                    <p class="text-xs">Phone: 020-24491111 | Email: bansalclasses@gmail.com</p>
                </td>
            </tr>
        </table>
        
        <!-- TOP INFO ROW -->
        <table width="100%" style="margin-bottom: 15px;">
            <tr>
                <td width="60%" valign="top">
                    <table width="100%">
                        <tr>
                            <td width="30%" class="text-xs font-semibold">Branch Code:</td>
                            <td><div class="border px-2 py-1 text-xs w-32">{{ $admission->remarks['form_details']['branch_code'] ?? 'BCPL-NDCY' }}</div></td>
                        </tr>
                        <tr>
                            <td class="text-xs font-semibold">Admission No:</td>
                            <td><div class="border px-2 py-1 text-xs w-32">{{ $admission->roll_number }}</div></td>
                        </tr>
                        <tr>
                            <td class="text-xs font-semibold">Invoice Code:</td>
                            <td><div class="border px-2 py-1 text-xs w-32">INV{{ $admission->id }}{{ date('Y') }}</div></td>
                        </tr>
                        <tr>
                            <td class="text-xs font-semibold">Date:</td>
                            <td><div class="border px-2 py-1 text-xs w-32">{{ $admission->admission_date }}</div></td>
                        </tr>
                    </table>
                </td>
                <td width="40%" class="text-center" valign="top">
                    <div class="border-2 px-4 py-2 mb-2">
                        <h2 class="text-base font-bold">ADMISSION FORM</h2>
                    </div>
                    <div class="border border-dashed w-32 h-32" style="margin: 0 auto;">
                        @if($admission->remarks['form_details']['student_photo'] ?? null)
                            <img src="{{ asset('storage/' . $admission->remarks['form_details']['student_photo']) }}" class="w-full h-full object-cover">
                        @else
                            <div style="padding: 40px 10px; text-align: center;">
                                <span class="text-xs text-gray-500">Student Photo</span>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
        </table>
        
        <!-- FULL NAME -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">FULL NAME OF THE APPLICANT</h3>
            <table width="100%">
                <tr>
                    <td width="33%">
                        <label class="text-xs">First Name</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['first_name'] ?? '' }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Middle Name</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['middle_name'] ?? '' }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Surname</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['surname'] ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- PERSONAL DETAILS -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">PERSONAL DETAILS</h3>
            <table width="100%">
                <tr>
                    <td width="33%">
                        <label class="text-xs">Nationality</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['nationality'] ?? 'Indian' }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Date of Birth</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->date_of_birth }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Gender</label>
                        <div class="border px-2 py-1 text-xs">
                            {{ ($admission->remarks['form_details']['gender'] ?? '') == 'male' ? '☑ Male' : '☐ Male' }} 
                            {{ ($admission->remarks['form_details']['gender'] ?? '') == 'female' ? '☑ Female' : '☐ Female' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- PARENT DETAILS -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">PARENT DETAILS</h3>
            <table width="100%">
                <tr>
                    <td width="50%">
                        <label class="text-xs">Father Name</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['father_name'] ?? '' }}</div>
                    </td>
                    <td width="50%">
                        <label class="text-xs">Father Occupation</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['father_occupation'] ?? '' }}</div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <label class="text-xs">Mother Name</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['mother_name'] ?? '' }}</div>
                    </td>
                    <td>
                        <label class="text-xs">Mother Occupation</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['mother_occupation'] ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CONTACT DETAILS -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">CONTACT DETAILS</h3>
            <table width="100%">
                <tr>
                    <td width="33%">
                        <label class="text-xs">Student Mobile</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['student_mobile'] ?? '' }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Father Mobile *</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->contact }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Mother Mobile</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['mother_mobile'] ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- SCHOOL DETAILS -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">SCHOOL DETAILS</h3>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <label class="text-xs">Present School / College Name & Address</label>
                        <div class="border px-2 py-1 text-xs" style="min-height: 40px;">{{ $admission->previous_school ?? '' }}</div>
                    </td>
                </tr>
                <tr>
                    <td width="25%">
                        <label class="text-xs">Previous Marks</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['previous_marks'] ?? '' }}</div>
                    </td>
                    <td width="25%">
                        <label class="text-xs">Last Year %</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['last_year_percentage'] ?? '' }}</div>
                    </td>
                    <td width="25%">
                        <label class="text-xs">Grade</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['grade'] ?? '' }}</div>
                    </td>
                    <td width="25%">
                        <label class="text-xs">CGPA</label>
                        <div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['cgpa'] ?? '' }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- ADDRESS -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">ADDRESS</h3>
            <table width="100%">
                <tr>
                    <td width="60%">
                        <label class="text-xs">Complete Address</label>
                        <div class="border px-2 py-1 text-xs" style="min-height: 50px;">{{ $admission->address }}</div>
                    </td>
                    <td width="40%" valign="top">
                        <table width="100%">
                            <tr>
                                <td><label class="text-xs">District</label></td>
                                <td><div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['district'] ?? '' }}</div></td>
                            </tr>
                            <tr>
                                <td><label class="text-xs">State</label></td>
                                <td><div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['state'] ?? '' }}</div></td>
                            </tr>
                            <tr>
                                <td><label class="text-xs">Pin Code</label></td>
                                <td><div class="border px-2 py-1 text-xs">{{ $admission->remarks['form_details']['pincode'] ?? '' }}</div></td>
                            </tr>
                            <tr>
                                <td><label class="text-xs">Email</label></td>
                                <td><div class="border px-2 py-1 text-xs">{{ $admission->email }}</div></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- COURSE OPTED - SCHOOL FOUNDATION -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">COURSE OPTED – SCHOOL FOUNDATION</h3>
            <table width="100%">
                <tr>
                    <td colspan="2">
                        <label class="text-xs font-semibold">Class</label>
                        <div class="border px-2 py-1 text-xs">
                            {{ in_array('5th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 5th' : '☐ 5th' }}
                            {{ in_array('6th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 6th' : '☐ 6th' }}
                            {{ in_array('7th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 7th' : '☐ 7th' }}
                            {{ in_array('8th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 8th' : '☐ 8th' }}
                            {{ in_array('9th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 9th' : '☐ 9th' }}
                            {{ in_array('10th', $admission->remarks['course_selections']['class'] ?? []) ? '☑ 10th' : '☐ 10th' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="text-xs font-semibold">Medium</label>
                        <div class="border px-2 py-1 text-xs">
                            {{ in_array('Semi', $admission->remarks['course_selections']['medium'] ?? []) ? '☑ Semi' : '☐ Semi' }}
                            {{ in_array('E/M', $admission->remarks['course_selections']['medium'] ?? []) ? '☑ E/M' : '☐ E/M' }}
                            {{ in_array('CBSE', $admission->remarks['course_selections']['medium'] ?? []) ? '☑ CBSE' : '☐ CBSE' }}
                            {{ in_array('ICSE', $admission->remarks['course_selections']['medium'] ?? []) ? '☑ ICSE' : '☐ ICSE' }}
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <label class="text-xs font-semibold">Foundation Options</label>
                        <div class="border px-2 py-1 text-xs">
                            {{ in_array('Scholarship', $admission->remarks['course_selections']['foundation'] ?? []) ? '☑ Scholarship' : '☐ Scholarship' }}
                            {{ in_array('Olympiad', $admission->remarks['course_selections']['foundation'] ?? []) ? '☑ Olympiad' : '☐ Olympiad' }}
                            {{ in_array('Homibhabha', $admission->remarks['course_selections']['foundation'] ?? []) ? '☑ Homibhabha' : '☐ Homibhabha' }}
                            {{ in_array('MTSE', $admission->remarks['course_selections']['foundation'] ?? []) ? '☑ MTSE' : '☐ MTSE' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- COURSE OPTED - MEDICAL / IIT -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">COURSE OPTED – MEDICAL / IIT</h3>
            <table width="100%">
                <tr>
                    <td width="50%" valign="top">
                        <h4 class="text-xs font-semibold mb-1">NEET</h4>
                        <div class="border px-2 py-1 text-xs">
                            <div>{{ in_array('11th_12th_NEET', $admission->remarks['course_selections']['course'] ?? []) ? '☑ 11th & 12th NEET' : '☐ 11th & 12th NEET' }}</div>
                            <div>{{ in_array('12th_NEET', $admission->remarks['course_selections']['course'] ?? []) ? '☑ 12th NEET' : '☐ 12th NEET' }}</div>
                            <div>{{ in_array('Repeater_NEET', $admission->remarks['course_selections']['course'] ?? []) ? '☑ Repeater NEET' : '☐ Repeater NEET' }}</div>
                            <div>{{ in_array('Crash_NEET', $admission->remarks['course_selections']['course'] ?? []) ? '☑ Crash NEET' : '☐ Crash NEET' }}</div>
                        </div>
                    </td>
                    <td width="50%" valign="top">
                        <h4 class="text-xs font-semibold mb-1">JEE</h4>
                        <div class="border px-2 py-1 text-xs">
                            <div>{{ in_array('11th_12th_JEE', $admission->remarks['course_selections']['course'] ?? []) ? '☑ 11th & 12th JEE' : '☐ 11th & 12th JEE' }}</div>
                            <div>{{ in_array('12th_JEE', $admission->remarks['course_selections']['course'] ?? []) ? '☑ 12th JEE' : '☐ 12th JEE' }}</div>
                            <div>{{ in_array('Repeater_JEE', $admission->remarks['course_selections']['course'] ?? []) ? '☑ Repeater JEE' : '☐ Repeater JEE' }}</div>
                            <div>{{ in_array('Crash_JEE', $admission->remarks['course_selections']['course'] ?? []) ? '☑ Crash JEE' : '☐ Crash JEE' }}</div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="border px-2 py-1 text-xs">
                            {{ in_array('MHT_CET_Test_Series', $admission->remarks['course_selections']['course'] ?? []) ? '☑ MHT-CET / Test Series' : '☐ MHT-CET / Test Series' }}
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- CATEGORY -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">CATEGORY</h3>
            <div class="border px-2 py-1 text-xs">
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'Open' ? '☑ Open' : '☐ Open' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'OBC' ? '☑ OBC' : '☐ OBC' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'EWS' ? '☑ EWS' : '☐ EWS' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'NT' ? '☑ NT' : '☐ NT' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'SC' ? '☑ SC' : '☐ SC' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'ST' ? '☑ ST' : '☐ ST' }}
                {{ ($admission->remarks['form_details']['category'] ?? '') == 'SEBC' ? '☑ SEBC' : '☐ SEBC' }}
            </div>
        </div>
        
        <!-- FEES -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">FEES</h3>
            <table width="100%">
                <tr>
                    <td width="33%">
                        <label class="text-xs">Total Fees *</label>
                        <div class="border px-2 py-1 text-xs">₹{{ number_format($admission->remarks['fees_breakdown']['total_fees'] ?? 0, 2) }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Discount Fees</label>
                        <div class="border px-2 py-1 text-xs">₹{{ number_format($admission->remarks['fees_breakdown']['discount_fees'] ?? 0, 2) }}</div>
                    </td>
                    <td width="33%">
                        <label class="text-xs">Final Fees *</label>
                        <div class="border px-2 py-1 text-xs bg-gray">₹{{ number_format($admission->total_fee, 2) }}</div>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- DECLARATION -->
        <div class="border p-3 mb-3">
            <h3 class="text-sm font-bold mb-2">DECLARATION</h3>
            <p class="text-xs leading-relaxed mb-2">
                I hereby declare that information furnished by me in this form is true and correct to the best of my knowledge and belief. I understand that if any information is found to be false or incorrect at any stage, my admission may be cancelled without any refund of fees. I have read and understood all the rules and regulations of the institute and agree to abide by them.
            </p>
            <div class="border px-2 py-1 text-xs">
                <div>{{ ($admission->remarks['declarations']['parent_declaration'] ?? '') == 'accepted' ? '☑' : '☐' }} I, as parent/guardian, declare that above information is true.</div>
                <div>{{ ($admission->remarks['declarations']['student_declaration'] ?? '') == 'accepted' ? '☑' : '☐' }} I, as student, have read and agree to terms and conditions.</div>
            </div>
        </div>
        
        <!-- SIGNATURES -->
        <div class="border p-3">
            <table width="100%">
                <tr>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8 mb-1"></div>
                        <p class="text-xs">Parent / Guardian Signature</p>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8 mb-1"></div>
                        <p class="text-xs">Branch Manager</p>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8 mb-1"></div>
                        <p class="text-xs">Admission Incharge</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- PAGE BREAK -->
    <div class="page-break"></div>
    
    <!-- PAGE 2: REFUND POLICY -->
    <div class="border-2 p-4" style="max-width: 800px; margin: 0 auto;">
        <h2 class="text-lg font-bold text-center mb-4">REFUND POLICY</h2>
        
        <p class="text-xs mb-3">
            The system at BANSAL CLASSES PVT LTD works in a transparent manner and the fee once paid is not refundable under any circumstances. However, the management may consider the request for refund of fee under the following circumstances:
        </p>
        
        <table class="table mb-4">
            <tr>
                <td class="text-left font-semibold">Fees Component</td>
                <td class="text-center font-semibold">Before Batch Commencement</td>
                <td class="text-center font-semibold">10 Days</td>
                <td class="text-center font-semibold">20 Days</td>
                <td class="text-center font-semibold">30 Days</td>
                <td class="text-center font-semibold">40 Days</td>
                <td class="text-center font-semibold">After 40 Days</td>
            </tr>
            <tr>
                <td class="text-left">Admission Registration Fees</td>
                <td>Non Refundable @15000 for senior student</td>
                <td>Non Refundable @15000 for senior student</td>
                <td>Non Refundable @15000 for senior student</td>
                <td>Non Refundable @15000 for senior student</td>
                <td>Non Refundable @15000 for senior student</td>
                <td>Non Refundable @15000 for senior student</td>
            </tr>
            <tr>
                <td></td>
                <td>5000 for junior student</td>
                <td>5000 for junior student</td>
                <td>5000 for junior student</td>
                <td>5000 for junior student</td>
                <td>5000 for junior student</td>
                <td>5000 for junior student</td>
            </tr>
            <tr>
                <td class="text-left">Tuition Fees</td>
                <td>90% Refund</td>
                <td>75% Refund</td>
                <td>50% Refund</td>
                <td>25% Refund</td>
                <td>10% Refund</td>
                <td>No Refund</td>
            </tr>
        </table>
        
        <div class="mb-3">
            <h3 class="text-sm font-bold mb-2">TERMS AND CONDITIONS:</h3>
            <ol class="text-xs" style="padding-left: 20px; line-height: 1.4;">
                <li>1. Refund will be processed within 15 working days from the date of request.</li>
                <li>2. Original receipt must be submitted for refund processing.</li>
                <li>3. Refund will be made through the same mode of payment as received.</li>
                <li>4. Administrative charges of ₹500 will be deducted from refund amount.</li>
                <li>5. Student must submit written application for refund with valid reason.</li>
                <li>6. Management decision regarding refund will be final and binding.</li>
                <li>7. Refund policy is subject to change without prior notice.</li>
                <li>8. In case of batch cancellation by institute, full refund will be provided.</li>
            </ol>
        </div>
        
        <div class="mb-3">
            <h3 class="text-sm font-bold mb-2">DOCUMENTS REQUIRED FOR REFUND:</h3>
            <ul class="text-xs" style="padding-left: 20px; line-height: 1.4;">
                <li>• Original fee receipt</li>
                <li>• Written application for refund</li>
                <li>• Cancelled cheque (for bank transfer)</li>
                <li>• ID proof of parent/guardian</li>
                <li>• Address proof</li>
            </ul>
        </div>
        
        <div class="border-t pt-3">
            <table width="100%">
                <tr>
                    <td width="50%" class="text-center">
                        <div class="border-b h-8 w-32" style="margin: 0 auto;"></div>
                        <p class="text-xs">Parent Signature</p>
                    </td>
                    <td width="50%" class="text-center">
                        <div class="border-b h-8 w-32" style="margin: 0 auto;"></div>
                        <p class="text-xs">Authorized Signatory</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    
    <!-- PAGE BREAK -->
    <div class="page-break"></div>
    
    <!-- PAGE 3: DECLARATION PAGES -->
    <div class="border-2 p-4" style="max-width: 800px; margin: 0 auto;">
        <h2 class="text-lg font-bold text-center mb-4">DECLARATION</h2>
        
        <!-- PARENTS DECLARATION -->
        <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">DECLARATION BY PARENTS/GUARDIAN</h3>
            <div class="border p-3">
                <p class="text-xs leading-relaxed mb-3">
                    I, the undersigned, parent/guardian of the student, hereby declare that:
                </p>
                <ol class="text-xs" style="padding-left: 20px; line-height: 1.4;">
                    <li>1. I have read and understood all the rules and regulations of BANSAL CLASSES PVT LTD.</li>
                    <li>2. I agree to abide by all the terms and conditions mentioned in the admission form.</li>
                    <li>3. I understand that the fees once paid are non-refundable as per the refund policy.</li>
                    <li>4. I ensure that my ward will maintain regular attendance and complete all assignments.</li>
                    <li>5. I will inform the institute in writing if there is any change in contact information.</li>
                    <li>6. I authorize the institute to use my ward's photographs for promotional purposes.</li>
                    <li>7. I understand that the institute reserves the right to cancel admission for misconduct.</li>
                    <li>8. I will be responsible for any damage caused by my ward to institute property.</li>
                </ol>
                
                <div class="mt-4">
                    <p class="text-xs mb-2">I hereby confirm that all information provided in the admission form is true and correct.</p>
                    
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <label class="text-xs">Parent/Guardian Name:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ $admission->parent_name }}</div>
                            </td>
                            <td width="50%">
                                <label class="text-xs">Signature:</label>
                                <div class="border-b px-2 py-1 h-8"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="text-xs">Contact Number:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ $admission->contact }}</div>
                            </td>
                            <td>
                                <label class="text-xs">Date:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ date('d/m/Y') }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- STUDENTS DECLARATION -->
        <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">DECLARATION BY STUDENT</h3>
            <div class="border p-3">
                <p class="text-xs leading-relaxed mb-3">
                    I, the undersigned, student of BANSAL CLASSES PVT LTD, hereby declare that:
                </p>
                <ol class="text-xs" style="padding-left: 20px; line-height: 1.4;">
                    <li>1. I will follow all rules and regulations of the institute.</li>
                    <li>2. I will maintain regular attendance and punctuality.</li>
                    <li>3. I will complete all assignments and homework on time.</li>
                    <li>4. I will respect teachers and fellow students.</li>
                    <li>5. I will not engage in any misconduct or indiscipline.</li>
                    <li>6. I will take care of institute property and premises.</li>
                    <li>7. I will appear for all tests and examinations conducted by the institute.</li>
                    <li>8. I understand that violation of rules may lead to cancellation of my admission.</li>
                </ol>
                
                <div class="mt-4">
                    <p class="text-xs mb-2">I hereby confirm that I have read and agree to all terms and conditions.</p>
                    
                    <table width="100%">
                        <tr>
                            <td width="50%">
                                <label class="text-xs">Student Name:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ $admission->student_name }}</div>
                            </td>
                            <td width="50%">
                                <label class="text-xs">Signature:</label>
                                <div class="border-b px-2 py-1 h-8"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="text-xs">Roll Number:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ $admission->roll_number }}</div>
                            </td>
                            <td>
                                <label class="text-xs">Date:</label>
                                <div class="border-b px-2 py-1 text-xs">{{ date('d/m/Y') }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- UNDERTAKING -->
        <div class="mb-6">
            <h3 class="text-sm font-bold mb-3">UNDERTAKING</h3>
            <div class="border p-3">
                <p class="text-xs leading-relaxed">
                    We hereby undertake to pay all fees as per the fee structure of BANSAL CLASSES PVT LTD. We understand that non-payment of fees on time may lead to cancellation of admission. We agree to pay late fees as applicable for delayed payments.
                </p>
                
                <table width="100%" style="margin-top: 15px;">
                    <tr>
                        <td width="50%" class="text-center">
                            <label class="text-xs">Parent/Guardian Signature</label>
                            <div class="border-b px-2 py-1 h-8"></div>
                        </td>
                        <td width="50%" class="text-center">
                            <label class="text-xs">Student Signature</label>
                            <div class="border-b px-2 py-1 h-8"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- FINAL SIGNATURE -->
        <div class="border-t pt-4">
            <div class="text-center mb-3">
                <h3 class="text-sm font-bold">CERTIFIED BY</h3>
            </div>
            <table width="100%">
                <tr>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8"></div>
                        <p class="text-xs">Admission Incharge</p>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8"></div>
                        <p class="text-xs">Branch Manager</p>
                    </td>
                    <td width="33%" class="text-center">
                        <div class="border-b h-8"></div>
                        <p class="text-xs">Director</p>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
