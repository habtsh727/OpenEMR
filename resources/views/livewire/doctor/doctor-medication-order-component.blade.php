<div>
    <div class="bg-white rounded-lg shadow">
        <!-- Header -->
        <div class="px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">Medication Order</h2>
                    <p class="text-sm text-gray-600">
                        Patient: {{ $encounter->patient->name ?? 'N/A' }} |
                        Encounter: #{{ $encounter->id }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="$toggle('showCustomMedicationModal')"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        + Custom Medication
                    </button>
                    @if($order->status === 'draft')
                    <button wire:click="$set('showSubmitConfirm', true)" wire:loading.attr="disabled"
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" {{ $items->isEmpty() ?
                        'disabled' : '' }}>
                        Submit Order
                    </button>
                    @else
                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg">
                        Order Submitted
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('success'))
        <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Search Bar -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchQuery"
                    class="w-full px-4 py-3 pl-10 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Search medications by name, code, or ingredients...">
                <div class="absolute left-3 top-3.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Search Results -->
            @if($searchQuery && (count($searchResults) > 0 || count($customSearchResults) > 0))
            <div
                class="mt-2 absolute z-50 w-full bg-white border border-gray-200 rounded-lg shadow-lg max-h-96 overflow-y-auto">
                <!-- Standard Medications -->
                @if(count($searchResults) > 0)
                <div class="p-2 border-b">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Standard Medications</h3>
                    @foreach($searchResults as $medication)
                    <button wire:click="selectMedication({{ json_encode($medication) }})"
                        class="w-full text-left p-3 hover:bg-blue-50 cursor-pointer border-b last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $medication['name'] }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ $medication['generic_name'] }} • {{ $medication['strength'] }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $medication['route'] }} • Stock: {{ $medication['stock'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-green-600">
                                    ₦{{ number_format($medication['selling_price'], 2) }}
                                </span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif

                <!-- Custom Medications -->
                @if(count($customSearchResults) > 0)
                <div class="p-2">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Custom Medications</h3>
                    @foreach($customSearchResults as $medication)
                    <button wire:click="selectMedication({{ json_encode($medication) }})"
                        class="w-full text-left p-3 hover:bg-purple-50 cursor-pointer border-b last:border-b-0">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-gray-900">{{ $medication['name'] }}</h4>
                                <p class="text-sm text-gray-600">
                                    {{ Str::limit($medication['ingredients'], 50) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $medication['dosage'] }} • {{ $medication['duration'] }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="font-semibold text-green-600">
                                    ₦{{ number_format($medication['base_price'], 2) }}
                                </span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Order Items Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Medication
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dosage
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Frequency
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Duration
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Quantity
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit Price
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Discount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Total
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $item->drug?->name ?? $item->customMedication?->name }}
                                        @if($item->custom_medication_id)
                                        <span
                                            class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Custom
                                        </span>
                                        @endif
                                    </div>
                                    @if($item->instructions)
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->instructions, 50) }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->dosage }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->frequency->name ?? '-'
                            }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->duration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $item->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ₦{{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($item->discount_type)
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium 
                            {{ $item->discount_type === 'percentage' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $item->discount_type === 'percentage' ? $item->discount_value.'%' :
                                    '₦'.number_format($item->discount_value, 2) }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ₦{{ number_format($item->total_price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="editItem({{ $item->id }})"
                                class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                            <button wire:click="removeItem({{ $item->id }})" wire:confirm="Remove this medication?"
                                class="text-red-600 hover:text-red-900">Remove</button>
                        </td>
                    </tr>
                    @empty
                    <!-- Keep your empty state -->
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Order Summary -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Order Discount -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Order Discount</h3>
                    <div class="space-y-3">
                        <div class="flex space-x-3">
                            <select wire:model.live="discountType" wire:change="calculateTotals"
                                class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                                <option value="">No Discount</option>
                                <option value="percentage">Percentage</option>
                                <option value="fixed">Fixed Amount</option>
                            </select>
                            <input type="number" wire:model.live.debounce.500ms="discountValue" @if(!$discountType)
                                disabled @endif class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 
                          @if(!$discountType) bg-gray-100 cursor-not-allowed @endif"
                                placeholder="@if($discountType === 'percentage') Percentage @elseif($discountType === 'fixed') Amount @endif"
                                min="0" @if($discountType==='percentage' ) max="100" @endif>
                        </div>
                        @if($discountType && $discountValue > 0)
                        <div class="text-sm text-green-600 font-medium">
                            Discount:
                            @if($discountType === 'percentage')
                            {{ $discountValue }}% (₦{{ number_format(($subtotal * $discountValue) / 100, 2) }})
                            @else
                            ₦{{ number_format($discountValue, 2) }}
                            @endif
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Totals -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Order Summary</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-medium">₦{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Discount:</span>
                            <span class="font-medium text-red-600">-₦{{ number_format($totalDiscount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold pt-2 border-t">
                            <span class="text-gray-800">Payable Amount:</span>
                            <span class="text-green-600">₦{{ number_format($payableAmount, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="bg-white p-4 rounded-lg border">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Order Information</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium">#{{ $order->id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $order->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Medications:</span>
                            <span class="font-medium">{{ $items->count() }} items</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Item Modal -->
    @if($showAddItemModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ $editingItemId ? 'Edit' : 'Add' }} Medication
                            </h3>

                            <form wire:submit="addItem" class="mt-4 space-y-4">
                                <!-- Medication Type -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Medication Type</label>
                                    <div class="mt-1 flex space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="itemType" value="standard"
                                                class="form-radio h-4 w-4 text-blue-600">
                                            <span class="ml-2 text-sm text-gray-700">Standard Drug</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" wire:model="itemType" value="custom"
                                                class="form-radio h-4 w-4 text-purple-600">
                                            <span class="ml-2 text-sm text-gray-700">Custom Medication</span>
                                        </label>
                                    </div>
                                    @error('itemType') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Dosage -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Dosage *</label>
                                    <input type="text" wire:model="dosage"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    @error('dosage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Frequency -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Frequency</label>
                                    <select wire:model="frequencyId"
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                        <option value="">Select Frequency</option>
                                        @foreach($frequencies as $frequency)
                                        <option value="{{ $frequency->id }}">{{ $frequency->name }} ({{
                                            $frequency->short_code }})</option>
                                        @endforeach
                                    </select>
                                    @error('frequencyId') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Duration *</label>
                                    <input type="text" wire:model="duration"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="e.g., 5 days, 1 week, 2 months">
                                    @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Quantity & Price -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Quantity *</label>
                                        <input type="number" wire:model="quantity" min="1"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Unit Price (₦) *</label>
                                        <input type="number" wire:model="unitPrice" min="0" step="0.01"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('unitPrice') <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Item Discount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Item Discount</label>
                                    <div class="mt-1 grid grid-cols-2 gap-3">
                                        <select wire:model.live="itemDiscountType"
                                            class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">No Discount</option>
                                            <option value="percentage">Percentage</option>
                                            <option value="fixed">Fixed Amount</option>
                                        </select>
                                        <input type="number" wire:model="itemDiscountValue" @if(!$itemDiscountType)
                                            disabled @endif class="border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm
                      @if(!$itemDiscountType) bg-gray-100 cursor-not-allowed @endif"
                                            placeholder="{{ $itemDiscountType === 'percentage' ? '%' : '₦' }}" min="0"
                                            {{ $itemDiscountType==='percentage' ? 'max="100"' : '' }}>
                                    </div>
                                    @error('itemDiscountValue') <span class="text-red-500 text-xs">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Instructions -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Instructions
                                        (Optional)</label>
                                    <textarea wire:model="instructions" rows="2"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        placeholder="Special instructions for patient..."></textarea>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit" wire:loading.attr="disabled"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                        {{ $editingItemId ? 'Update' : 'Add' }} Medication
                                    </button>
                                    <button type="button" wire:click="$set('showAddItemModal', false)"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Custom Medication Modal -->
    @if($showCustomMedicationModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <livewire:doctor.custom-medication-form-component />
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="$set('showCustomMedicationModal', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Submit Confirmation Modal -->
    @if($showSubmitConfirm)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Submit Medication Order
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    Are you sure you want to submit this medication order? Once submitted, it will be
                                    sent to the pharmacy for processing.
                                </p>
                                <div class="mt-4 p-4 bg-yellow-50 rounded-md">
                                    <div class="flex justify-between text-sm">
                                        <span class="font-medium">Total Amount:</span>
                                        <span class="font-semibold">₦{{ number_format($payableAmount, 2) }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm mt-1">
                                        <span class="font-medium">Medications:</span>
                                        <span>{{ $items->count() }} items</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="submitOrder" wire:loading.attr="disabled"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Yes, Submit Order
                    </button>
                    <button type="button" wire:click="$set('showSubmitConfirm', false)"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        // Listen for events
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('item-saved', () => {
            // Optional: Show toast notification
            console.log('Item saved');
        });
        
        Livewire.on('order-submitted', (data) => {
            // Optional: Redirect or show success message
            console.log('Order submitted:', data.orderId);
        });
    });
    </script>
    <script>
        // Handle discount field enabling/disabling
    Livewire.on('discount-type-changed', (discountType) => {
        const discountValueField = document.querySelector('input[wire\\:model="discountValue"]');
        if (discountValueField) {
            if (!discountType) {
                discountValueField.disabled = true;
                discountValueField.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                discountValueField.disabled = false;
                discountValueField.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }
    });
    
    // Handle item discount field enabling/disabling
    Livewire.on('item-discount-type-changed', (itemDiscountType) => {
        const itemDiscountValueField = document.querySelector('input[wire\\:model="itemDiscountValue"]');
        if (itemDiscountValueField) {
            if (!itemDiscountType) {
                itemDiscountValueField.disabled = true;
                itemDiscountValueField.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                itemDiscountValueField.disabled = false;
                itemDiscountValueField.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        }
    });
    </script>
    @endscript
</div>