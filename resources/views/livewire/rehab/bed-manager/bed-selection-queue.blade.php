<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200" x-data="{ showFilters: false }">
    {{-- Alert Component with Dark Mode Support --}}
    <div x-data="{ show: @entangle('showAlert') }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
            <div @class([
                'rounded-xl shadow-lg p-4 border backdrop-blur-sm',
                'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' => $alertType === 'success',
                'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' => $alertType === 'error',
                'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' => $alertType === 'info',
                'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800' => $alertType === 'warning',
            ])>
                <div class="flex items-start gap-3">
                    <div @class([
                        'shrink-0',
                        'text-green-600 dark:text-green-400' => $alertType === 'success',
                        'text-red-600 dark:text-red-400' => $alertType === 'error',
                        'text-blue-600 dark:text-blue-400' => $alertType === 'info',
                        'text-yellow-600 dark:text-yellow-400' => $alertType === 'warning',
                    ])>
                        @if($alertType === 'success')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                        @elseif($alertType === 'error')
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $alertMessage }}</p>
                    </div>
                    <button wire:click="closeAlert" class="shrink-0 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header with Stats --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        Bed Selection Queue
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select bed class and specific bed for patients awaiting admission</p>
                </div>
                <button @click="showFilters = !showFilters" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filters
                </button>
            </div>

            {{-- Statistics Cards with Dark Mode --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Available Beds</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $totalAvailableBeds }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Reserved Beds</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $totalReservedBeds }}</p>
                        </div>
                        <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Occupied Beds</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalOccupiedBeds }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waiting Patients</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $encounters->total() }}</p>
                        </div>
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filters with Dark Mode --}}
        <div class="mb-6 space-y-4">
            <div class="relative">
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by patient name or MRN..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            {{-- Expandable Filters with Dark Mode --}}
            <div x-show="showFilters" x-collapse class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 transition-colors">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bed Class</label>
                        <select wire:model.live="selectedBedClass" class="w-full border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">All Classes</option>
                            @foreach($bedClasses as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration Range</label>
                        <div class="flex gap-2">
                            <input type="number" placeholder="Min" class="w-full border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <input type="number" placeholder="Max" class="w-full border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select wire:model.live="statusFilter" class="w-full border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="pending">Pending Selection</option>
                            <option value="selected">Selected</option>
                            <option value="all">All</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Queue Table with Dark Mode --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient Information</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estimated Cost</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($encounters as $encounter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition group">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 flex items-center justify-center text-white font-medium shadow-sm">
                                        {{ substr($encounter->encounter->patient->name ?? 'N/A', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                            {{ $encounter->encounter->patient->name ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            MRN: {{ $encounter->encounter->patient->medical_record_number ?? 'N/A' }}
                                        </div>
                                        <div class="text-xs text-gray-400 dark:text-gray-500">
                                            Order #{{ $encounter->rehabOrders->first()?->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $encounter->encounter->doctor->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Attending</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $encounter->bed_duration }} <span class="text-sm font-normal text-gray-500 dark:text-gray-400">days</span></div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $estimatedRange = [
                                        'min' => $bedClasses->min('price_per_day') * $encounter->bed_duration,
                                        'max' => $bedClasses->max('price_per_day') * $encounter->bed_duration
                                    ];
                                @endphp
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    ETB {{ number_format($estimatedRange['min'], 0) }} - {{ number_format($estimatedRange['max'], 0) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Estimated range</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Awaiting Selection
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="openSelectBedModal({{ $encounter->id }})"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="opacity-50 cursor-not-allowed"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md disabled:opacity-50 group">
                                    <span wire:loading.remove wire:target="openSelectBedModal({{ $encounter->id }})" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                        </svg>
                                        Select Bed
                                    </span>
                                    <span wire:loading wire:target="openSelectBedModal({{ $encounter->id }})" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-4">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No patients waiting</h3>
                                    <p class="text-gray-500 dark:text-gray-400 max-w-sm">All patients have been assigned beds or there are no pending orders requiring bed selection.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination with Dark Mode --}}
            @if($encounters->hasPages())
                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                    {{ $encounters->links() }}
                </div>
            @endif
        </div>

        {{-- Bed Selection Modal with Dark Mode --}}
        @if($showSelectBedModal && $selectedEncounter)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-80 transition-opacity" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
                        {{-- Header with Patient Info --}}
                        <div class="sticky top-0 bg-white dark:bg-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700 z-10">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                        {{ substr($patientName, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">
                                            Select Bed for {{ $patientName }}
                                        </h3>
                                        <div class="flex items-center gap-4 mt-1 text-sm">
                                            <span class="text-gray-500 dark:text-gray-400">MRN: <span class="font-medium text-gray-900 dark:text-white">{{ $mrn }}</span></span>
                                            <span class="text-gray-500 dark:text-gray-400">Doctor: <span class="font-medium text-gray-900 dark:text-white">{{ $doctorName }}</span></span>
                                            <span class="text-gray-500 dark:text-gray-400">Duration: <span class="font-medium text-blue-600 dark:text-blue-400">{{ $bedDuration }} days</span></span>
                                        </div>
                                    </div>
                                </div>
                                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal Body with Dark Mode --}}
                        <div class="px-6 py-4 max-h-[calc(100vh-16rem)] overflow-y-auto">
                            {{-- Quick Stats Card with Dark Mode --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 border border-blue-100 dark:border-blue-800">
                                    <p class="text-xs text-blue-600 dark:text-blue-400 uppercase font-medium">Requested Duration</p>
                                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $bedDuration }} <span class="text-sm font-normal">days</span></p>
                                </div>
                                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 border border-green-100 dark:border-green-800">
                                    <p class="text-xs text-green-600 dark:text-green-400 uppercase font-medium">Available in Class</p>
                                    <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $availableBeds->count() }} <span class="text-sm font-normal">beds</span></p>
                                </div>
                                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-3 border border-purple-100 dark:border-purple-800">
                                    <p class="text-xs text-purple-600 dark:text-purple-400 uppercase font-medium">Est. Total Range</p>
                                    <p class="text-lg font-bold text-purple-700 dark:text-purple-300">
                                        ETB {{ number_format($bedClasses->min('price_per_day') * $bedDuration, 0) }} - {{ number_format($bedClasses->max('price_per_day') * $bedDuration, 0) }}
                                    </p>
                                </div>
                            </div>

                            {{-- Bed Class Selection with Dark Mode --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                        Select Bed Class
                                    </span>
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    @foreach($bedClasses as $class)
                                        <div wire:key="{{ $class->id }}" class="relative">
                                            <input type="radio" 
                                                   wire:model.live="selectedBedClass" 
                                                   value="{{ $class->id }}" 
                                                   id="class_{{ $class->id }}"
                                                   class="hidden peer">
                                            <label for="class_{{ $class->id }}" 
                                                   class="block p-4 border-2 rounded-xl cursor-pointer transition-all
                                                          peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20
                                                          hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-md
                                                          dark:border-gray-600 dark:hover:bg-gray-700/50
                                                          {{ $selectedBedClass === $class->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-600' }}">
                                                <div class="flex justify-between items-start mb-2">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $class->name }}</h4>
                                                    @if($selectedBedClass === $class->id)
                                                        <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">Selected</span>
                                                    @endif
                                                </div>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-white mb-1">
                                                    ETB {{ number_format($class->price_per_day, 0) }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">per day</p>
                                                <p class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-2">
                                                    Total: ETB {{ number_format($class->price_per_day * $bedDuration, 0) }}
                                                </p>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($selectedBedClass)
                                {{-- Bed Search with Dark Mode --}}
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            Search Available Beds
                                        </span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               wire:model.live.debounce.300ms="bedSearch" 
                                               placeholder="Search by bed number, room, or ward..."
                                               class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Available Beds Table with Dark Mode --}}
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bed</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Room</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ward</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Floor</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @forelse($availableBeds as $bed)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $selectedBedId === $bed->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $bed->bed_number }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $bed->room->room_number }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $bed->room->ward->name }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="text-sm text-gray-600 dark:text-gray-300">{{ $bed->room->floor ?? 'N/A' }}</span>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                            {{ $bed->bedType->name ?? 'Standard' }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        @if($selectedBedId === $bed->id)
                                                            <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Selected
                                                            </span>
                                                        @else
                                                            <button wire:click="selectBed({{ $bed->id }})" 
                                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium transition">
                                                                Select
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                                        <div class="flex flex-col items-center">
                                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                            </svg>
                                                            <p>No available beds in this class</p>
                                                            <p class="text-xs mt-1">Try selecting a different bed class</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                {{-- Price Summary Card with Dark Mode --}}
                                @if($selectedBed)
                                    @php
                                        $selectedClass = $bedClasses->firstWhere('id', $selectedBedClass);
                                    @endphp
                                    <div class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-4">
                                                <div class="p-3 bg-white dark:bg-gray-800 rounded-xl shadow-sm">
                                                    <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">Selected Bed</p>
                                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Bed #{{ $selectedBed->bed_number }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Room {{ $selectedBed->room->room_number }}, {{ $selectedBed->room->ward->name }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Cost</p>
                                                <p class="text-3xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($selectedClass->price_per_day * $bedDuration, 2) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bedDuration }} days × ETB {{ number_format($selectedClass->price_per_day, 2) }}/day</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- Modal Footer with Dark Mode --}}
                        <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="closeModal" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Cancel
                                </button>
                                <button wire:click="confirmBedSelection"
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md disabled:opacity-50">
                                    <span wire:loading.remove wire:target="confirmBedSelection" class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Confirm Bed Selection
                                    </span>
                                    <span wire:loading wire:target="confirmBedSelection" class="flex items-center gap-2">
                                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Processing...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @push('styles')
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush
</div>