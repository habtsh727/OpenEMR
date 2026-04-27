<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Treatment</h1>
                            <p class="text-green-100 mt-1">Session #{{ $session->session_number ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                        <p class="text-xs text-green-200">Status</p>
                        <p class="text-lg font-semibold text-white capitalize">{{ $session->treatment_status ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if($showAlert)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
            class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
                {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                   ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                    'bg-yellow-100 border border-yellow-400 text-yellow-700') }}">
            <span>{{ $alertMessage }}</span>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        {{-- Patient Info Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Patient Name</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Card Number</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $patient->card_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Session Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $session->session_date ? \Carbon\Carbon::parse($session->session_date)->format('F d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Package</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $therapyPackage->package_name_snapshot ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Treatments Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">Treatments to Perform</h2>
            </div>
            <div class="p-5">
                @if(!empty($treatments))
                    <div class="space-y-3">
                        @foreach($treatments as $treatment)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <div>
                                    <span class="font-medium">{{ $treatment['type_name'] ?? 'N/A' }}</span>
                                    <span class="text-sm text-gray-500 ml-2">on {{ $treatment['location_name'] ?? 'N/A' }}</span>
                                </div>
                                <span class="text-sm text-gray-600">ETB {{ number_format($treatment['price'] ?? 0, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">No treatments specified</p>
                @endif
            </div>
        </div>

        {{-- Materials Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="font-semibold text-gray-900 dark:text-white">Materials Required</h2>
                <span class="text-sm {{ $allMaterialsCollected ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $materialsCollectedCount }}/{{ $totalMaterialsCount }} Collected
                </span>
            </div>
            <div class="p-5">
                @if(count($materials) > 0)
                    <div class="space-y-3">
                        @foreach($materials as $index => $material)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                <div>
                                    <span class="font-medium">{{ $material['item_name'] ?? 'N/A' }}</span>
                                    <div class="text-sm text-gray-500">Quantity: {{ $material['quantity'] ?? 0 }}</div>
                                    <div class="text-xs {{ ($material['available_stock'] ?? 0) >= ($material['quantity'] ?? 0) ? 'text-green-600' : 'text-red-600' }}">
                                        Available: {{ $material['available_stock'] ?? 0 }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if($material['is_collected'] ?? false)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-700">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Collected
                                        </span>
                                    @else
                                        <button wire:click="toggleMaterialCollected({{ $index }})"
                                            {{ !($material['can_collect'] ?? false) ? 'disabled' : '' }}
                                            class="px-4 py-2 {{ ($material['can_collect'] ?? false) ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 cursor-not-allowed' }} text-white rounded-lg text-sm transition">
                                            Mark as Collected
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if(!$allMaterialsCollected && $totalMaterialsCount > 0)
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Please collect all materials from pharmacy before starting treatment.
                            </p>
                        </div>
                    @endif
                @else
                    <p class="text-gray-500 text-center py-4">No materials required for this session</p>
                @endif
            </div>
        </div>

        {{-- Treatment Documentation (shown when treatment is in progress) --}}
        @if($session->treatment_status === 'in_progress')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
            <div class="px-5 py-3 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-700">
                <h2 class="font-semibold text-gray-900 dark:text-white">Treatment Documentation</h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Treatment Notes</label>
                    <textarea wire:model="treatmentNotes" rows="3"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Record treatment details, procedures performed..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observations</label>
                    <textarea wire:model="observations" rows="2"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Skin reaction, cup marks, patient comfort level..."></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Recommendations</label>
                    <textarea wire:model="recommendations" rows="2"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:border-gray-600"
                        placeholder="Follow-up care, next session instructions..."></textarea>
                </div>
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="sticky bottom-6 z-10">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 backdrop-blur-sm">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('cupping.treatment.queue') }}" wire:navigate
                        class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Back to Queue
                    </a>

                    @if(in_array($session->treatment_status, ['pending', 'in_queue']))
                        <button wire:click="startTreatment"
                            {{ (!$allMaterialsCollected && $totalMaterialsCount > 0) ? 'disabled' : '' }}
                            class="flex-1 px-4 py-2 {{ (!$allMaterialsCollected && $totalMaterialsCount > 0) ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-semibold transition">
                            Start Treatment
                        </button>
                    @endif

                    @if($session->treatment_status === 'in_progress')
                        <button wire:click="completeTreatment"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                            Complete Treatment
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Complete Treatment Modal --}}
        @if($showCompleteModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             x-data="{ open: true }"
             x-show="open"
             x-on:keydown.escape.window="open = false; $wire.set('showCompleteModal', false)">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Complete Treatment</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to mark this session as completed?
                    </p>
                    <div class="flex gap-3">
                        <button wire:click="$set('showCompleteModal', false)"
                            class="flex-1 px-4 py-2 border rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            Cancel
                        </button>
                        <button wire:click="confirmComplete"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('redirect-to-queue', () => {
            setTimeout(() => {
                window.location.href = '{{ route("cupping.treatment.queue") }}';
            }, 2000);
        });
    });
</script>
