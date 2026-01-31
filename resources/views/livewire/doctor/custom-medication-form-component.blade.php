<div>
    <div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ $medicationId ? 'Edit Custom Medication' : 'Create Custom Medication' }}
                </h2>
                <p class="text-sm text-gray-600">Compound medications for prescriptions</p>
            </div>
            <div class="flex space-x-3">
                <button wire:click="resetForm"
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    New Medication
                </button>
                <button wire:click="save"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    {{ $medicationId ? 'Update' : 'Save' }} Medication
                </button>
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session()->has('success'))
    <div class="m-6 p-4 bg-green-50 border border-green-200 rounded-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Form -->
    <form wire:submit="save" class="p-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Medication Name -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Medication Name *</label>
                <input type="text" wire:model="name"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                       placeholder="Enter medication name">
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Ingredients -->
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700">Ingredients *</label>
                <textarea wire:model="ingredients" rows="3"
                          class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                          placeholder="List all ingredients with quantities (one per line)"></textarea>
                @error('ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Dosage -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Dosage *</label>
                <input type="text" wire:model="dosage"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                       placeholder="e.g., 10mg, 5ml">
                @error('dosage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Frequency -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Frequency *</label>
                <select wire:model="frequencyId"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="">Select Frequency</option>
                    @foreach($frequencies as $frequency)
                    <option value="{{ $frequency->id }}">{{ $frequency->name }} ({{ $frequency->short_code }})</option>
                    @endforeach
                </select>
                @error('frequencyId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Duration -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Duration *</label>
                <input type="text" wire:model="duration"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                       placeholder="e.g., 7 days, 2 weeks">
                @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Base Price -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Base Price (₦) *</label>
                <input type="number" wire:model="basePrice" step="0.01" min="0"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                @error('basePrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Preparation Instructions -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Preparation Instructions</label>
            <textarea wire:model="preparationInstructions" rows="2"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                      placeholder="How to prepare this medication..."></textarea>
        </div>

        <!-- Patient Instructions -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Patient Instructions</label>
            <textarea wire:model="instructions" rows="2"
                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm"
                      placeholder="Instructions for patient use..."></textarea>
        </div>
    </form>
</div>
</div>