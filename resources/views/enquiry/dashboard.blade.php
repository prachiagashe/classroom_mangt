@extends('layouts.app')
@section('title', 'BANSAL CLASS - Dashboard')
@section('content')

<style>
    .scrollbar-thin::-webkit-scrollbar {
        width: 6px;
    }
    .scrollbar-thin::-webkit-scrollbar-track {
        background: transparent;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .scrollbar-thin::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Sticky Header for PTM Groups */
    .ptm-group-header {
        position: sticky;
        top: -24px; /* Offset for parent padding */
        z-index: 20;
        background-color: white;
        margin-left: -24px;
        margin-right: -24px;
        padding-left: 24px;
        padding-right: 24px;
        padding-top: 4px;
        padding-bottom: 8px;
    }

    #allPTMsModal .modal-container {
        height: 650px;
        width: 900px;
        max-width: 95vw;
        max-height: 90vh;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6 border border-gray-100 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-blue-600 mb-1 md:mb-2">Dashboard</h1>
                <p class="text-sm md:text-base text-gray-500">Manage and track your students Enquiries</p>
            </div>
            <div class="flex flex-wrap gap-2 md:gap-3 items-center w-full md:w-auto">
                <button onclick="openPTMModal()" class="w-full md:w-auto justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 md:px-6 py-2.5 md:py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Schedule PTM
                </button>
                <a href="{{ route('admin.enquiry.form') }}"
                   class="w-full md:w-auto justify-center bg-gradient-to-r from-blue-600 to-purple-600 text-white px-4 md:px-6 py-2.5 md:py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200 flex items-center gap-2 md:mr-2">
                   <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                   Enquiry Form
                </a>
                <div class="hidden md:flex bg-blue-600 p-4 rounded-xl shadow-md">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
            </div>
        </div>
    <!-- Top Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Enquiries -->
    <div id="card-recentEnquiriesSection" onclick="toggleSection('recentEnquiriesSection', this)" class="group relative p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-blue-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-blue-200 cursor-pointer ring-4 ring-blue-300">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-blue-600 text-sm font-medium mb-1">Total Enquiries</p>
                <h2 class="text-3xl font-bold mt-1 text-blue-900">{{ $totalEnquiries ?? 0 }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-blue-500 text-sm font-medium">12% from last month</p>
                </div>
            </div>

            <!-- Clipboard Icon -->
            <div class="bg-blue-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="9" y="3" width="6" height="4"/>
                    <path d="M9 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Follow-ups -->
    <div id="card-pendingFollowUpsSection" onclick="toggleSection('pendingFollowUpsSection', this)" class="group relative p-6 rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-orange-200 cursor-pointer">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-orange-600 text-sm font-medium mb-1">Pending Follow-ups</p>
                <h2 class="text-3xl font-bold mt-1 text-orange-900">{{ $pendingFollowUpsCount ?? 0 }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                    <p class="text-orange-500 text-sm font-medium">Requires attention</p>
                </div>
            </div>

            <!-- Phone Icon -->
            <div class="bg-orange-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-orange-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92V21a1 1 0 0 1-1.11 1 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 3 4.11 1 1 0 0 1 4 3h4.09a1 1 0 0 1 1 .75 12.05 12.05 0 0 0 .57 2.57 1 1 0 0 1-.23 1L8.09 8.91a16 16 0 0 0 6 6l1.59-1.34a1 1 0 0 1 1-.23 12.05 12.05 0 0 0 2.57.57 1 1 0 0 1 .75 1z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Confirmed Admissions -->
    <div id="card-confirmedAdmissionsSection" onclick="toggleSection('confirmedAdmissionsSection', this)" class="group relative p-6 rounded-2xl bg-gradient-to-br from-green-50 to-green-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-green-200 cursor-pointer">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-green-600 text-sm font-medium mb-1">Confirmed Admissions</p>
                <h2 class="text-3xl font-bold mt-1 text-green-900">{{ $confirmedAdmissionsCount ?? 0 }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-500 text-sm font-medium">8% from last month</p>
                </div>
            </div>

            <!-- Check Icon -->
            <div class="bg-green-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Students -->
    <div id="card-studentsSection" onclick="toggleSection('studentsSection', this)" class="group relative p-6 rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-purple-200 cursor-pointer">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between items-start">
            <div>
                <p class="text-purple-600 text-sm font-medium mb-1">Total Students</p>
                <h2 class="text-3xl font-bold mt-1 text-purple-900">{{ $totalStudentsCount ?? 0 }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <p class="text-purple-500 text-sm font-medium">Active learners</p>
                </div>
            </div>

            <!-- Users Icon -->
            <div class="bg-purple-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-purple-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                </svg>
            </div>
        </div>
    </div>

</div>


    <!-- Second Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Fee Collection -->
    <div onclick="fetchDynamicTableData('fees-paid')" class="cursor-pointer group relative p-6 rounded-2xl bg-gradient-to-br from-teal-50 to-teal-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-teal-200">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between">
            <div>
                <p class="text-teal-600 text-sm font-medium mb-1">Fee Collection (This Month)</p>
                <h2 class="text-3xl font-bold mt-1 text-teal-900">₹{{ number_format($feeCollectionThisMonth) }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <div class="w-2 h-2 bg-teal-500 rounded-full"></div>
                    <p class="text-teal-500 text-sm font-medium">On track</p>
                </div>
            </div>

            <!-- Credit Card -->
            <div class="bg-teal-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-teal-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2"/>
                    <line x1="1" y1="10" x2="23" y2="10"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Fees -->
    <div onclick="fetchDynamicTableData('fees-pending')" class="cursor-pointer group relative p-6 rounded-2xl bg-gradient-to-br from-red-50 to-red-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-red-200">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between">
            <div>
                <p class="text-red-600 text-sm font-medium mb-1">Pending Fees</p>
                <h2 class="text-3xl font-bold mt-1 text-red-900">₹{{ number_format($pendingFees) }}</h2>
                <div class="flex items-center gap-1 mt-2">
                    <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></div>
                    <p class="text-red-500 text-sm font-medium">Action needed</p>
                </div>
            </div>

            <!-- Alert Icon -->
            <div class="bg-red-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-red-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Employees -->
    <div id="card-employeesSection" onclick="toggleSection('employeesSection', this)" class="group relative p-6 rounded-2xl bg-gradient-to-br from-indigo-50 to-indigo-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-indigo-200 cursor-pointer">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between">
            <div>
                <p class="text-indigo-600 text-sm font-medium mb-1">Total Employees</p>
                <h2 class="text-3xl font-bold mt-1 text-indigo-900">28</h2>
                <div class="flex items-center gap-1 mt-2">
                    <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                    <p class="text-indigo-500 text-sm font-medium">Full staff</p>
                </div>
            </div>

            <!-- UserCog -->
            <div class="bg-indigo-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="7" r="4"/>
                    <path d="M5.5 21a6.5 6.5 0 0 1 13 0"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Leave -->
    <div class="group relative p-6 rounded-2xl bg-gradient-to-br from-amber-50 to-amber-100 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-amber-200">
        <div class="absolute inset-0 bg-white/40 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="relative flex justify-between">
            <div>
                <p class="text-amber-600 text-sm font-medium mb-1">Leave Requests</p>
                <h2 class="text-3xl font-bold mt-1 text-amber-900">3</h2>
                <div class="flex items-center gap-1 mt-2">
                    <div class="w-2 h-2 bg-amber-500 rounded-full"></div>
                    <p class="text-amber-500 text-sm font-medium">Pending</p>
                </div>
            </div>

            <!-- Calendar -->
            <div class="bg-amber-200/50 p-3 rounded-xl backdrop-blur-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-amber-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                </svg>
            </div>
        </div>
    </div>

</div>


    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Enquiries -->
        <div id="recentEnquiriesSection" class="lg:col-span-2 bg-white/90 backdrop-blur-sm p-4 md:p-6 rounded-2xl shadow-lg border border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="text-2xl">📋</span>
                    Recent Enquiries
                </h3>
               <a href="{{ route('enquiry.enquiries.index') }}"
                    class="w-full sm:w-auto justify-center text-sm bg-gradient-to-r from-blue-500 to-purple-500 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-purple-600 transition-all duration-200 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead id="recentEnquiriesTableHeader">
                        <tr>
                            <th>Student Name</th>
                            <th>Parent</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="recentEnquiriesTableBody">
                        @forelse($recentEnquiries as $enquiry)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="crm-avatar bg-gradient-to-br from-blue-500 to-indigo-600">
                                        {{ substr($enquiry->first_name, 0, 1) }}{{ substr($enquiry->surname, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-900">{{ ucfirst(strtolower($enquiry->first_name)) }} {{ ucfirst(strtolower($enquiry->surname)) }}</span>
                                </div>
                            </td>
                            <td><span class="secondary-text">{{ ucfirst(strtolower($enquiry->middle_name)) }} {{ ucfirst(strtolower($enquiry->surname)) }}</span></td>
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $enquiry->class }}
                                </span>
                            </td>
                            <td>
                                <span class="crm-badge crm-badge-success">
                                    {{ ucfirst($enquiry->status) }}
                                </span>
                            </td>
                            <td><span class="secondary-text">{{ $enquiry->created_at->format('d M Y') }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">
                                No enquiries found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Follow-ups (Hidden by Default) -->
        <div id="pendingFollowUpsSection" class="hidden lg:col-span-2 bg-white/90 backdrop-blur-sm p-4 md:p-6 rounded-2xl shadow-lg border border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-xl font-bold text-orange-800 flex items-center gap-2">
                    <span class="text-2xl">📞</span>
                    Pending Follow-ups
                </h3>
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('enquiry.enquiries.index') }}" class="w-full sm:w-auto justify-center text-sm bg-gradient-to-r from-orange-500 to-red-500 text-white px-4 py-2 rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        View All
                    </a>
                    <button onclick="toggleSection('recentEnquiriesSection', document.getElementById('card-recentEnquiriesSection'))" class="w-full sm:w-auto justify-center text-sm bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Contact</th>
                            <th>Class</th>
                            <th>Follow-up Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($upcomingFollowUpsData ?? [] as $pending)
                        <tr>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-bold text-gray-900">{{ $pending['name'] }}</span>
                                    <span class="secondary-text">Parent: {{ $pending['parent'] }}</span>
                                </div>
                            </td>
                            <td><span class="secondary-text">{{ $pending['contact'] }}</span></td>
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $pending['class'] }}
                                </span>
                            </td>
                            <td><span class="font-bold text-orange-600">{{ $pending['date'] }}</span></td>
                            <td class="text-center">
                                <div class="flex justify-center items-center gap-2">
                                    <a href="tel:{{ $pending['contact'] }}" title="Call" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </a>
                                    <a href="{{ route('enquiry.enquiries.show', $pending['id']) }}" title="View" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-12 text-gray-500">
                                No pending follow-ups
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Confirmed Admissions (Hidden by Default) -->
        <div id="confirmedAdmissionsSection" class="hidden lg:col-span-2 bg-white/90 backdrop-blur-sm p-4 md:p-6 rounded-2xl shadow-lg border border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-xl font-bold text-green-800 flex items-center gap-2">
                    <span class="text-2xl">✅</span>
                    Confirmed Admissions
                </h3>
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('enquiry.enquiries.index') }}" class="w-full sm:w-auto justify-center text-sm bg-gradient-to-r from-green-500 to-emerald-500 text-white px-4 py-2 rounded-lg hover:from-green-600 hover:to-emerald-600 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        View All
                    </a>
                    <button onclick="toggleSection('recentEnquiriesSection', document.getElementById('card-recentEnquiriesSection'))" class="w-full sm:w-auto justify-center text-sm bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Contact</th>
                            <th>Class</th>
                            <th>Medium</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($confirmedAdmissionsData ?? [] as $admission)
                        <tr>
                            <td class="font-bold text-gray-900">{{ $admission->first_name }} {{ $admission->surname }}</td>
                            <td><span class="secondary-text">{{ $admission->parent_mobile }}</span></td>
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $admission->class }}
                                </span>
                            </td>
                            <td><span class="secondary-text">{{ $admission->medium }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-500">
                                No confirmed admissions
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Students (Hidden by Default) -->
        <div id="studentsSection" class="hidden lg:col-span-2 bg-white/90 backdrop-blur-sm p-4 md:p-6 rounded-2xl shadow-lg border border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-xl font-bold text-purple-800 flex items-center gap-2">
                    <span class="text-2xl">🎓</span>
                    Enrolled Students
                </h3>
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('enquiry.enquiries.index') }}" class="w-full sm:w-auto justify-center text-sm bg-gradient-to-r from-purple-500 to-fuchsia-500 text-white px-4 py-2 rounded-lg hover:from-purple-600 hover:to-fuchsia-600 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        View All
                    </a>
                    <button onclick="toggleSection('recentEnquiriesSection', document.getElementById('card-recentEnquiriesSection'))" class="w-full sm:w-auto justify-center text-sm bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Admission Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studentsData ?? [] as $student)
                        <tr>
                            <td class="font-bold text-gray-900">{{ $student->enquiry->first_name ?? 'N/A' }} {{ $student->enquiry->surname ?? '' }}</td>
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $student->class }}
                                </span>
                            </td>
                            <td><span class="secondary-text">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('d M Y') : 'N/A' }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-12 text-gray-500">
                                No registered students
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Employees (Hidden by Default) -->
        <div id="employeesSection" class="hidden lg:col-span-2 bg-white/90 backdrop-blur-sm p-4 md:p-6 rounded-2xl shadow-lg border border-white/20">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-xl font-bold text-indigo-800 flex items-center gap-2">
                    <span class="text-2xl">👨‍🏫</span>
                    Employees Directory
                </h3>
                <div class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full sm:w-auto">
                    <a href="{{ route('employee.index') }}" class="w-full sm:w-auto justify-center text-sm bg-gradient-to-r from-indigo-500 to-blue-500 text-white px-4 py-2 rounded-lg hover:from-indigo-600 hover:to-blue-600 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        View All
                    </a>
                    <button onclick="toggleSection('recentEnquiriesSection', document.getElementById('card-recentEnquiriesSection'))" class="w-full sm:w-auto justify-center text-sm bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all duration-200 inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="crm-table">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Code</th>
                            <th>Role</th>
                            <th>Contact</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employeesData ?? [] as $employee)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="crm-avatar bg-gradient-to-br from-indigo-500 to-blue-600">
                                        {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                                    </div>
                                    <span class="font-bold text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                                </div>
                            </td>
                            <td><span class="font-mono text-xs text-gray-500">{{ $employee->employee_code }}</span></td>
                            <td>
                                <span class="crm-badge crm-badge-info">
                                    {{ $employee->designation ?? 'Staff' }}
                                </span>
                            </td>
                            <td><span class="secondary-text">{{ $employee->phone }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-500">
                                No employees found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Follow-ups Component right side (using true DB bindings now) -->
      <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg border border-white/20">

    <!-- Card Header -->
    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-purple-50 rounded-t-2xl">
        <h3 class="text-xl font-bold flex items-center gap-3 text-gray-800">
            <span class="text-2xl">📅</span>
            Today's Follow-ups
        </h3>
    </div>

    <!-- Card Content -->
    <div class="p-6 space-y-4">

        @forelse(array_slice($upcomingFollowUpsData ?? [], 0, 3) as $followUp)
            <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-gray-50 to-blue-50 hover:from-blue-50 hover:to-purple-50 transition-all duration-200 border border-gray-100">

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-400 rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr($followUp['name'], 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-sm text-gray-800">
                            {{ $followUp['name'] }}
                        </p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $followUp['time'] }} <!-- Fallback dynamic date format mapping mapped in controller -->
                        </p>
                    </div>
                </div>

                <a href="{{ route('enquiry.enquiries.show', $followUp['id']) }}" class="text-xs font-semibold px-3 py-1 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-sm hover:opacity-80 transition">
                    View
                </a>
            </div>
        @empty
            <div class="text-sm text-gray-500 text-center py-4">
                No active follow-ups for today.
            </div>
        @endforelse

        <a href="{{ route('enquiry.followups.index') }}" 
           class="w-full mt-4 bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 text-sm py-3 rounded-xl hover:from-gray-200 hover:to-gray-300 transition-all duration-200 font-medium flex items-center justify-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            View All Follow-ups
        </a>

    </div>

</div>


</div>

</div>

<!-- PTM Scheduling Modal -->
<div id="ptmModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-2xl font-bold text-gray-800">Schedule Parent-Teacher Meeting</h3>
                <button onclick="closePTMModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="ptmForm" class="p-6 space-y-4" onsubmit="publishPTM(event)">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Class</label>
                    <select id="classSelect" name="class_name" onchange="toggleCourseType()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Class</option>
                        <option value="5">Class 5</option>
                        <option value="6">Class 6</option>
                        <option value="7">Class 7</option>
                        <option value="8">Class 8</option>
                        <option value="9">Class 9</option>
                        <option value="10">Class 10</option>
                        <option value="11">Class 11</option>
                        <option value="12">Class 12</option>
                    </select>
                </div>
                
                <div id="courseTypeDiv" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Course Type</label>
                    <select name="course_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Course</option>
                        <option value="neet">NEET</option>
                        <option value="jee">JEE</option>
                        <option value="mht-cet">MHT-CET</option>
                        <option value="regular">Regular</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Date</label>
                    <input type="date" name="meeting_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Time</label>
                    <input type="time" name="start_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Time</label>
                    <input type="time" name="end_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teacher</label>
                    <input type="text" name="teacher_name" placeholder="Teacher name" oninput="validateTeacherName(this)" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Mode</label>
                    <select id="meetingMode" name="meeting_mode" onchange="toggleMeetingLink()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Mode</option>
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                
                <div id="meetingLinkDiv" class="hidden md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Link</label>
                    <input type="url" name="meeting_link" placeholder="https://meet.example.com/..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div id="meetingLocationDiv" class="hidden md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Meeting Location</label>
                    <input type="text" name="meeting_location" placeholder="Room number or address" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" rows="3" placeholder="Meeting description..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4">
                <button type="button" onclick="closePTMModal()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-colors flex items-center gap-2">
                    <span id="publishBtnText">Publish PTM</span>
                    <svg id="publishLoader" class="w-4 h-4 animate-spin hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- PTM Schedule Widget -->
<div class="mt-8 bg-white/90 backdrop-blur-sm p-6 rounded-2xl shadow-lg border border-white/20">
    <div class="flex flex-row justify-between items-center mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <span class="text-2xl">📅</span>
            Next Upcoming PTM
        </h3>
        
        <button onclick="document.getElementById('allPTMsModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg text-sm font-semibold transition-colors flex items-center gap-2">
            View All PTMs
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </button>
    </div>
    
    @php
        $todayDate = now()->toDateString();
        
        // Fetch ALL active PTMs
        $allActivePTMsForModal = \App\Models\PTMSchedule::where('status', '!=', 'cancelled')
            ->orderBy('meeting_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
            
        // 1. Next Upcoming PTM (for the dashboard widget)
        $nextPTM = $allActivePTMsForModal->filter(fn($p) => $p->meeting_date->toDateString() >= $todayDate)->first();
        
        // 2. Upcoming & Today Group (Separated for UI)
        $upcomingAndTodayPTMs = $allActivePTMsForModal->filter(fn($p) => $p->meeting_date->toDateString() >= $todayDate)
            ->groupBy('class_name')->sortBy(fn($g, $k) => (int)$k);

        // 3. Past Group (Separated for UI)
        $pastPTMs = $allActivePTMsForModal->filter(fn($p) => $p->meeting_date->toDateString() < $todayDate)
            ->groupBy('class_name')->sortBy(fn($g, $k) => (int)$k);
            
        $modalAvailableClasses = $allActivePTMsForModal->pluck('class_name')->unique()->sort();
    @endphp
    
    @if($nextPTM)
        @php
            $isToday = $nextPTM->meeting_date->toDateString() === $todayDate;
        @endphp
        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-xl border {{ $isToday ? 'bg-orange-50 border-orange-200 shadow-sm' : 'bg-gray-50 border-gray-200' }}">
            <div class="flex items-center gap-5 mb-3 sm:mb-0">
                <div class="{{ $isToday ? 'bg-gradient-to-br from-orange-400 to-red-400' : 'bg-blue-600' }} text-white min-w-[3.5rem] h-14 rounded-xl flex flex-col items-center justify-center shadow-md">
                    <span class="text-[10px] font-bold uppercase tracking-wider">{{ $nextPTM->meeting_date->format('M') }}</span>
                    <span class="text-lg font-bold leading-none">{{ $nextPTM->meeting_date->format('d') }}</span>
                </div>
                <div>
                    <h4 class="font-bold text-gray-900 text-lg">Class {{ $nextPTM->class_name }}</h4>
                    <p class="font-semibold text-gray-700">
                        {{ $nextPTM->course_type ? strtoupper($nextPTM->course_type) . ' • ' : '' }}{{ $nextPTM->teacher_name }}
                    </p>
                    <div class="text-gray-500 text-sm flex flex-wrap items-center gap-x-3 gap-y-1 mt-1 font-medium">
                        <span class="flex items-center gap-1 {{ $isToday ? 'text-orange-600' : '' }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ \Carbon\Carbon::parse($nextPTM->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($nextPTM->end_time)->format('h:i A') }}
                        </span>
                        @if($nextPTM->meeting_mode === 'online')
                            <span class="text-blue-600 flex items-center gap-1 bg-blue-50 px-2 py-0.5 rounded-full text-xs">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Online
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <button onclick="cancelPTMSchedule({{ $nextPTM->id }})" title="Cancel Meeting" class="self-end sm:self-auto px-4 py-2 bg-red-50 text-red-600 font-semibold text-sm rounded-lg hover:bg-red-100 hover:text-red-700 transition-colors">
                Cancel
            </button>
        </div>
    @else
        <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <p>No upcoming PTMs are currently scheduled.</p>
            <button onclick="openPTMModal()" class="mt-3 text-blue-600 hover:underline text-sm font-semibold">Schedule One Now</button>
        </div>
    @endif
</div>

<!-- All Future PTMs Modal -->
<div id="allPTMsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="modal-container bg-white rounded-2xl flex flex-col overflow-hidden shadow-2xl transition-all">
        
        <!-- Modal Header -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-gray-50">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    PTM Directory
                </h3>
                <p class="text-sm text-gray-500 mt-1">Manage and filter scheduled parent-teacher meetings.</p>
            </div>
            <button onclick="document.getElementById('allPTMsModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 bg-white shadow-sm p-1.5 rounded-lg border border-gray-200 self-end sm:self-center transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        
        <!-- Modal Filters -->
        <div class="px-6 py-4 bg-white border-b border-gray-100 flex flex-col lg:flex-row gap-4 items-center justify-between">
            <!-- Time Filters -->
            <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg overflow-x-auto w-full lg:w-auto self-start">
                <button class="modal-time-filter px-4 py-1.5 text-sm rounded bg-white shadow-sm text-gray-800 font-semibold transition-all" data-filter="all">All</button>
                <button class="modal-time-filter px-4 py-1.5 text-sm rounded text-gray-500 hover:text-gray-700 font-semibold transition-all" data-filter="today">Today</button>
                <button class="modal-time-filter px-4 py-1.5 text-sm rounded text-gray-500 hover:text-gray-700 font-semibold transition-all" data-filter="upcoming">Upcoming</button>
                <button class="modal-time-filter px-4 py-1.5 text-sm rounded text-gray-500 hover:text-gray-700 font-semibold transition-all" data-filter="past">Past</button>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                <!-- Class Selection -->
                <select id="modalClassFilter" onchange="runModalFilters()" class="border-gray-200 rounded-lg text-sm px-3 py-2 w-full sm:w-auto focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Classes</option>
                    @for($i = 5; $i <= 12; $i++)
                        <option value="{{ $i }}">Class {{ $i }}</option>
                    @endfor
                </select>
                
                <!-- Search Input -->
                <div class="relative w-full sm:w-64">
                    <input type="text" id="modalSearchFilter" onkeyup="runModalFilters()" placeholder="Search teacher, class..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 transition-all">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>
        
        <!-- Modal List -->
        <div class="p-6 overflow-y-auto flex-1 scrollbar-thin space-y-8 bg-gray-50/50">
            
            <!-- Upcoming Section -->
            <div id="upcomingSection" class="{{ $upcomingAndTodayPTMs->isEmpty() ? 'hidden' : '' }}">
                <h4 class="text-sm font-bold text-blue-600 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-blue-200"></span>
                    Active & Upcoming Meetings
                </h4>
                
                @foreach($upcomingAndTodayPTMs as $className => $ptms)
                    <div class="ptm-modal-class-group mb-8" data-class="{{ $className }}">
                        <div class="ptm-group-header flex items-center gap-2">
                            <h5 class="font-bold text-gray-800 text-lg">Class {{ $className }}</h5>
                            <span class="bg-blue-100 text-blue-600 text-xs px-2.5 py-0.5 rounded-full font-bold">{{ $ptms->count() }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            @foreach($ptms as $ptm)
                                @php
                                    $dateStr = $ptm->meeting_date->toDateString();
                                    $timeCategory = ($dateStr === $todayDate) ? 'today' : 'upcoming';
                                    $searchString = strtolower($ptm->teacher_name . ' ' . $ptm->course_type . ' class ' . $ptm->class_name);
                                @endphp
                                
                                <div class="ptm-modal-card border border-gray-100 rounded-xl bg-white p-4 flex flex-col justify-between shadow-sm hover:shadow-md transition-all" 
                                     data-time="{{ $timeCategory }}" 
                                     data-search="{{ $searchString }}">
                                     
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="{{ $timeCategory === 'today' ? 'bg-orange-100 text-orange-600' : 'bg-blue-100 text-blue-600' }} min-w-[2.5rem] w-10 h-10 rounded-lg flex flex-col items-center justify-center">
                                                <span class="text-[9px] font-bold uppercase">{{ $ptm->meeting_date->format('M') }}</span>
                                                <span class="text-sm font-bold leading-none">{{ $ptm->meeting_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 text-sm">
                                                    {{ $ptm->course_type ? strtoupper($ptm->course_type) . ' • ' : '' }}{{ $ptm->teacher_name }}
                                                </p>
                                                <p class="text-gray-500 text-xs flex items-center gap-1 mt-0.5">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    {{ \Carbon\Carbon::parse($ptm->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($ptm->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        @if($timeCategory === 'today')
                                            <span class="bg-orange-100 text-orange-700 text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider border border-orange-200">Today</span>
                                        @endif
                                    </div>
                                    
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                                        <span class="text-{{ $ptm->meeting_mode === 'online' ? 'blue' : 'gray' }}-600 flex items-center gap-1 text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $ptm->meeting_mode === 'online' ? 'blue' : 'gray' }}-500"></span> {{ $ptm->meeting_mode === 'online' ? 'Online' : 'In-Person' }}
                                        </span>
                                        <button onclick="cancelPTMSchedule({{ $ptm->id }})" class="text-red-400 hover:text-red-700 hover:bg-red-50 p-1.5 rounded-md transition-colors" title="Cancel PTM">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Past Section -->
            <div id="pastSection" class="{{ $pastPTMs->isEmpty() ? 'hidden' : '' }}">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <span class="w-8 h-[1px] bg-gray-300"></span>
                    Past Meetings
                </h4>
                
                @foreach($pastPTMs as $className => $ptms)
                    <div class="ptm-modal-class-group mb-8" data-class="{{ $className }}">
                        <div class="ptm-group-header flex items-center gap-2">
                            <h5 class="font-bold text-gray-500 text-lg">Class {{ $className }}</h5>
                            <span class="bg-gray-200 text-gray-600 text-xs px-2.5 py-0.5 rounded-full font-bold">{{ $ptms->count() }}</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                            @foreach($ptms as $ptm)
                                @php
                                    $searchString = strtolower($ptm->teacher_name . ' ' . $ptm->course_type . ' class ' . $ptm->class_name);
                                @endphp
                                
                                <div class="ptm-modal-card border border-gray-100 rounded-xl bg-white/60 p-4 flex flex-col justify-between shadow-sm opacity-80" 
                                     data-time="past" 
                                     data-search="{{ $searchString }}">
                                     
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-gray-100 text-gray-500 min-w-[2.5rem] w-10 h-10 rounded-lg flex flex-col items-center justify-center">
                                                <span class="text-[9px] font-bold uppercase">{{ $ptm->meeting_date->format('M') }}</span>
                                                <span class="text-sm font-bold leading-none">{{ $ptm->meeting_date->format('d') }}</span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-600 text-sm">
                                                    {{ $ptm->course_type ? strtoupper($ptm->course_type) . ' • ' : '' }}{{ $ptm->teacher_name }}
                                                </p>
                                                <p class="text-gray-400 text-xs flex items-center gap-1 mt-0.5">
                                                    {{ \Carbon\Carbon::parse($ptm->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($ptm->end_time)->format('h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                        <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded uppercase font-bold tracking-wider border border-gray-200">Past</span>
                                    </div>
                                    
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-50">
                                        <span class="text-gray-500 flex items-center gap-1 text-xs font-semibold">
                                            {{ $ptm->meeting_mode === 'online' ? 'Online' : 'In-Person' }}
                                        </span>
                                        <button onclick="cancelPTMSchedule({{ $ptm->id }})" class="text-red-300 hover:text-red-500 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
                
                <div id="modalEmptyState" class="hidden text-center py-16 text-gray-500 transition-all">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <p class="text-lg font-medium text-gray-600">No PTMs found matching your filters.</p>
                    <p class="text-sm mt-1">Try adjusting your search criteria or categories above.</p>
                </div>
            @if($upcomingAndTodayPTMs->isEmpty() && $pastPTMs->isEmpty())
                <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <p>No PTMs have been scheduled yet.</p>
                </div>
            @endif
        </div>
        
    </div>
</div>

<script>
function toggleSection(sectionId, clickedElement) {
    const allSections = [
        'recentEnquiriesSection', 
        'pendingFollowUpsSection', 
        'confirmedAdmissionsSection',
        'studentsSection',
        'employeesSection'
    ];

    const allCards = [
        'card-recentEnquiriesSection',
        'card-pendingFollowUpsSection',
        'card-confirmedAdmissionsSection',
        'card-studentsSection',
        'card-employeesSection'
    ];

    // Reset all ring borders mapped statically via classes
    allCards.forEach(cardId => {
        let el = document.getElementById(cardId);
        if(el) {
            el.classList.remove('ring-4', 'ring-blue-300', 'ring-orange-300', 'ring-green-300', 'ring-purple-300', 'ring-indigo-300');
        }
    });

    // Hide all sections first
    allSections.forEach(id => {
        let el = document.getElementById(id);
        if(el) el.classList.add('hidden');
    });

    // Show target section
    const targetSection = document.getElementById(sectionId);
    if(targetSection) {
        targetSection.classList.remove('hidden');
        targetSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Apply color highlights based on matching logic mapping
    if(clickedElement) {
        if(sectionId === 'recentEnquiriesSection') clickedElement.classList.add('ring-4', 'ring-blue-300');
        if(sectionId === 'pendingFollowUpsSection') clickedElement.classList.add('ring-4', 'ring-orange-300');
        if(sectionId === 'confirmedAdmissionsSection') clickedElement.classList.add('ring-4', 'ring-green-300');
        if(sectionId === 'studentsSection') clickedElement.classList.add('ring-4', 'ring-purple-300');
        if(sectionId === 'employeesSection') clickedElement.classList.add('ring-4', 'ring-indigo-300');
    }
}

function openPTMModal() {
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.querySelector('input[name="meeting_date"]');
    if (dateInput) {
        dateInput.setAttribute('min', today);
    }
    document.getElementById('ptmModal').classList.remove('hidden');
}

function triggerPTMNotification(notificationData) {
    // Show notification badge immediately for admin/user feedback
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        badge.classList.remove('hidden');
        // If we don't have a count yet, just show a dot or '1'
        if (!badge.textContent) {
            badge.textContent = '1';
        }
    }
    
    const bellIcon = document.querySelector('.notification-bell');
    if (bellIcon) {
        bellIcon.classList.add('animate-pulse');
    }
    
    // Clear session storage to show new notification
    sessionStorage.removeItem('ptm_notification_seen');
    
    // Show success notification
    showNotification('PTM published successfully! Students have been notified.', 'success');
    checkForNewPTM();
}

function checkForNewPTM() {
    // This function can be used to trigger immediate notification checks
    // Students' pages will automatically detect new PTMs through their periodic checks
    console.log('PTM published - students will be notified on their next check');
}

function closePTMModal() {
    document.getElementById('ptmModal').classList.add('hidden');
    document.getElementById('ptmForm').reset();
    // Hide course type and meeting link/location divs
    document.getElementById('courseTypeDiv').classList.add('hidden');
    document.getElementById('meetingLinkDiv').classList.add('hidden');
    document.getElementById('meetingLocationDiv').classList.add('hidden');
}

function toggleCourseType() {
    const classSelect = document.getElementById('classSelect');
    const courseTypeDiv = document.getElementById('courseTypeDiv');
    
    if (classSelect.value === '11' || classSelect.value === '12') {
        courseTypeDiv.classList.remove('hidden');
        // Make course type required
        courseTypeDiv.querySelector('select').setAttribute('required', 'required');
    } else {
        courseTypeDiv.classList.add('hidden');
        // Remove required attribute
        courseTypeDiv.querySelector('select').removeAttribute('required');
    }
}

function toggleMeetingLink() {
    const meetingMode = document.getElementById('meetingMode');
    const meetingLinkDiv = document.getElementById('meetingLinkDiv');
    const meetingLocationDiv = document.getElementById('meetingLocationDiv');
    const meetingLinkInput = meetingLinkDiv.querySelector('input[name="meeting_link"]');
    const meetingLocationInput = meetingLocationDiv.querySelector('input[name="meeting_location"]');
    
    // Hide both fields first
    meetingLinkDiv.classList.add('hidden');
    meetingLocationDiv.classList.add('hidden');
    
    // Remove required attributes from both fields
    meetingLinkInput.removeAttribute('required');
    meetingLocationInput.removeAttribute('required');
    
    if (meetingMode.value === 'online') {
        // Show meeting link field and make it required
        meetingLinkDiv.classList.remove('hidden');
        meetingLinkInput.setAttribute('required', 'required');
    } else if (meetingMode.value === 'offline') {
        // Show meeting location field and make it required
        meetingLocationDiv.classList.remove('hidden');
        meetingLocationInput.setAttribute('required', 'required');
    }
    // If no mode selected, both fields remain hidden and not required
}

function validateTeacherName(input) {
    const val = input.value;
    const nameRegex = /[0-9]/;
    if (nameRegex.test(val)) {
        showNotification('Teacher name should not contain numbers.', 'error');
        // Remove the numbers
        input.value = val.replace(/[0-9]/g, '');
    }
}

async function publishPTM(event) {
    event.preventDefault();
    
    const form = document.getElementById('ptmForm');
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = document.getElementById('publishBtnText');
    const loader = document.getElementById('publishLoader');
    
    // Get meeting mode
    const meetingMode = formData.get('meeting_mode');
    
    // Remove irrelevant fields based on meeting mode
    if (meetingMode === 'online') {
        formData.delete('meeting_location'); // Remove location for online meetings
    } else if (meetingMode === 'offline') {
        formData.delete('meeting_link'); // Remove link for offline meetings
    }
    
    // Client-side validation
    const meetingDate = formData.get('meeting_date');
    const startTime = formData.get('start_time');
    const endTime = formData.get('end_time');
    const teacherName = formData.get('teacher_name');
    const meetingLink = formData.get('meeting_link');
    const meetingLocation = formData.get('meeting_location');
    
    // Validate date
    if (meetingDate) {
        const selectedDate = new Date(meetingDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            showNotification('Meeting date cannot be in the past.', 'error');
            return;
        }
    }

    // Validate teacher name (no numbers)
    const nameRegex = /^[a-zA-Z\s\.]+$/;
    if (teacherName && !nameRegex.test(teacherName)) {
        showNotification('Teacher name should only contain letters and spaces.', 'error');
        return;
    }
    
    // Validate time
    if (startTime && endTime && startTime >= endTime) {
        showNotification('End time must be after start time', 'error');
        return;
    }
    
    // Validate meeting mode specific fields
    if (meetingMode === 'online' && !meetingLink) {
        showNotification('Meeting link is required for online meetings', 'error');
        return;
    }
    
    if (meetingMode === 'offline' && !meetingLocation) {
        showNotification('Meeting location is required for offline meetings', 'error');
        return;
    }
    
    // Debug: Log form data
    console.log('Form data being submitted:');
    for (let [key, value] of formData.entries()) {
        console.log(key + ': ' + value);
    }
    
    // Show loading state
    submitBtn.disabled = true;
    btnText.textContent = 'Publishing...';
    loader.classList.remove('hidden');
    
    try {
        const response = await fetch('/admin/ptm/store', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success message
            showNotification('PTM scheduled successfully!', 'success');
            
            // Trigger notification if available
            if (data.notification) {
                triggerPTMNotification(data.notification);
            }
            
            // Close modal and reset form
            closePTMModal();
            
            // Refresh the PTM schedule list
            location.reload();
        } else {
            // Show validation errors if any
            if (data.errors) {
                let errorMessage = '';
                for (let field in data.errors) {
                    errorMessage += data.errors[field].join(', ') + '\n';
                }
                showNotification(errorMessage.trim(), 'error');
            } else {
                showNotification(data.message || 'Error scheduling PTM', 'error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Network error. Please try again.', 'error');
    } finally {
        // Reset loading state
        submitBtn.disabled = false;
        btnText.textContent = 'Publish PTM';
        loader.classList.add('hidden');
    }
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function triggerPTMNotification(notification) {
    // Show notification badge on bell icon
    const badge = document.getElementById('notificationBadge');
    if (badge) {
        badge.classList.remove('hidden');
        if (!badge.textContent) {
            badge.textContent = '1';
        }
    }
    
    const bellIcon = document.querySelector('.notification-bell');
    if (bellIcon) {
        bellIcon.classList.add('animate-pulse');
    }
    
    // Store notification in session storage for student dashboard
    sessionStorage.setItem('ptmNotification', JSON.stringify(notification));
}

// Modal Filtering JS Logic
let currentModalTimeFilter = 'all';

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.modal-time-filter').forEach(btn => {
        btn.addEventListener('click', (e) => {
            // Update UI styles
            document.querySelectorAll('.modal-time-filter').forEach(b => {
                b.classList.remove('bg-white', 'shadow-sm', 'text-gray-800');
                b.classList.add('text-gray-500');
            });
            e.target.classList.add('bg-white', 'shadow-sm', 'text-gray-800');
            e.target.classList.remove('text-gray-500');
            
            // Apply filter
            currentModalTimeFilter = e.target.dataset.filter;
            runModalFilters();
        });
    });
});

function runModalFilters() {
    const classVal = document.getElementById('modalClassFilter').value;
    const searchVal = document.getElementById('modalSearchFilter').value.toLowerCase();
    const upcomingSection = document.getElementById('upcomingSection');
    const pastSection = document.getElementById('pastSection');
    
    let totalVisible = 0;
    
    // Toggle main section visibility based on time filter
    if (currentModalTimeFilter === 'past') {
        if (upcomingSection) upcomingSection.classList.add('hidden');
        if (pastSection) pastSection.classList.remove('hidden');
    } else {
        if (upcomingSection) upcomingSection.classList.remove('hidden');
        if (pastSection) pastSection.classList.add('hidden');
    }

    document.querySelectorAll('.ptm-modal-class-group').forEach(group => {
        let hasVisibleCards = false;
        const groupClass = group.dataset.class;
        
        // Class Filter
        if (classVal && classVal !== groupClass) {
            group.classList.add('hidden');
            return;
        }
        
        group.querySelectorAll('.ptm-modal-card').forEach(card => {
            const timeRaw = card.dataset.time; // 'today', 'upcoming', 'past'
            const searchRaw = card.dataset.search; 
            
            let timeMatch = false;
            if (currentModalTimeFilter === 'all') {
                timeMatch = (timeRaw === 'today' || timeRaw === 'upcoming');
            } else if (currentModalTimeFilter === 'today') {
                timeMatch = (timeRaw === 'today');
            } else if (currentModalTimeFilter === 'upcoming') {
                timeMatch = (timeRaw === 'upcoming');
            } else if (currentModalTimeFilter === 'past') {
                timeMatch = (timeRaw === 'past');
            }
            
            let textMatch = searchVal === '' || searchRaw.includes(searchVal);
            
            if (timeMatch && textMatch) {
                card.classList.remove('hidden');
                hasVisibleCards = true;
                totalVisible++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        if (hasVisibleCards) {
            group.classList.remove('hidden');
        } else {
            group.classList.add('hidden');
        }
    });
    
    const emptyState = document.getElementById('modalEmptyState');
    if (emptyState) {
        if (totalVisible === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
}

function cancelPTMSchedule(id) {
    if(!confirm("Are you sure you want to cancel this meeting?")) return;
    
    fetch(`/admin/ptm/${id}/status`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: 'cancelled' })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            showNotification('PTM has been cancelled successfully.', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showNotification(data.message || 'Error updating status', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showNotification('Network error.', 'error');
    });
}

function fetchDynamicTableData(type) {
    // Show loading state
    const tbody = document.getElementById('recentEnquiriesTableBody');
    const thead = document.getElementById('recentEnquiriesTableHeader');
    
    if (!tbody || !thead) return;
    
    // Switch to Recent Enquiries section if not visible
    const recentSection = document.getElementById('recentEnquiriesSection');
    const activeSectionCard = document.getElementById('card-recentEnquiriesSection');
    if(recentSection.classList.contains('hidden')) {
        toggleSection('recentEnquiriesSection', activeSectionCard);
    }
    
    // Update headers based on type
    if (type === 'fees-paid' || type === 'fees-pending') {
        document.querySelector('#recentEnquiriesSection h3').innerHTML = `<span class="text-2xl">💰</span> ${type === 'fees-paid' ? 'Paid Fees (Confirmed)' : 'Pending Fees (Confirmed)'}`;
        
        thead.innerHTML = `
            <tr>
                <th>Student Name</th>
                <th>Class</th>
                <th>Paid Amount</th>
                <th>Pending Amount</th>
                <th>Payment Status</th>
            </tr>
        `;
    }
    
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12"><div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-blue-600 rounded-full" role="status" aria-label="loading"></div></td></tr>`;
    
    const endpoint = type === 'fees-paid' ? '/enquiry/api/fees-paid' : '/enquiry/api/fees-pending';
    
    fetch(endpoint)
        .then(response => response.json())
        .then(data => {
            if(data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12 text-gray-500">No students found</td></tr>`;
                return;
            }
            
            let html = '';
            data.forEach(student => {
                const statusBadge = student.status === 'Paid' 
                    ? '<span class="crm-badge crm-badge-success">Paid</span>'
                    : '<span class="crm-badge crm-badge-warning" style="background-color: #fef3c7; color: #92400e; border: 1px solid #fde68a;">Pending</span>';
                    
                html += `
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="crm-avatar bg-gradient-to-br from-blue-500 to-indigo-600">
                                    ${student.name.substring(0, 2).toUpperCase()}
                                </div>
                                <span class="font-bold text-gray-900">${student.name}</span>
                            </div>
                        </td>
                        <td><span class="crm-badge crm-badge-info">${student.class}</span></td>
                        <td class="font-bold text-green-600">₹${parseFloat(student.paid_amount).toLocaleString('en-IN')}</td>
                        <td class="font-bold text-red-600">₹${parseFloat(student.pending_amount).toLocaleString('en-IN')}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-12 text-red-500">Error loading data</td></tr>`;
        });
}
</script>
</div>
@endsection
