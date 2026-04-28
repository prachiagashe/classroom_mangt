@extends('layouts.app')

@section('title', 'Assignments')

@section('page-title', 'My Assignments')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Top Dashboard Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-950 tracking-tight">📚 Assignments</h1>
            <p class="text-gray-500 mt-2 text-base">View your coursework, keep track of deadlines, and upload your work.</p>
        </div>
        <div class="bg-blue-50 px-4 py-2.5 rounded-xl border border-blue-100 flex items-center gap-2.5 shadow-sm">
            <span class="text-sm font-semibold text-blue-700">Class Mapping:</span>
            <span class="bg-blue-600 text-white text-xs font-extrabold px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $studentClass ?? 'Unassigned' }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl shadow-sm">
            <div class="flex items-center gap-3 mb-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-sm">Action failed:</span>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700 pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Assignments Grid -->
    @if($assignments->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center shadow-sm max-w-xl mx-auto mt-12 flex flex-col items-center">
            <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-1">No Assignments Found</h3>
            <p class="text-gray-500 text-sm max-w-xs">You don't have any coursework assigned to your class currently.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($assignments as $assignment)
                @php
                    $isSubmitted = $assignment->status === 'Submitted';
                    $isChecked = $assignment->status === 'Checked';
                    $isOverdue = $assignment->status === 'Overdue';
                    $isPending = $assignment->status === 'Pending';
                @endphp
                
                <div class="bg-white rounded-2xl border border-gray-150 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 flex flex-col h-full relative">
                    
                    <!-- Top Status Strip -->
                    @if($isSubmitted || $isChecked)
                        <div class="h-2 bg-emerald-500"></div>
                    @elseif($isOverdue)
                        <div class="h-2 bg-rose-500"></div>
                    @else
                        <div class="h-2 bg-amber-500"></div>
                    @endif

                    <div class="p-6 flex-1 flex flex-col">
                        <!-- Subject & Status Badge -->
                        <div class="flex justify-between items-center mb-4">
                            <span class="bg-gray-100 text-gray-800 text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider">
                                {{ $assignment->subject }}
                            </span>

                            @if($isChecked)
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5 shadow-sm animate-pulse">
                                    <span class="w-1.5 h-1.5 bg-emerald-600 rounded-full"></span>
                                    Checked ({{ $assignment->marks }} Marks)
                                </span>
                            @elseif($isSubmitted)
                                <span class="bg-emerald-50 text-emerald-700 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-emerald-200 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                                    Submitted
                                </span>
                            @elseif($isOverdue)
                                <span class="bg-rose-50 text-rose-700 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-rose-200 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                    Overdue
                                </span>
                            @else
                                <span class="bg-amber-50 text-amber-700 text-xs font-extrabold px-3 py-1.5 rounded-xl border border-amber-200 flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                    Pending
                                </span>
                            @endif
                        </div>

                        <!-- Assignment Title -->
                        <h3 class="text-xl font-bold text-gray-900 leading-snug mb-2">{{ $assignment->title }}</h3>
                        
                        <!-- Teacher Info -->
                        <p class="text-sm text-gray-500 mb-4 flex items-center gap-2">
                            <span class="w-6 h-6 bg-blue-100 text-blue-700 font-bold text-xs rounded-full flex items-center justify-center">
                                {{ strtoupper(substr($assignment->teacher->name ?? 'T', 0, 1)) }}
                            </span>
                            By <span class="font-semibold text-gray-700">{{ $assignment->teacher->name ?? 'Class Instructor' }}</span>
                        </p>

                        <!-- Description (Truncated) -->
                        <p class="text-gray-600 text-sm leading-relaxed mb-6 flex-1">
                            {{ Str::limit($assignment->description, 120) }}
                        </p>

                        <!-- Due Date Metadata -->
                        <div class="border-t border-gray-100 pt-4 mt-auto flex flex-col gap-2">
                            <div class="flex items-center text-sm font-medium text-gray-700 gap-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Due: <span class="{{ $isOverdue ? 'text-rose-600' : 'text-gray-900' }}">{{ $assignment->due_date->format('M d, Y h:i A') }}</span></span>
                            </div>
                            @if($isSubmitted && $assignment->submission_date)
                                <div class="flex items-center text-xs font-medium text-gray-500 gap-2">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Uploaded: <span class="text-gray-700">{{ $assignment->submission_date->format('M d, Y') }}</span></span>
                                    @if($assignment->is_late)
                                        <span class="text-rose-600 font-extrabold text-[10px] uppercase bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">Late</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Footer Links -->
                    <div class="p-4 bg-gray-50 border-t border-gray-150 flex flex-wrap gap-2 justify-between items-center">
                        <!-- Download original Assignment -->
                        <a href="{{ asset('storage/' . $assignment->pdf_path) }}" 
                           target="_blank"
                           download
                           class="flex-1 bg-white hover:bg-gray-100 text-gray-800 text-center font-bold text-xs py-2.5 px-3 border border-gray-300 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </a>

                        <!-- Submission button -->
                        <button onclick="document.getElementById('file-{{ $assignment->id }}').click()"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center font-bold text-xs py-2.5 px-3 rounded-xl shadow-sm transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                            <span id="btn-text-{{ $assignment->id }}">{{ ($isSubmitted || $isChecked) ? 'Resubmit' : 'Submit' }}</span>
                        </button>
                    </div>

                    <!-- Hidden Form -->
                    <form id="form-{{ $assignment->id }}" class="hidden" enctype="multipart/form-data">
                        @csrf
                        <input type="file" 
                               id="file-{{ $assignment->id }}" 
                               name="attachment" 
                               accept=".pdf" 
                               onchange="submitAssignment('{{ $assignment->id }}')"
                               class="hidden">
                    </form>
                </div>
            @endforeach
        </div>
    @endif

</div>

<script>
    async function submitAssignment(assignmentId) {
        const fileInput = document.getElementById('file-' + assignmentId);
        const btnText = document.getElementById('btn-text-' + assignmentId);
        
        if (!fileInput.files || fileInput.files.length === 0) {
            return;
        }
        
        const file = fileInput.files[0];
        if (file.type !== 'application/pdf') {
            alert('Please upload a PDF file.');
            fileInput.value = ''; // Reset
            return;
        }
        
        // Show loading state
        const originalText = btnText.textContent;
        btnText.textContent = 'Uploading...';
        
        const formData = new FormData();
        formData.append('attachment', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        try {
            const response = await fetch(`/student/assignments/${assignmentId}/submit`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                alert(data.message || 'Assignment submitted successfully!');
                window.location.reload();
            } else {
                if (data.errors && data.errors.attachment) {
                    alert(data.errors.attachment[0]);
                } else {
                    alert(data.message || 'Submission failed.');
                }
                btnText.textContent = originalText;
                fileInput.value = ''; // Reset
            }
        } catch (error) {
            console.error('Error submitting assignment:', error);
            alert('An error occurred during submission.');
            btnText.textContent = originalText;
            fileInput.value = ''; // Reset
        }
    }
</script>
@endsection
