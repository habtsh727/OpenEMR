<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        {{-- Patient Header --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $patient->full_name ?? 'Patient' }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Encounter #{{ $encounter->id }} • {{ $encounter->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Order Stats --}}
                <div class="flex items-center space-x-4">
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $totalCount }}</div>
                        <div class="text-sm font-medium text-blue-500 dark:text-blue-300">Total Orders</div>
                    </div>
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $pendingCount }}</div>
                        <div class="text-sm font-medium text-yellow-500 dark:text-yellow-300">Pending</div>
                    </div>
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $completedCount }}</div>
                        <div class="text-sm font-medium text-green-500 dark:text-green-300">Completed</div>
                    </div>
                </div>
            </div>

            {{-- Workflow Indicator --}}
            {{-- <div class="mb-6">
                <div class="flex items-center justify-center">
                    <div class="flex items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Doctor Order</span>
                        </div>
                        <div class="w-24 h-1 bg-gray-300 dark:bg-gray-600 mx-2"></div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Cashier Payment</span>
                        </div>
                        <div class="w-24 h-1 bg-gray-300 dark:bg-gray-600 mx-2"></div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                    </path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Radiology</span>
                        </div>
                        <div class="w-24 h-1 bg-gray-300 dark:bg-gray-600 mx-2"></div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-500 flex items-center justify-center mb-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Results</span>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="flex flex-wrap justify-between">
                <button type="button"  wire:click="backToLabOrder"
                    class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 hover:shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                        </path>
                    </svg>
                    <span>Back To Lab Order</span>
                </button>

                <button type="button" wire:click="skipImaging"
                    class="px-6 py-3 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-all duration-200 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span>Skip Imaging Order</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Column: Order Form --}}
            <div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
                    <div
                        class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">New Imaging Order</h2>
                            </div>
                            <button @click="$wire.showInstructions = !$wire.showInstructions"
                                class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($showInstructions)
                    <div class="px-6 py-4 bg-blue-50 dark:bg-blue-900/10 border-b border-blue-100 dark:border-blue-800">
                        <div class="flex items-start space-x-3">
                            <svg class="w-5 h-5 text-blue-500 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 01118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <p class="font-medium mb-1">Ordering Instructions:</p>
                                <p class="mb-1">1. Select imaging type and body part</p>
                                <p class="mb-1">2. Set priority level (Routine/Urgent)</p>
                                <p class="mb-1">3. Add clinical notes for radiologist</p>
                                <p class="mb-1">4. Patient will proceed to cashier for payment</p>
                                <p>5. Radiology will process after payment</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="p-6">
                        <form wire:submit.prevent="createOrder" class="space-y-6">
                            {{-- Imaging Type Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Imaging Type <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="selectedImagingType"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none"
                                    required>
                                    <option value="">Select Imaging Procedure</option>
                                    @foreach($imagingTypes as $type)
                                    <option value="{{ $type->id }}">
                                        {{ $type->name }} • ${{ number_format($type->fee, 2) }}
                                        @if($type->description)
                                        • {{ Str::limit($type->description, 40) }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('selectedImagingType')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Body Part Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Body Part <span class="text-red-500">*</span>
                                </label>
                                <select wire:model="selectedBodyPart"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none"
                                    required>
                                    <option value="">Select Anatomical Location</option>
                                    @foreach($bodyParts as $part)
                                    <option value="{{ $part->id }}">
                                        {{ $part->name }}
                                        @if($part->code)
                                        ({{ $part->code }})
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                @error('selectedBodyPart')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Priority Selection --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Priority Level
                                    </label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="relative flex cursor-pointer">
                                            <input type="radio" wire:model="priority" value="routine"
                                                class="sr-only peer" checked>
                                            <div
                                                class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:ring-2 peer-checked:ring-blue-200 dark:peer-checked:ring-blue-800 transition-all duration-200">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center mr-3">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">Routine
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">Next 24-48
                                                            hours</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                        <label class="relative flex cursor-pointer">
                                            <input type="radio" wire:model="priority" value="urgent"
                                                class="sr-only peer">
                                            <div
                                                class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:ring-2 peer-checked:ring-red-200 dark:peer-checked:ring-red-800 transition-all duration-200">
                                                <div class="flex items-center">
                                                    <div
                                                        class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center mr-3">
                                                        <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">Urgent
                                                        </div>
                                                        <div class="text-xs text-gray-500 dark:text-gray-400">Immediate
                                                            attention</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                {{-- Fee Display --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estimated Fee
                                    </label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">$</span>
                                        </div>
                                        <div
                                            class="pl-8 w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700/50 dark:text-white">
                                            <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($fee, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                        Patient will pay at cashier
                                    </p>
                                </div>
                            </div>

                            {{-- Clinical Notes --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Clinical Notes <small class="text-gray-500 dark:text-gray-400">(For
                                        Radiologist)</small>
                                </label>
                                <textarea wire:model="clinicalNotes"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    rows="4"
                                    placeholder="Enter clinical indications, specific areas of interest, or any special instructions for the radiologist..."></textarea>
                                <div class="mt-1 flex justify-between">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ strlen($clinicalNotes) }}/1000 characters
                                    </p>
                                    @error('clinicalNotes')
                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit"
                                    class="w-full py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2"
                                    :disabled="!$wire.selectedImagingType || !$wire.selectedBodyPart">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <span>Create Imaging Order</span>
                                </button>
                                <p class="mt-2 text-xs text-center text-gray-500 dark:text-gray-400">
                                    Order will be sent to cashier for payment processing
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Quick Stats Card --}}
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Pending Orders</span>
                            <span class="font-semibold text-yellow-600 dark:text-yellow-400">{{ $pendingCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Completed Today</span>
                            <span class="font-semibold text-green-600 dark:text-green-400">{{ $completedCount }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Total Orders</span>
                            <span class="font-semibold text-blue-600 dark:text-blue-400">{{ $totalCount }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Orders List --}}
            <div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Orders</h2>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $orders->count() }} order(s)
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        @if($orders->count())
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Order
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Details
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg 
                                                        {{ $order->priority === 'urgent' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $order->imagingType->name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    #{{ $order->id }} • {{ $order->created_at->format('h:i A') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $order->bodyPart->name }}
                                            </div>
                                            <div class="text-gray-600 dark:text-gray-400">
                                                ${{ number_format($order->amount, 2) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                        $statusConfig = [
                                        'pending' => ['color' => 'yellow', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9
                                        0 0118 0z'],
                                        'paid' => ['color' => 'blue', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3
                                        .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11
                                        0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                        'in_progress' => ['color' => 'purple', 'icon' => 'M4 4v5h.582m15.356 2A8.001
                                        8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357
                                        2H15'],
                                        'completed' => ['color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9
                                        9 0 0118 0z'],
                                        'cancelled' => ['color' => 'red', 'icon' => 'M6 18L18 6M6 6l12 12'],
                                        ];
                                        $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 dark:bg-{{ $config['color'] }}-900/30 dark:text-{{ $config['color'] }}-300">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="{{ $config['icon'] }}"></path>
                                            </svg>
                                            {{ str_replace('_', ' ', ucfirst($order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            @if($order->status == 'pending')
                                            <button wire:click="cancelOrder({{ $order->id }})"
                                                onclick="return confirm('Are you sure you want to cancel this order?')"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                                Cancel
                                            </button>
                                            @endif
                                            @if($order->imagingResult)
                                            <a href="{{ route('doctor.imaging.results', ['encounter' => $order->encounter_id]) }}"
                                                wire:navigate
                                                class="inline-flex items-center px-3 py-1.5 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                View
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <div class="px-6 py-12 text-center">
                            <div
                                class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No imaging orders</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-4">
                                Create your first imaging order for this patient
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Status Legend --}}
                <div
                    class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Order Status Legend</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Pending</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-blue-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Paid</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-purple-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">In Progress</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-green-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completed</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-3 h-3 rounded-full bg-red-500"></span>
                            <span class="text-sm text-gray-600 dark:text-gray-400">Cancelled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add custom animations --}}
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        .animate-slide-down {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>