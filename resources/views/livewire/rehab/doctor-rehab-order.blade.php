<div class="max-w-7xl mx-auto p-6 space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 sticky top-0 z-10 bg-white/90 backdrop-blur-sm">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <button wire:click="backToReview" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order Rehabilitation Packages</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Select packages to order for {{ $rehabEncounter->encounter->patient->name }}
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 bg-green-100 text-green-800 text-sm font-medium rounded-full">
                    {{ $rehabEncounter->status_label }}
                </span>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="mt-4 grid grid-cols-3 gap-4 pt-4 border-t border-gray-100">
            <div>
                <span class="text-xs font-medium text-gray-500 uppercase">Patient</span>
                <p class="mt-1 font-medium text-gray-900">{{ $rehabEncounter->encounter->patient->name }}</p>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 uppercase">Encounter</span>
                <p class="mt-1 font-medium text-gray-900">#{{ $rehabEncounter->encounter_id }}</p>
            </div>
            <div>
                <span class="text-xs font-medium text-gray-500 uppercase">Review Status</span>
                <p class="mt-1 font-medium text-green-600">Completed</p>
            </div>
        </div>
    </div>

    <!-- Available Packages -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Available Rehabilitation Packages</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($availablePackages as $package)
                <div class="border rounded-xl p-4 {{ in_array($package->id, $selectedPackageIds) ? 'bg-gray-50 border-gray-300' : 'border-gray-200 hover:border-indigo-200 hover:shadow-sm' }} transition-all">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $package->name }}</h3>
                            @if($package->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $package->description }}</p>
                            @endif
                            <p class="text-lg font-bold text-gray-900 mt-2">${{ number_format($package->final_price, 2) }}</p>
                            
                            @if($package->items->count() > 0)
                                <div class="mt-2 flex flex-wrap gap-1">
                                    @php
                                        $medCount = $package->items->whereIn('item_type', ['standard_medication', 'custom_medication'])->count();
                                        $serviceCount = $package->items->where('item_type', 'service')->count();
                                        $hasBed = $package->items->where('item_type', 'bed')->isNotEmpty();
                                    @endphp
                                    @if($medCount > 0)
                                        <span class="px-2 py-0.5 bg-blue-100 text-blue-800 text-xs rounded-full">{{ $medCount }} Meds</span>
                                    @endif
                                    @if($serviceCount > 0)
                                        <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">{{ $serviceCount }} Services</span>
                                    @endif
                                    @if($hasBed)
                                        <span class="px-2 py-0.5 bg-purple-100 text-purple-800 text-xs rounded-full">Bed</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        @if(!in_array($package->id, $selectedPackageIds))
                            <button wire:click="addPackage({{ $package->id }})" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 ml-4 whitespace-nowrap">
                                Add Package
                            </button>
                        @else
                            <span class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md ml-4 whitespace-nowrap">
                                Added
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-gray-500">
                    No active packages available.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Draft Order Summary -->
    @if($draftOrder && $draftOrder->packages->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
            
            @foreach($draftOrder->packages as $orderPackage)
                <div class="border rounded-xl p-4 mb-4 border-gray-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $orderPackage->package_name }}</h3>
                            <p class="text-sm font-medium text-indigo-600 mt-1">${{ number_format($orderPackage->final_price, 2) }}</p>
                        </div>
                        <button wire:click="removePackage({{ $orderPackage->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Remove
                        </button>
                    </div>
                    
                    @if($orderPackage->items->isNotEmpty())
                        <div class="mt-4">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Package Items</h4>
                            <div class="space-y-2">
                                @foreach($orderPackage->items as $item)
                                    <div class="text-sm p-3 bg-gray-50 rounded-lg">
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-700">{{ $item->item_name }}</span>
                                            <span class="text-gray-500 text-xs">{{ str_replace('_', ' ', $item->item_type) }}</span>
                                        </div>
                                        <div class="grid grid-cols-4 gap-2 mt-1 text-xs text-gray-600">
                                            @if($item->dosage)
                                                <div>Dosage: {{ $item->dosage }}</div>
                                            @endif
                                            @if($item->frequency)
                                                <div>Freq: {{ $item->frequency }}</div>
                                            @endif
                                            @if($item->duration)
                                                <div>Duration: {{ $item->duration }}</div>
                                            @endif
                                            @if($item->quantity)
                                                <div>Qty: {{ $item->quantity }}</div>
                                            @endif
                                            @if($item->bed_duration_days)
                                                <div>Bed: {{ $item->bed_duration_days }} days</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
            
            <div class="flex justify-end items-center mt-4 pt-4 border-t border-gray-200">
                <span class="text-lg font-semibold text-gray-900">Total Amount: ${{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
    @endif

    <!-- Action Buttons -->
    <div class="flex justify-end gap-3 sticky bottom-6">
        <button wire:click="backToReview" class="px-6 py-3 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-all">
            Back to Review
        </button>
        <button wire:click="sendToCashier" wire:loading.attr="disabled" @if(!$draftOrder || $draftOrder->packages->isEmpty()) disabled @endif
            class="px-8 py-3 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
            <span wire:loading.remove wire:target="sendToCashier">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Send to Cashier
            </span>
            <span wire:loading wire:target="sendToCashier">
                <svg class="w-5 h-5 animate-spin inline mr-1" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button>
    </div>
</div>