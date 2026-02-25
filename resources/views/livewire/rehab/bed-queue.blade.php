{{-- resources/views/livewire/rehab/bed-queue.blade.php --}}
<div class="min-h-screen bg-gray-50 py-8" x-data="{ showAlert: @entangle('showAlert') }">
    {{-- Alert Notification --}}
    <div x-show="showAlert" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full"
         x-init="$watch('showAlert', value => { if(value) setTimeout(() => showAlert = false, 5000) })">
        <div @class([
            'rounded-xl shadow-lg p-4 flex items-start gap-3',
            'bg-green-50 border border-green-200' => $alertType === 'success',
            'bg-red-50 border border-red-200' => $alertType === 'error',
            'bg-blue-50 border border-blue-200' => $alertType === 'info',
            'bg-yellow-50 border border-yellow-200' => $alertType === 'warning',
        ])>
            <div @class([
                'shrink-0 w-6 h-6 rounded-full flex items-center justify-center',
                'text-green-600' => $alertType === 'success',
                'text-red-600' => $alertType === 'error',
                'text-blue-600' => $alertType === 'info',
                'text-yellow-600' => $alertType === 'warning',
            ])>
                @if($alertType === 'success')
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                @elseif($alertType === 'error')
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
            <div class="flex-1">
                <p @class([
                    'text-sm font-medium',
                    'text-green-800' => $alertType === 'success',
                    'text-red-800' => $alertType === 'error',
                    'text-blue-800' => $alertType === 'info',
                    'text-yellow-800' => $alertType === 'warning',
                ])>{{ $alertMessage }}</p>
            </div>
            <button wire:click="closeAlert" class="shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Bed Assignment Queue</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and assign beds to patients waiting for admission</p>
        </div>

        {{-- Search and Filters --}}
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search by patient name..." 
                       class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-xl bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150">
            </div>
        </div>

        {{-- Queue Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($encounters as $encounter)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm">
                                                {{ substr($encounter->encounter->patient->name ?? 'N/A', 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $encounter->encounter->patient->name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                ID: #{{ $encounter->encounter_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $encounter->encounter->doctor->name ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $duration = null;
                                        foreach($encounter->rehabOrders as $order) {
                                            foreach($order->orderPackages as $package) {
                                                foreach($package->orderItems as $item) {
                                                    if($item->item_type === 'bed' && $item->bed_duration_days) {
                                                        $duration = $item->bed_duration_days;
                                                        break 3;
                                                    }
                                                }
                                            }
                                        }
                                    @endphp
                                    <span class="text-sm text-gray-900">{{ $duration ?? 'N/A' }} days</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-mono text-gray-600">
                                        #{{ $encounter->rehabOrders->first()?->id ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Waiting Bed
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button wire:click="openAssignModal({{ $encounter->id }})" 
                                            wire:loading.attr="disabled"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                        <span wire:loading.remove wire:target="openAssignModal({{ $encounter->id }})">Assign Bed</span>
                                        <span wire:loading wire:target="openAssignModal({{ $encounter->id }})" class="flex items-center">
                                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
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
                                        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 mb-1">No patients waiting</h3>
                                        <p class="text-gray-500">All patients have been assigned beds or there are no pending admissions.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($encounters->hasPages())
                <div class="px-6 py-4 bg-white border-t border-gray-200">
                    {{ $encounters->links() }}
                </div>
            @endif
        </div>

        {{-- Assign Bed Modal --}}
        @if($showAssignModal && $selectedEncounter)
            <div class="fixed inset-0 overflow-y-auto z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    {{-- Background overlay --}}
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    {{-- Modal panel --}}
                    <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-top sm:max-w-4xl sm:w-full">
                        {{-- Sticky header --}}
                        <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 z-10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Assign Bed</h3>
                                    <p class="text-sm text-gray-500">Select an available bed for the patient</p>
                                </div>
                                <button wire:click="closeAssignModal" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Modal body --}}
                        <div class="px-6 py-4 max-h-[calc(100vh-16rem)] overflow-y-auto">
                            {{-- Patient Info Card --}}
                            <div class="bg-blue-50 rounded-xl p-4 mb-6 border border-blue-100">
                                <h4 class="text-sm font-medium text-blue-800 mb-3">Patient Information</h4>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-blue-600 mb-1">Patient Name</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $selectedEncounter->encounter->patient->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600 mb-1">Doctor</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $selectedEncounter->encounter->doctor->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-blue-600 mb-1">Duration</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $bedDuration }} days</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Bed Search --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Search Available Beds</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="bedSearch" 
                                           placeholder="Search by bed number, room, or ward..." 
                                           class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                            </div>

                            {{-- Beds Table --}}
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Room</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ward</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price/Day</th>
                                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Floor</th>
                                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($this->availableBeds as $bed)
                                            <tr class="hover:bg-gray-50 transition-colors duration-150 {{ $selectedBedId === $bed->id ? 'bg-blue-50' : '' }}">
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-900">{{ $bed->bed_number }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm text-gray-600">{{ $bed->room->room_number }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm text-gray-600">{{ $bed->room->ward->name }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                                        {{ $bed->room->bedClass->name }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ number_format($bed->room->bedClass->price_per_day, 2) }} {{ $bed->room->bedClass->currency }}
                                                    </span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap">
                                                    <span class="text-sm text-gray-600">{{ $bed->room->floor ?? 'N/A' }}</span>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-right">
                                                    @if($selectedBedId === $bed->id)
                                                        <span class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full">
                                                            Selected
                                                        </span>
                                                    @else
                                                        <button wire:click="selectBed({{ $bed->id }})" 
                                                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                            Select
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                                    No available beds found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            {{-- Total Calculation --}}
                            @if($selectedBed)
                                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-600">Selected Bed: <span class="font-medium">{{ $selectedBed->bed_number }}</span></p>
                                            <p class="text-xs text-gray-500 mt-1">{{ $bedDuration }} days × {{ number_format($selectedBed->room->bedClass->price_per_day, 2) }} {{ $selectedBed->room->bedClass->currency }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600">Total</p>
                                            <p class="text-2xl font-bold text-gray-900">{{ number_format($this->totalPrice, 2) }} {{ $selectedBed->room->bedClass->currency }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Modal footer --}}
                        <div class="sticky bottom-0 bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-end gap-3">
                                <button wire:click="closeAssignModal" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                    Cancel
                                </button>
                                <button wire:click="confirmAssignment" 
                                        wire:loading.attr="disabled"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                                    <span wire:loading.remove wire:target="confirmAssignment">Confirm Assignment</span>
                                    <span wire:loading wire:target="confirmAssignment" class="flex items-center">
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Assigning...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>