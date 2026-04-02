<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-4 bg-blue-600 dark:bg-blue-700">
            <h2 class="text-2xl font-bold text-white">Create Cupping Order</h2>
            <p class="text-blue-100">Patient: {{ $encounter->patient->name ?? 'N/A' }} | Encounter #{{ $encounter->id }}
            </p>
        </div>

        <form wire:submit.prevent="save" class="p-6">
            <!-- Basic Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-2">Total Sessions</label>
                    <input type="number" wire:model.live="total_sessions" min="1" max="10"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Discount (Fixed Amount)</label>
                    <input type="number" step="0.01" wire:model.live="discount"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700">
                </div>
            </div>

            <!-- Sessions -->
            @foreach($sessions as $sessionIndex => $session)
            <div class="mb-8 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 flex justify-between items-center">
                    <h3 class="font-semibold text-lg">Session #{{ $session['session_number'] }}</h3>
                    <div class="text-sm">
                        Date: <input type="date" wire:model="sessions.{{ $sessionIndex }}.session_date"
                            class="ml-2 px-2 py-1 border rounded dark:bg-gray-600">
                    </div>
                </div>

                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-3 py-2 text-left text-sm">Type</th>
                                    <th class="px-3 py-2 text-left text-sm">Location</th>
                                    <th class="px-3 py-2 text-center text-sm">Qty</th>
                                    <th class="px-3 py-2 text-right text-sm">Price</th>
                                    <th class="px-3 py-2 text-right text-sm">Total</th>
                                    <th class="px-3 py-2 text-center text-sm">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($session['items'] as $itemIndex => $item)
                                <tr class="border-b dark:border-gray-700">
                                    <td class="px-3 py-2">
                                        <select
                                            wire:model="sessions.{{ $sessionIndex }}.items.{{ $itemIndex }}.cupping_type_id"
                                            class="w-full px-2 py-1 border rounded text-sm dark:bg-gray-700">
                                            <option value="">Select Type</option>
                                            @foreach($cuppingTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2">
                                        <select
                                            wire:model="sessions.{{ $sessionIndex }}.items.{{ $itemIndex }}.cupping_location_id"
                                            class="w-full px-2 py-1 border rounded text-sm dark:bg-gray-700">
                                            <option value="">Select Location</option>
                                            @foreach($cuppingLocations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <input type="number"
                                            wire:model.live="sessions.{{ $sessionIndex }}.items.{{ $itemIndex }}.qty"
                                            wire:change="updateItemTotal({{ $sessionIndex }}, {{ $itemIndex }})"
                                            class="w-20 px-2 py-1 border rounded text-center dark:bg-gray-700">
                                    </td>
                                    <td class="px-3 py-2">
                                        <input type="number" step="0.01"
                                            wire:model.live="sessions.{{ $sessionIndex }}.items.{{ $itemIndex }}.price"
                                            wire:change="updateItemTotal({{ $sessionIndex }}, {{ $itemIndex }})"
                                            class="w-28 px-2 py-1 border rounded text-right dark:bg-gray-700">
                                    </td>
                                    <td class="px-3 py-2 text-right font-medium">
                                        {{ number_format($item['total'], 2) }}
                                    </td>
                                    <td class="px-3 py-2 text-center">
                                        <button type="button"
                                            wire:click="removeItem({{ $sessionIndex }}, {{ $itemIndex }})"
                                            class="text-red-500 hover:text-red-700">🗑️</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <button type="button" wire:click="addItem({{ $sessionIndex }})"
                        class="mt-3 text-sm text-blue-500 hover:text-blue-700">+ Add Item</button>

                    <div class="mt-3 text-right font-bold">
                        Session Total: {{ number_format($session['session_amount'], 2) }}
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Totals -->
            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6">
                <div class="flex justify-between text-lg">
                    <span>Grand Total:</span>
                    <span class="font-bold">{{ number_format($grand_total, 2) }}</span>
                </div>
                <div class="flex justify-between text-lg">
                    <span>Discount:</span>
                    <span class="font-bold text-green-600">- {{ number_format($discount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xl mt-2 pt-2 border-t dark:border-gray-600">
                    <span class="font-bold">Final Amount:</span>
                    <span class="font-bold text-blue-600">{{ number_format($final_amount, 2) }}</span>
                </div>
            </div>

            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">General Notes</label>
                <textarea wire:model="notes" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700"
                    placeholder="Any additional notes..."></textarea>
            </div>

            <!-- Submit -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    Create Order & Send to Payment Queue
                </button>
            </div>
        </form>
    </div>
</div>