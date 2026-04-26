@extends('layouts.app')

@section('title', 'My Timetable')

@section('page-title', 'My Timetable')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">My Timetable</h1>
        <p class="text-gray-600">View your class schedule and weekly timetable.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif



    <!-- Class Info -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900">Class {{ $fullClass ?? ($studentClass ?? 'N/A') }}</h2>
                <p class="text-gray-600">Academic Year 2024-2025</p>
            </div>
            <!-- <button onclick="window.print()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Print Timetable
            </button> -->
        </div>
    </div>

    <!-- Period Timings -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Period Timings</h3>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4" id="period-timings">
            @if(isset($periodTimings) && count($periodTimings) > 0)
                @foreach($periodTimings as $periodNumber => $timing)
                    <div class="text-center">
                        <div class="text-sm text-gray-600 font-medium" id="period{{ $periodNumber }}-time">
                            {{ $timing->start_time ? $timing->start_time . ' - ' . $timing->end_time : 'Not set' }}
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center">
                    <div class="text-sm text-gray-600 font-medium" id="period1-time">Not set</div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600 font-medium" id="period2-time">Not set</div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600 font-medium" id="period3-time">Not set</div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600 font-medium" id="period4-time">Not set</div>
                </div>
                <div class="text-center">
                    <div class="text-sm text-gray-600 font-medium" id="period5-time">Not set</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Timetable Grid -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Schedule</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="border border-gray-200 px-4 py-3 text-left text-sm font-medium text-gray-900">Day / Period</th>
                        @php
                            $periodCount = (isset($periodTimings) && count($periodTimings) > 0) ? count($periodTimings) : 5;
                            for($i = 1; $i <= $periodCount; $i++):
                                $periodKey = 'period' . $i;
                                $timing = isset($periodTimings[$periodKey]) ? $periodTimings[$periodKey] : null;
                                $timeRange = ($timing && isset($timing->start_time) && isset($timing->end_time)) 
                                    ? $timing->start_time . ' - ' . $timing->end_time 
                                    : 'Period ' . $i;
                        @endphp
                        <th class="border border-gray-200 px-4 py-3 text-center text-sm font-medium text-gray-900">{{ $timeRange }}</th>
                        @php
                            endfor;
                        @endphp
                    </tr>
                </thead>
                <tbody class="bg-white" id="timetable-body">
                    @if(isset($timetable) && count($timetable) > 0)
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                            <tr>
                                <td class="border border-gray-200 px-4 py-3 font-medium text-gray-900">{{ ucfirst($day) }}</td>
                                @php
                                    $periodCount = (isset($periodTimings) && count($periodTimings) > 0) ? count($periodTimings) : 5;
                                    for($i = 1; $i <= $periodCount; $i++):
                                @endphp
                                <td class="border border-gray-200 px-3 py-3 text-center">
                                    @php
                                        $dayTimetable = $timetable->get($day)?->get($i);
                                        if($dayTimetable && $dayTimetable->isNotEmpty()):
                                            $entry = $dayTimetable->first();
                                            $subject = $entry->subject;
                                    else:
                                            $subject = null;
                                    endif;
                                @endphp
                                    @if($subject)
                                        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-2 py-1 rounded text-sm">
                                            <div class="font-semibold text-blue-900">{{ $subject->name }}</div>
                                            <div class="text-xs text-blue-600">
                                                @php
                                                    $initials = '';
                                                    if ($subject->teacher_name) {
                                                        $parts = explode(' ', $subject->teacher_name);
                                                        foreach ($parts as $part) {
                                                            $initials .= strtoupper(substr($part, 0, 1));
                                                        }
                                                    }
                                                    echo $initials;
                                                @endphp
                                            </div>
                                            <div class="text-xs text-blue-500 mt-1">
                                                @php
                                                    $periodKey = 'period' . $i;
                                                    $timing = isset($periodTimings[$periodKey]) ? $periodTimings[$periodKey] : null;
                                                    if ($timing && isset($timing->start_time) && isset($timing->end_time)) {
                                                        echo $timing->start_time . ' - ' . $timing->end_time;
                                                    }
                                                @endphp
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500">
                                            No subject
                                            @php
                                                $periodKey = 'period' . $i;
                                                $timing = isset($periodTimings[$periodKey]) ? $periodTimings[$periodKey] : null;
                                                if ($timing && isset($timing->start_time) && isset($timing->end_time)) {
                                                    echo '<br><span class="text-xs text-gray-400">' . $timing->start_time . ' - ' . $timing->end_time . '</span>';
                                                }
                                            @endphp
                                        </div>
                                    @endif
                                </td>
                                @php
                                    endfor;
                                @endphp
                            </tr>
                        @endforeach
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span>
                                    @if($studentClass)
                                        Timetable has not been published yet for Class {{ $studentClass }}. Please check back later.
                                    @else
                                        Your class information is not available. Please contact the administrator.
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </table>
        </div>
    </div>

    <script>
    // Function to refresh timetable display
function refreshTimetableDisplay() {
    // Reload the page to get the latest data from database
    window.location.reload();
}

// Function to check for timetable changes
function checkForTimetableChanges() {
    const studentClass = '{{ $studentClass ?? "" }}';
    if (!studentClass) {
        return;
    }
    
    // Fetch latest timetable data
    fetch(`/student/api/timetable/${studentClass}`)
        .then(response => response.json())
        .then(data => {
            // Compare with current page data and refresh if needed
            const currentData = JSON.stringify(data);
            if (lastKnownTimetableData && currentData !== lastKnownTimetableData) {
                console.log('Timetable change detected, refreshing display...');
                refreshTimetableDisplay();
                
                // Show update notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg z-50 shadow-lg animate-pulse';
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h1.586c0-1.103.897-2 2-2s2 .897-2 2-2 2 .897 2 2v5m-7 2v5h5v-2a2 2 0 00-2-2h-1a2 2 0 00-2 2v7a2 2 0 002 2h5a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        </svg>
                        Timetable updated successfully! Your {{ count($periodTimings) }}-period schedule is now live.
                    </div>
                `;
                document.body.appendChild(notification);
                
                // Remove notification after 4 seconds
                setTimeout(() => {
                    notification.remove();
                }, 4000);
            }
            lastKnownTimetableData = currentData;
        })
        .catch(error => {
            console.error('Error checking timetable updates:', error);
        });
}

// Load timetable data from database
document.addEventListener('DOMContentLoaded', function() {
    const studentClass = '{{ $studentClass ?? "" }}';
    if (!studentClass) {
        return;
    }
    
    // Store initial data
    fetch(`/student/api/timetable/${studentClass}`)
        .then(response => response.json())
        .then(data => {
            lastKnownTimetableData = JSON.stringify(data);
        })
        .catch(error => {
            console.error('Error loading initial timetable data:', error);
        });
    
    // Check for changes every 10 seconds
    setInterval(() => {
        checkForTimetableChanges();
    }, 10000);
    
    // Also check when page becomes visible again
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            checkForTimetableChanges();
        }
    });
});
</script>
@endsection
