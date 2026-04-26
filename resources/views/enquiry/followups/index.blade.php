@extends('layouts.app')
@section('title', 'BANSAL CLASS - Follow-Up Schedule')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Follow-up Schedule</h1>
            <p class="text-gray-500">Manage and track your student follow-ups</p>
        </div>
        <div class="bg-blue-600 p-4 rounded-xl shadow-md">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- LEFT COLUMN -->
            <div class="space-y-6">

                <!-- Enhanced Calendar -->
                <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100">
                    <h3 class="font-semibold text-lg mb-4">📅 Calendar</h3>

                    <!-- Calendar Controls -->
                    <div class="flex items-center justify-between mb-4">
                        <button onclick="changeMonth(-1)" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <select id="monthSelect" onchange="updateCalendar()" class="bg-white border-gray-300 border rounded-lg px-3 py-1 text-sm font-medium focus:outline-none focus:border-blue-500">
                                <option value="0">January</option>
                                <option value="1">February</option>
                                <option value="2">March</option>
                                <option value="3">April</option>
                                <option value="4">May</option>
                                <option value="5">June</option>
                                <option value="6">July</option>
                                <option value="7">August</option>
                                <option value="8">September</option>
                                <option value="9">October</option>
                                <option value="10">November</option>
                                <option value="11">December</option>
                            </select>
                            
                            <select id="yearSelect" onchange="updateCalendar()" class="bg-white border-gray-300 border rounded-lg px-3 py-1 text-sm font-medium focus:outline-none focus:border-blue-500">
                                <!-- Years will be populated by JavaScript -->
                            </select>
                        </div>
                        
                        <button onclick="changeMonth(1)" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="grid grid-cols-7 gap-1 text-center text-xs font-medium mb-2">
                            <div class="text-gray-600">Sun</div>
                            <div class="text-gray-600">Mon</div>
                            <div class="text-gray-600">Tue</div>
                            <div class="text-gray-600">Wed</div>
                            <div class="text-gray-600">Thu</div>
                            <div class="text-gray-600">Fri</div>
                            <div class="text-gray-600">Sat</div>
                        </div>
                        <div id="calendarGrid" class="grid grid-cols-7 gap-1 text-sm">
                            <!-- Calendar days will be populated by JavaScript -->
                        </div>
                    </div>
                </div>

            <!-- Summary -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Follow-ups</h3>
                    <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-full text-xs font-medium">
                        {{ count($followUps) }}
                    </span>
                </div>

                <p class="text-sm text-gray-600">
                    {{ count($followUps) }} scheduled on selected date
                </p>
            </div>

        </div>


        <!-- RIGHT COLUMN -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Search -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <form method="GET">
                    <input type="text"
                           id="followUpSearchInput"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search follow-ups..."
                           class="w-full border rounded-lg p-2 focus:ring-2 focus:ring-blue-500">
                </form>
            </div>

            <!-- Date Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="font-semibold text-lg text-gray-900">
                        Follow-ups for {{ date('d M Y', strtotime($selectedDate)) }}
                    </h3>
                    @php
                        $todayDateStr = now()->toDateString();
                        $selDateStr = \Carbon\Carbon::parse($selectedDate)->toDateString();
                    @endphp
                    @if($selDateStr == $todayDateStr)
                        <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-md text-xs font-semibold">Today Follow-up</span>
                    @elseif($selDateStr > $todayDateStr)
                        <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded-md text-xs font-semibold">Upcoming Follow-up</span>
                    @else
                        <span class="bg-red-100 text-red-700 px-2 py-1 rounded-md text-xs font-semibold">Missed / Overdue Follow-up</span>
                    @endif
                </div>
            </div>

            <!-- Cards -->
            @forelse($followUps as $followUp)

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition follow-up-card" 
                     data-followup-id="{{ $followUp->id }}"
                     data-student="{{ strtolower($followUp->student_name) }}"
                     data-parent="{{ strtolower($followUp->enquiry ? $followUp->enquiry->middle_name . ' ' . $followUp->enquiry->surname : '') }}"
                     data-contact="{{ $followUp->enquiry->parent_mobile ?? '' }}"
                     data-class="{{ strtolower($followUp->enquiry->class ?? '') }}">

                    <div class="flex justify-between items-start gap-6">

                        <div class="flex-1">

                            <h4 class="font-semibold text-lg text-gray-900 mb-3">
                                {{ $followUp->student_name }}
                            </h4>

                            <div class="grid grid-cols-2 gap-y-2 text-sm text-gray-600 mb-4">

                                <p>
                                    Parent:
                                    {{ $followUp->enquiry
                                        ? ucwords(strtolower($followUp->enquiry->middle_name.' '.$followUp->enquiry->surname))
                                        : 'N/A'
                                    }}
                                </p>

                                <p>Contact: {{ $followUp->enquiry->parent_mobile ?? '-' }}</p>
                                <p>Class: {{ $followUp->enquiry->class ?? '-' }}</p>

                                <p>
                                    {{ \Carbon\Carbon::parse($followUp->followup_time)->format('h:i A') }}
                                    - {{ ucwords(strtolower($followUp->type)) }}
                                </p>

                                @if($followUp->next_followup_date)
                                <p class="next-followup-date">
                                    Next Follow-up: {{ \Carbon\Carbon::parse($followUp->next_followup_date)->format('d M Y - h:i A') }}
                                </p>
                                @endif

                                <p>
                                    Status:
                                    <span class="status-badge px-2 py-1 rounded-full text-xs font-medium
                                        {{ $followUp->enquiry->status == 'new' ? 'bg-blue-100 text-blue-600' : '' }}
                                        {{ $followUp->enquiry->status == 'follow-up' ? 'bg-yellow-100 text-yellow-600' : '' }}
                                        {{ $followUp->enquiry->status == 'confirmed' ? 'bg-green-100 text-green-600' : '' }}
                                        {{ $followUp->enquiry->status == 'rejected' ? 'bg-red-100 text-red-600' : '' }}">
                                        {{ ucfirst($followUp->enquiry->status) }}
                                    </span>
                                </p>

                            </div>

                            @if($followUp->notes)
                                <div class="follow-up-notes bg-gray-50 rounded-xl px-4 py-3 text-sm text-gray-700">
                                    {{ $followUp->notes }}
                                </div>
                            @endif

                        </div>


                        <!-- ACTIONS -->
                        <div class="flex flex-col gap-3 w-36">

                            <form method="POST"
                                  action="{{ route('enquiry.followups.markContacted', $followUp->id) }}"
                                  onsubmit="handleMarkContacted(event, {{ $followUp->id }})">
                                @csrf
                                <button class="border px-3 py-2 rounded-xl text-sm hover:bg-gray-50 w-full">
                                    Mark Contacted
                                </button>
                            </form>

                            <button onclick="showNoteModalSimple({{ $followUp->id }})"
                                    class="border px-3 py-2 rounded-xl text-sm hover:bg-gray-50 w-full">
                                    Note
                            </button>

                            <button onclick="showRescheduleModalSimple({{ $followUp->id }})"
                                    class="border px-3 py-2 rounded-xl text-sm hover:bg-gray-50 w-full">
                                    Reschedule
                            </button>

                            <button onclick="showConfirmModalSimple({{ $followUp->id }})"
                            class="bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold 
                             hover:bg-blue-700 transition w-full">
                        Confirm
                    </button>

                        </div>

                    </div>
                </div>

            @empty

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
                    <p class="text-gray-500">No follow-ups scheduled</p>
                </div>

            @endforelse

        </div>

    </div>

@endsection

<!-- Confirmation Modal -->
<x-modal id="confirmModal" title="Confirm Admission" :show="false" maxWidth="md">
    <form id="confirmAdmissionForm" class="space-y-6">
        <!-- Student Info -->
        <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Student Name</p>
                    <p class="font-semibold text-gray-900" id="modalStudentName">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Class</p>
                    <p class="font-semibold text-gray-900" id="modalClass">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Parent Name</p>
                    <p class="font-semibold text-gray-900" id="modalParentName">-</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Contact</p>
                    <p class="font-semibold text-gray-900" id="modalContact">-</p>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
            <button type="button" onclick="closeModal('confirmModal')" 
                    class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium order-2 sm:order-1">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all font-medium order-1 sm:order-2 shadow-lg">
                Confirm Admission
            </button>
        </div>
    </form>
</x-modal>
                </button>
            </form>

<!-- Reschedule Modal -->
<x-modal id="rescheduleModal" title="Reschedule Follow-up" :show="false" maxWidth="md">
    <form id="rescheduleForm" class="space-y-6">
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-amber-900 mb-2">Reschedule Follow-up</h3>
                    <p class="text-amber-700">Select a new date and time for the follow-up.</p>
                </div>
            </div>
        </div>

        <!-- Form Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Date Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Date <span class="text-red-500">*</span></label>
                <input type="date" id="rescheduleDate" required 
                       min="{{ now()->format('Y-m-d') }}" max="2100-12-31"
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
            </div>

            <!-- Time Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">New Time <span class="text-red-500">*</span></label>
                <input type="time" id="rescheduleTime" required 
                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Rescheduling</label>
                <textarea id="rescheduleNotes" rows="3" 
                          placeholder="Optional: Provide reason for rescheduling..."
                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"></textarea>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
            <button type="button" onclick="closeModal('rescheduleModal')" 
                    class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium order-2 sm:order-1">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-all font-medium order-1 sm:order-2 shadow-lg">
                Reschedule
            </button>
        </div>
    </form>
</x-modal>

<!-- Note Modal -->
<x-modal id="noteModal" title="Add Note" :show="false" maxWidth="md">
    <form id="noteForm" class="space-y-6">
        <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v10a2 2 0 002 2h5a2 2 0 002-2V7a2 2 0 00-2-2zm0 0a1 1 0 110 2h4a1 1 0 110-2v-4a1 1 0 110-2H7a1 1 0 110-2v4zm0 0a1 1 0 110 2h4a1 1 0 110-2v-4a1 1 0 110-2H7a1 1 0 110-2v4z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-green-900 mb-2">Add Note</h3>
                    <p class="text-green-700">Add a note about this enquiry or follow-up.</p>
                </div>
            </div>
        </div>

        <!-- Note Content -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Note <span class="text-red-500">*</span></label>
            <textarea id="noteContent" required rows="4" 
                      placeholder="Enter your note here..."
                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors resize-none"></textarea>
        </div>

        <!-- Note Type -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Note Type</label>
            <select id="noteType" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                <option value="">Select Type</option>
                <option value="general">General</option>
                <option value="important">Important</option>
                <option value="followup">Follow-up Required</option>
                <option value="information">Additional Information</option>
            </select>
        </div>

        <!-- Form Actions -->
        <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6">
            <button type="button" onclick="closeModal('noteModal')" 
                    class="w-full sm:w-auto px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all font-medium order-2 sm:order-1">
                Cancel
            </button>
            <button type="submit" 
                    class="w-full sm:w-auto px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-medium order-1 sm:order-2 shadow-lg">
                Add Note
            </button>
        </div>
    </form>
</x-modal>

<!-- Simple Note Modal -->
<div id="noteModalSimple" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px;">Add Note</h3>
        <textarea id="noteTextSimple" style="width: 100%; height: 100px; margin-bottom: 20px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" placeholder="Enter your note..."></textarea>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideModal('noteModalSimple')" style="padding: 10px 20px; border: 1px solid #ccc; background: #f5f5f5; border-radius: 5px; cursor: pointer;">Cancel</button>
            <button onclick="saveNoteSimple()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">Save Note</button>
        </div>
    </div>
</div>

<!-- Simple Reschedule Modal -->
<div id="rescheduleModalSimple" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px;">Reschedule Follow-up</h3>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">New Date:</label>
            <input type="date" id="rescheduleDateSimple" min="{{ date('Y-m-d') }}" max="2100-12-31" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px;">New Time:</label>
            <input type="time" id="rescheduleTimeSimple" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
        </div>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideModal('rescheduleModalSimple')" style="padding: 10px 20px; border: 1px solid #ccc; background: #f5f5f5; border-radius: 5px; cursor: pointer;">Cancel</button>
            <button onclick="saveRescheduleSimple()" style="padding: 10px 20px; background: #ffc107; color: white; border: none; border-radius: 5px; cursor: pointer;">Reschedule</button>
        </div>
    </div>
</div>

<!-- Simple Confirm Modal -->
<div id="confirmModalSimple" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; padding: 30px; border-radius: 10px; max-width: 500px; width: 90%;">
        <h3 style="margin-bottom: 20px;">Confirm Admission</h3>
        <p style="margin-bottom: 20px;">Are you sure you want to confirm this admission?</p>
        <div style="display: flex; gap: 10px; justify-content: flex-end;">
            <button onclick="hideModal('confirmModalSimple')" style="padding: 10px 20px; border: 1px solid #ccc; background: #f5f5f5; border-radius: 5px; cursor: pointer;">Cancel</button>
            <button onclick="confirmAdmissionSimple()" style="padding: 10px 20px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Confirm</button>
        </div>
    </div>
</div>

<script>
let modalFollowUpId = null;
let currentFollowUpId = null;

// Modal functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'flex';
        // Set currentFollowUpId for form submissions
        currentFollowUpId = modalFollowUpId;
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
    modalFollowUpId = null;
    currentFollowUpId = null;
}

function openConfirmModal(studentName, className, parentName, contact, followUpId) {
    modalFollowUpId = followUpId;
    
    // Set modal data
    document.getElementById('modalStudentName').textContent = studentName;
    document.getElementById('modalClass').textContent = className;
    document.getElementById('modalParentName').textContent = parentName;
    document.getElementById('modalContact').textContent = contact;
    
    openModal('confirmModal');
}

function closeConfirmModal() {
    closeModal('confirmModal');
    modalFollowUpId = null;
}

function openRescheduleModal(followUpId) {
    modalFollowUpId = followUpId;
    openModal('rescheduleModal');
}

function closeRescheduleModal() {
    closeModal('rescheduleModal');
    modalFollowUpId = null;
}

function openNoteModal(followUpId) {
    modalFollowUpId = followUpId;
    openModal('noteModal');
}

function closeNoteModal() {
    closeModal('noteModal');
    modalFollowUpId = null;
}

// Simple modal functions
function showNoteModalSimple(id) {
    currentFollowUpId = id;
    document.getElementById('noteModalSimple').style.display = 'flex';
    console.log('Note modal opened for ID:', id);
}

function showRescheduleModalSimple(id) {
    currentFollowUpId = id;
    document.getElementById('rescheduleModalSimple').style.display = 'flex';
    console.log('Reschedule modal opened for ID:', id);
}

function showConfirmModalSimple(id) {
    currentFollowUpId = id;
    document.getElementById('confirmModalSimple').style.display = 'flex';
    console.log('Confirm modal opened for ID:', id);
}

function hideModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    currentFollowUpId = null;
}

// Action functions
function saveNoteSimple() {
    const noteText = document.getElementById('noteTextSimple').value;
    if (!noteText.trim()) {
        alert('Please enter a note');
        return;
    }
    
    if (!currentFollowUpId) {
        alert('Error: No follow-up selected');
        return;
    }
    
    // Send AJAX request to backend
    fetch('/enquiry/followups/' + currentFollowUpId + '/add-note', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: 'notes=' + encodeURIComponent(noteText) + '&followup_id=' + currentFollowUpId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Note saved successfully!');
            hideModal('noteModalSimple');
            document.getElementById('noteTextSimple').value = '';
            location.reload(); // Refresh UI to show updated data
        } else {
            alert('Error: ' + (data.message || 'Failed to save note'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: Failed to save note');
    });
}

function saveRescheduleSimple() {
    const date = document.getElementById('rescheduleDateSimple').value;
    const time = document.getElementById('rescheduleTimeSimple').value;
    
    if (!date || !time) {
        alert('Please select both date and time');
        return;
    }
    
    if (!currentFollowUpId) {
        alert('Error: No follow-up selected');
        return;
    }
    
    // Send AJAX request to backend
    fetch('/enquiry/followups/' + currentFollowUpId + '/reschedule', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: 'next_followup_date=' + encodeURIComponent(date) + '&followup_time=' + encodeURIComponent(time) + '&followup_id=' + currentFollowUpId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Follow-up rescheduled successfully!');
            hideModal('rescheduleModalSimple');
            document.getElementById('rescheduleDateSimple').value = '';
            document.getElementById('rescheduleTimeSimple').value = '';
            location.reload(); // Refresh UI to show updated data
        } else {
            alert('Error: ' + (data.message || 'Failed to reschedule'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: Failed to reschedule');
    });
}

function confirmAdmissionSimple() {
    if (!currentFollowUpId) {
        alert('Error: No follow-up selected');
        return;
    }

    // Disable buttons and show processing
    const btnConfirm = document.querySelector('#confirmModalSimple button[onclick="confirmAdmissionSimple()"]');
    const btnCancel = document.querySelector('#confirmModalSimple button[onclick="hideModal(\'confirmModalSimple\')"]');
    if (btnConfirm) {
        btnConfirm.disabled = true;
        btnConfirm.style.opacity = '0.7';
        btnConfirm.style.cursor = 'not-allowed';
        const spinSvg = `<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
        btnConfirm.innerHTML = spinSvg + ' Processing...';
    }
    if (btnCancel) {
        btnCancel.disabled = true;
        btnCancel.style.opacity = '0.7';
    }
    
    // Send AJAX request to backend
    fetch('/enquiry/followups/' + currentFollowUpId + '/confirm', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: 'followup_id=' + currentFollowUpId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Admission confirmed successfully! Roll No: ' + (data.roll_number || ''));
            hideModal('confirmModalSimple');
            location.reload(); // Refresh UI to show updated data
        } else {
            alert('Error: ' + (data.message || 'Failed to confirm admission'));
            // Re-enable
            if (btnConfirm) {
                btnConfirm.disabled = false;
                btnConfirm.style.opacity = '1';
                btnConfirm.style.cursor = 'pointer';
                btnConfirm.innerHTML = 'Confirm';
            }
            if (btnCancel) {
                btnCancel.disabled = false;
                btnCancel.style.opacity = '1';
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error: Failed to confirm admission');
        // Re-enable
        if (btnConfirm) {
            btnConfirm.disabled = false;
            btnConfirm.style.opacity = '1';
            btnConfirm.style.cursor = 'pointer';
            btnConfirm.innerHTML = 'Confirm';
        }
        if (btnCancel) {
            btnCancel.disabled = false;
            btnCancel.style.opacity = '1';
        }
    });
}

// Form event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Note form submission
    const noteForm = document.getElementById('noteForm');
    if (noteForm) {
        noteForm.addEventListener('submit', handleAddNote);
    }
    
    // Reschedule form submission
    const rescheduleForm = document.getElementById('rescheduleForm');
    if (rescheduleForm) {
        rescheduleForm.addEventListener('submit', handleReschedule);
    }
    
    // Confirm form submission
    const confirmForm = document.getElementById('confirmAdmissionForm');
    if (confirmForm) {
        confirmForm.addEventListener('submit', handleConfirm);
    }
});

function handleMarkContacted(event, followUpId) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/enquiry/followups/${followUpId}/mark-contacted`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update status badge
            const statusElement = document.querySelector(`[data-followup-id="${followUpId}"] .status-badge`);
            if (statusElement) {
                statusElement.textContent = 'Contacted';
                statusElement.className = 'bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs font-semibold';
            }
            
            // Show success message
            showNotification('Marked as contacted successfully!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error marking as contacted', 'error');
    });
}

function handleReschedule(event) {
    event.preventDefault();
    
    console.log('Reschedule submitted. currentFollowUpId:', currentFollowUpId);
    
    if (!currentFollowUpId) {
        showNotification('Follow-up ID not found', 'error');
        return;
    }
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Get CSRF token safely
    const csrfToken = formData.get('_token') || 
                     document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                     '';
    
    if (!csrfToken) {
        showNotification('Security token missing', 'error');
        return;
    }
    
    console.log('Submitting reschedule to:', `/enquiry/followups/${currentFollowUpId}/reschedule`);
    
    fetch(`/enquiry/followups/${currentFollowUpId}/reschedule`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Reschedule response:', data);
        if (data.success) {
            closeRescheduleModal();
            showNotification('Follow-up rescheduled successfully!', 'success');
            // Refresh page to show updated follow-up
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showNotification(data.message || 'Reschedule failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error rescheduling follow-up', 'error');
    });
}

function handleAddNote(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(`/enquiry/followups/${currentFollowUpId}/add-note`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI with new note
            updateFollowUpCard(currentFollowUpId, data);
            closeNoteModal();
            showNotification('Note added successfully!', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding note', 'error');
    });
}

function handleConfirm(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': formData.get('_token')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeConfirmModal();
            showNotification('Admission confirmed successfully!', 'success');
            // Redirect after a short delay
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error confirming admission', 'error');
    });
}

function updateFollowUpCard(followUpId, data) {
    // Find the follow-up card
    const card = document.querySelector(`[data-followup-id="${followUpId}"]`);
    if (!card) return;
    
    // Update next follow-up date if exists
    const nextDateElement = card.querySelector('.next-followup-date');
    if (nextDateElement && data.next_followup_date) {
        const date = new Date(data.next_followup_date);
        nextDateElement.textContent = `Next Follow-up: ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })} – ${date.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}`;
    }
    
    // Update notes display
    const notesElement = card.querySelector('.follow-up-notes');
    if (notesElement && data.notes) {
        notesElement.textContent = data.notes;
        notesElement.style.display = 'block';
    }
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 300);
    }, 3000);
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeConfirmModal();
        closeRescheduleModal();
        closeNoteModal();
        hideModal('noteModalSimple');
        hideModal('rescheduleModalSimple');
        hideModal('confirmModalSimple');
    }
});

// Close modal on background click
document.getElementById('confirmModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeConfirmModal();
    }
});

document.getElementById('rescheduleModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeRescheduleModal();
    }
});

document.getElementById('noteModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeNoteModal();
    }
});

document.getElementById('noteModalSimple').addEventListener('click', function(event) {
    if (event.target === this) {
        hideModal('noteModalSimple');
    }
});

document.getElementById('rescheduleModalSimple').addEventListener('click', function(event) {
    if (event.target === this) {
        hideModal('rescheduleModalSimple');
    }
});

document.getElementById('confirmModalSimple').addEventListener('click', function(event) {
    if (event.target === this) {
        hideModal('confirmModalSimple');
    }
});

// Follow-up Search Functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('followUpSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterFollowUps);
    }
});

function filterFollowUps() {
    const searchTerm = document.getElementById('followUpSearchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.follow-up-card');
    
    cards.forEach(card => {
        const student = card.dataset.student || '';
        const parent = card.dataset.parent || '';
        const contact = card.dataset.contact || '';
        const className = card.dataset.class || '';
        
        // Search in all fields
        const isMatch = searchTerm === '' || 
            student.includes(searchTerm) || 
            parent.includes(searchTerm) || 
            contact.includes(searchTerm) ||
            className.includes(searchTerm);
        
        // Show/hide card
        if (isMatch) {
            card.style.display = '';
        } else {
            card.style.display = 'none';
        }
    });
}

// Calendar functionality
const selectedDateStr = "{{ \Carbon\Carbon::parse($selectedDate)->toDateString() }}";
const followUpDates = @json($followUpDates ?? []);
const todayStr = "{{ now()->toDateString() }}";

// Start calendar on the month of the selected date by default
let currentCalendarDate = new Date(selectedDateStr);
let currentMonth = currentCalendarDate.getMonth();
let currentYear = currentCalendarDate.getFullYear();

function initCalendar() {
    const yearSelect = document.getElementById('yearSelect');
    yearSelect.innerHTML = '';
    for (let year = currentYear - 10; year <= currentYear + 10; year++) {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        if (year === currentYear) {
            option.selected = true;
        }
        yearSelect.appendChild(option);
    }

    document.getElementById('monthSelect').value = currentMonth;
    updateCalendar();
}

function updateCalendar() {
    const monthSelect = document.getElementById('monthSelect');
    const yearSelect = document.getElementById('yearSelect');
    
    currentMonth = parseInt(monthSelect.value);
    currentYear = parseInt(yearSelect.value);
    
    generateCalendar(currentMonth, currentYear);
}

function changeMonth(direction) {
    currentMonth += direction;
    
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    } else if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    
    document.getElementById('monthSelect').value = currentMonth;
    document.getElementById('yearSelect').value = currentYear;
    
    generateCalendar(currentMonth, currentYear);
}

function generateCalendar(month, year) {
    const calendarGrid = document.getElementById('calendarGrid');
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    
    calendarGrid.innerHTML = '';
    
    for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement('div');
        emptyCell.className = 'p-2';
        calendarGrid.appendChild(emptyCell);
    }
    
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
        const dayCell = document.createElement('div');
        
        // Base styling for the wrapper cell
        dayCell.className = 'relative p-1 text-center rounded-lg transition-all h-10 w-full flex items-center justify-center';
        
        let linkClass = 'block w-full h-full flex items-center justify-center font-medium rounded-lg text-sm';
        
        // Conditional highlighting logic
        if (dateStr === todayStr) {
            // Strong primary color for Today
            linkClass += ' bg-blue-600 text-white shadow-md ring-2 ring-blue-600 ring-offset-1 hover:bg-blue-700';
        } else if (dateStr === selectedDateStr) {
            // Lighter highlight/border for selected date (different from today)
            linkClass += ' bg-blue-50 border-2 border-blue-400 text-blue-700 font-bold shadow-sm';
        } else {
            // Normal date styling
            linkClass += ' text-gray-700 hover:bg-gray-100 border border-transparent';
        }
        
        dayCell.innerHTML = `<a href="?date=${dateStr}" class="${linkClass}">${day}</a>`;
        
        // Overlay indicators for follow-ups
        if (followUpDates.includes(dateStr)) {
            const dot = document.createElement('div');
            // Positioning the dot correctly
            dot.className = 'absolute bottom-1.5 left-1/2 transform -translate-x-1/2 w-1.5 h-1.5 rounded-full';
            if (dateStr < todayStr) {
                dot.className += ' bg-red-500'; // Missing/Overdue
            } else if (dateStr === todayStr) {
                dot.className += ' bg-blue-200'; // Contrast against blue-600 parent
            } else {
                dot.className += ' bg-yellow-400'; // Upcoming
            }
            dayCell.querySelector('a').appendChild(dot);
            
            // Adjust padding to make room for dot
            dayCell.querySelector('a').classList.remove('items-center');
            dayCell.querySelector('a').classList.add('pt-1', 'items-start');
        }
        
        calendarGrid.appendChild(dayCell);
    }
}

// Initialize calendar when page loads
document.addEventListener('DOMContentLoaded', initCalendar);
</script>
