<flux:modal name="add-encounter" class="max-w-[65rem] mx-auto">
    <!-- Hero Header -->
    <div class="relative bg-gradient-to-r from-sky-500 to-sky-600 px-6 pt-8 pb-32 rounded-t-lg shadow-lg">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <!-- Patient Avatar -->
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <div class="text-white">
                    <h1 class="text-2xl font-bold">{{ $first_name }} {{ $middle_name }}</h1>
                    <p class="text-sky-100">Mother.N: {{ $mother_name }}</p>
                    <p class="text-sky-100">{{ $card_number }}</p>
                </div>
            </div>
            <!-- Status Badge -->
            <div class="px-4 py-2 bg-white text-sky-600 rounded-full text-sm font-semibold shadow-lg">
                Active
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="p-6 space-y-4 -mt-24">
        <h3 class="text-lg font-semibold mb-4">Encounter Details</h3>
requires_card_payment
        <!-- Card Payment Required -->
        <div class="flex items-center gap-2 mb-4">
            <input type="checkbox" id="requires_card_payment" wire:model="requires_card_payment"
                class="form-checkbox h-5 w-5 text-sky-600">
            <label for="requires_card_payment" class="text-sm font-medium text-gray-700">
                Requires Card Payment
            </label>
        </div>

        <!-- Save / Cancel Buttons -->
        <div class="flex justify-end gap-2 pt-4">
            <flux:button variant="ghost" wire:click="$dispatch('close-modal', { name: 'add-encounter' })">
                Cancel
            </flux:button>
            <flux:button wire:click="save" variant="primary" wire:click="save">
                Create Encounter
            </flux:button>
        </div>
    </div>
</flux:modal>