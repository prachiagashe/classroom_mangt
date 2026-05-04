@extends('layouts.app')

@section('title', 'Class ' . $className . ' Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Class {{ $className }}</h1>
            <p class="text-sm text-gray-600 mt-1">Manage assignments and track student progress.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openAssignmentModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-xl hover:bg-indigo-700 font-bold text-xs uppercase tracking-wider shadow-sm flex items-center gap-1.5 transition-all">
                ➕ Add Assignment
            </button>
            <a href="{{ route('teacher.assignments.assignment') }}" class="bg-blue-600 text-white px-4 py-2 rounded-xl hover:bg-blue-700 font-bold text-xs uppercase tracking-wider shadow-sm flex items-center gap-1.5 transition-all">
                📅 Add Schedule
            </a>
            <a href="{{ route('teacher.dashboard') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-xl hover:bg-gray-300 font-bold text-xs uppercase tracking-wider flex items-center transition-all">
                ← Back
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex gap-6" aria-label="Tabs">
            <button onclick="switchTab('assignments')" id="tab-assignments" class="tab-btn border-b-2 border-blue-600 text-blue-600 px-1 py-4 text-sm font-bold uppercase tracking-wider">
                Assignments
            </button>
            <button onclick="switchTab('submissions')" id="tab-submissions" class="tab-btn border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 px-1 py-4 text-sm font-bold uppercase tracking-wider">
                Students & Submissions
            </button>
        </nav>
    </div>

    <!-- SECTION: Assignments Tab -->
    <div id="section-assignments" class="tab-content">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Published Assignments</h2>
                <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">{{ $assignments->count() }} Total</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($assignments as $assignment)
                    <div class="p-4 flex justify-between items-center hover:bg-gray-50 transition-colors">
                        <div>
                            <span class="font-bold text-gray-900 text-sm block">{{ $assignment->title }}</span>
                            <span class="text-xs text-gray-500 block mt-0.5">{{ $assignment->subject }} • Due {{ \Carbon\Carbon::parse($assignment->due_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex items-center gap-6">
                            <!-- Submission Count Badge -->
                            <div class="text-right">
                                <span class="text-sm font-black text-gray-800 block">{{ $assignment->submissions->count() }}/{{ $students->count() }}</span>
                                <span class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400">Submissions</span>
                            </div>
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <button onclick="switchTab('submissions')" class="bg-blue-50 hover:bg-blue-100 text-blue-600 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                    View Submissions
                                </button>
                                <span class="text-xs text-gray-300">|</span>
                                <form action="{{ route('teacher.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-700 font-bold text-xs">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-500">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-gray-600">No assignments published yet.</p>
                        <p class="text-xs text-gray-400 mt-1">Click "Add Assignment" to get started.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECTION: Submissions Tab -->
    <div id="section-submissions" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Student Roster & Progress</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-200 text-[10px] font-extrabold uppercase tracking-wider text-gray-500 bg-gray-50/50">
                            <th class="p-4">Student Name</th>
                            <th class="p-4">Roll No</th>
                            <th class="p-4">Latest Status</th>
                            <th class="p-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @forelse($students as $student)
                            @php
                                // Find latest submission for any assignment in this class
                                $latestSub = null;
                                foreach($assignments as $assign) {
                                    $sub = $assign->submissions->where('student_id', $student->user_id)->first();
                                    if ($sub) {
                                        $latestSub = $sub;
                                        break; // Just get the first one for status demo
                                    }
                                }
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="p-4 font-bold text-gray-900">{{ $student->name }}</td>
                                <td class="p-4 text-gray-600">{{ $student->roll_no ?? 'N/A' }}</td>
                                <td class="p-4">
                                    @if($latestSub)
                                        @if($latestSub->status === 'checked')
                                            <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-0.5 rounded-full uppercase">Checked</span>
                                        @else
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-0.5 rounded-full uppercase">Submitted</span>
                                        @endif
                                    @else
                                        <span class="bg-gray-100 text-gray-400 text-xs font-bold px-2 py-0.5 rounded-full uppercase">Pending</span>
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    @if($latestSub)
                                        <div class="flex items-center gap-2 justify-end">
                                            <a href="{{ asset('storage/' . $latestSub->file_path) }}" target="_blank" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                                📄 View File
                                            </a>
                                            <button onclick="openEvaluationModal('{{ $latestSub->id }}', '{{ $student->name }}')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3 py-1.5 rounded-lg transition-colors">
                                                Evaluate
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">No submission</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-gray-500">
                                    No students enrolled in this class.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Assignment Modal -->
<div id="assignmentModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50 animate-fade-in backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-150 w-full max-w-2xl mx-4 overflow-hidden transform transition-all duration-300 scale-100">
        <div class="p-6 border-b border-gray-150 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">📝 Create Assignment</h2>
                <p class="text-xs text-gray-500 mt-1">Publish coursework guidelines for Class {{ $className }}.</p>
            </div>
            <button onclick="closeAssignmentModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form method="POST" action="{{ route('teacher.assignments.store') }}" class="p-6" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="class" value="{{ $className }}">
            <input type="hidden" name="section" value="A"> <!-- Default or fetch dynamically -->
            
            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Subject *</label>
                <select name="subject" required class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject }}">{{ $subject }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Assignment Title *</label>
                <input type="text" name="title" required
                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., Physics Chapter 5 Assignment">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Description *</label>
                <textarea name="description" rows="3" required
                          class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Provide detailed instructions..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Due Date *</label>
                    <input type="date" name="due_date" required
                           class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">PDF Attachment *</label>
                    <input type="file" name="attachment" accept=".pdf" required
                           class="w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-300 rounded-xl">
                </div>
            </div>

            <div class="flex gap-3 justify-end bg-gray-50 p-4 -mx-6 -mb-6 border-t border-gray-150">
                <button type="button" onclick="closeAssignmentModal()"
                        class="bg-gray-200 text-gray-800 font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-blue-600 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-blue-700 transition-colors shadow-sm">
                    Publish Assignment
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Evaluation Modal -->
<div id="evaluationModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50 animate-fade-in backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl border border-gray-150 w-full max-w-lg mx-4 overflow-hidden transform transition-all duration-300 scale-100">
        <div class="p-6 border-b border-gray-150 flex justify-between items-center bg-gray-50">
            <div>
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">Evaluate Submission</h2>
                <p class="text-xs text-gray-500 mt-1" id="eval-student-name"></p>
            </div>
            <button onclick="closeEvaluationModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <form id="eval-form" method="POST" action="" class="p-6">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Marks obtained *</label>
                <input type="number" name="marks" required min="0" max="100"
                       class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="e.g., 85">
            </div>

            <div class="mb-4">
                <label class="block text-xs font-extrabold text-gray-700 uppercase tracking-wider mb-2">Teacher Feedback</label>
                <textarea name="feedback" rows="3"
                          class="w-full text-sm px-3 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Good work! (Optional)"></textarea>
            </div>

            <div class="flex gap-3 justify-end bg-gray-50 p-4 -mx-6 -mb-6 border-t border-gray-150">
                <button type="button" onclick="closeEvaluationModal()"
                        class="bg-gray-200 text-gray-800 font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="bg-green-600 text-white font-bold text-xs uppercase tracking-wider py-2.5 px-5 rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                    Mark as Checked
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function switchTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
    document.getElementById('section-' + tabId).classList.remove('hidden');
    
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });
    
    const activeBtn = document.getElementById('tab-' + tabId);
    activeBtn.classList.remove('border-transparent', 'text-gray-500');
    activeBtn.classList.add('border-blue-600', 'text-blue-600');
}

function openAssignmentModal() {
    document.getElementById('assignmentModal').classList.remove('hidden');
}

function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
}

function openEvaluationModal(submissionId, studentName) {
    document.getElementById('eval-student-name').textContent = 'Evaluating for ' + studentName;
    document.getElementById('eval-form').action = '/teacher/assignments/submissions/' + submissionId + '/evaluate';
    document.getElementById('evaluationModal').classList.remove('hidden');
}

function closeEvaluationModal() {
    document.getElementById('evaluationModal').classList.add('hidden');
}
</script>
@endsection
