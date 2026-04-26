@extends('layouts.app')

@section('title', 'Calling Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Calling Management</h1>
            <p class="text-gray-500">Track and manage calling data efficiently</p>
        </div>
        <div class="flex gap-3 items-center">
            <a href="{{ route('calling.create') }}" 
               class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Entry
            </a>
            <button onclick="openUploadModal()" 
                    class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Upload Excel
            </button>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md ml-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
            <button type="button" class="ml-auto" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            {{ session('error') }}
            <button type="button" class="ml-auto" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Calls</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $callingData->count() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Positive Responses</p>
                    <p class="text-2xl font-bold text-green-600">{{ $callingData->where('response', 'positive')->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Negative Responses</p>
                    <p class="text-2xl font-bold text-red-600">{{ $callingData->where('response', 'negative')->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Follow-ups Required</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $callingData->where('follow_up', true)->count() }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('calling.index') }}" class="flex flex-col md:flex-row gap-4" id="filterForm">
            <div class="flex-1">
                <div class="relative">
                    <input type="text" name="search" placeholder="Search student name or mobile..." 
                           value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <select name="response" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">All Responses</option>
                <option value="positive" {{ request('response') == 'positive' ? 'selected' : '' }}>Positive</option>
                <option value="negative" {{ request('response') == 'negative' ? 'selected' : '' }}>Negative</option>
            </select>
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="all">All Status</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                <option value="not_received" {{ request('status') == 'not_received' ? 'selected' : '' }}>Not Received</option>
            </select>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Apply Filters
            </button>
            <a href="{{ route('calling.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                Reset
            </a>
        </form>
    </div>

    <!-- Calling Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Sr No</th>
                        <th class="text-left p-4 font-semibold text-gray-700">School</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Student</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Mobile</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Response</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Call Status</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Visit</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Follow-up</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Follow-up Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($callingData as $data)
                        <tr id="row-{{ $data->id }}" class="hover:bg-gray-50">
                            <td class="p-4 font-mono text-sm">
                                {{ ($callingData->currentPage() - 1) * $callingData->perPage() + $loop->iteration }}
                            </td>
                            <td class="p-4">
                                <div class="max-w-xs truncate" title="{{ $data->school_name }}">
                                    <input type="text" name="school_name" value="{{ $data->school_name }}" class="hidden edit-input w-full p-1 border rounded">
                                    <span class="view-text">{{ $data->school_name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="font-medium text-gray-900">
                                    <input type="text" name="student_name" value="{{ $data->student_name }}" class="hidden edit-input w-full p-1 border rounded">
                                    <span class="view-text">{{ $data->student_name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <input type="text" name="mobile_no" value="{{ $data->mobile_no }}" class="hidden edit-input w-full p-1 border rounded" maxlength="10" pattern="[6-9][0-9]{9}">
                                    <span class="view-text">{{ $data->mobile_no }}</span>
                                    @if($data->mobile_no && preg_match('/^[6-9][0-9]{9}$/', $data->mobile_no))
                                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                <!-- DEBUG: Response value = {{ $data->response }} -->
                                <select onchange="updateField({{ $data->id }}, 'response', this.value)" 
                                        class="w-full p-1 border rounded text-sm focus:ring-2 focus:ring-blue-400">
                                    <option value="positive" {{ $data->response == 'positive' ? 'selected' : '' }}>Positive</option>
                                    <option value="negative" {{ $data->response == 'negative' ? 'selected' : '' }}>Negative</option>
                                    <option value="pending" {{ $data->response == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </td>
                            <td class="p-4">
                                <!-- DEBUG: Call Status value = {{ $data->call_status }} -->
                                <select onchange="updateField({{ $data->id }}, 'call_status', this.value)" 
                                        class="w-full p-1 border rounded text-sm focus:ring-2 focus:ring-blue-400">
                                    <option value="Done" {{ $data->call_status == 'Done' ? 'selected' : '' }}>Done</option>
                                    <option value="Not Received" {{ $data->call_status == 'Not Received' ? 'selected' : '' }}>Not Received</option>
                                    <option value="Pending" {{ $data->call_status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center">
                                    <input type="checkbox"
                                           onchange="updateField({{ $data->id }}, 'visit_branch', this.checked ? 1 : 0)"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           {{ $data->visit_branch ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="p-4">
                                <div class="flex justify-center">
                                    <input type="checkbox"
                                           onchange="toggleFollowUpDateInline({{ $data->id }}, this.checked)"
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                           {{ $data->follow_up ? 'checked' : '' }}>
                                </div>
                            </td>
                            <td class="p-4">
                                <div id="follow_up_date_container_{{ $data->id }}" class="{{ $data->follow_up ? '' : 'hidden' }}">
                                    <input type="datetime-local" 
                                           id="follow_up_date_{{ $data->id }}"
                                           value="{{ $data->follow_up_date ? $data->follow_up_date->format('Y-m-d\TH:i') : '' }}"
                                           onchange="updateField({{ $data->id }}, 'follow_up_date', this.value)"
                                           class="w-full p-1 border rounded text-sm focus:ring-2 focus:ring-blue-400">
                                </div>
                                @if($data->follow_up_date)
                                    <span id="follow_up_date_display_{{ $data->id }}" class="text-sm text-gray-700">
                                        {{ $data->follow_up_date->format('M j, Y g:i A') }}
                                    </span>
                                @else
                                    <span id="follow_up_date_display_{{ $data->id }}" class="text-sm text-gray-400">No</span>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex gap-2">
                                    <form action="{{ route('calling.destroy', $data->id) }}" method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this student entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" 
                                                title="Delete Entry">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-lg font-medium">No calling data found</p>
                                    <p class="text-sm mt-1">Start by adding a new entry or uploading Excel data</p>
                                    <div class="mt-4 flex gap-3">
                                        <a href="{{ route('calling.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                            Add New Entry
                                        </a>
                                        <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                                                data-bs-toggle="modal" data-bs-target="#uploadModal">
                                            Upload Excel
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4 flex justify-between items-center bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="text-sm text-gray-500">
                Showing {{ $callingData->firstItem() }} to {{ $callingData->lastItem() }} 
                of {{ $callingData->total() }} results
            </div>

            <div>
                {{ $callingData->links() }}
            </div>
        </div>
    </div>

    <!-- Upload Excel Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-6 relative">
            <!-- Close Button -->
            <button onclick="closeUploadModal()" 
                class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl">
                ✕
            </button>

            <!-- Title -->
            <h2 class="text-xl font-semibold mb-4">Upload Excel File</h2>

            <!-- Form -->
            <form action="{{ route('calling.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Upload Box -->
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center mb-4">
                    <input type="file" name="excel_file" required class="mb-2">
                    <p class="text-sm text-gray-500">Upload .xlsx / .csv file</p>
                </div>

                <!-- Instructions -->
                <div class="text-sm text-gray-600 mb-4">
                    <p><b>Required Columns:</b></p>
                    <ul class="list-disc ml-5">
                        <li>School Name</li>
                        <li>Student Name</li>
                        <li>Mobile</li>
                    </ul>
                    <p class="text-xs text-blue-600 mt-2"><i>Note: Sr No will be auto-generated (1,2,3,4...)</i></p>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeUploadModal()" 
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                        Cancel
                    </button>

                    <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Upload Data
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
function openUploadModal() {
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
}

// Auto-close modal on success
document.addEventListener('DOMContentLoaded', function() {
    // Check if there's a success message and close modal
    @if (session('success'))
        closeUploadModal();
    @endif
});

// Update field function
function updateField(id, field, value) {
    fetch(`/calling/${id}/update`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            [field]: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            toast.textContent = 'Updated successfully!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Error updating field';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}
// Inline editing functions
function editRow(id) {
    let row = document.getElementById('row-' + id);
    
    // Hide view elements and show edit inputs
    row.querySelectorAll('.view-text').forEach(el => el.classList.add('hidden'));
    row.querySelectorAll('.edit-input').forEach(el => el.classList.remove('hidden'));
    
    // Toggle buttons
    row.querySelector('.edit-btn').classList.add('hidden');
    row.querySelector('.save-btn').classList.remove('hidden');
}

function saveRow(id) {
    let row = document.getElementById('row-' + id);
    
    // Collect form data
    let data = {
        school_name: row.querySelector('[name="school_name"]').value,
        student_name: row.querySelector('[name="student_name"]').value,
        mobile_no: row.querySelector('[name="mobile_no"]').value,
        response: row.querySelector('[name="response"]').value,
        call_status: row.querySelector('[name="call_status"]').value,
        visit_branch: row.querySelector('[name="visit_branch"]').checked,
        follow_up: row.querySelector('[name="follow_up"]').checked,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    };
    
    // Send update request
    fetch(`/calling/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload page to show updated data
            location.reload();
        } else {
            // Show error message
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
            toast.textContent = 'Error updating record';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Error updating record';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    });
}

// Direct field update function
function updateField(id, field, value) {
    console.log('updateField called:', { id, field, value });
    
    fetch('/calling/update-field/' + id, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            field: field,
            value: value
        })
    })
    .then(res => {
        console.log('Response status:', res.status);
        console.log('Response headers:', res.headers);
        return res.json();
    })
    .then(res => {
        console.log('Response data:', res);
        if(res.success){
            showToast("Updated successfully!");
        } else {
            showToast(res.error || "Update failed!", true);
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast("Update failed!", true);
    });
}

// Toggle inline follow-up date visibility
function toggleFollowUpDateInline(id, isChecked) {
    const container = document.getElementById('follow_up_date_container_' + id);
    const display = document.getElementById('follow_up_date_display_' + id);
    const dateInput = document.getElementById('follow_up_date_' + id);
    
    if (isChecked) {
        container.classList.remove('hidden');
        display.innerHTML = '';
    } else {
        container.classList.add('hidden');
        display.innerHTML = '<span class="text-sm text-gray-400">No</span>';
        // Clear follow-up date when unchecked
        updateField(id, 'follow_up_date', '');
    }
}

function showToast(message, isError = false) {
    let toast = document.createElement('div');
    toast.innerText = message;

    toast.style.position = 'fixed';
    toast.style.top = '20px';
    toast.style.right = '20px';
    toast.style.padding = '10px 20px';
    toast.style.borderRadius = '5px';
    toast.style.color = 'white';
    toast.style.zIndex = '9999';
    toast.style.background = isError ? '#dc3545' : '#28a745';

    document.body.appendChild(toast);

    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
