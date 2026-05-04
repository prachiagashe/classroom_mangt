@extends('layouts.app')

@section('title', 'My Profile')

@section('page-title', 'Student Profile')

@section('content')
<script>
// Debug: Log all form submissions
document.addEventListener('submit', function(e) {
    console.log('Form submitted:', e.target.action, e.target.method);
    console.log('Form data:', new FormData(e.target));
});
</script>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">My Profile</h1>
        <p class="text-gray-600 text-sm md:text-base">Manage your personal information and profile photo.</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2m0 0l7-7 7 7 0 017 0z"/>
                </svg>
                <p class="text-sm text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8">
        <!-- Left Column - Profile Photo -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="text-center">
                    <!-- Profile Photo -->
                    <div class="relative inline-block">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                                 alt="{{ $user->name }}" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                        @else
                            <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-3xl font-bold text-gray-500">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2 cursor-pointer hover:bg-blue-700 transition-colors"
                             onclick="document.getElementById('profile_photo').click()">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-gray-600">{{ $admission?->class ?? 'Not Assigned' }}</p>
                    
                    <!-- Photo Upload Form -->
                    <form id="uploadPhotoForm" action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                        @csrf
                        @method('PUT')
                        <input type="file" id="profile_photo" name="profile_photo" 
                               accept="image/jpeg,image/png,image/jpg,image/gif"
                               class="hidden" onchange="document.getElementById('uploadPhotoForm').submit()">
                    </form>
                    
                    <!-- Remove Photo Button -->
                    @if($user->profile_photo)
                        <a href="{{ route('student.profile.removePhoto') }}" 
                           onclick="event.preventDefault(); removeProfilePhoto();"
                           class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                            Remove Photo
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column - Profile Information -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Personal Information</h2>
                    <button onclick="toggleEditMode()" 
                            class="text-blue-600 hover:text-blue-800 font-medium transition-colors">
                        <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                </div>

                <!-- View Mode -->
                <div id="viewMode" class="space-y-6">
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Full Name</h3>
                            <p class="text-lg text-gray-900">{{ $user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Email Address</h3>
                            <p class="text-lg text-gray-900">{{ $user->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Phone Number</h3>
                            <p class="text-lg text-gray-900">{{ $user->phone ?? 'Not Provided' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Class</h3>
                            <p class="text-lg text-gray-900">{{ $admission?->class ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Roll Number</h3>
                            <p class="text-lg text-gray-900">{{ $admission?->roll_number ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Admission Date</h3>
                            <p class="text-lg text-gray-900">{{ $admission?->created_at ? $admission->created_at->format('M d, Y') : 'Not Available' }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-1">Date of Birth</h3>
                            <p class="text-lg text-gray-900">{{ $admission?->date_of_birth ? $admission->date_of_birth->format('M d, Y') : 'Not Provided' }}</p>
                        </div>
                    </div>

                    <!-- Parent Information -->
                    @if($admission)
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Parent Information</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Parent's Name</h4>
                                    <p class="text-lg text-gray-900">{{ $admission->parent_name ?? 'Not Provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Address</h4>
                                    <p class="text-lg text-gray-900">{{ $admission->address ?? 'Not Provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Parent Phone</h4>
                                    <p class="text-lg text-gray-900">{{ $admission->contact ?? 'Not Provided' }}</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 mb-1">Parent Email</h4>
                                    <p class="text-lg text-gray-900">{{ $admission->email ?? 'Not Provided' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Edit Mode -->
                <form id="editMode" action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6 hidden">
                    @csrf
                    @method('PUT')
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 md:gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ $user->name }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ $user->email }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ $user->phone ?? '' }}" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                Date of Birth
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" value="{{ $admission?->date_of_birth ? $admission->date_of_birth->format('Y-m-d') : '' }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            @error('date_of_birth')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="profile_photo_input" class="block text-sm font-medium text-gray-700 mb-2">
                                Profile Photo (Optional)
                            </label>
                            <input type="file" id="profile_photo_input" name="profile_photo" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-xs text-gray-500">Allowed formats: JPEG, PNG, JPG, GIF (Max: 2MB)</p>
                            @error('profile_photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="toggleEditMode()"
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleEditMode() {
    const viewMode = document.getElementById('viewMode');
    const editMode = document.getElementById('editMode');
    
    if (viewMode.classList.contains('hidden')) {
        viewMode.classList.remove('hidden');
        editMode.classList.add('hidden');
    } else {
        viewMode.classList.add('hidden');
        editMode.classList.remove('hidden');
    }
}

function removeProfilePhoto() {
    console.log('Remove photo clicked');
    const form = document.createElement('form'); 
    form.method = 'POST'; 
    form.action = '{{ route('student.profile.removePhoto') }}'; 
    console.log('Form action:', form.action);
    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">'; 
    document.body.appendChild(form); 
    if(confirm('Are you sure you want to remove your profile photo?')) { 
        console.log('Submitting form...');
        form.submit(); 
    } else { 
        document.body.removeChild(form); 
    }
}
</script>
@endsection
