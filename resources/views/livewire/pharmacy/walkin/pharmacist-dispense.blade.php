<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto p-6">

        {{-- Toast Notification (matching your style) --}}
        @if($showToast)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; $wire.hideToast() }, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed top-6 right-6 z-50 max-w-sm w-full">
            <div class="rounded-xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between p-4
                    @if($toastType === 'success') bg-gradient-to-r from-green-500 to-emerald-600
                    @elseif($toastType === 'error') bg-gradient-to-r from-red-500 to-rose-600
                    @elseif($toastType === 'warning') bg-gradient-to-r from-amber-500 to-orange-600
                    @else bg-gradient-to-r from-blue-500 to-blue-600
                    @endif">
                    <div class="flex items-center space-x-3">
                        @if($toastType === 'success')
                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @elseif($toastType === 'error')
                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        @endif
                        <div>
                            <p class="font-medium text-white">{{ $toastMessage }}</p>
                            @if($toastDetails)
                                <p class="text-sm text-white/80 mt-1">{{ $toastDetails }}</p>
                            @endif
                        </div>
                    </div>
                    <button @click="show = false; $wire.hideToast()" class="text-white/80 hover:text-white">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
                <div class="h-1 w-full bg-gray-200">
                    <div x-data="{ width: 100 }" x-init="width = 100; let interval = setInterval(() => { width -= 2; if(width <= 0) { clearInterval(interval); show = false; $wire.hideToast() } }, 100)"
                         :style="`width: ${width}%`" class="h-full bg-white/40 transition-all duration-100">
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Dispense Medication</h1>
            <p class="text-gray-600 dark:text-gray-400">Pharmacist - Dispense paid orders to customers</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <input type="text" wire:model.live="searchOrder" placeholder="Search by order number, customer name, or phone..."
                class="w-full px-3 py-2 border rounded-lg mb-6 focus:ring-2 focus:ring-blue-500">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($paidOrders as $order)
                    <div class="border rounded-lg p-4 hover:shadow-lg transition-shadow" wire:key="order-{{ $order->id }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="font-bold text-lg text-blue-600">{{ $order->order_number }}</div>
                                <div class="text-sm font-medium mt-1">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->paid_at ? $order->paid_at->format('h:i A, M d') : 'N/A' }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-blue-600 font-semibold">Paid</div>
                                <div class="text-green-600 font-bold">ETB {{ number_format($order->total_amount, 2) }}</div>
                            </div>
                        </div>

                        <div class="text-sm mb-3">
                            <div class="font-medium text-gray-700">Items ({{ $order->items->count() }}):</div>
                            <div class="ml-2 space-y-1 mt-1">
                                @foreach($order->items as $item)
                                    <div class="text-gray-600 text-xs">
                                        • {{ $item->medicine->name }} - {{ $item->quantity }} pcs @ ETB {{ number_format($item->unit_price, 2) }}
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="text-xs text-gray-500 mb-3">
                            Cashier: {{ $order->cashier ? $order->cashier->name : 'N/A' }}
                        </div>

                        <button wire:click="viewOrder({{ $order->id }})"
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Dispense Order
                        </button>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">💊</div>
                        <div class="text-gray-500">No paid orders waiting for dispensing</div>
                        <div class="text-sm text-gray-400 mt-2">Check back later for new orders</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Dispense Modal --}}
        @if($showDispenseModal && $selectedOrder)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             x-data="{ open: true }"
             x-show="open"
             @keydown.escape.window="open = false; $wire.closeModal()">

            <div class="bg-white rounded-xl p-6 max-w-lg w-full mx-4"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Dispense Order</h3>
                    <button @click="open = false; $wire.closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="border-t pt-4 mb-4 space-y-3">
                    <div class="flex justify-between">
                        <span class="font-medium">Order Number:</span>
                        <span class="font-bold text-blue-600">{{ $selectedOrder->order_number }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium">Customer Name:</span>
                        <span>{{ $selectedOrder->customer_name }}</span>
                    </div>

                    @if($selectedOrder->customer_phone)
                    <div class="flex justify-between">
                        <span class="font-medium">Phone Number:</span>
                        <span>{{ $selectedOrder->customer_phone }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between">
                        <span class="font-medium">Paid Amount:</span>
                        <span class="font-bold text-green-600">ETB {{ number_format($selectedOrder->total_amount, 2) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="font-medium">Paid At:</span>
                        <span>{{ $selectedOrder->paid_at ? $selectedOrder->paid_at->format('F d, Y h:i A') : 'N/A' }}</span>
                    </div>

                    <div class="border-t pt-3">
                        <div class="font-medium mb-2">Items to dispense:</div>
                        <div class="bg-gray-50 rounded-lg p-3 space-y-2 max-h-60 overflow-y-auto">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex justify-between text-sm border-b pb-2">
                                    <div>
                                        <span class="font-medium">{{ $item->medicine->name }}</span>
                                        <div class="text-xs text-gray-500">{{ $item->medicine->strength }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div>{{ $item->quantity }} pcs</div>
                                        <div class="text-xs text-gray-500">Batch: {{ $item->batch->batch_number }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dispense Notes (Optional)</label>
                        <textarea wire:model="dispenseNotes" rows="2"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            placeholder="Any notes about this dispensing..."></textarea>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button wire:click="completeDispensing"
                        wire:loading.attr="disabled"
                        class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition-colors disabled:opacity-50">
                        <span wire:loading.remove wire:target="completeDispensing">
                            ✅ Confirm Dispense
                        </span>
                        <span wire:loading wire:target="completeDispensing">
                            ⏳ Dispensing...
                        </span>
                    </button>

                    <button wire:click="closeModal"
                        class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
