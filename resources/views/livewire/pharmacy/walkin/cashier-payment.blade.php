<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Section --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v-4"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Process Payment</h1>
                                <p class="text-blue-100 mt-1">Cashier - Collect payment for walk-in orders</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm">Walk-in Customer Payment</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stats Badge --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-center">
                                <div class="text-white text-sm font-medium mb-1">Pending Orders</div>
                                <div class="text-3xl font-bold text-white">{{ $pendingOrders->count() }}</div>
                                <div class="text-blue-200 text-xs mt-2">Ready for payment</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert Messages --}}
        @if(session()->has('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 dark:text-green-400 hover:text-green-900">×</button>
            </div>
        @endif

        @if(session()->has('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mb-6 p-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-400 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-red-700 dark:text-red-400 hover:text-red-900">×</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Pending Orders List --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Pending Payments
                        </h2>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $pendingOrders->count() }} orders</span>
                    </div>
                </div>

                <div class="p-6">
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" wire:model.live="searchOrder" placeholder="Search by order number, customer name, or phone..."
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 mb-4">
                    </div>

                    <div class="space-y-3 max-h-[500px] overflow-y-auto custom-scrollbar">
                        @forelse($pendingOrders as $pendingOrder)
                            <div wire:click="selectOrder({{ $pendingOrder->id }})"
                                class="border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md
                                    {{ $order && $order->id == $pendingOrder->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 shadow-lg' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700' }}">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $pendingOrder->order_number }}</span>
                                            <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>{{ $pendingOrder->customer_name }}</span>
                                        </div>
                                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                            <span>{{ $pendingOrder->created_at->format('h:i A') }}</span>
                                            <span>{{ $pendingOrder->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">ETB {{ number_format($pendingOrder->total_amount, 2) }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $pendingOrder->items->count() }} item(s)</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">No pending orders</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">New orders will appear here</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Payment Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Payment Details
                    </h2>
                </div>

                <div class="p-6">
                    @if($order)
                        <div class="space-y-6">
                            {{-- Order Summary Card --}}
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                                <h3 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Order Summary</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Order Number:</span>
                                        <span class="font-mono font-semibold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Customer:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $order->customer_name }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">Total Items:</span>
                                        <span class="text-gray-900 dark:text-white">{{ $order->items->count() }} items</span>
                                    </div>
                                    <div class="border-t border-gray-200 dark:border-gray-700 mt-3 pt-3">
                                        <div class="flex justify-between text-lg font-bold">
                                            <span class="text-gray-900 dark:text-white">Total Amount:</span>
                                            <span class="text-green-600 dark:text-green-400">ETB {{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Payment Method --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Payment Method
                                </label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all
                                        {{ $paymentMethod == 'cash' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                                        <input type="radio" wire:model="paymentMethod" value="cash" class="mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm0 0v-4"></path>
                                        </svg>
                                        <span class="ml-2">Cash</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all
                                        {{ $paymentMethod == 'mobile_money' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                                        <input type="radio" wire:model="paymentMethod" value="mobile_money" class="mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="ml-2">Mobile Money</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all
                                        {{ $paymentMethod == 'card' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                                        <input type="radio" wire:model="paymentMethod" value="card" class="mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                        <span class="ml-2">Card</span>
                                    </label>
                                    <label class="flex items-center p-3 border-2 rounded-xl cursor-pointer transition-all
                                        {{ $paymentMethod == 'bank_transfer' ? 'border-green-500 bg-green-50 dark:bg-green-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-green-300' }}">
                                        <input type="radio" wire:model="paymentMethod" value="bank_transfer" class="mr-3">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                        </svg>
                                        <span class="ml-2">Bank Transfer</span>
                                    </label>
                                </div>
                            </div>

                            @if($paymentMethod == 'mobile_money')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Transaction ID / Reference Number
                                </label>
                                <input type="text" wire:model="transactionId" placeholder="e.g., MTC-2024-001234"
                                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            @endif

                            {{-- Amount Paid --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Amount Paid (ETB)
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">ETB</span>
                                    <input type="number" wire:model.live="amountPaid" step="0.01" min="{{ $order->total_amount }}"
                                        class="w-full pl-16 pr-4 py-4 bg-gray-50 dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all text-xl font-bold text-gray-900 dark:text-white">
                                </div>
                            </div>

                            {{-- Change Due --}}
                            @if($changeDue > 0)
                            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl p-5 text-center">
                                <div class="text-sm text-green-700 dark:text-green-400 font-medium mb-1">Change Due to Customer</div>
                                <div class="text-4xl font-bold text-green-700 dark:text-green-400">ETB {{ number_format($changeDue, 2) }}</div>
                            </div>
                            @elseif($amountPaid < $order->total_amount && $amountPaid > 0)
                            <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-900/20 dark:to-orange-900/20 border border-red-200 dark:border-red-800 rounded-xl p-5 text-center">
                                <div class="text-sm text-red-700 dark:text-red-400 font-medium mb-1">Short Payment</div>
                                <div class="text-2xl font-bold text-red-700 dark:text-red-400">Remaining: ETB {{ number_format($order->total_amount - $amountPaid, 2) }}</div>
                            </div>
                            @endif

                            {{-- Process Button --}}
                            <button wire:click="processPayment"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 rounded-xl font-semibold shadow-lg shadow-green-500/30 transition-all duration-200 flex items-center justify-center gap-2 text-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Process Payment
                            </button>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="w-24 h-24 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No order selected</p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Select an order from the list to process payment</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom scrollbar for dark/light mode */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #4b5563;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #6b7280;
    }
</style>

</div>
