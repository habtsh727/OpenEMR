<div>
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laboratory Results by Encounter</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View all lab tests grouped by patient encounter
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                @if($search || $filterStatus || $filterPatient)
                <button wire:click="clearFilters"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Encounters</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalEncounters }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 p-4 rounded-lg border border-green-100 dark:border-green-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Lab Tests</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalLabOrders }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Encounters or Patients
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by patient name, MRN, encounter ID, or test name..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                    </div>
                </div>

                <div class="w-full lg:w-64">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Filter by Patient
                    </label>
                    <select wire:model.live="filterPatient"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                            @if($patient->medical_record_number)
                            ({{ $patient->medical_record_number }})
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Encounters List -->
        <div
            class="overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Encounter & Patient
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Lab Tests
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date & Time
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status Summary
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Medication Order
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($encounters as $encounter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <!-- Patient & Encounter Information -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                Encounter #{{ $encounter->id }}
                                            </span>
                                            @if($encounter->patient->medical_record_number)
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                                MRN: {{ $encounter->patient->medical_record_number }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Lab Tests -->
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    @foreach($encounter->labOrders->take(3) as $labOrder)
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-2">
                                            <svg class="h-4 w-4 text-indigo-600 dark:text-indigo-300" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $labOrder->labTest->name }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $labOrder->labTest->code }} • {{ $labOrder->labResults->count() }}
                                                parameters
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    @if($encounter->labOrders->count() > 3)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        +{{ $encounter->labOrders->count() - 3 }} more tests
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Date & Time -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-medium">Encounter:</span> {{ $encounter->created_at->format('M
                                        d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $encounter->created_at->format('h:i A') }}
                                    </div>
                                    @php
                                    $latestResult = $encounter->labOrders->flatMap(function ($labOrder) {
                                    return $labOrder->labResults;
                                    })->sortByDesc('created_at')->first();
                                    @endphp
                                    @if($latestResult)
                                    <div class="text-sm text-gray-900 dark:text-white mt-2">
                                        <span class="font-medium">Latest Result:</span> {{
                                        $latestResult->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $latestResult->created_at->format('h:i A') }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Status Summary -->
                            <td class="px-6 py-4">
                                @php
                                $reportedCount = $encounter->labOrders->where('status', 'reported')->count();
                                $verifiedCount = $encounter->labOrders->where('status', 'verified')->count();
                                $totalTests = $encounter->labOrders->count();
                                @endphp

                                <div class="space-y-2">
                                    <div>
                                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                                            <span>Reported</span>
                                            <span>{{ $reportedCount }}/{{ $totalTests }}</span>
                                        </div>
                                        <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-green-500"
                                                style="width: {{ $totalTests > 0 ? ($reportedCount/$totalTests)*100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                                            <span>Verified</span>
                                            <span>{{ $verifiedCount }}/{{ $totalTests }}</span>
                                        </div>
                                        <div class="h-1 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-blue-500"
                                                style="width: {{ $totalTests > 0 ? ($verifiedCount/$totalTests)*100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($encounter->labOrders->where('status', 'verified')->count() > 0)
                                <a href="{{ route('doctor.order-medication', ['encounter' => $encounter->id]) }}"
                                    class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow text-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    Order Medication
                                </a>
                                @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">Verify lab results first</span>
                                @endif
                            </td>
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="viewEncounterResults({{ $encounter->id }})" x-data
                                    @click="$dispatch('open-modal', 'view-encounter-results')"
                                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-600 dark:hover:to-indigo-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center shadow-sm hover:shadow">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    View All Results
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No lab results
                                        found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if($search || $filterPatient)
                                        No encounters match your search criteria.
                                        @else
                                        No lab results have been reported or verified yet.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($encounters->hasPages())
        <div
            class="mt-6 px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
            {{ $encounters->links() }}
        </div>
        @endif
    </div>

    <!-- View Encounter Results Modal -->
    <!-- View Encounter Results Modal -->
   @if($selectedEncounter)
    <x-modal name="view-encounter-results" maxWidth="5xl">
        @livewire('order-lab.view-encounter-results', ['encounterId' => $selectedEncounter])
    </x-modal>
    @endif
    <!-- Order Medication Modal -->
    {{-- @if($selectedEncounter)
    <x-modal name="order-medication" maxWidth="4xl">
        @livewire('order-lab.order-medication', [
        'encounterId' => $selectedEncounter,
        'labOrderId' => null
        ])
    </x-modal>
    @endif --}}

</div>