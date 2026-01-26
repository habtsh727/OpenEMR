@props(['name', 'maxWidth' => '2xl'])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
    '3xl' => 'sm:max-w-3xl', // Add this
    '4xl' => 'sm:max-w-4xl', // Optional: add more sizes
    '5xl' => 'sm:max-w-5xl', // Optional: add more sizes
    '6xl' => 'sm:max-w-6xl', // Optional: add more sizes
    '7xl' => 'sm:max-w-7xl', // Optional: add more sizes
    'full' => 'sm:max-w-full', // Optional: add full width
][$maxWidth] ?? 'sm:max-w-2xl'; // Add null coalescing operator as fallback
@endphp

<div x-data="{ show: false }"
     x-on:open-modal.window="$event.detail === '{{ $name }}' ? show = true : null"
     x-on:close-modal.window="show = false"
     x-show="show"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="show" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>&#8203;

        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $maxWidth }} sm:w-full sm:p-6"
             role="dialog"
             aria-modal="true"
             aria-labelledby="modal-headline">
            {{ $slot }}
        </div>
    </div>
</div>