@extends('layouts.app')
@section('title', 'BANSAL CLASS - Reports Dashboard')
@section('content')

<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 p-6">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <!-- Page Header -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-blue-600 mb-2">Reports Dashboard</h1>
                <p class="text-gray-500">Analytics and insights for your CRM performance</p>
            </div>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
        </div>

        <!-- TOP SECTION – Enhanced Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
            
            <!-- Total Enquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full">TOTAL</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $totalEnquiries ?? 0 }}</div>
                <div class="text-sm text-gray-600 font-medium">Total Enquiries</div>
            </div>

            <!-- New Enquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-green-700 bg-green-50 px-3 py-1 rounded-full">NEW</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $newEnquiries ?? 0 }}</div>
                <div class="text-sm text-gray-600 font-medium">New Enquiries</div>
            </div>

            <!-- Follow-up Enquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-yellow-700 bg-yellow-50 px-3 py-1 rounded-full">FOLLOW-UP</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $followUpEnquiries ?? 0 }}</div>
                <div class="text-sm text-gray-600 font-medium">Follow-up Enquiries</div>
            </div>

            <!-- Confirmed Admissions -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full">CONFIRMED</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $confirmedAdmissions ?? 0 }}</div>
                <div class="text-sm text-gray-600 font-medium">Confirmed Admissions</div>
            </div>

            <!-- Rejected Enquiries -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-400 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-red-700 bg-red-50 px-3 py-1 rounded-full">REJECTED</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $rejectedEnquiries ?? 0 }}</div>
                <div class="text-sm text-gray-600 font-medium">Rejected Enquiries</div>
            </div>

            <!-- Conversion Rate -->
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-3 py-1 rounded-full">RATE</span>
                </div>
                <div class="text-4xl font-bold text-gray-900 mb-2">{{ $conversionRate ?? 0 }}%</div>
                <div class="text-sm text-gray-600 font-medium">Conversion Rate</div>
            </div>
        </div>

        <!-- SECOND SECTION – Enhanced Date Filter -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">📅 Date Range Filter</h3>
                    <p class="text-gray-600 mt-1">Filter reports by date range</p>
                </div>
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-3 rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div class="flex justify-end items-center">
                <form method="GET" action="{{ request()->url() }}" class="flex items-end gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" 
                               class="w-40 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" 
                               class="w-40 px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-3 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Apply Filter
                    </button>
                    <a href="{{ request()->url() }}" 
                       class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl font-semibold hover:bg-gray-300 transition-all duration-300">
                        Reset
                    </a>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- THIRD SECTION – Enhanced Class Admission Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">📊 Admissions by Class</h2>
                        <p class="text-gray-600 mt-1">Breakdown of admissions by class</p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                
                @if(isset($classWiseAdmissions) && count($classWiseAdmissions) > 0)
                    <div class="relative h-80 mb-8">
                        <canvas id="classChart"></canvas>
                    </div>
                    
                    <!-- Enhanced Class Legend -->
                    <div class="grid grid-cols-1 gap-3 overflow-y-auto max-h-60">
                        @foreach($classWiseAdmissions as $class => $count)
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl hover:from-purple-50 hover:to-purple-100 transition-all duration-300 border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-4 h-4 rounded-full bg-gradient-to-r from-purple-500 to-blue-600"></div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $class }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-lg font-bold text-gray-900">{{ $count }}</span>
                                    <span class="text-xs text-gray-500">students</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div class="text-gray-400 mb-6">
                            <svg class="w-20 h-20 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <p class="text-gray-500 text-lg font-medium">No class data available</p>
                        <p class="text-gray-400 text-sm mt-2">Start adding admissions to see class analytics</p>
                    </div>
                @endif
            </div>

            <!-- FOURTH SECTION – Enhanced Enquiry Status Distribution Chart -->
            <div class="bg-white rounded-2xl shadow-lg p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">📈 Enquiry Status Distribution</h2>
                        <p class="text-gray-600 mt-1">Confirmed vs Follow-up vs Rejected</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-bold text-blue-700 bg-blue-50 px-3 py-1 rounded-full">Total: {{ $totalEnquiries ?? 0 }}</span>
                        <div class="bg-gradient-to-r from-blue-500 to-green-600 p-3 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="relative h-80 mb-8">
                    <canvas id="statusChart"></canvas>
                </div>
                
                <!-- Status Legend -->
                <div class="grid grid-cols-1 gap-3">
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-green-500"></div>
                            <span class="text-sm font-semibold text-green-800">Confirmed Admissions</span>
                        </div>
                        <span class="text-lg font-bold text-green-900">{{ $confirmedAdmissions ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl border border-yellow-200">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-yellow-500"></div>
                            <span class="text-sm font-semibold text-yellow-800">In Follow-up</span>
                        </div>
                        <span class="text-lg font-bold text-yellow-900">{{ $followUpEnquiries ?? 0 }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-red-50 to-red-100 rounded-xl border border-red-200">
                        <div class="flex items-center gap-3">
                            <div class="w-4 h-4 rounded-full bg-red-500"></div>
                            <span class="text-sm font-semibold text-red-800">Rejected Enquiries</span>
                        </div>
                        <span class="text-lg font-bold text-red-900">{{ $rejectedEnquiries ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Class Admission Chart
    @if(isset($classWiseAdmissions) && count($classWiseAdmissions) > 0)
        const classCtx = document.getElementById('classChart').getContext('2d');
        new Chart(classCtx, {
            type: 'bar',
            data: {
                labels: @json(array_keys($classWiseAdmissions)),
                datasets: [{
                    data: @json(array_values($classWiseAdmissions)),
                    backgroundColor: [
                        '#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#6366F1',
                        '#14B8A6', '#F97316', '#84CC16', '#06B6D4', '#A855F7', '#EC4899'
                    ],
                    borderRadius: 6,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return `Students: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            precision: 0,
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    @endif

    // Status Distribution Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Follow-up', 'Rejected'],
            datasets: [{
                data: [
                    {{ $confirmedAdmissions ?? 0 }}, 
                    {{ $followUpEnquiries ?? 0 }}, 
                    {{ $rejectedEnquiries ?? 0 }}
                ],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                hoverOffset: 15,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.parsed;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return ` ${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
</script>

@endsection
