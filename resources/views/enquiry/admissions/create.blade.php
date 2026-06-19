@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('enquiry.admissions.store') }}" enctype="multipart/form-data">
    @csrf
<div class="max-w-4xl mx-auto bg-white border border-gray-800 p-6">
    <!-- Header -->
    <div class="border-2 border-gray-800 p-6 mb-6">
        <!-- Centered Institute Name -->
        <div class="text-center mb-6">
            <h1 class="text-xl font-bold uppercase">StudyFlow Classes PRIVATE LIMITED</h1>
            <p class="text-sm">BCPL, 2nd Floor, R.B. Ingle Plaza, Nanded City, Pune</p>
        </div>
        
        
        <!-- Left Stacked Fields + Right Admission Form + Photo -->
        <div class="flex justify-between items-start">
            <!-- Left Column Fields -->
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold">Branch Code</label>
                    <input type="text" name="branch_code" value="BCPL-NDCY" readonly class="w-36 border border-gray-700 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold">Admission No</label>
                    <input type="text" name="admission_no" class="w-36 border border-gray-700 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs font-semibold">Date</label>
                    <input type="date" name="date" class="w-36 border border-gray-700 px-2 py-1 text-sm">
                </div>
            </div>

            <div class="border-2 border-gray-800 px-8 py-2">
                    <h2 class="text-base font-bold">ADMISSION FORM</h2>
                </div>
            
            <!-- Right Admission Form Box + Photo -->
            <div class="flex flex-col items-end gap-4">
                <div class="border border-gray-700 p-3 text-center">
                    <label class="text-xs font-semibold">Student Photo</label>
                    <div class="w-28 h-36 border-2 border-dashed border-gray-400 flex items-center justify-center">
                        <input type="file" name="student_photo" accept="image/*" class="opacity-0 absolute w-28 h-36 cursor-pointer">
                        <span class="text-xs text-gray-500">Choose Photo</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <!-- Full Name Section -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">FULL NAME OF THE APPLICANT</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs">First Name</label>
                    <input type="text" name="first_name" required class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Middle Name</label>
                    <input type="text" name="middle_name" required class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Surname</label>
                    <input type="text" name="surname" required class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">PERSONAL DETAILS</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs">Nationality</label>
                    <input type="text" name="nationality" value="Indian" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Date of Birth</label>
                    <input type="date" name="dob" required class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Gender</label>
                    <div class="flex gap-4 mt-1">
                        <label class="text-xs"><input type="radio" name="gender" value="male" required> Male</label>
                        <label class="text-xs"><input type="radio" name="gender" value="female" required> Female</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parent Details -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">PARENT DETAILS</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs">Father Name</label>
                    <input type="text" name="father_name" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Father Occupation</label>
                    <input type="text" name="father_occupation" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Mother Name</label>
                    <input type="text" name="mother_name" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Mother Occupation</label>
                    <input type="text" name="mother_occupation" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">CONTACT DETAILS</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs">Student Mobile</label>
                    <input type="text" name="student_mobile" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Father Mobile *</label>
                    <input type="text" name="father_mobile" required class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Mother Mobile</label>
                    <input type="text" name="mother_mobile" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- School Details -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">SCHOOL DETAILS</h3>
            <div>
                <label class="text-xs">Present School / College Name & Address</label>
                <textarea name="school_name" rows="3" class="w-full border border-gray-400 px-2 py-1 text-sm"></textarea>
            </div>
            <div class="grid grid-cols-4 gap-4 mt-3">
                <div>
                    <label class="text-xs">Previous Marks</label>
                    <input type="text" name="previous_marks" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Last Year %</label>
                    <input type="text" name="last_year_percentage" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Grade</label>
                    <input type="text" name="grade" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">CGPA</label>
                    <input type="text" name="cgpa" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Address Block -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">ADDRESS</h3>
            <div class="grid grid-cols-2 gap-4 mb-3">
                <div>
                    <label class="text-xs">Complete Address</label>
                    <textarea name="address" rows="3" required class="w-full border border-gray-400 px-2 py-1 text-sm"></textarea>
                </div>
                <div>
                    <label class="text-xs">District</label>
                    <input type="text" name="district" class="w-full border border-gray-400 px-2 py-1 text-sm mb-2">
                    <label class="text-xs">State</label>
                    <input type="text" name="state" class="w-full border border-gray-400 px-2 py-1 text-sm mb-2">
                    <label class="text-xs">Pin Code</label>
                    <input type="text" name="pincode" class="w-full border border-gray-400 px-2 py-1 text-sm mb-2">
                    <label class="text-xs">Email</label>
                    <input type="email" name="email" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
            </div>
        </div>

        <!-- Course Opted - School Foundation -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">COURSE OPTED – SCHOOL FOUNDATION</h3>
            
            <div class="mb-3">
                <label class="text-xs font-semibold">Class</label>
                <div class="flex gap-3 mt-1">
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="5th"> 5th</label>
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="6th"> 6th</label>
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="7th"> 7th</label>
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="8th"> 8th</label>
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="9th"> 9th</label>
                    <label class="text-xs"><input type="checkbox" name="class_selected[]" value="10th"> 10th</label>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-xs font-semibold">Medium</label>
                <div class="flex gap-3 mt-1">
                    <label class="text-xs"><input type="checkbox" name="medium[]" value="Semi"> Semi</label>
                    <label class="text-xs"><input type="checkbox" name="medium[]" value="E/M"> E/M</label>
                    <label class="text-xs"><input type="checkbox" name="medium[]" value="CBSE"> CBSE</label>
                    <label class="text-xs"><input type="checkbox" name="medium[]" value="ICSE"> ICSE</label>
                </div>
            </div>

            <div>
                <label class="text-xs font-semibold">Foundation Options</label>
                <div class="flex gap-3 mt-1">
                    <label class="text-xs"><input type="checkbox" name="foundation[]" value="Scholarship"> Scholarship</label>
                    <label class="text-xs"><input type="checkbox" name="foundation[]" value="Olympiad"> Olympiad</label>
                    <label class="text-xs"><input type="checkbox" name="foundation[]" value="Homibhabha"> Homibhabha</label>
                    <label class="text-xs"><input type="checkbox" name="foundation[]" value="MTSE"> MTSE</label>
                </div>
            </div>
        </div>

        <!-- Course Opted - Medical / IIT -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">COURSE OPTED – MEDICAL / IIT</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-semibold mb-2">NEET</h4>
                    <div class="space-y-1">
                        <label class="text-xs"><input type="checkbox" name="course[]" value="11th_12th_NEET"> 11th & 12th NEET</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="12th_NEET"> 12th NEET</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="Repeater_NEET"> Repeater NEET</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="Crash_NEET"> Crash NEET</label>
                    </div>
                </div>
                <div>
                    <h4 class="text-xs font-semibold mb-2">JEE</h4>
                    <div class="space-y-1">
                        <label class="text-xs"><input type="checkbox" name="course[]" value="11th_12th_JEE"> 11th & 12th JEE</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="12th_JEE"> 12th JEE</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="Repeater_JEE"> Repeater JEE</label>
                        <label class="text-xs"><input type="checkbox" name="course[]" value="Crash_JEE"> Crash JEE</label>
                    </div>
                </div>
            </div>
            <div>
                <label class="text-xs"><input type="checkbox" name="course[]" value="MHT_CET_Test_Series"> MHT-CET / Test Series</label>
            </div>
        </div>

        <!-- Category -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">CATEGORY</h3>
            <div class="flex gap-3">
                <label class="text-xs"><input type="radio" name="category" value="Open"> Open</label>
                <label class="text-xs"><input type="radio" name="category" value="OBC"> OBC</label>
                <label class="text-xs"><input type="radio" name="category" value="EWS"> EWS</label>
                <label class="text-xs"><input type="radio" name="category" value="NT"> NT</label>
                <label class="text-xs"><input type="radio" name="category" value="SC"> SC</label>
                <label class="text-xs"><input type="radio" name="category" value="ST"> ST</label>
                <label class="text-xs"><input type="radio" name="category" value="SEBC"> SEBC</label>
            </div>
        </div>

        <!-- Fees -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">FEES</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="text-xs">Total Fees *</label>
                    <input type="number" name="total_fees" required min="0" step="0.01" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Discount Fees</label>
                    <input type="number" name="discount_fees" min="0" step="0.01" class="w-full border border-gray-400 px-2 py-1 text-sm">
                </div>
                <div>
                    <label class="text-xs">Final Fees *</label>
                    <input type="number" name="final_fees" required min="0" step="0.01" readonly class="w-full border border-gray-400 px-2 py-1 text-sm bg-gray-100">
                </div>
            </div>
        </div>

        <!-- Declaration -->
        <div class="border border-gray-400 p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">DECLARATION</h3>
            <p class="text-xs text-gray-600 leading-relaxed mb-3">
                I hereby declare that the information furnished by me in this form is true and correct to the best of my knowledge and belief. I understand that if any information is found to be false or incorrect at any stage, my admission may be cancelled without any refund of fees. I have read and understood all the rules and regulations of the institute and agree to abide by them.
            </p>
            <div class="space-y-2">
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="parent_declaration" required class="mt-1">
                    <span class="text-xs">I, as parent/guardian, declare that the above information is true.</span>
                </label>
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="student_declaration" required class="mt-1">
                    <span class="text-xs">I, as student, have read and agree to the terms and conditions.</span>
                </label>
            </div>
        </div>

        <!-- Signature Row -->
        <div class="border border-gray-400 p-4">
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="border-b border-gray-400 h-8 mb-1"></div>
                    <p class="text-xs">Parent / Guardian Signature</p>
                </div>
                <div class="text-center">
                    <div class="border-b border-gray-400 h-8 mb-1"></div>
                    <p class="text-xs">Branch Manager</p>
                </div>
                <div class="text-center">
                    <div class="border-b border-gray-400 h-8 mb-1"></div>
                    <p class="text-xs">Admission Incharge</p>
                </div>
            </div>
        </div>

        <div style="page-break-before: always;"></div>
        
        <!-- Refund Policy -->
        <div class="border border-black p-4 mb-6">
            <h3 class="text-sm font-bold mb-3">Refund Policy :</h3>
            <div class="text-xs leading-relaxed space-y-2">
                <p class="mb-3">The system at StudyFlow Classes PVT LTD works in a transparent manner and the fee once paid is not refundable under any circumstances. However, the management may consider the request for refund of fee under the following circumstances:</p>
                
                <table class="w-full border-collapse border border-black text-xs">
                    <tr class="border border-black">
                        <td class="border border-black p-2 font-semibold">Fees Component</td>
                        <td class="border border-black p-2 font-semibold">Before Batch Commencement</td>
                        <td class="border border-black p-2 text-center font-semibold">10 Days</td>
                        <td class="border border-black p-2 text-center font-semibold">20 Days</td>
                        <td class="border border-black p-2 text-center font-semibold">30 Days</td>
                        <td class="border border-black p-2 text-center font-semibold">40 Days</td>
                        <td class="border border-black p-2 text-center font-semibold">After 40 Days</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2">Admission Registration Fees</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                        <td class="border border-black p-2">Non Refundable @15000 for senior student</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2"></td>
                        <td class="border border-black p-2">5000 for junior student</td>
                        <td class="border border-black p-2">5000 for junior student</td>
                        <td class="border border-black p-2">5000 for junior student</td>
                        <td class="border border-black p-2">5000 for junior student</td>
                        <td class="border border-black p-2">5000 for junior student</td>
                        <td class="border border-black p-2">5000 for junior student</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2">Admission Kit & Digital Access Fees</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                        <td class="border border-black p-2">Non Refundable as per clause 1</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2">Tuition Fees</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                        <td class="border border-black p-2">80% / 70% / 60% / 50% Refundable as per clause 2</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2">Classroom Service Fees</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                    </tr>
                    <tr class="border border-black">
                        <td class="border border-black p-2">Technology & Exam Fees</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                        <td class="border border-black p-2">Non Refundable as per clause 3/4</td>
                    </tr>
                </table>
                
                <div class="space-y-2 mt-4">
                    <div>
                        <p class="font-semibold">Terms & Condition:</p>
                        <div class="space-y-2 mt-2">
                            <p><strong>Registration Refund Eligibility:</strong> Students who cancel their registration within the specified timeframes are eligible for refunds as per the above schedule.</p>
                            <p><strong>Partial vs. Full Refunds:</strong> Refund amounts vary based on the timing of cancellation relative to batch commencement dates.</p>
                            <p><strong>Non-Refundable Fees:</strong> Registration fees, processing charges, and material costs may be non-refundable after certain dates.</p>
                            <p><strong>Documentation Required:</strong> Written cancellation request with valid identification and original receipt required for all refunds.</p>
                            <p><strong>Refund Process Time:</strong> Refunds are processed within 15-20 working days from receipt of complete documentation.</p>
                            <p><strong>Mode of Refund:</strong> Refunds will be made through the same payment mode used for enrollment (cash/check/online transfer).</p>
                            <p><strong>Transfer of Enrollment:</strong> Enrollment transfers to future batches are subject to availability and applicable fee differences.</p>
                            <p><strong>Appeals Process:</strong> Students may appeal refund decisions within 7 days of receiving the refund decision.</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-right mt-4">
                    <p class="text-xs">(Parents/Guardian Signature)</p>
                </div>
            </div>
        </div>

        <!-- Declaration by Parents -->
        <div class="border border-black p-4 mb-6">
            <div class="border-b-2 border-black pb-2 mb-4">
                <h3 class="text-sm font-bold text-center">DECLARATION BY THE PARENTS</h3>
            </div>
            <div class="text-xs leading-relaxed space-y-2">
                <p>I Mr./Mrs ______ request to StudyFlow Classes Pvt. Ltd. To enroll my ward ______ Master/Miss ______</p>
                
                <ol class="list-decimal list-inside space-y-1">
                    <li>Fees once paid are not refundable under any circumstances.</li>
                    <li>Instalments must be paid on or before the due date without any reminder.</li>
                    <li>Post Dated Cheques (PDC) are mandatory for all instalments.</li>
                    <li>Admission may be cancelled if fees are not paid on time.</li>
                    <li>No transfer of fees to any other student or batch is allowed.</li>
                    <li>Students must maintain discipline and follow institute rules strictly.</li>
                    <li>Any student found consuming alcohol or involved in misconduct will be expelled.</li>
                    <li>No photography or publicity without prior permission from management.</li>
                    <li>Parents are not allowed to enter classrooms during lecture hours.</li>
                    <li>All disputes are subject to Aurangabad jurisdiction only.</li>
                    <li>Institute is not responsible for any damage to vehicles or personal belongings.</li>
                    <li>Warning system: 15/30/45/60 days for disciplinary actions.</li>
                    <li>No oral communication will be accepted - all requests must be in writing.</li>
                </ol>
                
                <div class="mt-4">
                    <p>Hence this declaration made on ____ day of ____ month of ____</p>
                </div>
                
                <div class="text-right mt-4">
                    <p class="text-xs">(Parents/Guardian Signature)</p>
                </div>
            </div>
        </div>

        <div style="page-break-before: always;"></div>
        
        <!-- Declaration by Students -->
        <div class="border border-black p-4 mb-6">
            <div class="border-b-2 border-black pb-2 mb-4">
                <h3 class="text-sm font-bold text-center">DECLARATION BY THE STUDENTS</h3>
            </div>
            <div class="text-xs leading-relaxed space-y-2">
                <p>I hereby declare that the information provided above is true to the best of my knowledge.</p>
                
                <ol class="list-decimal list-inside space-y-1">
                    <li>I will not misuse social media platforms or WhatsApp for any objectionable content.</li>
                    <li>I will not contact teachers personally outside the institute premises.</li>
                    <li>Mobile phones are strictly prohibited inside the classroom and institute premises.</li>
                    <li>I will not post any objectionable content on social media platforms.</li>
                    <li>I will respect all staff members and maintain proper behavior with female students.</li>
                    <li>I will be responsible for any damage caused to institute property.</li>
                    <li>I will follow all rules and regulations of the institute strictly.</li>
                </ol>
                
                <div class="flex justify-between mt-6">
                    <div>
                        <p class="text-xs">Date: ___ / ___ / ___</p>
                    </div>
                    <div>
                        <p class="text-xs">(Signature of the Students)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="text-center mt-6">
            <button type="submit" class="bg-gray-800 text-white px-6 py-2 text-sm font-semibold">
                SUBMIT
            </button>
        </div>
    </form>
@endsection