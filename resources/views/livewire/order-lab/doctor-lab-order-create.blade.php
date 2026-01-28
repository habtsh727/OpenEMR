<div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">
        <!-- Header with Patient Info -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Create Lab Order</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Order for current encounter</p>
            </div>

            <!-- Patient Information Card -->
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 p-4 rounded-lg border border-blue-100 dark:border-gray-700 min-w-[300px]">
                <div class="flex items-center space-x-3">
                    <div class="flex-shrink-0">
                        <div
                            class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex ">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{
                            $encounter->patient->first_name }} {{ $encounter->patient->last_name }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('success'))
        <div
            class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- Selected Tests Section -->
        @if(count($selectedTestIds) > 0)
        <div
            class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg transition-colors duration-200">
            <div class="flex justify-between items-center mb-3">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-blue-300">
                    Selected Tests <span
                        class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 rounded-full px-2 py-1 text-sm ml-2">{{
                        count($selectedTestIds) }}</span>
                </h3>
                <button wire:click="clearAllTests"
                    class="text-sm text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear All
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
                @foreach($selectedTests as $test)
                <div
                    class="flex justify-between items-center p-3 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg hover:shadow-sm transition-shadow duration-150">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $test->name }}</div>
                        <div class="flex items-center space-x-2 mt-1">
                            <span
                                class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300">
                                {{ $test->code }}
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $test->sample_type }}</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 ml-2">
                        <span class="font-semibold text-green-600 dark:text-green-400">
                            {{ number_format($test->price, 2) }} Birr
                        </span>
                        <button wire:click="removeTest({{ array_search($test->id, $selectedTestIds) }})"
                            class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 p-1 rounded-full hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div
                class="flex flex-col md:flex-row justify-between items-center pt-4 border-t border-blue-200 dark:border-blue-800">
                <div class="mb-3 md:mb-0">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Order Details:</div>
                    <div class="flex items-center space-x-4 mt-1">
                        <div class="flex items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400 mr-2">Priority:</span>
                            @php
                            $priorityColors = [
                            'routine' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                            'urgent' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                            'stat' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                            ];
                            @endphp
                            <span
                                class="text-xs font-medium px-2 py-1 rounded-full {{ $priorityColors[$priority] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                {{ ucfirst($priority) }}
                            </span>
                        </div>
                        @if($notes)
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Has notes</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Amount</div>
                    <div class="text-2xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($this->totalAmount, 2) }} Birr
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Order Details Form -->
        <form wire:submit.prevent="submitOrder" class="mb-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Priority Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Priority Level
                        </span>
                    </label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" wire:click="$set('priority', 'routine')"
                            class="p-3 text-center rounded-lg border transition-all duration-150 {{ $priority === 'routine' 
                                    ? 'bg-blue-50 border-blue-300 dark:bg-blue-900/30 dark:border-blue-700 text-blue-700 dark:text-blue-300' 
                                    : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            <div class="font-medium">Routine</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">24-48 hours</div>
                        </button>
                        <button type="button" wire:click="$set('priority', 'urgent')"
                            class="p-3 text-center rounded-lg border transition-all duration-150 {{ $priority === 'urgent' 
                                    ? 'bg-yellow-50 border-yellow-300 dark:bg-yellow-900/30 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300' 
                                    : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            <div class="font-medium">Urgent</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">4-6 hours</div>
                        </button>
                        <button type="button" wire:click="$set('priority', 'stat')"
                            class="p-3 text-center rounded-lg border transition-all duration-150 {{ $priority === 'stat' 
                                    ? 'bg-red-50 border-red-300 dark:bg-red-900/30 dark:border-red-700 text-red-700 dark:text-red-300' 
                                    : 'bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600' }}">
                            <div class="font-medium">STAT</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Immediate</div>
                        </button>
                    </div>
                    @error('priority')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes Section -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Clinical Notes (Optional)
                        </span>
                    </label>
                    <textarea wire:model="notes" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors"
                        placeholder="Add clinical notes, special instructions, or relevant patient history..."></textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ strlen($notes) }}/500 characters
                        </span>
                        @if($notes)
                        <button type="button" wire:click="$set('notes', '')"
                            class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                            Clear notes
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Test Search and Selection -->
            <div class="mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Available Lab Tests</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Select tests from the catalog</p>
                    </div>
                    <div class="relative w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, code, or department..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                    </div>
                </div>

                @if($labTests->count() > 0)
                <div
                    class="overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Test Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Code
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Sample
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Department
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($labTests as $test)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{
                                                    $test->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{
                                                    $test->description ?? 'No description' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                                            {{ $test->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $test->sample_type
                                                }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $test->department
                                            }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            {{ number_format($test->price, 2) }} Birr
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if(in_array($test->id, $selectedTestIds))
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Selected
                                        </span>
                                        @else
                                        <button type="button" wire:click="addTest({{ $test->id }})"
                                            class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Add Test
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4">
                    {{ $labTests->links() }}
                </div>
                @else
                <div
                    class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No lab tests found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        @if($search)
                        No tests matching "{{ $search }}". Try a different search term.
                        @else
                        No lab tests are currently available in the system.
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div
                class="flex flex-col sm:flex-row justify-between items-center pt-6 border-t border-gray-200 dark:border-gray-700 gap-4">
                <div class="flex space-x-3">
                    <button type="button" wire:click="skipOrder"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-gray-400 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back
                    </button>


                    @if(count($selectedTestIds) > 0)
                    <button type="button" wire:click="clearAllTests"
                        class="px-4 py-2 border border-red-300 dark:border-red-700 rounded-lg text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Clear Selection
                    </button>
                    @endif
                </div>

                <div class="flex space-x-3">
                    <button type="button" wire:click="imagingOrder"
                        class="px-6 py-3 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-all duration-200 flex items-center space-x-2">
                        Skip To Imaging Order<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>

                    </button>
                    <button type="submit" @if(count($selectedTestIds)===0) disabled @endif
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-600 dark:hover:to-indigo-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 flex items-center shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Create Lab Order
                        <span class="ml-2 bg-white/20 px-2 py-1 rounded text-xs">
                            {{ count($selectedTestIds) }} test(s)
                        </span>
                    </button>
                </div>
            </div>
        </form>

        <!-- Order Summary Card (Fixed at bottom on mobile) -->
        @if(count($selectedTestIds) > 0)
        <div
            class="lg:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ count($selectedTestIds) }} tests selected
                    </div>
                    <div class="text-lg font-bold text-gray-800 dark:text-white">{{ number_format($this->totalAmount, 2)
                        }} Birr</div>
                </div>
                <button type="submit" form="orderForm"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg font-medium">
                    Create Order
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Add form ID for mobile submit -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[wire\\:submit]');
            if (form) {
                form.id = 'orderForm';
            }
        });
    </script>
</div>