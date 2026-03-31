<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
                    <h2 class="text-2xl font-bold text-white">Cupping Therapy Order Form</h2>
                    <p class="text-blue-100 mt-1">Encounter #{{ $encounter->id }} | Patient: {{ $encounter->patient->name ?? 'N/A' }}</p>
                </div>
                
                <div class="p-6">
                    @if(session()->has('message'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('message') }}
                        </div>
                    @endif
                    
                    @if(session()->has('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Cupping Items</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cupping Type</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price (ETB)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total (ETB)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($items as $index => $item)
                                        <tr>
                                            <td class="px-4 py-3">
                                                <select wire:model="items.{{ $index }}.cupping_type_id" 
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Select Type</option>
                                                    @foreach($cuppingTypes as $type)
                                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.cupping_type_id") 
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3">
                                                <select wire:model="items.{{ $index }}.cupping_location_id" 
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="">Select Location</option>
                                                    @foreach($cuppingLocations as $location)
                                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.cupping_location_id") 
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                       wire:model="items.{{ $index }}.qty" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       min="1">
                                                @error("items.{$index}.qty") 
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="number" 
                                                       wire:model="items.{{ $index }}.price" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       step="0.01"
                                                       min="0">
                                                @error("items.{$index}.price") 
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" 
                                                       value="{{ number_format($item['total'], 2) }}" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 bg-gray-50"
                                                       readonly
                                                       disabled>
                                            </td>
                                            <td class="px-4 py-3">
                                                <input type="text" 
                                                       wire:model="items.{{ $index }}.notes" 
                                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                       placeholder="Optional notes">
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                @if(count($items) > 1)
                                                    <button type="button" 
                                                            wire:click="removeItem({{ $index }})"
                                                            class="text-red-600 hover:text-red-800">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <button type="button" 
                                    wire:click="addItem"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Item
                            </button>
                        </div>
                        @error('items') 
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <div class="space-y-4 max-w-md ml-auto">
                            <div class="flex justify-between items-center">
                                <label class="text-gray-700 font-medium">Grand Total:</label>
                                <span class="text-2xl font-bold text-gray-900">{{ number_format($grandTotal, 2) }} ETB</span>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <label class="text-gray-700 font-medium">Discount:</label>
                                <div class="w-48">
                                    <input type="number" 
                                           wire:model="discount"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-right"
                                           step="0.01"
                                           min="0">
                                    @error('discount') 
                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                                <label class="text-xl font-semibold text-gray-900">Final Amount:</label>
                                <span class="text-2xl font-bold text-blue-600">{{ number_format($finalTotal, 2) }} ETB</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label class="block text-gray-700 font-medium mb-2">Additional Notes:</label>
                        <textarea wire:model="notes"
                                  rows="3"
                                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="Any additional notes about the cupping therapy..."></textarea>
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" 
                                onclick="window.history.back()"
                                class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-150">
                            Cancel
                        </button>
                        <button type="button" 
                                wire:click="save"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition duration-150">
                            Place Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>