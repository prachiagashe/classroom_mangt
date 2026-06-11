@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto bg-white shadow-lg rounded-xl p-8">

    <!-- Header -->
    <div class="text-center mb-8 border-b pb-4">
        <h1 class="text-2xl font-bold">BANSAL CLASSES PRIVATE LIMITED</h1>
        <p class="text-sm text-gray-600">Rajasthan Kota's Pioneer Brand of India | Since 1981</p>
        <p class="text-sm text-gray-600">BCPL, 2nd Floor, R.B. Ingle Plaza, Nanded City, Pune</p>
        <h2 class="mt-4 text-lg font-semibold bg-gray-200 inline-block px-4 py-1 rounded">
            ENQUIRY FORM
        </h2>
    </div>

  <form method="POST" action="{{ route('enquiry.store') }}">

        @csrf
        
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div id="success-message" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Enquiry Info - Top Row -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <label class="font-medium">Enq No :</label>
                <input type="text" name="enquiry_no" value="{{ $enquiryNo ?? '' }}" readonly class="mt-1 block w-32 border rounded px-3 py-2 bg-gray-100">
            </div>
            
            <div>
                <label class="font-medium">Date :</label>
                <input type="text" name="date" value="{{ $today ?? date('d/m/Y') }}" readonly class="mt-1 block w-32 border rounded px-3 py-2 bg-gray-100">
            </div>
        </div>

        <!-- Branch Code -->
        <div class="mb-6">
            <label class="font-medium">Branch code :</label>
            <input type="text" name="branch_code" value="BCPL-NDCY" readonly class="mt-1 block w-64 border rounded px-3 py-2 bg-gray-100">
        </div>

        <!-- Full Name -->
        <h3 class="font-semibold mb-2">
    Full Name of Applicant <span class="text-red-600">*</span>
</h3>

<div class="grid grid-cols-3 gap-4 mb-2">
    <div>
        <input type="text" name="first_name" placeholder="First Name *"
               required minlength="2" maxlength="50" pattern="[a-zA-Z]+" title="Only alphabets allowed (no spaces)" value="{{ old('first_name') }}"
               class="w-full border rounded px-3 py-2 {{ $errors->has('first_name') ? 'border-red-500' : '' }}" id="first_name">
    </div>

    <div>
        <input type="text" name="middle_name" placeholder="Middle Name *"
               required minlength="2" maxlength="50" pattern="[a-zA-Z]+" title="Only alphabets allowed (no spaces)" value="{{ old('middle_name') }}"
               class="w-full border rounded px-3 py-2 {{ $errors->has('middle_name') ? 'border-red-500' : '' }}" id="middle_name">
    </div>

    <div>
        <input type="text" name="surname" placeholder="Surname *"
               required minlength="2" maxlength="50" pattern="[a-zA-Z]+" title="Only alphabets allowed (no spaces)" value="{{ old('surname') }}"
               class="w-full border rounded px-3 py-2 {{ $errors->has('surname') ? 'border-red-500' : '' }}" id="surname">
    </div>
</div>

<div class="grid grid-cols-3 gap-4 mb-6">
    <div id="first_name_err">@error('first_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror</div>
    <div id="middle_name_err">@error('middle_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror</div>
    <div id="surname_err">@error('surname') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror</div>
</div>


        <!-- Academic Info -->
        <div class="grid grid-cols-3 gap-6 mb-6">
    <div>
        <label>
            Class <span class="text-red-500">*</span>
        </label>
        <select name="class" required class="w-full border rounded px-3 py-2 {{ $errors->has('class') ? 'border-red-500' : '' }}">
            <option value="">Select Class</option>
            @foreach([
                5 => '5th', 6 => '6th', 7 => '7th', 8 => '8th', 
                9 => '9th', 10 => '10th', 11 => '11th', 12 => '12th'
            ] as $val => $label)
                <option value="{{ $val }}" {{ old('class') == $val ? 'selected' : '' }}>{{ $label }} Class</option>
            @endforeach
        </select>
        @error('class')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>
            College / School Time <span class="text-red-500">*</span>
        </label>
        <input type="text"
               name="school_time"
               value="{{ old('school_time') }}"
               class="w-full border rounded px-3 py-2">
    </div>

    <div>
        <label>
            Last Year % <span class="text-red-500">*</span>
        </label>
        <input type="number"
               name="last_year_percentage" min="0.01" max="100" step="0.01" required
               value="{{ old('last_year_percentage') }}"
               class="w-full border rounded px-3 py-2 {{ $errors->has('last_year_percentage') ? 'border-red-500' : '' }}" id="last_year_percentage">
        <div id="last_year_percentage_err">
            @error('last_year_percentage')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<div class="mb-6">
    <label>
        College / School Name <span class="text-red-500">*</span>
    </label>
    <input type="text" required maxlength="150"
           name="school_name"
           value="{{ old('school_name') }}"
           class="w-full border rounded px-3 py-2 {{ $errors->has('school_name') ? 'border-red-500' : '' }}">
    @error('school_name')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>



        <!-- Medium -->
        <div class="mb-6">
            <label class="font-medium">Medium</label><span class="text-red-500">*</span>
            <div class="flex gap-6 mt-2 {{ $errors->has('medium') ? 'border-red-500' : '' }}">
                <label><input type="radio" name="medium" required value="Semi English" {{ old('medium') == 'Semi English' ? 'checked' : '' }}> Semi English</label>
                <label><input type="radio" name="medium" required value="English" {{ old('medium') == 'English' ? 'checked' : '' }}> English</label>
                <label><input type="radio" name="medium" required value="CBSE" {{ old('medium') == 'CBSE' ? 'checked' : '' }}> CBSE</label>
                <label><input type="radio" name="medium" required value="ICSE" {{ old('medium') == 'ICSE' ? 'checked' : '' }}> ICSE</label>
            </div>
            
            @error('medium')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Personal Info -->
        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label>Date of Birth <span class="text-red-500">*</span></label>
                <input type="date" name="dob" value="{{ old('dob') }}" 
                       max="{{ now()->subYears(5)->format('Y-m-d') }}" 
                       class="w-full border rounded px-3 py-2 {{ $errors->has('dob') ? 'border-red-500' : '' }}" required>
                @error('dob')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label>Gender <span class="text-red-500">*</span></label>
                <div class="flex gap-6 mt-2">
                    <label>
                        <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                        Male
                    </label>
                    <label>
                        <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                        Female
                    </label>
                    <label>
                        <input type="radio" name="gender" value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                        Other
                    </label>
                </div>

                @error('gender')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label>Father's Occupation</label>
                <input type="text" name="father_occupation" value="{{ old('father_occupation') }}" class="w-full border rounded px-3 py-2">
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-6">
            <div>
                <label>Mobile No (Parent) <span class="text-red-500">*</span></label>
                <input type="tel" name="parent_mobile" id="parent_mobile" maxlength="10" required 
                       inputmode="numeric" pattern="[6-9][0-9]{9}" title="Must be exactly 10 digits starting with 6, 7, 8, or 9" 
                       value="{{ old('parent_mobile') }}" class="w-full border rounded px-3 py-2 {{ $errors->has('parent_mobile') ? 'border-red-500' : '' }}">
                <div id="parent_mobile_err">
                    @error('parent_mobile')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label>Mobile No (Student)</label>
                <input type="tel" name="student_mobile" id="student_mobile" maxlength="10" 
                       inputmode="numeric" pattern="[6-9][0-9]{9}" title="Must be exactly 10 digits starting with 6, 7, 8, or 9" 
                       value="{{ old('student_mobile') }}" class="w-full border rounded px-3 py-2 {{ $errors->has('student_mobile') ? 'border-red-500' : '' }}">
                <div id="student_mobile_err">
                    @error('student_mobile')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label>WhatsApp No <span class="text-red-500">*</span></label>
                <input type="tel" name="whatsapp" id="whatsapp" maxlength="10" required 
                       inputmode="numeric" pattern="[6-9][0-9]{9}" title="Must be exactly 10 digits starting with 6, 7, 8, or 9" 
                       value="{{ old('whatsapp') }}" class="w-full border rounded px-3 py-2 {{ $errors->has('whatsapp') ? 'border-red-500' : '' }}">
                <div id="whatsapp_err">
                    @error('whatsapp')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label>Email <span class="text-red-500">*</span></label>
                <input type="email" id="email" required
                       name="email" maxlength="255"
                       value="{{ old('email') }}"
                       class="w-full border rounded px-3 py-2 {{ $errors->has('email') ? 'border-red-500' : '' }}">
                <div id="email_err">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

        </div>

        <div class="mb-6">
            <label>Address <span class="text-red-500">*</span></label>
            <textarea name="address" rows="2" minlength="10" maxlength="1000" required class="w-full border rounded px-3 py-2 {{ $errors->has('address') ? 'border-red-500' : '' }}">{{ old('address') }}</textarea>
            
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Foundation Courses -->
        <div class="mb-6">
            <label class="font-medium">Foundation</label>
            <div class="flex flex-wrap gap-4 mt-2 {{ $errors->has('foundation') ? 'border-red-500' : '' }}">
                <label><input type="checkbox" name="foundation[]" value="Scholarship" {{ old('foundation') && in_array('Scholarship', old('foundation')) ? 'checked' : '' }}> Scholarship</label>
                <label><input type="checkbox" name="foundation[]" value="Dr Homibhabha" {{ old('foundation') && in_array('Dr Homibhabha', old('foundation')) ? 'checked' : '' }}> Dr Homibhabha</label>
                <label><input type="checkbox" name="foundation[]" value="Olympiad" {{ old('foundation') && in_array('Olympiad', old('foundation')) ? 'checked' : '' }}> Olympiad</label>
                <label><input type="checkbox" name="foundation[]" value="MTSE" {{ old('foundation') && in_array('MTSE', old('foundation')) ? 'checked' : '' }}> MTSE</label>
            </div>
            
            @error('foundation')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Courses -->
        <div class="mb-6">
            <label class="font-medium">Courses</label>
            <div class="flex flex-wrap gap-4 mt-2 {{ $errors->has('course') ? 'border-red-500' : '' }}">
                <label><input type="checkbox" name="course[]" value="NEET" {{ old('course') && in_array('NEET', old('course')) ? 'checked' : '' }}> NEET</label>
                <label><input type="checkbox" name="course[]" value="JEE" {{ old('course') && in_array('JEE', old('course')) ? 'checked' : '' }}> JEE</label>
                <label><input type="checkbox" name="course[]" value="MHT-CET" {{ old('course') && in_array('MHT-CET', old('course')) ? 'checked' : '' }}> MHT-CET</label>
                <label><input type="checkbox" name="course[]" value="REPT" {{ old('course') && in_array('REPT', old('course')) ? 'checked' : '' }}> REPT</label>
                <label><input type="checkbox" name="course[]" value="Test Series" {{ old('course') && in_array('Test Series', old('course')) ? 'checked' : '' }}> TEST SERIES</label>
                <label><input type="checkbox" name="course[]" value="Crash Course" {{ old('course') && in_array('Crash Course', old('course')) ? 'checked' : '' }}> CRASH COURSE</label>
            </div>
            
            @error('course')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Sibling -->
        <div class="mb-6">
            <label class="font-medium">Sibling</label>
            <input type="text" name="sibling1" placeholder="Sibling 1 Details" value="{{ old('sibling1') }}" maxlength="100" pattern="[a-zA-Z\s]*" title="Only alphabets and spaces" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="w-full border rounded px-3 py-2 mb-2 {{ $errors->has('sibling1') ? 'border-red-500' : '' }}">
            @error('sibling1') <p class="text-red-500 text-sm mb-2 -mt-1">{{ $message }}</p> @enderror
            <input type="text" name="sibling2" placeholder="Sibling 2 Details" value="{{ old('sibling2') }}" maxlength="100" pattern="[a-zA-Z\s]*" title="Only alphabets and spaces" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="w-full border rounded px-3 py-2 {{ $errors->has('sibling2') ? 'border-red-500' : '' }}">
            @error('sibling2') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Reference -->
        <div class="mb-6">
            <label class="font-medium">Reference</label>
            <input type="text" name="reference1" placeholder="Reference 1" value="{{ old('reference1') }}" maxlength="100" class="w-full border rounded px-3 py-2 mb-2 {{ $errors->has('reference1') ? 'border-red-500' : '' }}" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');">
            @error('reference1') <p class="text-red-500 text-sm mb-2 -mt-1">{{ $message }}</p> @enderror
            <input type="text" name="reference2" placeholder="Reference 2" value="{{ old('reference2') }}" maxlength="100" class="w-full border rounded px-3 py-2 {{ $errors->has('reference2') ? 'border-red-500' : '' }}" oninput="this.value = this.value.replace(/[^A-Za-z\s]/g, '');">
            @error('reference2') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- How did you know -->
        <div class="mb-6">
            <label class="font-medium">How did you know about us?</label>
            <div class="flex flex-wrap gap-4 mt-2">
                <label><input type="checkbox" name="source[]" value="Boost/TSE Exam" {{ old('source') && in_array('Boost/TSE Exam', old('source')) ? 'checked' : '' }}> Boost/TSE</label>
                <label><input type="checkbox" name="source[]" value="Paper Advertisement" {{ old('source') && in_array('Paper Advertisement', old('source')) ? 'checked' : '' }}> Paper Advt</label>
                <label><input type="checkbox" name="source[]" value="TV Advertisement" {{ old('source') && in_array('TV Advertisement', old('source')) ? 'checked' : '' }}> TV Advt</label>
                <label><input type="checkbox" name="source[]" value="Student Reference" {{ old('source') && in_array('Student Reference', old('source')) ? 'checked' : '' }}> Student Ref</label>
                <label><input type="checkbox" name="source[]" value="Employee Reference" {{ old('source') && in_array('Employee Reference', old('source')) ? 'checked' : '' }}> Employee Ref</label>
                <label><input type="checkbox" name="source[]" value="Other" {{ old('source') && in_array('Other', old('source')) ? 'checked' : '' }}> Other</label>
            </div>
        </div>

        @auth
        <!-- Remarks -->
        <div class="mb-6">
            <label class="font-medium">Remarks</label>
            <div class="flex gap-4 mt-2">
                <label><input type="radio" name="remarks" value="Hot" {{ old('remarks') == 'Hot' ? 'checked' : '' }}> Hot</label>
                <label><input type="radio" name="remarks" value="Warm" {{ old('remarks') == 'Warm' ? 'checked' : '' }}> Warm</label>
                <label><input type="radio" name="remarks" value="Cold" {{ old('remarks') == 'Cold' ? 'checked' : '' }}> Cold</label>
            </div>
        </div>

        <!-- Parent Feedback Section -->
        <!-- <div class="mb-6 border-t pt-6">
            <label class="font-medium">Sign Section</label>
        </div> -->

        <!-- Fees Section -->
        <!-- <div class="grid grid-cols-3 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium mb-1">Total Fees <span class="text-red-500">*</span></label>
                <input type="number" id="total_fees" name="total_fees" min="1" step="0.01" required placeholder="Enter Total Fees" value="{{ old('total_fees') }}" class="border rounded px-3 py-2 w-full {{ $errors->has('total_fees') ? 'border-red-500' : '' }}">
                
                @error('total_fees')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Discount Fees</label>
                <input type="number" id="discount_fees" name="discount_fees" min="0" step="0.01" placeholder="Enter Discount" value="{{ old('discount_fees') }}" class="border rounded px-3 py-2 w-full {{ $errors->has('discount_fees') ? 'border-red-500' : '' }}">
                @error('discount_fees')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Final Fees <span class="text-red-500">*</span></label>
                <input type="number" id="final_fees" name="final_fees" min="0" step="0.01" placeholder="Auto-calculated" class="border rounded px-3 py-2 w-full {{ $errors->has('final_fees') ? 'border-red-500' : '' }}" readonly>
                
                @error('final_fees')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>
        @endauth -->

        
        <!-- <div class="grid grid-cols-3 gap-6 mb-6 text-center">
            <div>
                <label>Parent's Sign</label>
                <input type="text" name="parent_sign" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label>Student's Sign</label>
                <input type="text" name="student_sign" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label>Counsellor Name & Sign</label>
                <input type="text" name="counsellor_sign" class="w-full border rounded px-3 py-2">
            </div>
        </div> -->


@auth
<!-- ================= COUNSELLING SECTION ================= -->
<div class="border-t mt-10 pt-8">

    <h2 class="text-center text-xl font-bold mb-6 border rounded-full inline-block px-6 py-1">
        COUNSELLING
    </h2>

    <div class="grid grid-cols-2 gap-8">

        <!-- Left Side - Points Checklist -->
        <div class="border rounded-lg p-6">

            <h3 class="bg-gray-200 font-semibold px-3 py-2 rounded mb-4">
                Points to be discussed / done
            </h3>

            @php
                $counsellingPoints = [
                    "Welcome To Parents And Students",
                    "Introduce Yourself And Bansal's History",
                    "Ask Std And Brief",
                    "Needs Of Classes For Particular Std",
                    "Why Bansal Is Best?",
                    "Syllabus / Academic Planner",
                    "Faculty Team",
                    "Day Care Schedule",
                    "Double Session",
                    "Dppp, Modules",
                    "Study Materials, Bag, T-shirt",
                    "Fortnightly Test (15 Days)",
                    "Subjective Test",
                    "Major Test",
                    "Result",
                    "Class Room & Infrastructure",
                    "Parents Teacher Meeting",
                    "Amenities",
                    "Security",
                    "CCTV",
                    "Gate Pass",
                    "Batch Coordinator For Girls",
                    "Fees",
                    "Total Fees",
                    "Discount Fees",
                    "Final Fees"
                ];
            @endphp

            <div class="space-y-2">
                @foreach($counsellingPoints as $point)
                    <label class="flex items-start gap-2">
                        <input type="checkbox" name="counselling_points[]" value="{{ $point }}" class="mt-1">
                        <span>{{ $point }}</span>
                    </label>
                @endforeach
            </div>

        </div>

        <!-- Right Side - Parent Feedback -->
        <div class="border rounded-lg p-6">

            <h3 class="font-semibold mb-4">
                Parent's Feedback / Conversation
            </h3>

            <textarea name="parent_feedback"
                      rows="18"
                      class="w-full border rounded px-3 py-2 resize-none"></textarea>

        </div>

    </div>

</div>
@endauth

<!-- sign section  -->
 <div class="mb-6 border-t pt-6">
            <label class="font-medium">Sign Section :</label>
        </div>

         <div class="grid grid-cols-3 gap-6 mb-6 text-center">
            <div>
                <label>Parent's Sign</label>
                <input type="text" name="parent_sign" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label>Student's Sign</label>
                <input type="text" name="student_sign" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label>Counsellor Name & Sign</label>
                <input type="text" name="counsellor_sign" class="w-full border rounded px-3 py-2">
            </div>
        </div>



    <!-- ================= IMPORTANT INSTRUCTIONS ================= -->
    <div class="border-t mt-10 pt-6">
        <h3 class="text-lg font-bold mb-4">
            Important Instructions:
        </h3>

        @php
            $importantNotes = [
                "For all admissions, the fee structure will be divided as 30% Study Material Fee and 70% Tuition Fee.",
                "For all admissions, scholarship or discount will be applied on the total fee amount (100%).",
                "No scholarship or discount will be applied on the 30% Study Material Fee.",
                "Residential students must maintain a minimum attendance of 95%.",
                "For admissions where a receipt has already been issued, the 30% Study Material Fee will not be refunded.",
                "If a student receives more than 40% scholarship, special approval will be required.",
                "For Class 11 and Class 12 (NEET/JEE – 2 Years) admissions, 95% of the total fee for both years must be paid."
            ];
        @endphp

        <div class="space-y-2">
            @foreach($importantNotes as $note)
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="important_notes[]" value="{{ $note }}" class="mt-1">
                    <span>{{ $note }}</span>
                </label>
            @endforeach
        </div>
    </div>




        <div class="text-center">
            <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700" onclick="return validateFees(event)">
                Submit Enquiry
            </button>
        </div>

    </form>

</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form');
    const successMessage = document.getElementById('success-message');

    // Auto-capitalize first letter of text inputs and textareas
    document.querySelectorAll('input[type="text"], textarea').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                // Capitalize first letter of every word
                this.value = this.value.replace(/\b\w/g, c => c.toUpperCase());
            }
        });
    });

    // Deselect radio buttons on double click for remark, medium, and gender
    const radiosToDeselect = document.querySelectorAll('input[type="radio"][name="remarks"], input[type="radio"][name="medium"], input[type="radio"][name="gender"]');
    radiosToDeselect.forEach(radio => {
        radio.addEventListener('dblclick', function() {
            this.checked = false;
        });
    });
    
    // Auto-hide success message
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.transition = 'opacity 0.5s ease-out';
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 500);
        }, 5000);
    }

    // Helper: Show error
    function showError(fieldId, message) {
        const errorContainer = document.getElementById(fieldId + '_err');
        if (errorContainer) {
            errorContainer.innerHTML = `<p class="text-red-500 text-xs mt-1">${message}</p>`;
        }
        const field = document.getElementById(fieldId);
        if (field) field.classList.add('border-red-500');
    }

    // Helper: Clear error
    function clearError(fieldId) {
        const errorContainer = document.getElementById(fieldId + '_err');
        if (errorContainer) errorContainer.innerHTML = '';
        const field = document.getElementById(fieldId);
        if (field) field.classList.remove('border-red-500');
    }

    // 1. Name Validation (First, Middle, Surname)
    function validateName(id) {
        const field = document.getElementById(id);
        const val = field.value.trim();
        clearError(id);

        if (val === '') {
            showError(id, 'This field should not be empty');
            return false;
        }
        if (val.length < 2) {
            showError(id, 'Minimum length: 2 characters');
            return false;
        }
        if (!/^[a-zA-Z]+$/.test(val)) {
            showError(id, 'Allow only alphabets (A–Z, a–z), no spaces or numbers');
            return false;
        }
        return true;
    }

    ['first_name', 'middle_name', 'surname'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => validateName(id));
            el.addEventListener('blur', () => validateName(id));
        }
    });

    // 2. Percentage Validation
    const percentField = document.getElementById('last_year_percentage');
    function validatePercentage() {
        if (!percentField) return true;
        const val = percentField.value.trim();
        clearError('last_year_percentage');

        if (val === '') {
            showError('last_year_percentage', 'Percentage is required');
            return false;
        }
        const num = parseFloat(val);
        if (isNaN(num)) {
            showError('last_year_percentage', 'Allow only numeric values');
            return false;
        }
        if (num <= 0 || num > 100) {
            showError('last_year_percentage', 'Range should be greater than 0 and up to 100');
            return false;
        }
        if (!/^\d+(\.\d{1,2})?$/.test(val)) {
            showError('last_year_percentage', 'Only up to 2 decimal places allowed');
            return false;
        }
        return true;
    }
    if (percentField) {
        percentField.addEventListener('input', function(e) {
            let val = this.value;
            // Prevent more than 2 decimal places during typing
            if (val.includes('.')) {
                let parts = val.split('.');
                if (parts[1].length > 2) {
                    this.value = parts[0] + '.' + parts[1].substring(0, 2);
                }
            }
            validatePercentage();
        });
    }

    // 3. Email Validation
    const emailField = document.getElementById('email');
    function validateEmail() {
        if (!emailField) return true;
        const val = emailField.value.trim();
        clearError('email');

        if (val === '') {
            showError('email', 'Email is required');
            return false;
        }
        if (/\s/.test(val)) {
            showError('email', 'No spaces allowed');
            return false;
        }
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailRegex.test(val)) {
            showError('email', 'Must follow standard email format (user@example.com)');
            return false;
        }
        return true;
    }
    if (emailField) {
        emailField.addEventListener('input', validateEmail);
    }

    // 4. Mobile Number Validation
    function validateMobile(id, isRequired = true) {
        const field = document.getElementById(id);
        if (!field) return true;
        let val = field.value.trim();
        
        // Remove non-numeric characters for clean check
        val = val.replace(/[^0-9]/g, '');
        field.value = val;
        
        clearError(id);

        if (val === '') {
            if (isRequired) {
                showError(id, 'Mobile number is required');
                return false;
            }
            return true;
        }

        if (val.length !== 10) {
            showError(id, 'Must be exactly 10 digits');
            return false;
        }

        if (!/^[6-9]/.test(val)) {
            showError(id, 'Should start only with 6, 7, 8, or 9');
            return false;
        }

        return true;
    }

    const mobileIds = ['parent_mobile', 'student_mobile', 'whatsapp'];
    mobileIds.forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => validateMobile(id, id !== 'student_mobile'));
        }
    });

    // Final Form Submission Check
    form.addEventListener('submit', function (e) {
        let isValid = true;

        if (!validateName('first_name')) isValid = false;
        if (!validateName('middle_name')) isValid = false;
        if (!validateName('surname')) isValid = false;
        if (!validatePercentage()) isValid = false;
        if (!validateEmail()) isValid = false;
        if (!validateMobile('parent_mobile', true)) isValid = false;
        if (!validateMobile('whatsapp', true)) isValid = false;
        if (!validateMobile('student_mobile', false)) isValid = false;

        if (!isValid) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.text-red-500');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Fee calculation logic (if present)
    const totalFees = document.getElementById('total_fees');
    const discountFees = document.getElementById('discount_fees');
    const finalFees = document.getElementById('final_fees');

    if (totalFees && discountFees && finalFees) {
        function calculateFinalFees() {
            let total = parseFloat(totalFees.value) || 0;
            let discount = parseFloat(discountFees.value) || 0;
            finalFees.value = (discount > total) ? 0 : total - discount;
        }
        totalFees.addEventListener('input', calculateFinalFees);
        discountFees.addEventListener('input', calculateFinalFees);
    }
});
</script>
@endsection
