@props([
    'id' => 'required',
    'title' => 'required',
    'maxWidth' => '2xl',
    'show' => false,
    'backdropClose' => true,
    'escClose' => true
])

@php
    $maxWidthClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md', 
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '6xl' => 'max-w-6xl',
        '7xl' => 'max-w-7xl',
        'full' => 'max-w-full'
    ];
    
    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['2xl'];
@endphp

<!-- Modal Backdrop -->
<div 
    id="{{ $id }}-backdrop"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 transition-opacity duration-300 {{ $show ? 'opacity-100' : 'opacity-0 pointer-events-none' }}"
    @if($backdropClose)
        onclick="closeModal('{{ $id }}')"
    @endif
>
    <!-- Modal Container -->
    <div 
        id="{{ $id }}"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto"
        @if($escClose)
            x-data="{ open: {{ $show ? 'true' : 'false' }}"
            x-init="window.addEventListener('keydown', (e) => e.key === 'Escape' && open && closeModal('{{ $id }}'))"
        @endif
    >
        <!-- Modal Content -->
        <div 
            class="relative w-full {{ $maxWidthClass }} mx-auto bg-white rounded-2xl shadow-2xl transform transition-all duration-300 {{ $show ? 'scale-100 opacity-100 translate-y-0' : 'scale-95 opacity-0 translate-y-4' }}"
            onclick="event.stopPropagation()"
        >
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">{{ $title }}</h2>
                <button 
                    type="button"
                    onclick="closeModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 transition-colors p-2 rounded-lg hover:bg-gray-100"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 max-h-[70vh] overflow-y-auto">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Scripts -->
<script>
// Modal Management Functions
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + '-backdrop');
    
    if (modal && backdrop) {
        // Show modal with animation
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-100');
        
        modal.classList.remove('scale-95', 'opacity-0', 'translate-y-4');
        modal.classList.add('scale-100', 'opacity-100', 'translate-y-0');
        
        // Lock body scroll
        document.body.style.overflow = 'hidden';
        document.body.style.paddingRight = '0px';
        
        // Focus management
        setTimeout(() => {
            const firstInput = modal.querySelector('input, select, textarea, button');
            if (firstInput) firstInput.focus();
        }, 100);
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const backdrop = document.getElementById(modalId + '-backdrop');
    
    if (modal && backdrop) {
        // Hide modal with animation
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        
        modal.classList.remove('scale-100', 'opacity-100', 'translate-y-0');
        modal.classList.add('scale-95', 'opacity-0', 'translate-y-4');
        
        // Restore body scroll
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
        
        // Remove focus
        setTimeout(() => {
            modal.blur();
        }, 100);
    }
}

// Initialize modals on page load
document.addEventListener('DOMContentLoaded', function() {
    // Auto-close modals on backdrop click
    document.querySelectorAll('[data-modal-backdrop-close]').forEach(backdrop => {
        backdrop.addEventListener('click', function(e) {
            if (e.target === backdrop) {
                const modalId = backdrop.id.replace('-backdrop', '');
                closeModal(modalId);
            }
        });
    });
    
    // ESC key handling
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.fixed.inset-0.z-50 .scale-100');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
});

// Form grid responsive helper
function getFormGridClasses() {
    return 'grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6';
}

// Button responsive helper
function getButtonContainerClasses() {
    return 'flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 mt-6';
}
</script>

<style>
/* Custom scrollbar for modal content */
.modal-content::-webkit-scrollbar {
    width: 6px;
}

.modal-content::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden !important;
    padding-right: 15px !important;
}

/* Smooth transitions */
.modal-backdrop {
    transition: opacity 0.3s ease-in-out;
}

.modal-content {
    transition: all 0.3s ease-in-out;
}
</style>
