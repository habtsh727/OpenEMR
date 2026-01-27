<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        Order Medication
                    </h1>
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex flex-wrap items-center gap-2 md:gap-4">
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <strong>Patient:</strong> 
                                {{ $patient->first_name }} {{ $patient->last_name }}
                                @if($patient->medical_record_number)
                                    ({{ $patient->medical_record_number }})
                                @endif
                            </span>
                            <span class="hidden md:inline">•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <strong>Encounter:</strong> #{{ $encounter->id }}
                            </span>
                            @if($labOrder)
                            <span class="hidden md:inline">•</span>
                            <span class="inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                                <strong>Lab Test:</strong> {{ $labOrder->labTest->name ?? 'N/A' }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('doctor.lab-results') }}" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Lab Results
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if($successMessage)
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-green-800 dark:text-green-300">{{ $successMessage }}</span>
                <button wire:click="clearMessages" class="ml-auto text-green-600 hover:text-green-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        @if($errorMessage)
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <span class="text-red-800 dark:text-red-300">{{ $errorMessage }}</span>
                <button wire:click="clearMessages" class="ml-auto text-red-600 hover:text-red-800">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        @endif

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Search & Selected Items -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Search Medication -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Search Medication
                    </h2>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search by medication name, generic name, or code..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                    </div>
                    
                    <!-- Search Results -->
                @if($search && !empty($searchResults))
                    <div class="mt-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <div class="max-h-64 overflow-y-auto">
                            @foreach($searchResults as $item)
                            <button type="button"
                                    wire:click="selectMedication({{ $item->id }})"
                                    class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                                <div class="flex justify-between items-center">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                        @if($item->generic_name)
                                        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $item->generic_name }}</div>
                                        @endif
                                        <div class="flex flex-wrap items-center gap-2 mt-1">
                                            <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">
                                                {{ $item->code }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->strength }}
                                            </span>
                                            <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded">
                                                {{ $item->unit->short_name ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-right ml-4">
                                        <div class="text-sm font-medium {{ $item->has_stock ? 'text-green-600 dark:text-green-400' : 'text-yellow-600 dark:text-yellow-400' }}">
                                            {{ $item->has_stock ? 'In Stock' : 'Out of Stock' }}
                                        </div>
                                        @if($item->has_stock)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $item->available_stock }} available
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @elseif($search && !empty($searchResults))
                    <div class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                            <span class="text-sm text-yellow-800 dark:text-yellow-300">
                                No medications found. Try a different search or add a custom medication.
                            </span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Add Custom Medication Button -->
                    <button type="button"
                            wire:click="addCustomMedication"
                            class="mt-4 w-full px-4 py-3 border border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 hover:border-gray-400 dark:hover:border-gray-500 transition-colors flex items-center justify-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Custom Medication
                    </button>
                </div>

                <!-- Selected Medications -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Selected Medications
                            @if(!empty($selectedItems))
                            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                                ({{ count($selectedItems) }} items)
                            </span>
                            @endif
                        </h2>
                        
                        @if(!empty($selectedItems))
                        <button wire:click="resetForm"
                                class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Clear All
                        </button>
                        @endif
                    </div>
                    
                    @if(!empty($selectedItems))
                    <div class="space-y-4">
                        @foreach($selectedItems as $index => $item)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-blue-300 dark:hover:border-blue-700 transition-colors">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-start gap-3">
                                        @if($item['is_custom'])
                                        <div class="h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="h-5 w-5 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        @else
                                        <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                        </div>
                                        @endif
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-semibold text-gray-900 dark:text-white">
                                                    {{ $item['name'] }}
                                                    @if($item['is_custom'])
                                                    <span class="text-xs text-purple-600 dark:text-purple-400 ml-2">(Custom)</span>
                                                    @endif
                                                </h4>
                                                @if($item['unit_price'] > 0)
                                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                                    ${{ number_format($item['unit_price'], 2) }} each
                                                </span>
                                                @endif
                                            </div>
                                            
                                            <div class="mt-2 space-y-1">
                                                <!-- Dosage & Frequency -->
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $item['dosage'] }}
                                                    </span>
                                                    
                                                    {{-- @isset($item['frequency_name'])
                                                        @if($item['frequency_name'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        {{ $item['frequency_name'] }}
                                                    </span>
                                                    @endif
                                                    @endisset --}}
                                                    
                                                    @if($item['duration'])
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ $item['duration'] }}
                                                    </span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Stock Status -->
                                                @if($item['from_stock'])
                                                <div class="text-xs text-green-600 dark:text-green-400 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                    Will be dispensed from hospital stock
                                                    @if($item['batch_info'])
                                                    <span class="text-gray-500">({{ $item['batch_info'] }})</span>
                                                    @endif
                                                </div>
                                                @elseif(!$item['is_custom'])
                                                <div class="text-xs text-yellow-600 dark:text-yellow-400 flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                    </svg>
                                                    Out of stock - prescription only
                                                </div>
                                                @endif
                                                
                                                <!-- Instructions -->
                                                @if($item['instructions'])
                                                <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                    <span class="font-medium">Instructions:</span> {{ $item['instructions'] }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2 ml-4">
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            Qty: {{ $item['quantity'] }}
                                        </div>
                                        @if($item['unit_price'] > 0)
                                        <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            ${{ number_format($item['unit_price'] * $item['quantity'], 2) }}
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="flex flex-col gap-1">
                                        <button wire:click="editItem({{ $index }})"
                                                class="p-1.5 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="removeItem({{ $index }})"
                                                class="p-1.5 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">No medications selected</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Search for medications above or add custom medications to get started.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Order Summary & Actions -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Order Summary
                    </h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Total Items</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ count($selectedItems) }}</span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">From Stock</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ collect($selectedItems)->where('from_stock', true)->count() }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Custom Items</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ collect($selectedItems)->where('is_custom', true)->count() }}
                            </span>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            @php
                                $totalAmount = collect($selectedItems)->sum(fn($item) => $item['unit_price'] * $item['quantity']);
                            @endphp
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Total Amount</span>
                                <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                    ${{ number_format($totalAmount, 2) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Clinical Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Clinical Notes
                    </h2>
                    
                    <textarea wire:model="clinicalNotes"
                              rows="4"
                              placeholder="Add any clinical notes, diagnosis, or special instructions for the pharmacy..."
                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors"></textarea>
                </div>

                <!-- Order Options -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Order Options
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <input type="checkbox"
                                   id="prescriptionOnly"
                                   wire:model="prescriptionOnly"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded mt-1">
                            <div class="ml-3">
                                <label for="prescriptionOnly" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Prescription Only
                                </label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Patient will purchase medication from external pharmacy. Only prescription will be created.
                                </p>
                            </div>
                        </div>
                        
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
                            <div class="flex items-center">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-sm text-blue-800 dark:text-blue-300">
                                    @if($prescriptionOnly)
                                        Only prescription will be created. Patient can fill it at any pharmacy.
                                    @else
                                        Order will be sent to cashier for payment, then to pharmacy for dispensing.
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="space-y-3">
                        <button wire:click="submitOrder"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                @if(empty($selectedItems)) disabled @endif
                                class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-sm hover:shadow flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span wire:loading.remove wire:target="submitOrder">
                                @if($prescriptionOnly)
                                    Create Prescription
                                @else
                                    Submit Order to Cashier
                                @endif
                            </span>
                            <span wire:loading wire:target="submitOrder">
                                Processing...
                            </span>
                        </button>
                        
                        <a href="{{ route('doctor.lab-results') }}"
                           class="w-full px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors flex items-center justify-center gap-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Cancel Order
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Medication Modal -->
    @if($showAddModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:key="add-medication-modal">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Overlay -->
            <div class="fixed inset-0 transition-opacity" wire:click="resetAddModal">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="px-6 py-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            @if($isCustomMedication)
                                Add Custom Medication
                            @else
                                Add Medication Details
                            @endif
                        </h3>
                        <button wire:click="resetAddModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        @if($isCustomMedication)
                        <!-- Custom Medication Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Medication Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="customMedicationName"
                                   placeholder="e.g., Herbal supplement, Special ointment"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                            @error('customMedicationName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        @elseif($currentItem)
                        <!-- Existing Medication Info -->
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $currentItem->name }}</div>
                            @if($currentItem->generic_name)
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $currentItem->generic_name }}</div>
                            @endif
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $currentItem->strength }} • {{ $currentItem->unit->name ?? '' }}
                            </div>
                        </div>
                        @endif
                        
                        <!-- Dosage -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dosage <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   wire:model="currentDosage"
                                   placeholder="e.g., 1 tablet, 5ml, 500mg"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                            @error('currentDosage') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Frequency -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Frequency (Optional)
                            </label>
                            <select wire:model="currentFrequencyId"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                                <option value="">Select Frequency</option>
                                @foreach($frequencies as $frequency)
                                <option value="{{ $frequency->id }}">
                                    {{ $frequency->name }} ({{ $frequency->short_code }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Duration -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Duration (Optional)
                            </label>
                            <input type="text"
                                   wire:model="currentDuration"
                                   placeholder="e.g., 7 days, 2 weeks, 1 month"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                            @error('currentDuration') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- Instructions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Instructions (Optional)
                            </label>
                            <textarea wire:model="currentInstructions"
                                      rows="2"
                                      placeholder="e.g., Take after food, Avoid dairy products, Apply twice daily"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors"></textarea>
                        </div>
                        
                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number"
                                   wire:model="currentQuantity"
                                   min="1"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                            @error('currentQuantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        
                        <!-- From Stock (only for non-custom items with stock) -->
                        @if(!$isCustomMedication && $currentItem && ($currentItem->has_stock ?? false))
                        <div class="flex items-center">
                            <input type="checkbox"
                                   id="fromStock"
                                   wire:model="currentFromStock"
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="fromStock" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Dispense from hospital stock
                            </label>
                        </div>
                        @endif
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="button"
                                    wire:click="resetAddModal"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button type="button"
                                    wire:click="addToOrder"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                Add to Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
</div>