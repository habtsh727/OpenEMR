{{-- resources/views/livewire/rehab-package-form.blade.php --}}
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <form wire:submit="save">
                <div class="grid grid-cols-1 gap-6">
                    {{-- Basic Information --}}
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Package Name *</label>
                                <input type="text" wire:model="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700">Base Price *</label>
                                <input type="number" step="0.01" wire:model.live="base_price" id="base_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('base_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea wire:model="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                    
                    {{-- Discount Section --}}
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Discount Settings</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="discount_type" class="block text-sm font-medium text-gray-700">Discount Type</label>
                                <select wire:model.live="discount_type" id="discount_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">No Discount</option>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            
                            @if($discount_type)
                            <div>
                                <label for="discount_value" class="block text-sm font-medium text-gray-700">Discount Value</label>
                                <input type="number" step="0.01" wire:model.live="discount_value" id="discount_value" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('discount_value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Final Price</label>
                                <div class="mt-1 block w-full p-2 bg-gray-100 rounded-md">
                                    ${{ number_format($finalPrice, 2) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Items Section with Tabs --}}
                    <div class="bg-gray-50 p-4 rounded-lg">
                        {{-- Tabs --}}
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8">
                                <button type="button" wire:click="$set('activeTab', 'medications')" class="{{ $activeTab === 'medications' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Standard Medications
                                </button>
                                <button type="button" wire:click="$set('activeTab', 'custom')" class="{{ $activeTab === 'custom' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Custom Medications
                                </button>
                                <button type="button" wire:click="$set('activeTab', 'services')" class="{{ $activeTab === 'services' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Services
                                </button>
                                <button type="button" wire:click="$set('activeTab', 'bed')" class="{{ $activeTab === 'bed' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                                    Bed
                                </button>
                            </nav>
                        </div>
                        
                        {{-- Add Item Buttons --}}
                        <div class="mt-4 space-x-2">
                            @if($activeTab === 'medications')
                            <button type="button" wire:click="addItem('standard_medication')" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                + Add Standard Medication
                            </button>
                            @endif
                            
                            @if($activeTab === 'custom')
                            <button type="button" wire:click="addItem('custom_medication')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                + Add Custom Medication
                            </button>
                            @endif
                            
                            @if($activeTab === 'services')
                            <button type="button" wire:click="addItem('service')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                + Add Service
                            </button>
                            @endif
                            
                            @if($activeTab === 'bed' && $this->bed_count === 0)
                            <button type="button" wire:click="addItem('bed')" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                + Add Bed
                            </button>
                            @endif
                        </div>
                        
                        {{-- Items List --}}
                        <div class="mt-6 space-y-4">
                            @php
                                $filteredItems = collect($items)->filter(function($item) use ($activeTab) {
                                    if($activeTab === 'medications') return $item['item_type'] === 'standard_medication';
                                    if($activeTab === 'custom') return $item['item_type'] === 'custom_medication';
                                    if($activeTab === 'services') return $item['item_type'] === 'service';
                                    if($activeTab === 'bed') return $item['item_type'] === 'bed';
                                    return false;
                                });
                            @endphp
                            
                            @forelse($filteredItems as $index => $item)
                                <div class="border rounded-lg p-4 bg-white">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($item['item_type'] === 'bed') bg-purple-100 text-purple-800
                                            @elseif(in_array($item['item_type'], ['standard_medication', 'custom_medication'])) bg-green-100 text-green-800
                                            @else bg-blue-100 text-blue-800
                                            @endif">
                                            {{ str_replace('_', ' ', ucwords($item['item_type'])) }}
                                        </span>
                                        <button type="button" wire:click="removeItem({{ $index }})" class="text-red-600 hover:text-red-900">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Name *</label>
                                            <input type="text" wire:model="items.{{ $index }}.item_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            @error("items.{$index}.item_name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>
                                        
                                        @if(in_array($item['item_type'], ['standard_medication', 'custom_medication']))
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Dosage *</label>
                                                <input type="text" wire:model="items.{{ $index }}.dosage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                @error("items.{$index}.dosage") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Frequency *</label>
                                                <select wire:model="items.{{ $index }}.frequency_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">Select Frequency</option>
                                                    @foreach($frequencies as $frequency)
                                                        <option value="{{ $frequency->id }}">{{ $frequency->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error("items.{$index}.frequency_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Duration *</label>
                                                <input type="text" wire:model="items.{{ $index }}.duration" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                @error("items.{$index}.duration") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        @elseif($item['item_type'] === 'bed')
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700">Duration (Days) *</label>
                                                <input type="number" wire:model="items.{{ $index }}.bed_duration_days" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                @error("items.{$index}.bed_duration_days") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        @endif
                                        
                                        <div class="md:col-span-2 lg:col-span-4">
                                            <label class="block text-xs font-medium text-gray-700">Notes</label>
                                            <input type="text" wire:model="items.{{ $index }}.notes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    No items in this category. Click the button above to add items.
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    {{-- Status --}}
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Active</span>
                        </label>
                    </div>
                    
                    {{-- Submit Button --}}
                    <div class="flex justify-end space-x-2">
                        <a href="{{ route('rehab.packages.index') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            {{ $packageId ? 'Update' : 'Create' }} Package
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>