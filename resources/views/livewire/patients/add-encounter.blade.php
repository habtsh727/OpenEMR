<flux:modal name="add-encounter" class="max-w-2xl mx-auto dark:bg-gray-900">
    <!-- Hero Header -->
    <div
        class="relative bg-gradient-to-r from-sky-500 to-sky-600 dark:from-sky-600 dark:to-sky-700 px-6 pt-8 pb-32 rounded-t-lg shadow-lg">
        <div class="absolute top-4 right-4 opacity-10">
            {{-- <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
            </svg> --}}
        </div>

        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <!-- Patient Avatar -->
                <div
                    class="w-20 h-20 bg-white/20 dark:bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center shadow-lg border-2 border-white/30 dark:border-white/20">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                </div>
                <div class="text-white">
                    <div class="flex items-center gap-2 mb-1">
                        <h1 class="text-xl font-bold tracking-tight">{{ $first_name }} {{ $middle_name }}</h1>
                        {{-- <span class="text-xs bg-white/20 px-2 py-1 rounded-full">ID: {{ $card_number }}</span> --}}
                    </div>

                    <div class="flex gap-4 mt-2 text-sm text-sky-100 dark:text-sky-200">
                        <span>New Encounter</span>
                    </div>
                </div>
            </div>

            <!-- Status & Close -->
            <div class="flex flex-col items-end gap-3">
                {{-- <div
                    class="px-4 py-2 bg-white/20 dark:bg-white/10 backdrop-blur-sm text-white rounded-full text-sm font-semibold border border-white/30 dark:border-white/20">
                    Active Patient
                </div> --}}

            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="p-6 space-y-6 -mt-24 dark:bg-gray-900">
        <!-- Form Header -->
        <div class="flex items-center justify-between mb-2">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">New Patient Encounter</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Create a new visit record for the patient</p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ now()->format('F j, Y') }}
            </div>
        </div>

        <!-- Payment Information -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 shadow-sm dark:shadow-gray-900/30">
            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-sky-600 dark:text-sky-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Payment Information
            </h4>

            <!-- Card Payment Toggle -->
            <div class="mb-4">
                <label class="flex items-start gap-3 cursor-pointer">
                    <div class="relative flex-shrink-0 mt-1">
                        <input type="checkbox" id="requires_card_payment" wire:model="requires_card_payment"
                            class="sr-only peer">
                        <div
                            class="w-12 h-6 bg-gray-200 dark:bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-sky-300 dark:peer-focus:ring-sky-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-600 dark:peer-checked:bg-sky-500">
                        </div>
                    </div>
                    <div>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100 block">Requires Card
                            Payment</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Patient needs to make payment before
                            consultation</p>
                    </div>
                </label>
            </div>

            <!-- Payment Amount (Conditional) -->
            @if($requires_card_payment)
            <div
                class="mt-4 p-4 bg-sky-50 dark:bg-sky-900/20 border border-sky-100 dark:border-sky-800 rounded-lg animate-fade-in">
                <label for="payment_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Payment Amount
                </label>
                <div class="relative max-w-xs">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">$</span>
                    </div>
                    <input type="number" id="payment_amount" wire:model="payment_amount"
                        class="pl-7 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 dark:focus:ring-sky-400 focus:border-sky-500 dark:focus:border-sky-400 sm:text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                        placeholder="200.00" min="0" step="0.01" value="200">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400">USD</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Default consultation fee: $200.00</p>
            </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-500 dark:text-gray-400">
                <span class="font-medium">Patient ID:</span>
                <span class="font-mono text-gray-700 dark:text-gray-300 ml-1">{{ $card_number }}</span>
            </div>

            <div class="flex gap-3">

                <flux:button variant="primary" wire:click="save" class="px-6" wire:loading.attr="disabled"
                    wire:loading.class="opacity-50 cursor-not-allowed">
                    <span wire:loading.remove>Create Encounter</span>
                    <span wire:loading>
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Creating...
                    </span>
                </flux:button>
            </div>
        </div>
    </div>
</flux:modal>