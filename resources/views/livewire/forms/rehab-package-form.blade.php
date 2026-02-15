{{-- resources/views/livewire/rehab-package-form.blade.php --}}
<div class="py-12" x-data="{ showForm: true }">
    {{-- Alert Notification --}}
    @if($showAlert)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-6 right-6 z-50 max-w-sm w-full">
        <div class="rounded-xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 
                @if($alertType === 'success') bg-gradient-to-r from-green-500 to-emerald-600
                @elseif($alertType === 'error') bg-gradient-to-r from-red-500 to-rose-600
                @elseif($alertType === 'warning') bg-gradient-to-r from-amber-500 to-orange-600
                @else bg-gradient-to-r from-blue-500 to-blue-600
                @endif">
                <div class="flex items-center space-x-3">
                    @if($alertType === 'success')
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @elseif($alertType === 'error')
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Rehabilitation Package</h1>
                                <p class="text-purple-100 mt-1">
                                    {{ $packageId ? 'Edit package details' : 'Create new rehabilitation package' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Form --}}
        <div class="space-y-6">
            {{-- Basic Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                placeholder="e.g., Basic Rehabilitation Package">
                            @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Base Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500">$</span>
                                <input type="number" step="0.01" wire:model.live="base_price"
                                    class="w-full pl-8 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="0.00">
                            </div>
                            @error('base_price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Package description..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Discount Settings --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Discount Settings</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Discount Type
                            </label>
                            <select wire:model.live="discount_type"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white">
                                <option value="">No Discount</option>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage</option>
                            </select>
                        </div>
                        
                        @if($discount_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Discount Value
                            </label>
                            <div class="relative">
                                @if($discount_type === 'fixed')
                                <span class="absolute left-4 top-3 text-gray-500">$</span>
                                @endif
                                <input type="number" step="0.01" wire:model.live="discount_value"
                                    class="w-full {{ $discount_type === 'fixed' ? 'pl-8' : 'px-4' }} pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="{{ $discount_type === 'percentage' ? '0' : '0.00' }}">
                                @if($discount_type === 'percentage')
                                <span class="absolute right-4 top-3 text-gray-500">%</span>
                                @endif
                            </div>
                            @error('discount_value') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Final Price
                            </label>
                            <div class="w-full px-4 py-3 bg-gray-100 dark:bg-gray-700 rounded-xl text-gray-900 dark:text-white font-semibold">
                                ${{ number_format($finalPrice, 2) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Standard Medications --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Standard Medications</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Add standard medications from formulary</p>
                    </div>
                    <button type="button" wire:click="addStandardMedication"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Medication</span>
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($standardMedications as $index => $med)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded-full">
                                    Standard
                                </span>
                                @if($editingType === 'standard' && $editingIndex === $index)
                                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs font-medium rounded-full">
                                    Editing
                                </span>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" wire:click="editStandardMedication({{ $index }})"
                                    class="p-1 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="removeStandardMedication({{ $index }})"
                                    onclick="return confirm('Remove this medication?')"
                                    class="p-1 text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Medication</label>
                                <div class="font-medium">{{ $med['item_name'] }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Dosage</label>
                                <div>{{ $med['dosage'] }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Frequency</label>
                                <div>{{ $frequencies->firstWhere('id', $med['frequency_id'])?->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Duration</label>
                                <div>{{ $med['duration'] }}</div>
                            </div>
                            @if($med['notes'])
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $med['notes'] }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                        <p class="text-sm">No standard medications added yet.</p>
                        <p class="text-xs mt-1">Click "Add Medication" to add standard medications.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Custom Medications --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Custom Medications</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Add custom medications not in formulary</p>
                    </div>
                    <button type="button" wire:click="addCustomMedication"
                        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Custom</span>
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($customMedications as $index => $med)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs font-medium rounded-full">
                                    Custom
                                </span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" wire:click="editCustomMedication({{ $index }})"
                                    class="p-1 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="removeCustomMedication({{ $index }})"
                                    onclick="return confirm('Remove this medication?')"
                                    class="p-1 text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Medication</label>
                                <div class="font-medium">{{ $med['item_name'] }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Dosage</label>
                                <div>{{ $med['dosage'] }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Frequency</label>
                                <div>{{ $frequencies->firstWhere('id', $med['frequency_id'])?->name ?? 'N/A' }}</div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Duration</label>
                                <div>{{ $med['duration'] }}</div>
                            </div>
                            @if($med['notes'])
                            <div class="md:col-span-4">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Notes</label>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $med['notes'] }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-sm">No custom medications added yet.</p>
                        <p class="text-xs mt-1">Click "Add Custom" to add custom medications.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Services --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Services</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Add therapy services and procedures</p>
                    </div>
                    <button type="button" wire:click="addService"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Service</span>
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    @forelse($services as $index => $service)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">
                                        Service
                                    </span>
                                    <h3 class="font-medium">{{ $service['item_name'] }}</h3>
                                </div>
                                @if($service['notes'])
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $service['notes'] }}</p>
                                @endif
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <button type="button" wire:click="editService({{ $index }})"
                                    class="p-1 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="removeService({{ $index }})"
                                    onclick="return confirm('Remove this service?')"
                                    class="p-1 text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <p class="text-sm">No services added yet.</p>
                        <p class="text-xs mt-1">Click "Add Service" to add therapy services.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Bed --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 flex justify-between items-center">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bed Accommodation</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Add bed stay details (max one per package)</p>
                    </div>
                    @if(!$bed)
                    <button type="button" wire:click="addBed"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Bed</span>
                    </button>
                    @endif
                </div>
                
                <div class="p-6">
                    @if($bed)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded-full">
                                Bed Accommodation
                            </span>
                            <button type="button" wire:click="removeBed"
                                onclick="return confirm('Remove bed accommodation?')"
                                class="p-1 text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Duration (Days) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" wire:model="bed.bed_duration_days" min="1"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Enter number of days">
                                @error('bed.bed_duration_days') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Notes
                                </label>
                                <input type="text" wire:model="bed.notes"
                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                    placeholder="Room type, special requirements...">
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-8 text-gray-500 dark:text-gray-400 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                        <p class="text-sm">No bed accommodation added.</p>
                        <p class="text-xs mt-1">Click "Add Bed" to add bed stay details (max one per package).</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Status --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Package Status</h2>
                </div>
                
                <div class="p-6">
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" wire:model="is_active"
                            class="h-5 w-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active (available for use)</span>
                    </label>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="sticky bottom-6 z-10">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('rehab.packages.index') }}" wire:navigate
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Cancel</span>
                            </a>
                            
                            @if($editingType)
                            <button type="button" wire:click="cancelEdit"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                Cancel Edit
                            </button>
                            @endif
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" wire:click="resetForm"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                onclick="return confirm('Clear all form data?')">
                                Clear Form
                            </button>
                            
                            <button type="button" wire:click="saveAndContinue" wire:loading.attr="disabled"
                                class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                <span>Save & Continue</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                            
                            <button type="button" wire:click="save" wire:loading.attr="disabled"
                                class="px-8 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span>{{ $packageId ? 'Update' : 'Save' }} Package</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('redirectAfterDelay', (data) => {
            setTimeout(() => {
                window.location.href = data.url;
            }, 1500);
        });
        
        $wire.on('scrollToForm', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    @endscript
</div>