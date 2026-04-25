<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto p-6">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Walk-in Customer Order</h1>
            <p class="text-gray-600 dark:text-gray-400">Create order for walk-in customer - Pharmacist Role</p>
        </div>

        @if(session()->has('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
        @endif
        @if(session()->has('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">{{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Left: Medicine Selection --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h2 class="text-lg font-semibold mb-4">Search Medicine</h2>

                    <input type="text" wire:model.live="search" placeholder="Search by name, generic name, or code..."
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 mb-4">

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse($medicines as $medicine)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer"
                                 wire:click="selectMedicine({{ $medicine->id }})">
                                <div class="font-semibold">{{ $medicine->name }}</div>
                                <div class="text-sm text-gray-600">{{ $medicine->generic_name }} - {{ $medicine->strength }}</div>
                                <div class="text-xs text-gray-500">Code: {{ $medicine->code }}</div>
                            </div>
                        @empty
                            <div class="text-center py-8 text-gray-500">No medicines found</div>
                        @endforelse
                    </div>

                    {{ $medicines->links() }}
                </div>

                {{-- Batch Selection (when medicine selected) --}}
                @if($selectedMedicine)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mt-4">
                    <h3 class="font-semibold mb-3">Select Batch for: {{ $selectedMedicine->name }}</h3>

                    <div class="space-y-3">
                        @foreach($batches as $batch)
                            <label class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                   :class="{'border-blue-500 bg-blue-50': {{ $selectedBatch == $batch->id }}}">
                                <div class="flex-1">
                                    <input type="radio" name="batch" wire:model="selectedBatch" value="{{ $batch->id }}" class="mr-3">
                                    <span class="font-medium">Batch: {{ $batch->batch_number }}</span>
                                    <div class="text-sm text-gray-600">Expires: {{ $batch->expiry_date->format('M d, Y') }}</div>
                                    <div class="text-sm">Stock: {{ $batch->quantity }} | Price: ETB {{ number_format($batch->selling_price, 2) }}</div>
                                </div>
                            </label>
                        @endforeach

                        <div class="grid grid-cols-2 gap-3 mt-4">
                            <input type="number" wire:model="quantity" min="1" placeholder="Quantity"
                                class="px-3 py-2 border rounded-lg">
                            <button wire:click="addToCart" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Shopping Cart --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 h-fit sticky top-6">
                <h2 class="text-lg font-semibold mb-4">Shopping Cart</h2>

                @if(empty($cart))
                    <div class="text-center py-8 text-gray-500">Cart is empty</div>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto mb-4">
                        @foreach($cart as $index => $item)
                            <div class="border-b pb-3">
                                <div class="font-semibold">{{ $item['medicine_name'] }} {{ $item['strength'] }}</div>
                                <div class="text-sm text-gray-600">Batch: {{ $item['batch_number'] }}</div>
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] - 1 }})"
                                            class="px-2 py-1 bg-gray-200 rounded">-</button>
                                        <span>{{ $item['quantity'] }}</span>
                                        <button wire:click="updateCartQuantity({{ $index }}, {{ $item['quantity'] + 1 }})"
                                            class="px-2 py-1 bg-gray-200 rounded">+</button>
                                    </div>
                                    <div>ETB {{ number_format($item['total_price'], 2) }}</div>
                                    <button wire:click="removeFromCart({{ $index }})" class="text-red-600">Remove</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span>ETB {{ number_format($cart_total, 2) }}</span>
                        </div>

                        <div class="mt-4 space-y-3">
                            <input type="text" wire:model="customerName" placeholder="Customer Name (Optional)"
                                class="w-full px-3 py-2 border rounded-lg">
                            <input type="text" wire:model="customerPhone" placeholder="Phone Number (Optional)"
                                class="w-full px-3 py-2 border rounded-lg">
                            <textarea wire:model="notes" placeholder="Notes (Optional)" rows="2"
                                class="w-full px-3 py-2 border rounded-lg"></textarea>

                            <button wire:click="submitOrder" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700">
                                Create Order & Send to Cashier
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Success Modal --}}
        @if($showSuccessModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl p-6 max-w-md w-full">
                <div class="text-center">
                    <div class="text-green-600 text-5xl mb-4">✓</div>
                    <h3 class="text-xl font-bold mb-2">Order Created Successfully!</h3>
                    <p class="text-gray-600 mb-4">Order Number: <strong class="text-lg">{{ $generatedOrderNumber }}</strong></p>
                    <p class="text-sm text-gray-500 mb-4">Please inform the customer to proceed to the cashier for payment.</p>
                    <button wire:click="$set('showSuccessModal', false)" class="bg-blue-600 text-white px-6 py-2 rounded-lg">
                        OK
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
