<!-- Modal Content -->
<div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-hidden">
    <!-- Modal Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Attendance - {{ $date }}</h1>
                <p class="text-blue-100 mt-1">Teacher attendance status for selected date</p>
            </div>
            <button onclick="closeAttendanceModal()" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="bg-gray-50 border-b border-gray-200 p-4">
        <div class="flex gap-6 text-sm">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-gray-700">Present: {{ collect($attendanceData)->filter(fn($status) => $status === 'present')->count() }}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="text-gray-700">Absent: {{ collect($attendanceData)->filter(fn($status) => $status === 'absent')->count() }}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                <span class="text-gray-700">Not Marked: {{ collect($attendanceData)->filter(fn($status) => $status === 'not_marked')->count() }}</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                <span class="text-gray-700">Total: {{ count($attendanceData) }}</span>
            </div>
        </div>
    </div>

    <!-- Teacher List -->
    <div class="p-6 overflow-y-auto max-h-[60vh]">
        <div class="space-y-3">
            @foreach($teacherNames as $teacher)
                @php
                    $status = $attendanceData[$teacher] ?? 'not_marked';
                    $statusColor = match($status) {
                        'present' => 'green',
                        'absent' => 'red',
                        default => 'gray'
                    };
                    $statusText = match($status) {
                        'present' => 'Present',
                        'absent' => 'Absent',
                        default => 'Not Marked'
                    };
                    $statusIcon = match($status) {
                        'present' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>',
                        'absent' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>',
                        default => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                    };
                @endphp
                
                <div class="flex items-center justify-between p-4 bg-{{ $statusColor }}-50 border border-{{ $statusColor }}-200 rounded-lg hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-{{ $statusColor }}-500 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                            {{ substr($teacher, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $teacher }}</h3>
                            <p class="text-sm text-gray-600">Teacher</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <span class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $statusIcon !!}
                            </svg>
                            {{ $statusText }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <div class="bg-gray-50 border-t border-gray-200 p-4">
        <div class="flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Last updated: {{ now()->format('d M Y, H:i') }}
            </div>
            <button onclick="closeAttendanceModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                Close
            </button>
        </div>
    </div>
</div>
