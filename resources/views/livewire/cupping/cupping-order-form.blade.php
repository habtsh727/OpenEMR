<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-7 h-7 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Cupping Therapy Order
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Patient: <span class="font-semibold">{{ $encounter->patient->name }}</span> | 
                        Encounter #{{ $encounter->id }} | 
                        Doctor: {{ $encounter->doctor->name ?? 'Not Assigned' }}
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm font-medium">
                        Order Status: Pending
                    </span>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 text-blue-700 dark:text-blue-300 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('info') }}
                </div>
            </div>
        @endif

        <!-- Items Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 mb-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Cupping Items
                    </h2>
                    <button type="button" 
                            wire:click="addItem"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Item
                    </button>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        32
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Cupping Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Location</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price (ETB)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total (ETB)</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($items as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3">
                                    <select wire:model.live="items.{{ $index }}.cupping_type_id" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm">
                                        <option value="">Select Type</option>
                                        @foreach($cuppingTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("items.{$index}.cupping_type_id") 
                                        <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <select wire:model.live="items.{{ $index }}.cupping_location_id" 
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm">
                                        <option value="">Select Location</option>
                                        @foreach($cuppingLocations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                    @error("items.{$index}.cupping_location_id") 
                                        <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" 
                                           wire:model.live="items.{{ $index }}.qty" 
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm"
                                           min="1">
                                    @error("items.{$index}.qty") 
                                        <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" 
                                           wire:model.live="items.{{ $index }}.price" 
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm"
                                           step="0.01"
                                           min="0">
                                    @error("items.{$index}.price") 
                                        <span class="text-red-500 dark:text-red-400 text-xs mt-1 block">{{ $message }}</span>
                                    @enderror
                                </td>
                                <td class="px-4 py-3">
                                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-right font-medium text-gray-700 dark:text-gray-300">
                                        {{ number_format($item['total'], 2) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" 
                                           wire:model="items.{{ $index }}.notes" 
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-sm"
                                           placeholder="Optional notes...">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if(count($items) > 1)
                                        <button type="button" 
                                                wire:click="removeItem({{ $index }})"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors"
                                                title="Remove item">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <span>No items added. Click "Add Item" to begin.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary and Notes Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Additional Notes -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 border border-gray-200 dark:border-gray-700">
                <label class="block text-gray-700 dark:text-gray-300 font-medium mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Additional Notes
                </label>
                <textarea wire:model="notes"
                          rows="4"
                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400"
                          placeholder="Enter any additional notes about the cupping therapy (e.g., special instructions, patient preferences, precautions)..."></textarea>
            </div>
            
            <!-- Order Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Order Summary
                </h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                        <span class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($grandTotal, 2) }} ETB</span>
                    </div>
                    
                    <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                        <label class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Discount (ETB):
                        </label>
                        <div class="w-40">
                            <input type="number" 
                                   wire:model.live="discount"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400 text-right"
                                   step="0.01"
                                   min="0">
                            @error('discount') 
                                <span class="text-red-500 dark:text-red-400 text-xs block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-center py-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg px-4 -mx-2">
                        <span class="text-xl font-bold text-gray-900 dark:text-gray-100">Final Amount:</span>
                        <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($finalTotal, 2) }} ETB</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <button wire:click="back"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                               w-full sm:w-auto transition-colors duration-200">
                    ← Back to Examination
                </button>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <button type="button"
                            wire:click="saveDraft"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 border-2 border-yellow-300 dark:border-yellow-700 rounded-lg 
                                   text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 
                                   hover:bg-yellow-100 dark:hover:bg-yellow-900/30 
                                   transition-all duration-200 font-medium">
                        <svg wire:loading.remove wire:target="saveDraft" class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <svg wire:loading wire:target="saveDraft" class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Save as Draft
                    </button>
                    
                    <button type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-green-700 
                                   hover:from-green-700 hover:to-green-800 
                                   dark:from-green-600 dark:to-green-700 
                                   text-white font-medium rounded-lg transition duration-200 
                                   shadow-md hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg wire:loading.remove wire:target="save" class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <svg wire:loading wire:target="save" class="w-5 h-5 inline mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Place Order & Send to Cashier
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>