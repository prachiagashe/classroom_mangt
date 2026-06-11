@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-6">
    <form id="enquiryEditForm" action="{{ route('enquiry.enquiries.update', $enquiry->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Student Details</h1>
                <p class="text-gray-500 mt-1">Complete student information and management</p>
            </div>
            <div class="flex gap-3">

               <a href="{{ route('enquiry.enquiries.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                    ← Back to List
                </a>
                <button type="button" id="editBtn" onclick="toggleEditMode()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Edit Details
                </button>
                <button type="submit" id="saveHeaderBtn" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors hidden">
                    Save Changes
                </button>
                <button type="button" id="cancelBtn" onclick="toggleEditMode()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors hidden">
                    Cancel
                </button>
               
                <a href="{{ route('enquiry.enquiries.print', $enquiry->id) }}" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors view-mode">
                    🖨️ Download PDF
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Student Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Student Basic Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Student Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->first_name }} {{ $enquiry->middle_name }} {{ $enquiry->surname }}</p>
                            <div class="edit-mode hidden grid grid-cols-3 gap-2 mt-1">
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $enquiry->first_name) }}" required minlength="2" pattern="[a-zA-Z]+" title="Alphabets only (no spaces)" class="border rounded px-2 py-1 text-sm w-full" placeholder="First Name">
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name', $enquiry->middle_name) }}" required minlength="2" pattern="[a-zA-Z]+" title="Alphabets only (no spaces)" class="border rounded px-2 py-1 text-sm w-full" placeholder="Middle Name">
                                <input type="text" name="surname" id="surname" value="{{ old('surname', $enquiry->surname) }}" required minlength="2" pattern="[a-zA-Z]+" title="Alphabets only (no spaces)" class="border rounded px-2 py-1 text-sm w-full" placeholder="Surname">
                            </div>
                            <div class="edit-mode hidden grid grid-cols-3 gap-2 mt-1">
                                <div id="first_name_err">
                                    @error('first_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div id="middle_name_err">
                                    @error('middle_name') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                                <div id="surname_err">
                                    @error('surname') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Class</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->formatted_class }}</p>
                            <div class="edit-mode hidden mt-1">
                                <select name="class" class="border rounded px-2 py-1 text-sm w-full">
                                    @php
                                        $classOptions = [
                                            5 => '5th', 6 => '6th', 7 => '7th', 8 => '8th', 
                                            9 => '9th', 10 => '10th', 11 => '11th', 12 => '12th'
                                        ];
                                        $currentClass = $enquiry->class ?? null;
                                        if ($currentClass && in_array($currentClass, [1, 2, 3, 4])) {
                                            $legacyOptions = [1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th'];
                                            $classOptions[$currentClass] = $legacyOptions[$currentClass];
                                            ksort($classOptions);
                                        }
                                    @endphp
                                    @foreach($classOptions as $val => $label)
                                        <option value="{{ $val }}" {{ old('class', $enquiry->class) == $val ? 'selected' : '' }}>{{ $label }} Class</option>
                                    @endforeach
                                </select>
                                @error('class') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->email ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="email" name="email" id="email" value="{{ old('email', $enquiry->email) }}" required class="border rounded px-2 py-1 text-sm w-full" placeholder="Email Address">
                                <div id="email_err">
                                    @error('email') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Mobile</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->contact ?? $enquiry->parent_mobile ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="parent_mobile" id="parent_mobile" value="{{ old('parent_mobile', $enquiry->parent_mobile) }}" required maxlength="10" pattern="[6-9][0-9]{9}" class="border rounded px-2 py-1 text-sm w-full" placeholder="Parent Mobile">
                                <div id="parent_mobile_err">
                                    @error('parent_mobile') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->dob ? $enquiry->dob->format('d M Y') : 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="date" name="dob" value="{{ $enquiry->dob ? $enquiry->dob->format('Y-m-d') : '' }}" class="border rounded px-2 py-1 text-sm w-full">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Gender</label>
                            <p class="text-gray-900 font-medium view-mode">{{ ucfirst($enquiry->gender ?? 'N/A') }}</p>
                            <div class="edit-mode hidden mt-1">
                                <select name="gender" class="border rounded px-2 py-1 text-sm w-full">
                                    <option value="male" {{ $enquiry->gender == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ $enquiry->gender == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ $enquiry->gender == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Parent Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Father's Occupation</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->father_occupation ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="father_occupation" id="father_occupation" value="{{ old('father_occupation', $enquiry->father_occupation) }}" required pattern="[a-zA-Z\s]+" title="Alphabets and spaces only" class="border rounded px-2 py-1 text-sm w-full" placeholder="Father's Occupation">
                                <div id="father_occupation_err">
                                    @error('father_occupation') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                             <label class="text-sm font-medium text-gray-500">Address</label>
                            <div class="text-gray-900 font-medium view-mode break-words whitespace-normal max-w-full">
                                {{ $enquiry->address ?? 'N/A' }}
                            </div>
                            <div class="edit-mode hidden mt-1">
                                <textarea name="address" rows="2" class="border rounded px-2 py-1 text-sm w-full" placeholder="Full Address">{{ old('address', $enquiry->address) }}</textarea>
                                @error('address') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Parent Mobile</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->parent_mobile ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="parent_mobile_alt" id="parent_mobile_alt" value="{{ old('parent_mobile', $enquiry->parent_mobile) }}" required maxlength="10" pattern="[6-9][0-9]{9}" class="border rounded px-2 py-1 text-sm w-full" placeholder="Alternative Parent Mobile">
                                <div id="parent_mobile_alt_err">
                                    @error('parent_mobile_alt') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">WhatsApp No</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->whatsapp ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $enquiry->whatsapp) }}" required maxlength="10" pattern="[6-9][0-9]{9}" class="border rounded px-2 py-1 text-sm w-full" placeholder="WhatsApp Number">
                                <div id="whatsapp_err">
                                    @error('whatsapp') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Student Mobile</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->student_mobile ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="student_mobile" id="student_mobile" value="{{ old('student_mobile', $enquiry->student_mobile) }}" required maxlength="10" pattern="[6-9][0-9]{9}" class="border rounded px-2 py-1 text-sm w-full" placeholder="Student Mobile">
                                <div id="student_mobile_err">
                                    @error('student_mobile') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Academic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Medium</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->medium ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="medium" id="medium" value="{{ old('medium', $enquiry->medium) }}" class="border rounded px-2 py-1 text-sm w-full" placeholder="Medium (e.g. English)">
                                @error('medium') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Year Percentage</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->last_year_percentage ?? 'N/A' }}%</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="number" name="last_year_percentage" id="last_year_percentage" step="0.01" min="0" max="100" value="{{ old('last_year_percentage', $enquiry->last_year_percentage) }}" required class="border rounded px-2 py-1 text-sm w-full" placeholder="Percentage (0-100)">
                                <div id="last_year_percentage_err">
                                    @error('last_year_percentage') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Foundation</label>
                            <p class="text-gray-900 font-medium view-mode">{{ is_array($enquiry->foundation) ? implode(', ', $enquiry->foundation) : ($enquiry->foundation ?? 'N/A') }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="foundation" value="{{ is_array($enquiry->foundation) ? implode(', ', $enquiry->foundation) : $enquiry->foundation }}" class="border rounded px-2 py-1 text-sm w-full">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Course</label>
                            <p class="text-gray-900 font-medium view-mode">{{ is_array($enquiry->course) ? implode(', ', $enquiry->course) : ($enquiry->course ?? 'N/A') }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="course" value="{{ is_array($enquiry->course) ? implode(', ', $enquiry->course) : $enquiry->course }}" class="border rounded px-2 py-1 text-sm w-full">
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Source</label>
                            <p class="text-gray-900 font-medium view-mode">{{ is_array($enquiry->source) ? implode(', ', $enquiry->source) : ($enquiry->source ?? 'N/A') }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="source" value="{{ is_array($enquiry->source) ? implode(', ', $enquiry->source) : $enquiry->source }}" class="border rounded px-2 py-1 text-sm w-full">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Additional Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sibling 1</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->sibling1 ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="sibling1" id="sibling1" value="{{ old('sibling1', $enquiry->sibling1) }}" pattern="[a-zA-Z\s]*" title="Alphabets only" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="border rounded px-2 py-1 text-sm w-full" placeholder="Sibling 1 Name">
                                <div id="sibling1_err">
                                    @error('sibling1') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Sibling 2</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->sibling2 ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="sibling2" id="sibling2" value="{{ old('sibling2', $enquiry->sibling2) }}" pattern="[a-zA-Z\s]*" title="Alphabets only" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="border rounded px-2 py-1 text-sm w-full" placeholder="Sibling 2 Name">
                                <div id="sibling2_err">
                                    @error('sibling2') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Reference 1</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->reference1 ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="reference1" id="reference1" value="{{ old('reference1', $enquiry->reference1) }}" pattern="[a-zA-Z\s]*" title="Alphabets only" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="border rounded px-2 py-1 text-sm w-full" placeholder="Reference 1 Name">
                                <div id="reference1_err">
                                    @error('reference1') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Reference 2</label>
                            <p class="text-gray-900 font-medium view-mode">{{ $enquiry->reference2 ?? 'N/A' }}</p>
                            <div class="edit-mode hidden mt-1">
                                <input type="text" name="reference2" id="reference2" value="{{ old('reference2', $enquiry->reference2) }}" pattern="[a-zA-Z\s]*" title="Alphabets only" oninput="this.value=this.value.replace(/[^a-zA-Z\s]/g,'')" class="border rounded px-2 py-1 text-sm w-full" placeholder="Reference 2 Name">
                                <div id="reference2_err">
                                    @error('reference2') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Remarks & Feedback -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Remarks</h3>
                    <p class="text-gray-900 view-mode">{{ $enquiry->remarks ?? 'No remarks available' }}</p>
                    <div class="edit-mode hidden mt-1">
                        <textarea name="remarks" rows="3" class="border rounded px-2 py-1 text-sm w-full" placeholder="Remarks">{{ old('remarks', $enquiry->remarks) }}</textarea>
                        @error('remarks') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Parent Feedback</h3>
                    <p class="text-gray-900 view-mode">{{ $enquiry->parent_feedback ?? 'No feedback available' }}</p>
                    <div class="edit-mode hidden mt-1">
                        <textarea name="parent_feedback" rows="3" class="border rounded px-2 py-1 text-sm w-full" placeholder="Feedback">{{ old('parent_feedback', $enquiry->parent_feedback) }}</textarea>
                        @error('parent_feedback') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column: Status & Fees -->
            <div class="space-y-6">
                
                <!-- Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Status</h3>
                    
                    <div class="mb-4 view-mode">
                        <span class="px-4 py-2 rounded-full text-sm font-medium
                            {{ $enquiry->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $enquiry->status == 'follow-up' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $enquiry->status == 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $enquiry->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </div>
                    <div class="edit-mode hidden mt-1 mb-4">
                        <select name="status" class="border rounded px-2 py-1 text-sm w-full">
                            <option value="new" {{ $enquiry->status == 'new' ? 'selected' : '' }}>New</option>
                            <option value="follow-up" {{ $enquiry->status == 'follow-up' ? 'selected' : '' }}>Follow-up</option>
                            <option value="confirmed" {{ $enquiry->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="rejected" {{ $enquiry->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="text-sm text-gray-600">
                        <p><strong>Enquiry No:</strong> {{ $enquiry->enquiry_no }}</p>
                        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($enquiry->date)->format('d M Y') }}</p>
                        <p><strong>Branch Code:</strong> {{ $enquiry->branch_code ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Fees Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900 border-b pb-2">Fees Information</h3>
                    
                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Total Fees</p>
                                <p class="text-xl font-bold text-gray-900 view-mode">₹{{ number_format($enquiry->total_fees ?? 0, 2) }}</p>
                                <div class="edit-mode hidden mt-1">
                                    <input type="number" step="0.01" name="total_fees" id="edit_total_fees" value="{{ old('total_fees', $enquiry->total_fees) }}" oninput="calculateFinalFees()" class="border rounded px-2 py-1 text-sm w-full" placeholder="Total Fees">
                                    @error('total_fees') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="p-2 bg-gray-200 rounded-lg">💰</div>
                        </div>

                        <div class="bg-orange-50 p-4 rounded-xl border border-orange-100 flex justify-between items-center text-orange-700">
                            <div>
                                <p class="text-xs text-orange-600 uppercase tracking-wider font-semibold">Discount</p>
                                <p class="text-xl font-bold view-mode">₹{{ number_format($enquiry->discount_fees ?? 0, 2) }}</p>
                                <div class="edit-mode hidden mt-1">
                                    <input type="number" step="0.01" name="discount_fees" id="edit_discount_fees" value="{{ old('discount_fees', $enquiry->discount_fees) }}" oninput="calculateFinalFees()" class="border rounded px-2 py-1 text-sm w-full" placeholder="Discount">
                                    @error('discount_fees') <div class="text-red-600 text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="p-2 bg-orange-200 rounded-lg">🏷️</div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex justify-between items-center text-blue-700">
                            <div>
                                <p class="text-xs text-blue-600 uppercase tracking-wider font-semibold">Admission Fees</p>
                                <p class="text-2xl font-black view-mode" id="view_final_fees">&#8377;{{ number_format($enquiry->final_fees ?? 0, 2) }}</p>
                                <p class="text-2xl font-black edit-mode hidden" id="edit_final_fees">&#8377;{{ number_format($enquiry->final_fees ?? 0, 2) }}</p>
                                {{-- Hidden input to submit final_fees with form --}}
                                <input type="hidden" name="final_fees" id="hidden_final_fees" value="{{ old('final_fees', $enquiry->final_fees ?? 0) }}">
                            </div>
                            <div class="p-2 bg-blue-200 rounded-lg">&#10003;</div>
                        </div>
                    </div>
                </div>

                <!-- Actions (Always visible unless in edit mode) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 view-mode">
                    <h3 class="font-semibold text-lg mb-4 text-gray-900">Workflow Actions</h3>
                    <div class="space-y-3">
                        @if($enquiry->status != 'confirmed')
                            <button form="confirm-form" type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                ✓ Confirm Admission
                            </button>
                        @endif
                        
                        @if($enquiry->status != 'rejected')
                            <button form="reject-form" type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                ✕ Reject
                            </button>
                        @endif

                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enquiry->whatsapp ?: $enquiry->parent_mobile) }}?text={{ urlencode('Hello ' . $enquiry->first_name . ', this is ' . env('INSTITUTE_NAME', 'Bansal Classes') . '. How can we help you?') }}"
                           target="_blank"
                           class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors flex items-center justify-center gap-2">
                             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.067 2.877 1.215 3.076.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                             <span>Send WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Save Button in Edit Mode -->
        <div id="saveBtnContainer" class="edit-mode hidden fixed bottom-8 right-8 z-50">
            <button type="submit" class="bg-green-600 text-white px-8 py-3 rounded-full shadow-2xl hover:bg-green-700 font-bold transition-all transform hover:scale-105 flex items-center gap-2">
                <span>📁</span> Save All Changes
            </button>
        </div>
    </form>

    <!-- Hidden Action Forms -->
    <form id="confirm-form" method="POST" action="{{ route('enquiry.enquiries.confirm', $enquiry->id) }}" onsubmit="return handleConfirmSubmission(this, 'Confirm admission for {{ str_replace('\'', '\\\'', $enquiry->first_name) }}?')">@csrf</form>
    <form id="reject-form" method="POST" action="{{ route('enquiry.enquiries.reject', $enquiry->id) }}" onsubmit="return confirm('Reject this enquiry?')">@csrf</form>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mainForm = document.getElementById('enquiryEditForm');
    
    // Helper: Show error
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errId = fieldId + '_err';
        let errDiv = document.getElementById(errId);
        
        if (!errDiv) {
            errDiv = document.createElement('div');
            errDiv.id = errId;
            errDiv.className = 'error-message text-red-600 text-xs mt-1';
            field.parentNode.appendChild(errDiv);
        }
        
        errDiv.innerText = message;
        field.classList.add('border-red-500');
        field.classList.remove('border-gray-200');
    }

    // Helper: Clear error
    function clearError(fieldId) {
        const field = document.getElementById(fieldId);
        const errDiv = document.getElementById(fieldId + '_err');
        if (errDiv) errDiv.innerText = '';
        if (field) {
            field.classList.remove('border-red-500');
            field.classList.add('border-gray-200');
        }
    }

    // 1. Name Validation
    function validateName(id, fieldName) {
        const field = document.getElementById(id);
        if (!field) return true;
        const val = field.value.trim();
        clearError(id);

        if (val === '') {
            showError(id, `${fieldName} is required`);
            return false;
        }
        if (val.length < 2) {
            showError(id, 'Minimum 2 characters required');
            return false;
        }
        if (!/^[a-zA-Z]+$/.test(val)) {
            showError(id, 'Only alphabets (A-Z, a-z) allowed');
            return false;
        }
        return true;
    }

    ['first_name', 'middle_name', 'surname', 'father_occupation'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => validateName(id, id.replace('_', ' ')));
        }
    });

    // 2. Mobile Validation
    function validateMobile(id, fieldName) {
        const field = document.getElementById(id);
        if (!field) return true;
        let val = field.value.trim();
        
        // Sanitize: allow only digits
        val = val.replace(/[^0-9]/g, '');
        field.value = val;
        
        clearError(id);

        if (val === '') {
            showError(id, `${fieldName} is required`);
            return false;
        }
        if (val.length !== 10) {
            showError(id, 'Must be exactly 10 digits');
            return false;
        }
        if (!/^[6-9]/.test(val)) {
            showError(id, 'Must start with 6, 7, 8, or 9');
            return false;
        }
        return true;
    }

    ['parent_mobile', 'parent_mobile_alt', 'student_mobile', 'whatsapp'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', () => validateMobile(id, id.replace('_', ' ')));
        }
    });

    // 3. Email Validation
    function validateEmail() {
        const field = document.getElementById('email');
        if (!field) return true;
        const val = field.value.trim();
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
            showError('email', 'Invalid email format');
            return false;
        }
        return true;
    }
    const emailEl = document.getElementById('email');
    if (emailEl) emailEl.addEventListener('input', validateEmail);

    // 4. Percentage Validation
    function validatePercentage() {
        const field = document.getElementById('last_year_percentage');
        if (!field) return true;
        const val = field.value.trim();
        clearError('last_year_percentage');

        if (val === '') {
            showError('last_year_percentage', 'Percentage is required');
            return false;
        }
        const num = parseFloat(val);
        if (isNaN(num)) {
            showError('last_year_percentage', 'Must be numeric');
            return false;
        }
        if (num < 0 || num > 100) {
            showError('last_year_percentage', 'Range 0 to 100');
            return false;
        }
        return true;
    }
    const percentEl = document.getElementById('last_year_percentage');
    if (percentEl) percentEl.addEventListener('input', validatePercentage);

    // 5. Fees Validation
    function validateFees() {
        const totalEl = document.getElementById('edit_total_fees');
        const discountEl = document.getElementById('edit_discount_fees');
        if (!totalEl || !discountEl) return true;

        const total = parseFloat(totalEl.value) || 0;
        const discount = parseFloat(discountEl.value) || 0;
        
        clearError('edit_total_fees');
        clearError('edit_discount_fees');

        let isValid = true;
        if (total <= 0) {
            showError('edit_total_fees', 'Total Fees must be greater than 0');
            isValid = false;
        }
        if (discount < 0) {
            showError('edit_discount_fees', 'Discount cannot be negative');
            isValid = false;
        }
        if (discount > total) {
            showError('edit_discount_fees', 'Discount cannot exceed Total Fees');
            isValid = false;
        }
        return isValid;
    }
    const feeElements = ['edit_total_fees', 'edit_discount_fees'];
    feeElements.forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', () => {
            calculateFinalFees();
            validateFees();
        });
    });

    // Form Submission
    if (mainForm) {
        mainForm.addEventListener('submit', function (e) {
            let isValid = true;

            if (!validateName('first_name', 'First name')) isValid = false;
            if (!validateName('middle_name', 'Middle name')) isValid = false;
            if (!validateName('surname', 'Surname')) isValid = false;
            if (!validateName('father_occupation', 'Occupation')) isValid = false;
            if (!validateEmail()) isValid = false;
            if (!validateMobile('parent_mobile', 'Mobile')) isValid = false;
            if (!validateMobile('whatsapp', 'WhatsApp')) isValid = false;
            if (!validateMobile('student_mobile', 'Student mobile')) isValid = false;
            if (!validatePercentage()) isValid = false;
            if (!validateFees()) isValid = false;

            if (!isValid) {
                e.preventDefault();
                const firstErr = document.querySelector('.error-message:not(:empty)');
                if (firstErr) firstErr.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    }

    // Toggle Edit Mode
    window.toggleEditMode = function() {
        const viewElements = document.querySelectorAll('.view-mode');
        const editElements = document.querySelectorAll('.edit-mode');
        const editBtn = document.getElementById('editBtn');
        const saveHeaderBtn = document.getElementById('saveHeaderBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const saveBtn = document.getElementById('saveBtnContainer');

        viewElements.forEach(el => el.classList.toggle('hidden'));
        editElements.forEach(el => el.classList.toggle('hidden'));
        
        if (editBtn) editBtn.classList.toggle('hidden');
        if (saveHeaderBtn) saveHeaderBtn.classList.toggle('hidden');
        if (cancelBtn) cancelBtn.classList.toggle('hidden');
        if (saveBtn) saveBtn.classList.toggle('hidden');

        // Clear errors when toggling
        document.querySelectorAll('.error-message').forEach(el => el.innerText = '');
        document.querySelectorAll('.error-message div').forEach(el => el.remove());
        document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
    };

    // Auto-toggle edit mode if there are server-side errors
    @if($errors->any())
        toggleEditMode();
    @endif

    // Global calculate function
    window.calculateFinalFees = function() {
        const totalEl  = document.getElementById('edit_total_fees');
        const discountEl = document.getElementById('edit_discount_fees');
        const hiddenFinalEl = document.getElementById('hidden_final_fees');
        if (!totalEl) return;

        const total    = parseFloat(totalEl.value)    || 0;
        const discount = parseFloat(discountEl ? discountEl.value : 0) || 0;
        const final    = Math.max(0, total - discount);

        const formatted = '\u20b9' + final.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});

        const viewFinal = document.getElementById('view_final_fees');
        const editFinal = document.getElementById('edit_final_fees');
        if (viewFinal) viewFinal.innerText = formatted;
        if (editFinal) editFinal.innerText = formatted;

        // Write the raw numeric value to the hidden input so it is submitted
        if (hiddenFinalEl) hiddenFinalEl.value = final.toFixed(2);
    }
});

function handleConfirmSubmission(form, msg) {
    if (!confirm(msg)) return false;
    const btn = document.querySelector('button[form="' + form.id + '"]') || form.querySelector('button[type="submit"]');
    if (btn) {
        if (btn.disabled) return false;
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        btn.innerHTML = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;
    }
    return true;
}
</script>

<style>
    .edit-mode input, .edit-mode select, .edit-mode textarea {
        background-color: #f9fafb;
        transition: border-color 0.2s;
    }
    .edit-mode input:focus, .edit-mode select:focus, .edit-mode textarea:focus {
        background-color: #fff;
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
</style>

@endsection
