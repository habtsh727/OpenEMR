<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Section --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Dispense Medication</h1>
                                <p class="text-purple-100 mt-1">Pharmacist - Dispense paid orders to customers</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-purple-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm">Ready for Dispensing</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Stats Badge --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-center">
                                <div class="text-white text-sm font-medium mb-1">Orders to Dispense</div>
                                <div class="text-3xl font-bold text-white">{{ $paidOrders->count() }}</div>
                                <div class="text-purple-200 text-xs mt-2">Waiting for pharmacist</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Toast Notification --}}
        @if($showToast)
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => { show = false; $wire.hideToast() }, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             class="fixed top-6 right-6 z-50 max-w-sm w-full">
            <div class="rounded-xl shadow-xl overflow-hidden">
                <div class="flex items-center justify-between p-4
                    @if($toastType === 'success') bg-gradient-to-r from-green-500 to-emerald-600
                    @elseif($toastType === 'error') bg-gradient-to-r from-red-500 to-rose-600
                    @else bg-gradient-to-r from-blue-500 to-blue-600
                    @endif">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                            @if($toastType === 'success')
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
                            <p class="font-medium text-white">{{ $toastMessage }}</p>
                            @if($toastDetails)
                                <p class="text-sm text-white/80 mt-1">{{ $toastDetails }}</p>
                            @endif
                        </div>
                    </div>
                    <button @click="show = false; $wire.hideToast()" class="text-white/80 hover:text-white">×</button>
                </div>
                <div class="h-1 w-full bg-gray-200">
                    <div x-data="{ width: 100 }" x-init="width = 100; let interval = setInterval(() => { width -= 2; if(width <= 0) { clearInterval(interval); show = false; $wire.hideToast() } }, 100)"
                         :style="`width: ${width}%`" class="h-full bg-white/40 transition-all duration-100">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Main Content --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                {{-- Search Bar --}}
                <div class="relative mb-6">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live="searchOrder"
                        placeholder="Search by order number, customer name, or phone number..."
                        class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100">
                </div>

                {{-- Orders Grid --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($paidOrders as $order)
                        <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 hover:-translate-y-1"
                             wire:key="order-{{ $order->id }}">
                            {{-- Order Header --}}
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 p-4">
                                <div class="flex justify-between items-center">
                                    <div class="text-white">
                                        <div class="text-xs font-mono opacity-80">Order #</div>
                                        <div class="font-bold text-lg">{{ $order->order_number }}</div>
                                    </div>
                                    <div class="bg-green-500 px-3 py-1 rounded-full text-xs font-semibold text-white shadow-lg">
                                        Paid
                                    </div>
                                </div>
                            </div>

                            {{-- Order Body --}}
                            <div class="p-4 space-y-3">
                                {{-- Customer Info --}}
                                <div class="flex items-start gap-2">
                                    <svg class="w-4 h-4 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                                        @if($order->customer_phone)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->customer_phone }}</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Items Preview --}}
                                <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-600 dark:text-gray-400">Items:</span>
                                        <span class="font-semibold text-gray-900 dark:text-white">{{ $order->items->count() }}</span>
                                    </div>
                                    <div class="space-y-1 max-h-24 overflow-y-auto text-xs">
                                        @foreach($order->items->take(3) as $item)
                                            <div class="flex justify-between text-gray-600 dark:text-gray-400">
                                                <span>{{ $item->medicine->name }}</span>
                                                <span>{{ $item->quantity }} pcs</span>
                                            </div>
                                        @endforeach
                                        @if($order->items->count() > 3)
                                            <div class="text-gray-400 text-xs">+{{ $order->items->count() - 3 }} more items</div>
                                        @endif
                                    </div>
                                </div>

                                {{-- Total and Action --}}
                                <div class="border-t border-gray-100 dark:border-gray-700 pt-3">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-gray-600 dark:text-gray-400">Total:</span>
                                        <span class="text-xl font-bold text-green-600 dark:text-green-400">ETB {{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                    <button wire:click="viewOrder({{ $order->id }})"
                                        class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Dispense Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-16">
                            <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Orders to Dispense</h3>
                            <p class="text-gray-500 dark:text-gray-400">All paid orders have been dispensed</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Check back later for new orders</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Dispense Modal --}}
        @if($showDispenseModal && $selectedOrder)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             x-data="{ open: true }"
             x-show="open"
             @keydown.escape.window="open = false; $wire.closeModal()">

            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                {{-- Modal Header --}}
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        Dispense Order
                    </h3>
                    <button @click="open = false; $wire.closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-4">
                    {{-- Order Details --}}
                    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-4 space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Order Number:</span>
                            <span class="font-mono font-bold text-purple-600 dark:text-purple-400">{{ $selectedOrder->order_number }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Customer:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->customer_name }}</span>
                        </div>
                        @if($selectedOrder->customer_phone)
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Phone:</span>
                            <span class="text-gray-900 dark:text-white">{{ $selectedOrder->customer_phone }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Paid Amount:</span>
                            <span class="font-bold text-green-600 dark:text-green-400">ETB {{ number_format($selectedOrder->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Paid At:</span>
                            <span>{{ $selectedOrder->paid_at ? $selectedOrder->paid_at->format('F d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>

                    {{-- Items List --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Items to Dispense
                        </h4>
                        <div class="space-y-2 max-h-64 overflow-y-auto">
                            @foreach($selectedOrder->items as $item)
                                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $item->medicine->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->medicine->strength }} | Batch: {{ $item->batch->batch_number }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ $item->quantity }} pcs</div>
                                        <div class="text-xs text-gray-500">@ ETB {{ number_format($item->unit_price, 2) }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Notes --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dispense Notes (Optional)</label>
                        <textarea wire:model="dispenseNotes" rows="3"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="Any notes about this dispensing..."></textarea>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex gap-3">
                    <button wire:click="completeDispensing" wire:loading.attr="disabled"
                        class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-3 rounded-xl font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="completeDispensing">
                            ✅ Confirm Dispense & Give to Customer
                        </span>
                        <span wire:loading wire:target="completeDispensing">
                            ⏳ Dispensing...
                        </span>
                    </button>
                    <button wire:click="closeModal"
                        class="flex-1 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 py-3 rounded-xl font-semibold hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
