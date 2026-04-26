@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2 bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Follow-up History</h1>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-gray-600 text-sm">Complete follow-up history for <span class="font-semibold text-gray-900">{{ $enquiry->first_name }} {{ $enquiry->surname }}</span></p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('enquiry.enquiries.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7 7" />
                </svg>
                Back to List
            </a>
            <a href="{{ route('enquiry.enquiries.show', $enquiry->id) }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2 shadow-lg hover:shadow-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                View Details
            </a>
        </div>
    </div>

    <!-- Student Info Card -->
    <div class="bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 rounded-2xl shadow-xl border border-blue-100 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                    {{ substr(ucfirst(strtolower(trim($enquiry->first_name . ' ' . $enquiry->middle_name . ' ' . $enquiry->surname))), 0, 1) }}
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ ucwords(strtolower(trim($enquiry->first_name . ' ' . $enquiry->middle_name . ' ' . $enquiry->surname))) }}</h3>
                    <p class="text-gray-600 text-sm">Student Information</p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    {{ $enquiry->status == 'new' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $enquiry->status == 'follow-up' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $enquiry->status == 'confirmed' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $enquiry->status == 'rejected' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst($enquiry->status) }}
                </span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center gap-2 mb-2">
                   
                    <span class="text-sm font-medium text-gray-700">Class</span>
                </div>
                <p class="text-gray-900 font-semibold text-lg">{{ $enquiry->class ?? 'N/A' }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center gap-2 mb-2">
                    
                    <span class="text-sm font-medium text-gray-700">Mobile</span>
                </div>
                <p class="text-gray-900 font-semibold text-lg">{{ $enquiry->parent_mobile ?? 'N/A' }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center gap-2 mb-2">
                    
                    <span class="text-sm font-medium text-gray-700">Status</span>
                </div>
                <span class="px-3 py-1 rounded-full text-sm font-bold uppercase
                    {{ $enquiry->status == 'new' ? 'bg-blue-500 text-white' : '' }}
                    {{ $enquiry->status == 'follow-up' ? 'bg-yellow-500 text-white' : '' }}
                    {{ $enquiry->status == 'confirmed' ? 'bg-green-500 text-white' : '' }}
                    {{ $enquiry->status == 'rejected' ? 'bg-red-500 text-white' : '' }}">
                    {{ ucfirst($enquiry->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Follow-up History -->
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">Follow-up Timeline</h3>
                </div>
                <div class="flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">{{ $followUps->count() }}</span>
                    <span class="text-gray-600 text-sm">Total follow-ups recorded</span>
                </div>
            </div>
        </div>

        @forelse($followUps as $followUp)
            <div class="p-6 border-b border-gray-100 hover:bg-gradient-to-r hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                <div class="flex items-start gap-6">
                   

                    <!-- Content -->
                    <div class="flex-1">
                        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-5 hover:shadow-lg transition-shadow duration-200">
                            <!-- Header -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-3">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $followUp->student_name }}</h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $followUp->status == 'follow-up' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $followUp->status == 'contacted' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $followUp->status == 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ ucfirst($followUp->status ?? 'follow-up') }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($followUp->followup_date)->format('d M Y') }} at {{ \Carbon\Carbon::parse($followUp->followup_time)->format('h:i A') }}
                                </div>
                            </div>
                            
                            <!-- Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                   
                                    <span class="font-medium text-gray-700">Type:</span>
                                    <span class="text-gray-900 font-semibold">{{ ucfirst($followUp->type) }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                   
                                    <span class="font-medium text-gray-700">Parent:</span>
                                    <span class="text-gray-900 font-semibold">{{ $enquiry->middle_name ?? '' }} {{ $enquiry->surname ?? '' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-gray-700">Class:</span>
                                    <span class="text-gray-900 font-semibold">{{ $enquiry->class ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    
                                    <span class="font-medium text-gray-700">Mobile:</span>
                                    <span class="text-gray-900 font-semibold">{{ $enquiry->parent_mobile ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <!-- Notes Section -->
                            @if($followUp->notes)
                                <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg border border-blue-100">
                                    <div class="flex items-start gap-2">
                                        
                                        <span class="font-medium text-blue-700">Notes:</span>
                                    </div>
                                    <p class="text-gray-700 text-sm leading-relaxed">{{ $followUp->notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="p-16 text-center">
                <div class="max-w-md mx-auto">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                        
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Follow-up History</h3>
                    <p class="text-gray-600 mb-6">This student doesn't have any follow-up records yet.</p>
                    <div class="flex gap-3 justify-center">
                        <a href="{{ route('enquiry.enquiries.show', $enquiry->id) }}" class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            View Student Details
                        </a>
                        <a href="{{ route('enquiry.enquiries.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition-colors">
                            Back to All Enquiries
                        </a>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection
