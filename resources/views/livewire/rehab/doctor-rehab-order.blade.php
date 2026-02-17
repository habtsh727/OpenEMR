<div class="max-w-7xl mx-auto p-6">
    {{-- Section 1: Encounter Info --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Encounter Information</h2>
        <div class="grid grid-cols-3 gap-4">
            <div>
                <span class="text-sm text-gray-500">Encounter ID</span>
                <p class="font-medium text-gray-900">#{{ $rehabEncounter->encounter->id }}</p>
            </div>
            <div>
                <span class="text-sm text-gray-500">Patient</span>
                <p class="font-medium text-gray-900">{{ $rehabEncounter->encounter->patient->name ?? 'N/A' }}</p>
            </div>
            <div>
                <span class="text-sm text-gray-500">Encounter Status</span>
                <p class="font-medium text-gray-900">{{ $rehabEncounter->encounter->status ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    {{-- Section 2: Available Packages --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Available Rehabilitation Packages</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($availablePackages as $package)
                <div class="border rounded-lg p-4 {{ in_array($package->id, $selectedPackageIds) ? 'bg-gray-50 border-gray-300' : 'border-gray-200' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900">{{ $package->name }}</h3>
                            @if($package->description)
                                <p class="text-sm text-gray-600 mt-1">{{ $package->description }}</p>
                            @endif
                            <p class="text-lg font-bold text-gray-900 mt-2">${{ number_format($package->final_price, 2) }}</p>
                            
                            {{-- Package items summary --}}
                            @if($package->items->count() > 0)
                                <div class="mt-2 text-xs text-gray-500">
                                    {{ $package->items->count() }} item(s)
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

    {{-- Section 3: Draft Order Summary --}}
    @if($draftOrder && $draftOrder->packages->isNotEmpty())
        <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Draft Order Summary</h2>
            
            @foreach($draftOrder->packages as $orderPackage)
                <div class="border rounded-lg p-4 mb-4 border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $orderPackage->package_name }}</h3>
                            <p class="text-sm font-medium text-indigo-600 mt-1">${{ number_format($orderPackage->final_price, 2) }}</p>
                        </div>
                        <button wire:click="removePackage({{ $orderPackage->id }})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            Remove
                        </button>
                    </div>
                    
                    @if($orderPackage->items->isNotEmpty())
                        <div class="mt-3">
                            <h4 class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Package Items</h4>
                            <div class="space-y-2">
                                @foreach($orderPackage->items as $item)
                                    <div class="text-sm p-2 bg-gray-50 rounded">
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
                                        @if($item->notes)
                                            <div class="mt-1 text-xs text-gray-500">Note: {{ $item->notes }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
            
            <div class="flex justify-end items-center mt-4 pt-4 border-t border-gray-200">
                <span class="text-lg font-semibold text-gray-900">Total: ${{ number_format($totalAmount, 2) }}</span>
            </div>
        </div>
    @endif

    {{-- Section 4: Send To Cashier --}}
    <div class="flex justify-end">
        <button wire:click="sendToCashier" wire:loading.attr="disabled" @if(!$draftOrder || $draftOrder->packages->isEmpty()) disabled @endif
            class="px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove wire:target="sendToCashier">Send to Cashier</span>
            <span wire:loading wire:target="sendToCashier">Processing...</span>
        </button>
    </div>
</div>