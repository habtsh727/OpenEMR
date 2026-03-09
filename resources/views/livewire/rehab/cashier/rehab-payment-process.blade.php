<div class="max-w-6xl mx-auto p-6">
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
        
        <!-- Payment Status Badges -->
        <div class="flex gap-2 mt-4">
            @if($rehabOrder->payment_status === 'paid')
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Fully Paid</span>
            @elseif($rehabOrder->payment_status === 'partial')
                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Partially Paid ({{ $this->installmentsPaidCount }}/{{ $this->installmentsTotalCount }} installments)</span>
            @else
                <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm font-medium">Pending</span>
            @endif
            
            @if($rehabOrder->payment_type === 'installment')
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">{{ $rehabOrder->installment_count }} Installments</span>
            @else
                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-medium">Full Payment</span>
            @endif
        </div>
    </div>

    <!-- Payment Progress Overview -->
    @if($paymentInstallments->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Total Amount</p>
            <p class="text-2xl font-bold text-gray-900">ETB {{ number_format($grandTotal, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Paid Amount</p>
            <p class="text-2xl font-bold text-green-600">ETB {{ number_format($rehabOrder->paid_amount, 2) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500">Remaining</p>
            <p class="text-2xl font-bold {{ ($grandTotal - $rehabOrder->paid_amount) > 0 ? 'text-orange-600' : 'text-green-600' }}">
                ETB {{ number_format(max(0, $grandTotal - $rehabOrder->paid_amount), 2) }}
            </p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-sm text-gray-500 mb-2">Progress</p>
            <div class="flex items-center gap-2">
                <div class="flex-1">
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $this->paymentProgress }}%"></div>
                    </div>
                </div>
                <span class="font-bold text-indigo-600">{{ $this->paymentProgress }}%</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Installment Selection (if multiple installments exist) -->
    @if($paymentInstallments->isNotEmpty() && $paymentInstallments->count() > 1)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Select Installment to Pay</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($paymentInstallments as $installment)
            <div class="border rounded-xl p-4 cursor-pointer transition-all {{ $selectedInstallment && $selectedInstallment->id === $installment->id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}"
                 wire:click="selectInstallment({{ $installment->id }})">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900">Installment #{{ $installment->installment_number }}</span>
                            @if($installment->status === 'paid')
                                <span class="px-2 py-0.5 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                            @elseif($installment->due_date->isPast())
                                <span class="px-2 py-0.5 bg-red-100 text-red-800 text-xs rounded-full">Overdue</span>
                            @else
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 mt-1">Amount: ETB {{ number_format($installment->amount, 2) }}</p>
                        @if($installment->paid_amount > 0)
                            <p class="text-xs text-green-600">Paid: ETB {{ number_format($installment->paid_amount, 2) }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Due Date</p>
                        <p class="font-medium {{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $installment->due_date->format('M d, Y') }}
                        </p>
                    </div>
                </div>
                
                @if($installment->paid_amount > 0 && $installment->status !== 'paid')
                <div class="mt-2">
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-500">Progress</span>
                        <span class="text-indigo-600">{{ round(($installment->paid_amount / $installment->amount) * 100, 1) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1.5">
                        <div class="bg-indigo-600 h-1.5 rounded-full" style="width: {{ ($installment->paid_amount / $installment->amount) * 100 }}%"></div>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Order Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
        
        @foreach($rehabOrder->packages as $package)
            <div class="border-b border-gray-100 last:border-0 py-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="font-medium text-gray-900">{{ $package->package_name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">ETB {{ number_format($package->final_price, 2) }}</p>
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
        
        @if($bedCost > 0)
        <div class="mt-3 pt-3 border-t border-gray-200">
            <div class="flex justify-between items-center text-purple-600">
                <span>Bed Cost ({{ $totalDurationDays }} days)</span>
                <span class="font-semibold">ETB {{ number_format($bedCost, 2) }}</span>
            </div>
        </div>
        @endif
        
        <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between items-center">
            <span class="font-semibold text-gray-900">Grand Total</span>
            <span class="text-2xl font-bold text-indigo-600">ETB {{ number_format($grandTotal, 2) }}</span>
        </div>
    </div>

    <!-- Payment Form (only show if an installment is selected) -->
    @if($selectedInstallment || (!$paymentInstallments->isEmpty() && $paymentInstallments->count() === 1))
    @php $activeInstallment = $selectedInstallment ?? $paymentInstallments->first(); @endphp
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
            Payment Details - Installment #{{ $activeInstallment->installment_number }}
        </h2>
        
        <div class="space-y-4">
            <!-- Payment Method -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @php
                    $methods = ['cash' => 'Cash', 'card' => 'Card', 'insurance' => 'Insurance', 'bank_transfer' => 'Bank Transfer'];
                    @endphp
                    @foreach($methods as $value => $label)
                    <label class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50 {{ $paymentMethod === $value ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="{{ $value }}" class="sr-only">
                        <span class="text-sm block text-center font-medium">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Installment Details -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Installment Amount:</span>
                    <span class="font-bold text-gray-900">ETB {{ number_format($activeInstallment->amount, 2) }}</span>
                </div>
                @if($activeInstallment->paid_amount > 0)
                <div class="flex justify-between mb-2">
                    <span class="text-gray-600">Already Paid:</span>
                    <span class="font-medium text-green-600">ETB {{ number_format($activeInstallment->paid_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between pt-2 border-t border-gray-200">
                    <span class="font-semibold text-gray-900">Remaining:</span>
                    <span class="text-xl font-bold text-indigo-600">ETB {{ number_format($activeInstallment->getRemainingAmount(), 2) }}</span>
                </div>
            </div>

            <!-- Payment Amount -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Amount to Pay</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">ETB</span>
                    <input type="number" step="0.01" wire:model.live="paymentAmount" 
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="0.00"
                        min="1"
                        max="{{ $activeInstallment->getRemainingAmount() }}">
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-xs text-gray-500">Min: 1 ETB</span>
                    <span class="text-xs text-gray-500">Max: {{ number_format($activeInstallment->getRemainingAmount(), 2) }} ETB</span>
                </div>
            </div>

            <!-- Change Due -->
            @if($changeAmount > 0)
                <div class="p-4 bg-green-50 rounded-lg">
                    <span class="text-sm font-medium text-green-800">Change Due:</span>
                    <span class="text-xl font-bold text-green-600 ml-2">ETB {{ number_format($changeAmount, 2) }}</span>
                </div>
            @endif

            <!-- Process Button -->
            <button wire:click="processPayment" 
                @if(!$activeInstallment || $paymentAmount <= 0 || $paymentAmount > $activeInstallment->getRemainingAmount()) disabled @endif
                class="w-full py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                Process Payment
            </button>
        </div>
    </div>
    @endif

    <!-- Payment Plan Creation Modal (for orders with no installments) -->
    @if($showPaymentPlanModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-900">Create Payment Plan</h2>
                    <button wire:click="closePaymentPlanModal" class="p-2 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <!-- Order Summary -->
                <div class="bg-gray-50 rounded-xl p-4 mb-4">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Package Total:</span>
                        <span class="font-medium">ETB {{ number_format($rehabOrder->total_amount, 2) }}</span>
                    </div>
                    @if($bedCost > 0)
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Bed Cost:</span>
                        <span class="font-medium text-purple-600">ETB {{ number_format($bedCost, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-gray-200">
                        <span class="font-semibold">Grand Total:</span>
                        <span class="text-xl font-bold text-indigo-600">ETB {{ number_format($grandTotal, 2) }}</span>
                    </div>
                </div>
                
                <!-- Payment Type Selection -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Payment Type</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" wire:click="$set('paymentType', 'full')"
                            class="p-4 border-2 rounded-xl text-center {{ $paymentType === 'full' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
                            <span class="block font-medium">Full Payment</span>
                            <span class="text-xs text-gray-500">Pay entire amount now</span>
                        </button>
                        <button type="button" wire:click="$set('paymentType', 'installment')"
                            class="p-4 border-2 rounded-xl text-center {{ $paymentType === 'installment' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-300' }}">
                            <span class="block font-medium">Installment Plan</span>
                            <span class="text-xs text-gray-500">Split into multiple payments</span>
                        </button>
                    </div>
                </div>
                
                @if($paymentType === 'installment')
                <!-- Installment Configuration -->
                <div class="space-y-4 mb-4">
                    <div class="flex items-center justify-between">
                        <label class="text-sm font-medium text-gray-700">Number of Installments</label>
                        <div class="flex items-center gap-2">
                            <button type="button" wire:click="$set('installmentCount', max(1, $installmentCount - 1))"
                                class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </button>
                            <span class="w-12 text-center font-bold">{{ $installmentCount }}</span>
                            <button type="button" wire:click="$set('installmentCount', $installmentCount + 1)"
                                class="p-2 bg-gray-100 rounded-lg hover:bg-gray-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div>
                        <p class="text-sm text-gray-500 mb-2">First Payment: ETB {{ number_format($customInstallments[0]['amount'] ?? 0, 2) }}</p>
                        <p class="text-sm text-gray-500">Due: {{ $customInstallments[0]['due_date'] ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif
                
                <div class="flex justify-end gap-3">
                    <button wire:click="closePaymentPlanModal" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button wire:click="createInstallmentSchedule" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Payment Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

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
                    <span class="text-gray-600">Installment</span>
                    <span class="font-semibold">#{{ $selectedInstallment->installment_number }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Installment Amount</span>
                    <span class="font-semibold">ETB {{ number_format($selectedInstallment->amount, 2) }}</span>
                </div>
                @if($selectedInstallment->paid_amount > 0)
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Already Paid</span>
                    <span class="font-semibold text-green-600">ETB {{ number_format($selectedInstallment->paid_amount, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Payment Method</span>
                    <span class="font-semibold capitalize">{{ $paymentMethod }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Amount to Pay Now</span>
                    <span class="font-semibold">ETB {{ number_format($paymentAmount, 2) }}</span>
                </div>
                @if($changeAmount > 0)
                <div class="flex justify-between py-2">
                    <span class="text-gray-600">Change Due</span>
                    <span class="font-semibold text-green-600">ETB {{ number_format($changeAmount, 2) }}</span>
                </div>
                @endif
            </div>
            
            <div class="flex justify-end gap-3">
                <button wire:click="$set('showConfirmModal', false)" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Cancel
                </button>
                <button wire:click="confirmPayment" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <span wire:loading.remove>Confirm Payment</span>
                    <span wire:loading>Processing...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>