<div class="max-w-4xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('cashier.rehab.payments') }}" wire:navigate class="p-2 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Process Payment</h1>
                <p class="text-gray-600 mt-1">Order #{{ $rehabOrder->id }} - {{ $rehabOrder->encounter->encounter->patient->name }}</p>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
        
        @foreach($rehabOrder->packages as $package)
            <div class="border-b border-gray-100 last:border-0 py-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $package->package_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">${{ number_format($package->final_price, 2) }}</p>
                    </div>
                </div>
                
                @if($package->items->isNotEmpty())
                    <div class="mt-2 pl-4">
                        @foreach($package->items as $item)
                            <div class="text-xs text-gray-500">
                                • {{ $item->item_name }}
                                @if($item->dosage) - {{ $item->dosage }} @endif
                                @if($item->frequency) ({{ $item->frequency }}) @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
        
        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
            <span class="font-semibold text-gray-900">Total Amount</span>
            <span class="text-2xl font-bold text-indigo-600">${{ number_format($rehabOrder->total_amount, 2) }}</span>
        </div>
    </div>

    <!-- Payment Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Details</h2>
        
        <div class="space-y-4">
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'cash' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="cash" class="sr-only">
                        <svg class="w-6 h-6 mx-auto mb-2 {{ $paymentMethod === 'cash' ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="text-sm block text-center">Cash</span>
                    </label>
                    
                    <label class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'card' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="card" class="sr-only">
                        <svg class="w-6 h-6 mx-auto mb-2 {{ $paymentMethod === 'card' ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span class="text-sm block text-center">Card</span>
                    </label>
                    
                    <label class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $paymentMethod === 'insurance' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="insurance" class="sr-only">
                        <svg class="w-6 h-6 mx-auto mb-2 {{ $paymentMethod === 'insurance' ? 'text-indigo-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        <span class="text-sm block text-center">Insurance</span>
                    </label>
                </div>
            </div>

            <!-- Payment Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount Received</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">$</span>
                    <input type="number" step="0.01" wire:model.live="paymentAmount" 
                        class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00">
                </div>
            </div>

            <!-- Change Due -->
            @if($changeAmount > 0)
                <div class="p-4 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-green-800">Change Due:</span>
                    <span class="text-xl font-bold text-green-600 ml-2">${{ number_format($changeAmount, 2) }}</span>
                </div>
            @endif

            <!-- Process Button -->
            <button wire:click="processPayment" 
                class="w-full py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                Process Payment
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Payment</h3>
                    <p class="text-sm text-gray-500">Review payment details before confirming</p>
                </div>
            </div>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Order Total</span>
                    <span class="font-semibold">${{ number_format($rehabOrder->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Payment Method</span>
                    <span class="font-semibold capitalize">{{ $paymentMethod }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Amount Paid</span>
                    <span class="font-semibold">${{ number_format($paymentAmount, 2) }}</span>
                </div>
                @if($changeAmount > 0)
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Change Due</span>
                    <span class="font-semibold text-green-600">${{ number_format($changeAmount, 2) }}</span>
                </div>
                @endif
            </div>
            
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showConfirmModal', false)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="confirmPayment" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <span wire:loading.remove>Confirm & Submit to Rehab</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>