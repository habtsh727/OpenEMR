<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
    <!-- Alert Notification inside modal -->
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
            <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                    'bg-gradient-to-r from-yellow-500 to-orange-600') }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $alertMessage }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700">
        <h2 class="text-2xl font-bold text-white">Process Payment</h2>
        <p class="text-blue-100">Session #{{ $session->session_number }} of {{ $session->cuppingTherapy->total_sessions }}</p>
    </div>

    <div class="p-6">
        <!-- Patient Info -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Patient</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Session Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Items Summary -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Therapy Items</h3>
            <div class="space-y-2">
                @foreach($session->items as $item)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">
                            {{ $item->qty }}x {{ $item->cuppingType->name }} on {{ $item->cuppingLocation->name }}
                        </span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($item->total, 2) }} ETB</span>
                    </div>
                @endforeach
                
                <div class="border-t border-gray-200 dark:border-gray-600 pt-3 mt-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Session Total</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($session_amount, 2) }} ETB</span>
                    </div>
                    <div class="flex justify-between text-green-600 dark:text-green-400">
                        <span>Already Paid</span>
                        <span>{{ number_format($paid_amount, 2) }} ETB</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2 pt-2 border-t border-gray-200 dark:border-gray-600">
                        <span class="text-gray-900 dark:text-white">Remaining Balance</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ number_format($remaining, 2) }} ETB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <form wire:submit.prevent="processPayment">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount (ETB)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">ብር</span>
                        <input type="number" step="0.01" wire:model.live="amount" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
                    <select wire:model="payment_method" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Card</option>
                        <option value="bank_transfer">🏦 Bank Transfer</option>
                        <option value="mobile_money">📱 Mobile Money (Telebirr)</option>
                    </select>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-6">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    💡 <strong>Note:</strong> Partial payments will keep the session in queue until fully paid.
                </p>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" wire:click="$dispatch('close-payment-form')" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    Process Payment
                </button>
            </div>
        </form>
    </div>
</div>