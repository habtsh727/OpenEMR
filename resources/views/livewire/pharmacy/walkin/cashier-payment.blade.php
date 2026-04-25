<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-6xl mx-auto p-6">

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Process Payment</h1>
            <p class="text-gray-600 dark:text-gray-400">Cashier - Collect payment for walk-in orders</p>
        </div>

        @if(session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Pending Orders List --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Pending Payments</h2>

                <input type="text" wire:model.live="searchOrder" placeholder="Search by order number, customer name, or phone..."
                    class="w-full px-3 py-2 border rounded-lg mb-4">

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($pendingOrders as $pendingOrder)
                        <div class="border rounded-lg p-4 cursor-pointer hover:bg-gray-50"
                             :class="{'border-blue-500 bg-blue-50': {{ $order && $order->id == $pendingOrder->id }}}"
                             wire:click="selectOrder({{ $pendingOrder->id }})">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold">{{ $pendingOrder->order_number }}</div>
                                    <div class="text-sm">{{ $pendingOrder->customer_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $pendingOrder->created_at->format('h:i A, M d') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-lg">ETB {{ number_format($pendingOrder->total_amount, 2) }}</div>
                                    <div class="text-xs text-yellow-600">Pending</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">No pending orders</div>
                    @endforelse
                </div>
            </div>

            {{-- Payment Form --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Payment Details</h2>

                @if($order)
                    <div class="space-y-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <div class="flex justify-between mb-2">
                                <span>Order Number:</span>
                                <span class="font-bold">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Customer:</span>
                                <span>{{ $order->customer_name }}</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Items:</span>
                                <span>{{ $order->items->count() }} item(s)</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-2 border-t">
                                <span>Total Amount:</span>
                                <span class="text-green-600">ETB {{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-2">Payment Method</label>
                            <select wire:model="paymentMethod" class="w-full px-3 py-2 border rounded-lg">
                                <option value="cash">Cash</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="card">Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>

                        @if($paymentMethod == 'mobile_money')
                        <div>
                            <label class="block text-sm font-medium mb-2">Transaction ID</label>
                            <input type="text" wire:model="transactionId" placeholder="Enter transaction ID"
                                class="w-full px-3 py-2 border rounded-lg">
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-2">Amount Paid</label>
                            <input type="number" wire:model.live="amountPaid" step="0.01"
                                class="w-full px-3 py-2 border rounded-lg text-lg font-bold">
                        </div>

                        @if($changeDue > 0)
                        <div class="bg-green-100 p-4 rounded-lg text-center">
                            <div class="text-sm text-green-700">Change Due</div>
                            <div class="text-2xl font-bold text-green-700">ETB {{ number_format($changeDue, 2) }}</div>
                        </div>
                        @endif

                        <button wire:click="processPayment"
                            class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700">
                            Process Payment
                        </button>
                    </div>
                @else
                    <div class="text-center py-12 text-gray-500">
                        Select an order from the list to process payment
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
