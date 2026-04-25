<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div
                            class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Therapy Order</h1>
                            <p class="text-emerald-100 mt-1">Order cupping therapy packages for patient</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <div class="flex items-center space-x-2 text-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    <span class="text-sm">{{ $patient->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                        <div class="text-center">
                            <div class="text-white text-sm font-medium mb-1">Doctor</div>
                            <div class="text-white font-semibold">Dr. {{ auth()->user()->name }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Notification --}}
        @if($showAlert)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
                {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                   ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                   'bg-blue-100 border border-blue-400 text-blue-700') }}">
            <div class="flex items-center gap-3">
                @if($alertType === 'success')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @elseif($alertType === 'error')
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                @endif
                <span>{{ $alertMessage }}</span>
            </div>
            <button @click="show = false" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Package Selection --}}
            <div class="lg:col-span-2 space-y-6">
                <!-- Available Packages -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Available Cupping Packages
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($packages as $package)
                            <div class="border-2 rounded-xl p-4 cursor-pointer transition-all hover:shadow-md
                                    {{ $selectedPackageId == $package->id ? 'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-emerald-300' }}"
                                wire:click="selectPackage({{ $package->id }})">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="font-bold text-gray-900 dark:text-white">{{ $package->name }}</h3>
                                    <span class="text-lg font-bold text-emerald-600">ETB {{
                                        number_format($package->total_price, 2) }}</span>
                                </div>
                                @if($package->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $package->description }}</p>
                                @endif
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-full">
                                        {{ $package->treatments->count() }} treatment(s)
                                    </span>
                                    <span class="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-full">
                                        {{ $package->materials->count() }} material(s)
                                    </span>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-2 text-center py-8 text-gray-500">
                                No active packages found. Please contact administrator.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Package Preview Modal -->
                <!-- Package Preview Modal -->
                @if($showPackagePreview && $selectedPackage)
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                        <div class="flex justify-between items-center">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Package Details: {{ $selectedPackage->name }}
                            </h2>
                            <button wire:click="$set('showPackagePreview', false)"
                                class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Treatments -->
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Treatments Included:</h3>
                            <div class="space-y-2">
                                @foreach($packageTreatments as $treatment)
                                <div
                                    class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <span class="font-medium">{{ $treatment['type_name'] }}</span>
                                        <span class="text-sm text-gray-500 ml-2">on {{ $treatment['location_name']
                                            }}</span>
                                    </div>
                                    <span class="text-emerald-600 font-semibold">ETB {{
                                        number_format($treatment['price'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Materials -->
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Materials Required (per
                                session):</h3>
                            <div class="space-y-2">
                                @foreach($packageMaterials as $material)
                                <div
                                    class="flex justify-between items-center p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div>
                                        <span class="font-medium">{{ $material['item_name'] }}</span>
                                        <span class="text-sm text-gray-500 ml-2">x{{ $material['quantity'] }}</span>
                                    </div>
                                    <span class="text-gray-700">ETB {{ number_format($material['total'], 2) }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Package Price -->
                        <div class="border-t pt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-lg">Price per Session:</span>
                                <span class="text-2xl font-bold text-emerald-600">ETB {{
                                    number_format($packageTotalPrice, 2) }}</span>
                            </div>
                        </div>

                        <!-- Session Schedule -->
                        <div class="border-t pt-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-semibold text-gray-900 dark:text-white">Schedule Sessions</h3>
                                <button type="button" wire:click="addSession"
                                    class="text-sm text-blue-600 hover:text-blue-700">+ Add Session</button>
                            </div>

                            <div class="space-y-3">
                                @foreach($sessions as $index => $session)
                                <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-medium">Session #{{ $session['session_number'] }}</span>
                                        @if(count($sessions) > 1)
                                        <button wire:click="removeSession({{ $index }})"
                                            class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                        @endif
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-600 dark:text-gray-400 mb-1">Session
                                            Date</label>
                                        <input type="date" wire:model="sessions.{{ $index }}.session_date"
                                            min="{{ $minDate }}" max="{{ $maxDate }}"
                                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                        @error("sessions.{$index}.session_date")
                                        <span class="text-xs text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            @if(empty($sessions))
                            <div class="text-center py-4 text-gray-500">
                                No sessions added. Click "Add Session" to schedule.
                            </div>
                            @else
                            <div class="mt-3 p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                <div class="flex justify-between items-center text-sm">
                                    <span>Total Sessions:</span>
                                    <span class="font-bold">{{ count($sessions) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span>Total Price:</span>
                                    <span class="font-bold text-emerald-600">ETB {{ number_format($packageTotalPrice *
                                        count($sessions), 2) }}</span>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Add to Cart Button -->
                        <div class="border-t pt-4">
                            <button wire:click="addToCart"
                                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-lg font-medium transition-all">
                                Add to Order ({{ count($sessions) }} session(s))
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Shopping Cart --}}
            <div class="lg:col-span-1">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M18 13l1.5 6M5 21h14M9 7h6">
                                </path>
                            </svg>
                            Order Summary
                            @if(count($cart) > 0)
                            <span class="ml-2 px-2 py-0.5 bg-emerald-100 text-emerald-800 text-xs rounded-full">{{
                                count($cart) }} item(s)</span>
                            @endif
                        </h2>
                    </div>

                    <div class="p-6">
                        @if(empty($cart))
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M18 13l1.5 6M5 21h14M9 7h6">
                                </path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No packages added yet</p>
                            <p class="text-sm text-gray-400 mt-1">Select a package from the left</p>
                        </div>
                        @else
                        <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
                            @foreach($cart as $index => $item)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-semibold">{{ $item['package_name'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['session_count'] }} session(s)</div>
                                    </div>
                                    <button wire:click="removeFromCart({{ $index }})"
                                        class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>

                                <!-- Show scheduled dates -->
                                <div class="mb-2">
                                    <div class="text-xs text-gray-500 mb-1">Scheduled Dates:</div>
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($item['sessions'] as $session)
                                        <span class="text-xs px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">
                                            {{ \Carbon\Carbon::parse($session['session_date'])->format('M d, Y') }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">@ ETB {{ number_format($item['package_price'],
                                        2) }}/session</span>
                                    <span class="font-semibold text-emerald-600">ETB {{
                                        number_format($item['total_amount'], 2) }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="text-lg font-semibold">ETB {{ number_format($cartTotal, 2) }}</span>
                            </div>

                            <textarea wire:model="notes" rows="2" placeholder="Additional notes..."
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-600"></textarea>

                            <div class="flex gap-2">
                                <button wire:click="clearCart"
                                    class="flex-1 px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition">
                                    Clear
                                </button>
                                <button wire:click="submitOrder"
                                    class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold transition">
                                    Place Order
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Confirmation Modal --}}
        @if($showConfirmModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full p-6">
                <div class="text-center">
                    <div
                        class="w-16 h-16 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confirm Cupping Order</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Total amount: <span class="font-bold text-emerald-600">ETB {{ number_format($cartTotal, 2)
                            }}</span>
                        <br>Total sessions: <span class="font-bold">{{ $totalSessions }}</span>
                    </p>

                    <!-- Show session breakdown -->
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-3 mb-4 max-h-40 overflow-y-auto">
                        <div class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Session Schedule:</div>
                        @foreach($cart as $item)
                        <div class="text-sm mb-2">
                            <div class="font-medium">{{ $item['package_name'] }}</div>
                            <div class="text-xs text-gray-500 ml-2">
                                @foreach($item['sessions'] as $session)
                                • {{ \Carbon\Carbon::parse($session['session_date'])->format('M d, Y') }}<br>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg p-3 mb-6">
                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                            ⚠️ Once confirmed, the order will be sent to the cashier for payment processing.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="$set('showConfirmModal', false)"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                        <button wire:click="confirmOrder"
                            class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-semibold">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('redirect-to-cashier', (event) => {
            setTimeout(() => {
                window.location.href = '/cupping/cashier-queue';
            }, 2000);
        });
    });
</script>
