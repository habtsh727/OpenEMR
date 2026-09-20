<div>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900">
        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                        {{ $medicationId ? 'Edit Custom Medication' : 'Create Custom Medication' }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Compound medications for prescriptions</p>
                </div>
                <div class="flex space-x-3">
                    <button wire:click="resetForm"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        New Medication
                    </button>
                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-lg hover:bg-purple-700 dark:hover:bg-purple-800 transition-colors">
                        {{ $medicationId ? 'Update' : 'Save' }} Medication
                    </button>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('success'))
        <div class="m-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400 dark:text-green-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form wire:submit="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Medication Name -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medication Name *</label>
                    <input type="text" wire:model="name"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                           placeholder="Enter medication name">
                    @error('name') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Ingredients -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ingredients *</label>
                    <textarea wire:model="ingredients" rows="3"
                              class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                              placeholder="List all ingredients with quantities (one per line)"></textarea>
                    @error('ingredients') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Dosage -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage *</label>
                    <input type="text" wire:model="dosage"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                           placeholder="e.g., 10mg, 5ml">
                    @error('dosage') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Frequency -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency *</label>
                    <select wire:model="frequencyId"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm">
                        <option value="">Select Frequency</option>
                        @foreach($frequencies as $frequency)
                        <option value="{{ $frequency->id }}">{{ $frequency->name }} ({{ $frequency->short_code }})</option>
                        @endforeach
                    </select>
                    @error('frequencyId') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration *</label>
                    <input type="text" wire:model="duration"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                           placeholder="e.g., 7 days, 2 weeks">
                    @error('duration') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Base Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base Price (₦) *</label>
                    <input type="number" wire:model="basePrice" step="0.01" min="0"
                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm">
                    @error('basePrice') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Preparation Instructions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preparation Instructions</label>
                <textarea wire:model="preparationInstructions" rows="2"
                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                          placeholder="How to prepare this medication..."></textarea>
            </div>

            <!-- Patient Instructions -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Instructions</label>
                <textarea wire:model="instructions" rows="2"
                          class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 sm:text-sm"
                          placeholder="Instructions for patient use..."></textarea>
            </div>
            
            <!-- Live Preview Section (Optional) -->
            @if($name || $ingredients || $dosage)
            <div class="mt-8 pt-6 border-t dark:border-gray-700">
                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Live Preview</h3>
                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border dark:border-gray-700">
                    <div class="space-y-2 text-sm">
                        @if($name)
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24">Name:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $name }}</span>
                        </div>
                        @endif
                        
                        @if($dosage)
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24">Dosage:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $dosage }}</span>
                        </div>
                        @endif
                        
                        @if($frequencyId)
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24">Frequency:</span>
                            <span class="text-gray-900 dark:text-gray-100">
                                @php
                                    $selectedFrequency = $frequencies->firstWhere('id', $frequencyId);
                                @endphp
                                {{ $selectedFrequency->name ?? '' }} ({{ $selectedFrequency->short_code ?? '' }})
                            </span>
                        </div>
                        @endif
                        
                        @if($duration)
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24">Duration:</span>
                            <span class="text-gray-900 dark:text-gray-100">{{ $duration }}</span>
                        </div>
                        @endif
                        
                        @if($basePrice)
                        <div class="flex">
                            <span class="font-medium text-gray-600 dark:text-gray-400 w-24">Price:</span>
                            <span class="text-gray-900 dark:text-gray-100">₦{{ number_format($basePrice, 2) }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>