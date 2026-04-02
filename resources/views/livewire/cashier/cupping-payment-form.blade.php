<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-blue-600 dark:bg-blue-700">
        <h2 class="text-2xl font-bold text-white">💳 Process Payment</h2>
        <p class="text-blue-100">Cashier Payment Terminal</p>
    </div>

    <div class="p-6">
        <!-- Session Details -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Patient Name</p>
                    <p class="font-semibold text-lg">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Therapy ID</p>
                    <p class="font-semibold">#{{ $session->cupping_therapy_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Session</p>
                    <p class="font-semibold">Session {{ $session->session_number }} of {{ $session->cuppingTherapy->total_sessions }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Session Date</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Payment Status</p>
                    <p class="font-semibold">
                        <span class="px-2 py-1 rounded text-xs {{ $session->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($session->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                            {{ ucfirst($session->payment_status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Treatment Status</p>
                    <p class="font-semibold">
                        <span class="px-2 py-1 rounded text-xs bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                            {{ ucfirst(str_replace('_', ' ', $session->treatment_status)) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Items Summary -->
        <div class="mb-6">
            <h3 class="font-semibold mb-3 flex items-center">
                <span class="mr-2">📋</span> Therapy Items
            </h3>
            <div class="space-y-2 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                @foreach($session->items as $item)
                    <div class="flex justify-between text-sm">
                        <span>
                            <span class="font-medium">{{ $item->qty }}x</span> 
                            {{ $item->cuppingType->name }} 
                            <span class="text-gray-500">({{ $item->cuppingLocation->name }})</span>
                        </span>
                        <span class="font-medium">{{ number_format($item->total, 2) }} ETB</span>
                    </div>
                @endforeach
                <div class="border-t dark:border-gray-600 pt-2 mt-2">
                    <div class="flex justify-between font-bold">
                        <span>Session Total</span>
                        <span>{{ number_format($session_amount, 2) }} ETB</span>
                    </div>
                    <div class="flex justify-between text-sm text-green-600 dark:text-green-400">
                        <span>Already Paid</span>
                        <span>{{ number_format($paid_amount, 2) }} ETB</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold mt-2 pt-2 border-t dark:border-gray-600">
                        <span>Remaining Balance</span>
                        <span class="text-blue-600 dark:text-blue-400">{{ number_format($remaining, 2) }} ETB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="border-t dark:border-gray-700 pt-6">
            <h3 class="font-semibold mb-4 flex items-center">
                <span class="mr-2">💵</span> Payment Details
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Payment Amount (ETB)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">ብር</span>
                        <input type="number" step="0.01" wire:model.live="amount" 
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700">
                    </div>
                    @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Payment Method</label>
                    <select wire:model="payment_method" 
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700">
                        <option value="cash">💵 Cash</option>
                        <option value="card">💳 Card</option>
                        <option value="bank_transfer">🏦 Bank Transfer</option>
                        <option value="mobile_money">📱 Mobile Money (Telebirr)</option>
                    </select>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3 mb-6">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    💡 <strong>Note:</strong> If this is a partial payment, the session will remain in payment queue until fully paid. 
                    Once fully paid, it will automatically move to treatment queue.
                </p>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" wire:click="$dispatch('close-payment-form')" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    Cancel
                </button>
                <button wire:click="processPayment" 
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Process Payment
                </button>
            </div>
        </div>
    </div>
</div>