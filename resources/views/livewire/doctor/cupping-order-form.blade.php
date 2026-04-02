{{-- resources/views/livewire/doctor/cupping-order-form.blade.php --}}
<div>
    <div class="p-6 max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Create Cupping Order</h2>
        
        <!-- Encounter Info -->
        <div class="bg-gray-100 p-4 rounded mb-6">
            <p><strong>Patient ID:</strong> {{ $encounter->id }}</p>
            <p><strong>Patient Name:</strong> {{ $encounter->patient->name ?? 'N/A' }}</p>
        </div>

        <form wire:submit.prevent="save">
            <!-- Treatment Date -->
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Treatment Date</label>
                <input type="date" wire:model="treatment_date" class="w-full p-2 border rounded">
                @error('treatment_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Items -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-medium">Cupping Items</label>
                    <button type="button" wire:click="addItem" class="bg-green-500 text-white px-3 py-1 rounded text-sm">
                        + Add Item
                    </button>
                </div>

                @foreach($items as $index => $item)
                    <div class="border p-4 rounded mb-3 bg-gray-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3">
                            <div>
                                <label class="block text-xs font-medium mb-1">Cupping Type</label>
                                <select wire:model="items.{{ $index }}.cupping_type_id" class="w-full p-2 border rounded text-sm">
                                    <option value="">Select Type</option>
                                    @foreach($cuppingTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium mb-1">Location</label>
                                <select wire:model="items.{{ $index }}.cupping_location_id" class="w-full p-2 border rounded text-sm">
                                    <option value="">Select Location</option>
                                    @foreach($cuppingLocations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium mb-1">Quantity</label>
                                <input type="number" wire:model.live="items.{{ $index }}.qty" wire:change="updateItemTotal({{ $index }})" class="w-full p-2 border rounded text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-medium mb-1">Price</label>
                                <input type="number" step="0.01" wire:model.live="items.{{ $index }}.price" wire:change="updateItemTotal({{ $index }})" class="w-full p-2 border rounded text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-medium mb-1">Total</label>
                                <input type="text" value="{{ number_format($item['total'], 2) }}" readonly class="w-full p-2 border rounded bg-gray-100 text-sm">
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="block text-xs font-medium mb-1">Notes</label>
                            <input type="text" wire:model="items.{{ $index }}.notes" class="w-full p-2 border rounded text-sm">
                        </div>

                        <button type="button" wire:click="removeItem({{ $index }})" class="mt-2 text-red-500 text-sm">Remove</button>
                    </div>
                @endforeach

                @error('items') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Notes & Discount -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-2">General Notes</label>
                    <textarea wire:model="notes" rows="3" class="w-full p-2 border rounded"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-2">Discount</label>
                    <input type="number" step="0.01" wire:model.live="discount" class="w-full p-2 border rounded">
                </div>
            </div>

            <!-- Totals -->
            <div class="bg-blue-50 p-4 rounded mb-4">
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Grand Total:</span>
                    <span class="font-bold">{{ number_format($grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between mb-2">
                    <span class="font-medium">Discount:</span>
                    <span>{{ number_format($discount, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span class="font-bold">Final Amount:</span>
                    <span class="font-bold text-blue-600">{{ number_format($final_amount, 2) }}</span>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Create Order & Proceed to Payment
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert', (event) => {
                alert(event.type.toUpperCase() + ': ' + event.message);
            });
        });
    </script>
</div>