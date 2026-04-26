@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Header with Gradient Background -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Admission Details</h1>
                <p class="text-blue-100">Complete student admission information and profile</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('enquiry.admissions.index') }}" class="bg-white/20 hover:bg-white/30 backdrop-blur text-white px-4 py-2 rounded-lg transition-all duration-200 border border-white/30">
                    ← Back to List
                </a>
                <a href="{{ route('enquiry.admissions.edit', $admission->id) }}" class="bg-white text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-lg transition-all duration-200 shadow-lg">
                    ✏ Edit Admission
                </a>
                <!-- <a href="{{ route('enquiry.admissions.track-attendence', $admission->id) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-lg">
                    📊 View Progress
                </a> -->
            </div>
        </div>
    </div>

    <!-- Admission Info -->
   <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Student Info -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Student Basic Info -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center mb-6">
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full p-3 mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900">Student Information</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Full Name</label>
                        <p class="text-lg font-medium text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $admission->student_name ?? ($admission->enquiry ? $admission->enquiry->first_name . ' ' . $admission->enquiry->surname : 'N/A') }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Class</label>
                        <p class="text-lg font-medium text-gray-900 bg-blue-50 px-3 py-2 rounded-lg border-l-4 border-blue-500">{{ $admission->class ?? ($admission->enquiry?->class ?? 'N/A') }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Email</label>
                        <p class="text-lg font-medium text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $admission->email ?? ($admission->enquiry?->email ?? 'N/A') }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Mobile</label>
                        <p class="text-lg font-medium text-gray-900 bg-green-50 px-3 py-2 rounded-lg border-l-4 border-green-500">{{ $admission->contact ?? ($admission->enquiry?->student_mobile ?? 'N/A') }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-sm font-semibold text-gray-600 uppercase tracking-wide">Date of Birth</label>
                        <p class="text-lg font-medium text-gray-900 bg-gray-50 px-3 py-2 rounded-lg">{{ $admission->date_of_birth ? \Carbon\Carbon::parse($admission->date_of_birth)->format('d M Y') : ($admission->enquiry?->dob ? \Carbon\Carbon::parse($admission->enquiry->dob)->format('d M Y') : 'N/A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Parent Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Parent Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Father Name</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->middle_name ?? ($admission->parent_name ?? 'N/A') }}</p>
                    </div>
                    <div>
                         <label class="text-sm font-medium text-gray-500">Address</label>
                        <p class="text-gray-900 font-medium">{{ $admission->address ?? ($admission->enquiry?->address ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Parent Mobile</label>
                        <p class="text-gray-900 font-medium">{{ $admission->contact ?? ($admission->enquiry?->parent_mobile ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">WhatsApp</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->whatsapp ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Academic Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Medium</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->medium ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Foundation</label>
                        <p class="text-gray-900 font-medium">
                            @if(is_array($admission->enquiry?->foundation))
                                {{ implode(', ', $admission->enquiry->foundation) }}
                            @else
                                {{ $admission->enquiry?->foundation ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Course</label>
                        <p class="text-gray-900 font-medium">
                            @if(is_array($admission->enquiry?->course))
                                {{ implode(', ', $admission->enquiry->course) }}
                            @else
                                {{ $admission->enquiry?->course ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Source</label>
                        <p class="text-gray-900 font-medium">
                            @if(is_array($admission->enquiry?->source))
                                {{ implode(', ', $admission->enquiry->source) }}
                            @else
                                {{ $admission->enquiry?->source ?? 'N/A' }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Additional Information</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sibling 1</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->sibling1 ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Sibling 2</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->sibling2 ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Reference 1</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->reference1 ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500">Reference 2</label>
                        <p class="text-gray-900 font-medium">{{ $admission->enquiry?->reference2 ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Remarks -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Remarks</h3>
                <p class="text-gray-900">{{ $admission->enquiry?->remarks ?? 'No remarks available' }}</p>
            </div>


            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Prents Feedback</h3>
                <p class="text-gray-900">{{ $admission->enquiry?->parent_feedback ?? 'No remarks available' }}</p>
            </div>
        </div>

        <!-- Right Column: Status & Actions -->
        <div class="space-y-6">
            
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                

                <div class="text-sm text-gray-600">
                    <p><strong>Enquiry No:</strong> {{ $admission->enquiry?->enquiry_no ?? 'N/A' }}</p>
                    <p><strong>Date:</strong> {{ $admission->enquiry?->date ? \Carbon\Carbon::parse($admission->enquiry->date)->format('d M Y') : 'N/A' }}</p>
                    <p><strong>Branch Code:</strong> {{ $admission->enquiry?->branch_code ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Profile Photo -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Student Profile Photo</h3>
                
                @php
                $studentUser = \App\Models\User::where('email', $admission->email)->first();
                $profilePhoto = $studentUser ? $studentUser->profile_photo : null;
                @endphp
                
                <div class="text-center">
                    <div class="relative inline-block mb-4">
                        @if($profilePhoto)
                            <img src="{{ asset('storage/' . $profilePhoto) }}" 
                                 alt="{{ $admission->student_name }}" 
                                 class="w-32 h-32 rounded-lg object-cover border-4 border-gray-200">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-3xl font-bold text-gray-500">{{ strtoupper(substr($admission->student_name, 0, 1)) }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <form action="{{ route('admin.upload.student.photo') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="student_email" value="{{ $admission->email }}">
                        
                        <div class="space-y-3">
                            <input type="file" name="profile_photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            
                            <button type="submit" 
                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                                Upload Photo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Fees Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Fees Information</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Fees:</span>
                        <span class="font-medium">₹{{ number_format($admission->total_fee ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Paid Amount:</span>
                        <span class="font-medium text-green-600">₹{{ number_format($admission->paid_amount ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t">
                        <span class="font-medium">Final Fees:</span>
                        <span class="font-bold text-lg">₹{{ number_format($admission->total_fee - $admission->paid_amount ?? 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="font-semibold text-lg mb-4 text-gray-900">Actions</h3>
                
                <div class="space-y-3">
                    @if($admission->status != 'follow-up')
                        <form method="POST" action="{{ route('enquiry.enquiries.followup', $admission->id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                                📞 Set Follow-up
                            </button>
                        </form>
                    @endif
                    
                    @if($admission->status != 'confirmed')
                        <form method="POST" action="{{ route('enquiry.enquiries.confirm', $admission->id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                ✓ Confirm Admission
                            </button>
                        </form>
                    @endif
                    
                    @if($admission->status != 'rejected')
                        <form method="POST" action="{{ route('enquiry.enquiries.reject', $admission->id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                                ✕ Reject
                            </button>
                        </form>
                    @endif
                </div>
            </div> -->
        </div>
    </div>
</div>

@endsection
