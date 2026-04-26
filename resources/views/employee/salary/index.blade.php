@extends('layouts.app')

@section('title', 'Salary Management')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -mt-6 -mx-6 p-6">
    <!-- Page Header -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-blue-600 mb-2">Salary Management</h1>
            <p class="text-gray-500">Manage employee payroll and salary generation</p>
        </div>
        <div class="flex gap-4 items-center">
            <!-- Generate Salary Form -->
            <form method="POST" action="{{ route('salary.generate') }}" class="flex gap-2 items-center bg-gray-50 p-2 rounded-xl border border-gray-200">
                @csrf
                <select name="month" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-sm">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
                <select name="year" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 bg-white text-sm">
                    @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:shadow-md transition-all text-sm font-medium">
                    Generate Salary
                </button>
            </form>
            <div class="bg-blue-600 p-4 rounded-xl shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Month & Year Filter -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('salary.index') }}" class="flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <select name="month" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select name="year" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                    @for($i = date('Y'); $i >= date('Y') - 2; $i--)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>
        </form>
    </div>

    <!-- Salary Records Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left p-4 font-semibold text-gray-700">Employee Name</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Employee Code</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Basic Salary</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Net Salary</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Paid Amount</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Status</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Payment Date</th>
                        <th class="text-left p-4 font-semibold text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($employeesWithSalary as $employeeData)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="p-4">
                                <a href="{{ route('salary.history', $employeeData['employee']->employee_code) }}" 
                                   class="font-medium text-gray-900 hover:text-blue-800 transition-colors">
                                    {{ $employeeData['employee']->full_name }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $employeeData['employee']->email }}</div>
                            </td>
                            <td class="p-4 font-mono text-sm text-gray-900">
                                <a href="{{ route('salary.history', $employeeData['employee']->employee_code) }}" 
                                   class="text-gray-900 hover:text-blue-900 transition-colors">
                                    #{{ $employeeData['employee']->employee_code }}
                                </a>
                            </td>
                            <td class="p-4 text-gray-900">
                                @if($employeeData['salary_record'])
                                    ₹{{ number_format($employeeData['salary_record']->basic_salary, 2) }}
                                @else
                                    ₹{{ number_format($employeeData['employee']->basic_salary, 2) }}
                                @endif
                            </td>
                            <td class="p-4 text-gray-900">
                                @if($employeeData['salary_record'])
                                    ₹{{ number_format($employeeData['salary_record']->net_salary, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4 text-gray-900">
                                @if($employeeData['salary_record'])
                                    ₹{{ number_format($employeeData['salary_record']->paid_amount, 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4">
                                @switch($employeeData['status'])
                                    @case('not_generated')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Not Generated
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                        @break
                                    @case('partial')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            Partial
                                        </span>
                                        @break
                                    @case('paid')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                        @break
                                @endswitch
                            </td>
                            <td class="p-4 text-gray-900">
                                @if($employeeData['salary_record'] && $employeeData['salary_record']->payment_date)
                                    {{ date('d M Y', strtotime($employeeData['salary_record']->payment_date)) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-4">
                                @if($employeeData['status'] == 'not_generated')
                                    <form method="POST" action="{{ route('salary.generate') }}" class="inline">
                                        @csrf
                                        <input type="hidden" name="month" value="{{ $selectedMonth }}">
                                        <input type="hidden" name="year" value="{{ $selectedYear }}">
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                            Generate Salary
                                        </button>
                                    </form>
                                @else
                                    <div class="flex gap-2">
                                        <a href="{{ route('salary.pay', $employeeData['salary_record']->id) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            Pay Salary
                                        </a>
                                        <a href="{{ route('salary.history', $employeeData['employee']->employee_code) }}" 
                                           class="inline-flex items-center px-3 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                                            View History
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-gray-500">
                                No active employees found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
