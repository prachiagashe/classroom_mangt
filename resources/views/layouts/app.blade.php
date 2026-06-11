<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/icon.png') }}">
    <title>@yield('title', 'Bansal Classes')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSRF Token for AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Tailwind CSS --}}
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">

   {{-- Custom Styles --}}
   <style>
        .student-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        /* Mobile responsive styles */
        @media (max-width: 768px) {
            .sidebar-mobile {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 50;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar-mobile.open {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 100vw;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
                display: none;
            }
            
            .sidebar-overlay.show {
                display: block;
            }
        }
        
        /* Custom Pagination Styles */
        .pagination {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 4px;
        }
        
        .pagination .page-item {
            display: inline-block;
        }
        
        .pagination .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .pagination .page-link:hover {
            background-color: #f3f4f6;
            border-color: #d1d5db;
            color: #374151;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #d1d5db;
            cursor: not-allowed;
        }
        
        /* Desktop sidebar toggle styles */
        @media (min-width: 768px) {
            #sidebar {
                transition: margin-left 0.3s ease-in-out, transform 0.3s ease-in-out;
            }
            
            .sidebar-desktop-hidden {
                margin-left: -16rem; /* Tailwind w-64 equivalent */
            }
            
            .main-content-full {
                flex-grow: 1;
                transition: all 0.3s ease-in-out;
            }
        }
        /* CRM Standardized Styles */
        .crm-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
        }
        
        .crm-table thead th {
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            color: #6b7280;
            background-color: #f9fafb;
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
            white-space: nowrap;
        }
        
        .crm-table tbody tr {
            height: 58px;
            transition: all 0.2s;
        }
        
        .crm-table tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }
        
        .crm-table tbody tr:hover {
            background-color: rgba(239, 246, 255, 0.5);
        }
        
        .crm-table td {
            padding: 12px 16px;
            vertical-align: middle;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
        }
        
        .crm-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .crm-badge-success { background-color: #dcfce7; color: #166534; }
        .crm-badge-warning { background-color: #fef3c7; color: #d97706; }
        .crm-badge-danger { background-color: #fee2e2; color: #991b1b; }
        .crm-badge-info { background-color: #dbeafe; color: #1e40af; }
        
        .crm-avatar {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            color: white;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .secondary-text {
            font-size: 12px;
            color: #9ca3af;
        }
    </style>

</head>

<body class="bg-gray-100 {{ isset($noSidebar) && $noSidebar ? '' : 'h-screen overflow-hidden' }}">

<div class="flex {{ isset($noSidebar) && $noSidebar ? 'min-h-screen' : 'h-full relative overflow-hidden' }}">

    {{-- Mobile Sidebar Overlay --}}
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    @if(!isset($noSidebar) || !$noSidebar)
    {{-- Sidebar - Conditional based on user role --}}
    <aside class="w-64 text-white shadow-xl sidebar-mobile md:relative md:transform-none xl:transition-transform xl:duration-300  flex-shrink-0" id="sidebar">
        @if(Auth::check() && Auth::user()->role === 'teacher')
            <div class="bg-gray-900 h-full">
                @include('layouts.teacher-sidebar')
            </div>
        @elseif(Auth::check() && Auth::user()->role === 'student')
            <div class="student-sidebar h-full" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                @include('layouts.student-sidebar')
            </div>
        @else
            <div class="bg-gray-900 h-full">
                @include('layouts.sidebar')
            </div>
        @endif
    </aside>
    @endif

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col {{ isset($noSidebar) && $noSidebar ? 'min-h-screen overscroll-none' : 'h-full' }} md:transition-all md:duration-300" id="mainContent">

        @if(!isset($noHeader) || !$noHeader)
        {{-- Header --}}
        <header class="bg-white shadow relative">
            @include('layouts.header')
        </header>
        @endif

        {{-- Page Content --}}
        <main class="flex-1 p-4 md:p-6 {{ isset($noSidebar) && $noSidebar ? '' : 'overflow-y-auto' }}">
            @yield('content')
        </main>

    </div>

</div>

{{-- Mobile Sidebar Toggle Script --}}
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
}

function toggleSidebarDesktop() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleButton = document.getElementById('sidebarToggle');
    
    sidebar.classList.toggle('sidebar-desktop-hidden');
    mainContent.classList.toggle('main-content-full');
    
    // Rotate arrow based on sidebar state
    if (sidebar.classList.contains('sidebar-desktop-hidden')) {
        toggleButton.innerHTML = '<svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>';
    } else {
        toggleButton.innerHTML = '<svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>';
    }
}

// Close sidebar when clicking outside on mobile
window.addEventListener('resize', function() {
    if (window.innerWidth >= 768) {
        closeSidebar();
    }
});
</script>


    {{-- SweetAlert2 Flash Messages --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: {!! json_encode(session('success')) !!},
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: {!! json_encode(session('error')) !!},
                    timer: 5000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>

    @stack('modals')
</body>
</html>
