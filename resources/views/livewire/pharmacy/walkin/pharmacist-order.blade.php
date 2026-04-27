<div>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header Section --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 dark:from-teal-600 dark:to-cyan-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Walk-in Customer Order</h1>
                                <p class="text-teal-100 mt-1">Create order for walk-in customer - Pharmacist Role</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-teal-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <span class="text-sm">OTC Sale - No Prescription Required</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Cart Summary Badge --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-center">
                                <div class="text-white text-sm font-medium mb-1">Cart Items</div>
                                <div class="text-3xl font-bold text-white">{{ count($cart) }}</div>
                                <div class="text-teal-200 text-xs mt-2">Ready for checkout</div>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Left: Medicine Selection (2 columns) --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Medicine Search Card --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search Medicine
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="relative">
                            <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search by medicine name, generic name, or code..."
                                class="w-full pl-12 pr-4 py-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500">
                        </div>

                        <div class="mt-4 space-y-2 max-h-96 overflow-y-auto custom-scrollbar">
                            @forelse($medicines as $medicine)
                                <div wire:click="selectMedicine({{ $medicine->id }})"
                                    class="group border-2 rounded-xl p-4 cursor-pointer transition-all duration-200 hover:shadow-md
                                        {{ $selectedMedicine && $selectedMedicine->id == $medicine->id ? 'border-teal-500 bg-teal-50 dark:bg-teal-900/20 shadow-lg' : 'border-gray-200 dark:border-gray-700 hover:border-teal-300 dark:hover:border-teal-700' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $medicine->name }}</div>
                                                @if($medicine->is_prescription_required)
                                                    <span class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 text-xs rounded-full">Rx Required</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs rounded-full">OTC</span>
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ $medicine->generic_name }} - {{ $medicine->strength }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">Code: {{ $medicine->code }}</div>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-teal-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 font-medium">No medicines found</p>
                                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try searching with a different keyword</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-4">
                            {{ $medicines->links() }}
                        </div>
                    </div>
                </div>

                {{-- Batch Selection Card (when medicine selected) --}}
                @if($selectedMedicine)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden animate-fadeIn">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                            Select Batch for: {{ $selectedMedicine->name }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($batches as $batch)
                                @php
                                    $isExpiringSoon = $batch->expiry_date <= now()->addDays(30);
                                    $isLowStock = $batch->quantity <= 10;
                                @endphp
                                <label class="flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all hover:shadow-md
                                    {{ $selectedBatch == $batch->id ? 'border-teal-500 bg-teal-50 dark:bg-teal-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-teal-300' }}">
                                    <input type="radio" name="batch" wire:model="selectedBatch" value="{{ $batch->id }}" class="mt-1 mr-4">
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium text-gray-900 dark:text-white">Batch: {{ $batch->batch_number }}</div>
                                            @if($isExpiringSoon)
                                                <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Expiring Soon</span>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div class="text-gray-600 dark:text-gray-400">
                                                <span class="text-xs">Expires:</span>
                                                <span class="font-medium ml-1">{{ $batch->expiry_date->format('M d, Y') }}</span>
                                            </div>
                                            <div class="text-gray-600 dark:text-gray-400">
                                                <span class="text-xs">Stock:</span>
                                                <span class="font-medium ml-1 {{ $isLowStock ? 'text-red-600 dark:text-red-400' : '' }}">{{ $batch->quantity }} units</span>
                                            </div>
                                            <div class="text-gray-600 dark:text-gray-400 col-span-2">
                                                <span class="text-xs">Price:</span>
                                                <span class="font-bold text-teal-600 dark:text-teal-400 ml-1">ETB {{ number_format($batch->selling_price, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach

                            <div class="grid grid-cols-2 gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity</label>
                                    <div class="flex items-center gap-2">
                                        <button wire:click="$set('quantity', max(1, {{ $quantity }} - 1))"
                                            class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center text-xl font-bold">-</button>
                                        <input type="number" wire:model="quantity" min="1"
                                            class="w-full px-4 py-2 text-center bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                                        <button wire:click="$set('quantity', {{ $quantity }} + 1)"
                                            class="w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center text-xl font-bold">+</button>
                                    </div>
                                </div>
                                <div class="flex items-end">
                                    <button wire:click="addToCart"
                                        class="w-full bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Shopping Cart (1 column) --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden sticky top-6">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1.5 6M18 13l1.5 6M5 21h14M9 7h6"></path>
                            </svg>
                            Shopping Cart
                            @if(count($cart) > 0)
                                <span class="ml-2 px-2 py-0.5 bg-teal-100 dark:bg-teal-900/30 text-teal-800 dark:text-teal-300 text-xs rounded-full">{{ count($cart) }} items</span>
                            @endif
                        </h2>
                    </div>

                    <div class="p-6">
                        @if(empty($cart))
                            <div class="text-center py-12">
                                <svg class="w-20 h-20 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 font-medium">Cart is empty</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Add items to create an order</p>
                            </div>
                        @else
                            <div class="space-y-3 max-h-96 overflow-y-auto mb-4 custom-scrollbar">
                                @foreach($cart as $index => $item)
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-3">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-white">{{ $item['medicine_name'] }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $item['strength'] }} | Batch: {{ $item['batch_number'] }}</div>
                                            </div>
                                            <button wire:click="removeFromCart({{ $index }})"
                                                class="text-red-500 hover:text-red-700 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <button wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                                    class="w-7 h-7 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors flex items-center justify-center">-</button>
                                                <span class="w-8 text-center font-medium">{{ $item['quantity'] }}</span>
                                                <button wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                                    class="w-7 h-7 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors flex items-center justify-center">+</button>
                                            </div>
                                            <div class="font-semibold text-teal-600 dark:text-teal-400">ETB {{ number_format($item['total_price'], 2) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                    <span class="text-lg text-gray-900 dark:text-white">ETB {{ number_format($cart_total, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-600 dark:text-gray-400">VAT (0%):</span>
                                    <span class="text-gray-900 dark:text-white">ETB 0.00</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">Total:</span>
                                    <span class="text-2xl font-bold text-teal-600 dark:text-teal-400">ETB {{ number_format($cart_total, 2) }}</span>
                                </div>

                                <div class="space-y-3 pt-2">
                                    <input type="text" wire:model="customerName"
                                        placeholder="Customer Name (Optional)"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                    <input type="text" wire:model="customerPhone"
                                        placeholder="Phone Number (Optional)"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all">
                                    <textarea wire:model="notes" placeholder="Notes (Optional)" rows="2"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"></textarea>

                                    <button wire:click="submitOrder"
                                        class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white py-4 rounded-xl font-semibold shadow-lg shadow-green-500/30 transition-all duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Create Order & Send to Cashier
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Modal --}}
        @if($showSuccessModal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             x-data="{ open: true }"
             x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 text-center"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                    <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Order Created!</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">Order Number:</p>
                <div class="bg-gray-100 dark:bg-gray-900 rounded-xl p-3 mb-4">
                    <span class="font-mono text-xl font-bold text-teal-600 dark:text-teal-400">{{ $generatedOrderNumber }}</span>
                </div>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 mb-6">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        Please inform the customer to proceed to the cashier for payment.
                    </p>
                </div>
                <button wire:click="$set('showSuccessModal', false)"
                    class="w-full bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white py-3 rounded-xl font-semibold transition-all duration-200">
                    Continue
                </button>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
    }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>
</div>
