@extends('layouts.app')
@section('title', 'BANSAL CLASS - Enquiry Management')
@section('content')

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

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">

    <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-blue-600 mb-2">Enquiries</h1>
                <p class="text-gray-500">Manage and track all student enquiries</p>
            </div>
            <div class="flex gap-3 items-center">
                 <a href="{{ route('admin.enquiry.form') }}"
                  class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-5 py-2.5 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Enquiry
                </a>
                <div class="bg-blue-600 p-4 rounded-xl shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

    <!-- Search Filter -->
    <div class="bg-white/80 backdrop-blur-sm p-4 rounded-2xl shadow-lg border border-white/20 mb-6 w-full">
    <div class="flex gap-4 items-center">
       <!-- Search Input -->
        <div class="flex-1 relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <input type="text"
                id="searchInput"
                placeholder="Search by student, parent name or contact..."
                class="w-full pl-10 border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none bg-white transition-shadow">
        </div>

        <!-- Status Filter Dropdown -->
        <div class="relative">
            <button onclick="toggleStatusDropdown()"
                class="flex items-center gap-2 border border-gray-200 px-5 py-3 rounded-xl bg-white hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 transition-colors shadow-sm font-medium text-gray-700 w-48 justify-between">

                <div class="flex items-center gap-2">
                    <!-- Filter Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="w-4 h-4 text-blue-500"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M3 4a1 1 0 011-1h16a1 1 0 011 1l-6 8v6l-4-2v-4L3 4z" />
                    </svg>

                    <span id="selectedStatus">All Status</span>
                </div>

                <!-- Arrow -->
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="w-4 h-4 text-gray-600"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M19 9l-7 7-7-7" />
                </svg>

            </button>

            <!-- Dropdown Menu -->
            <div id="statusDropdown"
                 class="hidden absolute right-0 mt-2 w-44 bg-white border rounded-xl shadow-lg z-50">

                @php
                    $statuses = [
                        'all' => 'All Status',
                        'new' => 'New',
                        'followup' => 'Follow-up',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected'
                    ];
                @endphp
                @foreach($statuses as $key => $label)
                    <div onclick="selectStatus('{{ $key }}','{{ $label }}')"
                         class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex justify-between items-center">

                        <span>{{ $label }}</span>

                        <span class="hidden text-blue-600" id="check-{{ $key }}">✓</span>

                    </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

<!-- Table -->
 <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <table class="crm-table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Parent</th>
                <th>Contact</th>
                <th>Class</th>
                <th>Source</th>
                <th class="text-center">Status</th>
                <th>Date</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($enquiries as $enquiry)
        <tr class="enquiry-row" 
            data-status="{{ $enquiry->status }}"
            data-student="{{ strtolower($enquiry->first_name . ' ' . $enquiry->surname) }}"
            data-parent="{{ strtolower($enquiry->middle_name . ' ' . $enquiry->surname) }}"
            data-contact="{{ $enquiry->parent_mobile }}">

            <!-- Student -->
            <td>
                <div class="flex items-center gap-3">
                    <div class="crm-avatar bg-gradient-to-br from-blue-400 to-purple-400">
                        {{ substr($enquiry->first_name, 0, 1) }}{{ substr($enquiry->surname, 0, 1) }}
                    </div>
                    <span class="font-bold">{{ ucfirst($enquiry->first_name) }} {{ ucfirst($enquiry->surname) }}</span>
                </div>
            </td>

            <!-- Parent -->
            <td>
                {{ ucfirst($enquiry->middle_name) }} {{ ucfirst($enquiry->surname) }}
            </td>

            <!-- Contact -->
            <td>
                {{ $enquiry->parent_mobile }}
            </td>

            <!-- Class -->
            <td>
                {{ $enquiry->class }}
            </td>

            <!-- Source -->
            <td>
                @php
                    $source = $enquiry->source;

                    if (is_string($source)) {
                        $decoded = json_decode($source, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $source = $decoded;
                        }
                    }
                @endphp

                <span class="secondary-text">{{ is_array($source) ? implode(', ', $source) : ($source ?? 'Direct') }}</span>
            </td>

            <!-- Status -->
           <td class="text-center">
                <span class="crm-badge
                    @if($enquiry->status === 'new') crm-badge-info
                    @elseif($enquiry->status === 'followup') crm-badge-warning
                    @elseif($enquiry->status === 'confirmed') crm-badge-success
                    @elseif($enquiry->status === 'rejected') crm-badge-danger
                    @else crm-badge-info @endif">
                    {{ ucfirst($enquiry->status) }}
                </span>
            </td>

            <!-- Date -->
            <td>
                <span class="secondary-text">{{ $enquiry->created_at->format('M d, Y') }}</span>
            </td>

            <!-- Actions -->
            <td class="text-center">
                <button onclick="toggleDropdown(event, {{ $enquiry->id }})"
                        class="p-2 rounded-full hover:bg-gray-100 transition text-gray-500 font-bold">
                    ⋮
                </button>

                <div id="dropdown-{{ $enquiry->id }}"
                     class="hidden fixed w-64 bg-white border rounded-xl shadow-xl z-[9999]">
                    <div class="py-2 text-sm text-gray-700">
                        <a href="{{ route('enquiry.enquiries.show', $enquiry->id) }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                            👁 <span>View Details</span>
                        </a>

                        <a href="{{ route('enquiry.enquiries.print', $enquiry->id) }}"
                          target="_blank"
                          class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100 transition">
                              🖨 <span>Download PDF</span>
                        </a>

                        <a href="#"
                           onclick="openFollowUpModal({{ $enquiry->id }},'{{ $enquiry->first_name }} {{ $enquiry->surname }}')"
                            class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                             📅 <span>Set Follow-up</span>
                        </a>

                        <a href="{{ route('enquiry.enquiries.history', $enquiry->id) }}"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-gray-100">
                            🔄 <span>Follow-up History</span>
                        </a>

                        @php
                            $parentName = trim($enquiry->middle_name . ' ' . $enquiry->surname);
                            $parentName = preg_replace('/\s+/', ' ', $parentName);
                            if (empty($parentName) || preg_match('/^[0-9\s]+$/', $parentName)) {
                                $parentName = 'Parent';
                            }
                            $waText = "Hello {$parentName},\n\nGreetings from Bansal Classes!\n\nThank you for your interest in our courses. We specialize in guiding students towards success in JEE/NEET and other competitive exams.\n\nOur academic counselor would be happy to assist you with:\n• Course details\n• Fee structure\n• Batch timings\n• Admission process\n\nPlease let us know a convenient time to connect, or feel free to call us directly.\n\nWe look forward to helping your child achieve their goals!";
                        @endphp
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $enquiry->whatsapp ?: $enquiry->parent_mobile) }}?text={{ urlencode($waText) }}"
                           target="_blank"
                           class="flex items-center gap-3 px-4 py-2 hover:bg-green-50 text-green-600">
                             <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.067 2.877 1.215 3.076.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                             <span>Send WhatsApp</span>
                        </a>

                        <div class="border-t my-1"></div>

                        <form action="{{ route('enquiry.enquiries.confirm', $enquiry->id) }}" method="POST" onsubmit="return handleConfirmSubmission(this, 'Confirm admission for {{ str_replace('\'', '\\\'', $enquiry->first_name) }}?')">
                           @csrf
                            <button type="submit"
                                class="flex items-center gap-3 px-4 py-2 text-green-600 hover:bg-green-50 w-full text-left">
                             ✔ <span class="btn-text">Confirm Admission</span>
                            </button>
                       </form>

                        <form action="{{ route('enquiry.enquiries.reject', $enquiry->id) }}" method="POST">
                           @csrf
                            <button type="submit"
                                  class="flex items-center gap-3 px-4 py-2 text-red-600 hover:bg-red-50 w-full text-left">
                            ✖ <span>Reject</span>
                            </button>
                       </form>
                   </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center py-12 text-gray-500">
                <div class="flex flex-col items-center gap-3">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-lg">No enquiries found</p>
                    <a href="{{ route('admin.enquiry.form') }}" class="text-blue-600 hover:underline">Add a new enquiry</a>
                </div>
            </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination Links Removed -->
</div>
</div>


<!-- ================= VIEW DETAILS MODAL ================= -->
<div id="enquiryModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
    <div class="bg-white w-[750px] rounded-2xl shadow-2xl p-8 relative">

        <!-- Close Icon -->
        <button onclick="closeEnquiryModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-semibold mb-8">Enquiry Details</h2>

        <div class="grid grid-cols-2 gap-8">

            <div>
                <p class="text-sm text-gray-500">Student Name</p>
                <p id="studentName" class="text-lg font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Parent Name</p>
                <p id="parentName" class="text-lg font-semibold"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Contact</p>
                <p id="parentMobile"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Student Mobile</p>
                <p id="studentMobile"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Class Applied</p>
                <p id="classApplied"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">School</p>
                <p id="schoolName"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Source</p>
                <p id="source"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Date</p>
                <p id="date"></p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span id="statusBadge"
                      class="inline-block px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-600">
                </span>
            </div>

        </div>

        <!-- Buttons -->
        <div class="mt-10 flex justify-end gap-4">

            <button onclick="closeEnquiryModal()"
                    class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-100">
                Close
            </button>
<!-- 
            <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                📅 Set Follow-up
            </button> -->


             <button id="modalFollowUpBtn"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                             📅 <span>Set Follow-up</span>
             </button>
        </div>

    </div>
</div>

<script>function openEnquiryModal(url) {

    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {

        // Student Name
        document.getElementById('studentName').innerText =
            (data.first_name ?? '') + ' ' + (data.surname ?? '');

        // Parent Name (fixing your ?? issue)
        document.getElementById('parentName').innerText =
            (data.middle_name ? data.middle_name + ' ' : '') +
            (data.surname ?? '-');

        // Mobiles
        document.getElementById('parentMobile').innerText =
            data.parent_mobile ?? '-';

        document.getElementById('studentMobile').innerText =
            data.student_mobile ?? '-';

        // Class
        document.getElementById('classApplied').innerText =
            data.class ?? '-';

        // School
        document.getElementById('schoolName').innerText =
            data.school_name ?? '-';

        // Source
        document.getElementById('source').innerText =
            data.source ?? '-';

        // Date (use created_at instead of data.date)
        document.getElementById('date').innerText =
            data.created_at
                ? new Date(data.created_at).toLocaleDateString()
                : '-';

        // Status (since not stored in DB)
        document.getElementById('statusBadge').innerText = 'New';
        
        // Dynamic Follow-up Button
        const followUpBtn = document.getElementById('modalFollowUpBtn');
        if (followUpBtn) {
            followUpBtn.setAttribute('onclick', `openFollowUpModal(${data.id}, '${(data.first_name ?? '')} ${(data.surname ?? '')}'.trim())`);
        }

        // Open Modal
        document.getElementById('enquiryModal').classList.remove('hidden');

    })
    .catch(error => console.error(error));
}

function closeEnquiryModal() {
    document.getElementById('enquiryModal').classList.add('hidden');
}

</script>
<!-- ================= End VIEW DETAILS MODAL and SCRIPT ================= -->


<!-- FOLLOW UP MODAL -->
<div id="followUpModal"
     class="hidden fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">

    <div class="bg-white w-[600px] rounded-2xl shadow-2xl p-8 relative">

        <button onclick="closeFollowUpModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
            ✕
        </button>

        <h2 class="text-2xl font-semibold mb-2">
            Set Follow-up Reminder
        </h2>

        <p id="followUpStudentName"
           class="text-gray-500 mb-6">
        </p>

     <form id="followUpForm">

    @csrf

    <input type="hidden" id="followUpEnquiryId" name="enquiry_id">

    <div class="mb-4">
        <label class="block text-sm mb-2">Follow-up Date</label>
        <input type="date" name="followup_date" required min="{{ date('Y-m-d') }}" max="2100-12-31"
               class="w-full border rounded-xl px-4 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-2">Follow-up Time</label>
        <input type="time" name="followup_time" required
               class="w-full border rounded-xl px-4 py-2">
    </div>

    <div class="mb-4">
        <label class="block text-sm mb-2">Follow-up Type</label>
        <select name="type" required
                class="w-full border rounded-xl px-4 py-2">
            <option value="">Select type</option>
            <option value="Call">Call</option>
            <option value="Meeting">Meeting</option>
            <option value="WhatsApp">WhatsApp</option>
        </select>
    </div>

    <div class="mb-6">
        <label class="block text-sm mb-2">Notes</label>
        <textarea name="notes"
                  class="w-full border rounded-xl px-4 py-2"
                  rows="3"></textarea>
    </div>

    <div class="flex justify-end gap-4">
        <button type="button"
                onclick="closeFollowUpModal()"
                class="px-5 py-2 border rounded-lg">
            Cancel
        </button>

        <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            📅 Set Follow-up
        </button>
    </div>

</form>


    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // OPEN MODAL
    window.openFollowUpModal = function (id) {
        const enquiryInput = document.getElementById('followUpEnquiryId');
        const modal = document.getElementById('followUpModal');

        if (!enquiryInput || !modal) {
            console.error('Follow up modal elements not found');
            return;
        }

        enquiryInput.value = id;
        modal.classList.remove('hidden');
    };

    // CLOSE MODAL
    window.closeFollowUpModal = function () {
        const modal = document.getElementById('followUpModal');
        if (modal) modal.classList.add('hidden');
    };

    // SUBMIT FORM
    const followUpForm = document.getElementById('followUpForm');

    if (!followUpForm) {
        console.error('FollowUp form not found');
        return;
    }

    followUpForm.addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        fetch("{{ route('enquiry.followups.store') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: formData
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeFollowUpModal();

                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            }
        })
        .catch(error => {
            console.error(error);
            alert(error.message || 'Something went wrong while saving follow up');
        });
    });

});
</script>


<!-- FOLLOW UP MODAL and SCRPT END HERE -->



<script>
function toggleDropdown(event, id) {
    event.stopPropagation();

    const dropdown = document.getElementById('dropdown-' + id);
    const btn = event.currentTarget;

    // Close others
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        if (el !== dropdown) {
            el.classList.add('hidden');
        }
    });

    if (dropdown.classList.contains('hidden')) {
        // Remove hidden to calculate height
        dropdown.classList.remove('hidden');

        // Reset previous styles
        dropdown.style.top = '';
        dropdown.style.left = '';
        dropdown.style.bottom = '';
        dropdown.style.right = '';

        const btnRect = btn.getBoundingClientRect();
        const dropdownHeight = dropdown.offsetHeight || 250;
        const dropdownWidth = dropdown.offsetWidth || 256; // 64 * 4 = 256px
        
        const spaceBelow = window.innerHeight - btnRect.bottom;
        const spaceAbove = btnRect.top;

        // Position Horizontal (align right edges)
        let leftPos = btnRect.right - dropdownWidth;
        if (leftPos < 10) leftPos = 10; // Prevent falling off left edge
        dropdown.style.left = `${leftPos}px`;

        // Smart Vertical Positioning
        // If there's no space below, but space above, drop UP
        if (spaceBelow < dropdownHeight + 20 && spaceAbove > dropdownHeight + 20) {
            dropdown.style.top = `${btnRect.top - dropdownHeight - 8}px`; // 8px margin
        } else {
            // Default drop DOWN
            dropdown.style.top = `${btnRect.bottom + 8}px`;
        }

    } else {
        dropdown.classList.add('hidden');
    }
}

document.addEventListener('click', function () {
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        el.classList.add('hidden');
    });
});
</script>

<script>
function toggleStatusDropdown() {
    const dropdown = document.getElementById('statusDropdown');
    dropdown.classList.toggle('hidden');
}

function selectStatus(key, label) {
    document.getElementById('selectedStatus').innerText = label;

    // hide dropdown
    document.getElementById('statusDropdown').classList.add('hidden');

    // optional: future filtering logic
    console.log('Selected status:', key);
}

// Close dropdown on outside click (consolidated)
document.addEventListener('click', function (e) {
    // Close action dropdowns
    document.querySelectorAll('[id^="dropdown-"]').forEach(el => {
        el.classList.add('hidden');
    });
    
    // Close status dropdown
    const button = e.target.closest('button');
    const dropdown = document.getElementById('statusDropdown');
    if (!button || !button.closest('.relative')) {
        dropdown.classList.add('hidden');
    }
});
</script>

<script>
// Search and Filter Functionality
let selectedStatus = 'all';

function toggleStatusDropdown() {
    const dropdown = document.getElementById('statusDropdown');
    dropdown.classList.toggle('hidden');
}

function selectStatus(status, label) {
    selectedStatus = status;
    
    // Update selected label
    document.getElementById('selectedStatus').textContent = label;
    
    // Hide all checkmarks
    document.querySelectorAll('[id^="check-"]').forEach(check => {
        check.classList.add('hidden');
    });
    
    // Show checkmark for selected status
    const selectedCheck = document.getElementById(`check-${status}`);
    if (selectedCheck) {
        selectedCheck.classList.remove('hidden');
    }
    
    // Close dropdown
    document.getElementById('statusDropdown').classList.add('hidden');
    
    // Apply filters
    filterEnquiries();
}

function filterEnquiries() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.enquiry-row');
    
    rows.forEach(row => {
        let status = row.dataset.status;
        let normalizedStatus = status;
        
        // Normalize status values - treat "followup" and "follow-up" as same
        if (status === 'followup' || status === 'follow-up') {
            normalizedStatus = 'followup';
        }
        
        let normalizedSelectedStatus = selectedStatus;
        if (selectedStatus === 'followup' || selectedStatus === 'follow-up') {
            normalizedSelectedStatus = 'followup';
        }
        
        const student = row.dataset.student;
        const parent = row.dataset.parent;
        const contact = row.dataset.contact;
        
        // Status filter
        const statusMatch = normalizedSelectedStatus === 'all' || normalizedStatus === normalizedSelectedStatus;
        
        // Search filter
        const searchMatch = searchTerm === '' || 
            student.includes(searchTerm) || 
            parent.includes(searchTerm) || 
            contact.includes(searchTerm) ||
            status.toLowerCase().includes(searchTerm); // Also search in status
        
        // Show/hide row based on combined filters
        if (statusMatch && searchMatch) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Search input event listener
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', filterEnquiries);
    }
});

// Click outside to close dropdown
document.addEventListener('click', function(e) {
    if (!e.target.closest('.relative')) {
        document.getElementById('statusDropdown').classList.add('hidden');
    }
});

function handleConfirmSubmission(form, msg) {
    if (!confirm(msg)) return false;
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
        if (btn.disabled) return false;
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        const textSpan = btn.querySelector('.btn-text') || btn;
        textSpan.innerHTML = `<svg class="animate-spin h-4 w-4 mr-2 text-green-600 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...`;
    }
    return true;
}
</script>


@endsection
