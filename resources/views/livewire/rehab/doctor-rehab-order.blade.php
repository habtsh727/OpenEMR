<div class="max-w-7xl mx-auto p-4 sm:p-6 space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-0 z-10 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <button wire:click="backToReview" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-7 h-7 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Order Rehabilitation Packages
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Select packages to order for 
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $rehabEncounter->encounter->patient->name }}</span>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 text-sm font-medium rounded-full">
                    {{ $rehabEncounter->status_label }}
                </span>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</span>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white">{{ $rehabEncounter->encounter->patient->name }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Encounter</span>
                    <p class="mt-1 font-medium text-gray-900 dark:text-white font-mono">#{{ $rehabEncounter->encounter_id }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Review Status</span>
                    <p class="mt-1 font-medium text-emerald-600 dark:text-emerald-500">Completed</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Available Packages -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Available Rehabilitation Packages</h2>
            </div>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($availablePackages as $package)
                    <div class="border rounded-xl p-5 transition-all duration-200 {{ in_array($package->id, $selectedPackageIds) ? 'bg-indigo-50/50 dark:bg-indigo-900/20 border-indigo-300 dark:border-indigo-700' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md dark:hover:bg-gray-700/50' }}">
                        <div class="flex justify-between items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate">{{ $package->name }}</h3>
                                    @if(in_array($package->id, $selectedPackageIds))
                                        <span class="px-2 py-0.5 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-400 text-xs font-medium rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Added
                                        </span>
                                    @endif
                                </div>
                                
                                @if($package->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $package->description }}</p>
                                @endif
                                
                                <div class="flex items-center gap-4 mt-3">
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">
                                        ETB {{ number_format($package->final_price, 2) }}
                                    </span>
                                    
                                    @if($package->items->count() > 0)
                                        <div class="flex flex-wrap gap-1.5">
                                            @php
                                                $medCount = $package->items->whereIn('item_type', ['standard_medication', 'custom_medication'])->count();
                                                $serviceCount = $package->items->where('item_type', 'service')->count();
                                                $hasBed = $package->items->where('item_type', 'bed')->isNotEmpty();
                                            @endphp
                                            @if($medCount > 0)
                                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-400 text-xs font-medium rounded-full flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path>
                                                    </svg>
                                                    {{ $medCount }} Meds
                                                </span>
                                            @endif
                                            @if($serviceCount > 0)
                                                <span class="px-2 py-0.5 bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-400 text-xs font-medium rounded-full flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ $serviceCount }} Services
                                                </span>
                                            @endif
                                            @if($hasBed)
                                                <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-400 text-xs font-medium rounded-full flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2M5 12v6M3 20h18M15 12v6"></path>
                                                    </svg>
                                                    Bed
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if(!in_array($package->id, $selectedPackageIds))
                                <button wire:click="addPackage({{ $package->id }})" 
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 whitespace-nowrap shadow-sm hover:shadow">
                                    Add Package
                                </button>
                            {{-- @else
                                <button wire:click="removePackage({{ $package->id }})" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg transition-colors whitespace-nowrap">
                                    Remove
                                </button> --}}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No active packages available.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Draft Order Summary -->
    @if($draftOrder && $draftOrder->packages->isNotEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Order Summary</h2>
                </div>
            </div>
            
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($draftOrder->packages as $orderPackage)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-sm transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $orderPackage->package_name }}</h3>
                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs font-medium rounded-full">
                                            Package
                                        </span>
                                    </div>
                                    <p class="text-lg font-bold text-indigo-600 dark:text-indigo-500">ETB {{ number_format($orderPackage->final_price, 2) }}</p>
                                </div>
                                <button wire:click="removePackage({{ $orderPackage->id }})" 
                                    class="p-2 text-gray-400 hover:text-red-600 dark:text-gray-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors group">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            
                            @if($orderPackage->items->isNotEmpty())
                                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Package Items
                                    </h4>
                                    <div class="grid grid-cols-1 gap-2">
                                        @foreach($orderPackage->items as $item)
                                            <div class="text-sm p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-100 dark:border-gray-600">
                                                <div class="flex justify-between items-center">
                                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $item->item_name }}</span>
                                                    <span class="text-xs px-2 py-1 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full">
                                                        {{ str_replace('_', ' ', $item->item_type) }}
                                                    </span>
                                                </div>
                                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2 text-xs">
                                                    @if($item->dosage)
                                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path>
                                                            </svg>
                                                            <span>{{ $item->dosage }}</span>
                                                        </div>
                                                    @endif
                                                    @if($item->frequency)
                                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                            </svg>
                                                            <span>{{ $item->frequency }}</span>
                                                        </div>
                                                    @endif
                                                    @if($item->duration)
                                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ $item->duration }}</span>
                                                        </div>
                                                    @endif
                                                    @if($item->quantity)
                                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                                                            </svg>
                                                            <span>Qty: {{ $item->quantity }}</span>
                                                        </div>
                                                    @endif
                                                    @if($item->bed_duration_days)
                                                        <div class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2M5 12v6M3 20h18M15 12v6"></path>
                                                            </svg>
                                                            <span>{{ $item->bed_duration_days }} days</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <span class="text-base font-medium text-gray-600 dark:text-gray-400">Total Amount</span>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($totalAmount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="sticky bottom-6 z-10">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
            <div class="flex flex-col sm:flex-row items-center justify-end gap-3">
                <button wire:click="backToReview" 
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition-all w-full sm:w-auto order-2 sm:order-1">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Review
                    </span>
                </button>
                
                <button wire:click="sendToCashier" wire:loading.attr="disabled" 
                    @if(!$draftOrder || $draftOrder->packages->isEmpty()) disabled @endif
                    class="px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-medium rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all w-full sm:w-auto order-1 sm:order-2">
                    <span wire:loading.remove wire:target="sendToCashier" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Send to Cashier
                    </span>
                    <span wire:loading wire:target="sendToCashier" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>

            <!-- Helper Text -->
            @if(!$draftOrder || $draftOrder->packages->isEmpty())
                <p class="mt-3 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-3">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Select at least one package to proceed
                </p>
            @endif
        </div>
    </div>
</div>