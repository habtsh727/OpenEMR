<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Package Manager</h1>
                            <p class="text-purple-100 mt-1">Create and manage cupping therapy packages</p>
                        </div>
                    </div>

                    <button wire:click="$set('showForm', true)"
                        class="px-6 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create New Package
                    </button>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if($showAlert)
        <div class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
            {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' : 'bg-red-100 border border-red-400 text-red-700' }}">
            <span>{{ $alertMessage }}</span>
            <button wire:click="closeAlert" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        {{-- Package Form --}}
        @if($showForm)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    {{ $editingId ? 'Edit Package' : 'Create New Package' }}
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Basic Info --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Package Name *</label>
                        <input type="text" wire:model="name" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">

                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600"></textarea>

                        <div class="grid grid-cols-2 gap-3 mt-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Type</label>
                                <select wire:model.live="discount_type" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                                    <option value="">No Discount</option>
                                    <option value="fixed">Fixed Amount (ETB)</option>
                                    <option value="percentage">Percentage (%)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount Value</label>
                                <input type="number" wire:model.live="discount_value" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600"
                                    placeholder="{{ $discount_type === 'fixed' ? 'Amount in ETB' : 'Percentage %' }}">
                            </div>
                        </div>
                    </div>

                    {{-- Price Summary --}}
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Price Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span>Treatments Total:</span>
                                <span class="font-medium">ETB {{ number_format($basePrice - (array_sum(array_column($materials, 'total'))), 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Materials Total:</span>
                                <span class="font-medium">ETB {{ number_format(array_sum(array_column($materials, 'total')), 2) }}</span>
                            </div>
                            <div class="border-t pt-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Base Price:</span>
                                    <span class="font-semibold">ETB {{ number_format($basePrice, 2) }}</span>
                                </div>
                                @if($discount_type && $discount_value)
                                <div class="flex justify-between text-red-600">
                                    <span>Discount:</span>
                                    <span>- ETB {{ number_format($basePrice - $totalPrice, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between text-lg font-bold text-purple-600 mt-2">
                                    <span>Final Price:</span>
                                    <span>ETB {{ number_format($totalPrice, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Treatments Section --}}
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Treatments Included</h3>
                        <button type="button" wire:click="addTreatment" class="text-sm text-purple-600 hover:text-purple-700">+ Add Treatment</button>
                    </div>

                    @foreach($treatments as $index => $treatment)
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 mb-3">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-medium">Treatment #{{ $index + 1 }}</span>
                            <button wire:click="removeTreatment({{ $index }})" class="text-red-500 hover:text-red-700">Remove</button>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Cupping Type</label>
                                <select wire:model="treatments.{{ $index }}.cupping_type_id" class="w-full px-2 py-1 border rounded text-sm">
                                    <option value="">Select Type</option>
                                    @foreach($treatmentTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Location</label>
                                <select wire:model="treatments.{{ $index }}.cupping_location_id" class="w-full px-2 py-1 border rounded text-sm">
                                    <option value="">Select Location</option>
                                    @foreach($treatmentLocations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Price (ETB)</label>
                                <input type="number" wire:model.live="treatments.{{ $index }}.treatment_price" class="w-full px-2 py-1 border rounded text-sm">
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Materials Section --}}
                <div class="mt-6">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-gray-900 dark:text-white">Materials Required (from Pharmacy)</h3>
                        <button type="button" wire:click="addMaterial" class="text-sm text-purple-600 hover:text-purple-700">+ Add Material</button>
                    </div>

                    @foreach($materials as $index => $material)
                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 mb-3">
                        <div class="flex justify-between items-start mb-3">
                            <span class="font-medium">Material #{{ $index + 1 }}</span>
                            <button wire:click="removeMaterial({{ $index }})" class="text-red-500 hover:text-red-700">Remove</button>
                        </div>
                        <div class="grid grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Pharmacy Item</label>
                                <select wire:model="materials.{{ $index }}.pharmacy_item_id" wire:change="updateMaterialPrice({{ $index }})" class="w-full px-2 py-1 border rounded text-sm">
                                    <option value="">Select Item</option>
                                    @foreach($pharmacyItems as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Quantity</label>
                                <input type="number" wire:model.live="materials.{{ $index }}.quantity_required" class="w-full px-2 py-1 border rounded text-sm" min="1">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-600 mb-1">Total Cost (ETB)</label>
                                <input type="text" wire:model="materials.{{ $index }}.total" class="w-full px-2 py-1 border rounded text-sm bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Form Actions --}}
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                    <button wire:click="resetForm" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="savePackage" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">Save Package</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Packages List --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Available Packages</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700/50 border-b">
                            <th class="px-6 py-3 text-left text-xs font-semibold">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold">Treatments</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold">Materials</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold">Base Price</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold">Final Price</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($packages as $package)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $package->name }}</div>
                                @if($package->description)
                                    <div class="text-xs text-gray-500">{{ Str::limit($package->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">{{ $package->treatments->count() }} treatment(s)</td>
                            <td class="px-6 py-4 text-sm">{{ $package->materials->count() }} material(s)</td>
                            <td class="px-6 py-4 text-right text-sm">ETB {{ number_format($package->base_price, 2) }}</td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-purple-600">ETB {{ number_format($package->total_price, 2) }}</td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="togglePackageStatus({{ $package->id }})"
                                    class="px-2 py-1 text-xs rounded-full {{ $package->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="editPackage({{ $package->id }})" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="deletePackage({{ $package->id }})" onclick="return confirm('Delete this package?')" class="text-red-600 hover:text-red-800">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No packages found. Click "Create New Package" to add one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
