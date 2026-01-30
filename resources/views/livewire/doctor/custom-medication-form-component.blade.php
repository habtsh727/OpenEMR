<div>
<div>
    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
        {{ $medicationId ? 'Edit Custom Medication' : 'Create Custom Medication' }}
    </h3>

    @if(session()->has('success'))
    <div class="mb-4 rounded-md bg-green-50 p-4">
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

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Medication Name *</label>
            <input type="text" wire:model.defer="name" id="name"
                   class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Ingredients -->
        <div>
            <label for="ingredients" class="block text-sm font-medium text-gray-700">Ingredients *</label>
            <textarea wire:model.defer="ingredients" id="ingredients" rows="3"
                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      placeholder="List all ingredients with quantities"></textarea>
            @error('ingredients') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Preparation Instructions -->
        <div>
            <label for="preparationInstructions" class="block text-sm font-medium text-gray-700">Preparation Instructions</label>
            <textarea wire:model.defer="preparationInstructions" id="preparationInstructions" rows="2"
                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      placeholder="How to prepare this medication..."></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Dosage -->
            <div>
                <label for="dosage" class="block text-sm font-medium text-gray-700">Dosage *</label>
                <input type="text" wire:model.defer="dosage" id="dosage"
                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                       placeholder="e.g., 10mg, 5ml">
                @error('dosage') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Frequency -->
            <div>
                <label for="frequencyId" class="block text-sm font-medium text-gray-700">Frequency *</label>
                <select wire:model.defer="frequencyId" id="frequencyId"
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">Select Frequency</option>
                    @foreach($frequencies as $frequency)
                    <option value="{{ $frequency->id }}">{{ $frequency->name }} ({{ $frequency->short_code }})</option>
                    @endforeach
                </select>
                @error('frequencyId') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Duration -->
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duration *</label>
                <input type="text" wire:model.defer="duration" id="duration"
                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                       placeholder="e.g., 7 days, 2 weeks">
                @error('duration') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Base Price -->
            <div>
                <label for="basePrice" class="block text-sm font-medium text-gray-700">Base Price (₦) *</label>
                <input type="number" wire:model.defer="basePrice" id="basePrice" step="0.01" min="0"
                       class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                @error('basePrice') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Instructions -->
        <div>
            <label for="instructions" class="block text-sm font-medium text-gray-700">Patient Instructions</label>
            <textarea wire:model.defer="instructions" id="instructions" rows="2"
                      class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                      placeholder="Instructions for patient use..."></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 pt-6 border-t">
            @if($medicationId)
            <button type="button" wire:click="resetForm"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create New
            </button>
            @endif
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                {{ $medicationId ? 'Update' : 'Save' }} Medication
            </button>
        </div>
    </form>
</div>
</div>
