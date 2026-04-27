<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            {{-- Header --}}
            <div class="mb-8">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Treatment Results</h1>
                                <p class="text-purple-100 mt-1">View completed cupping therapy sessions and reports</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                                <p class="text-xs text-purple-200">Completed Sessions</p>
                                <p class="text-2xl font-bold text-white">{{ $stats['total_completed_sessions'] }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                                <p class="text-xs text-purple-200">Patients Treated</p>
                                <p class="text-2xl font-bold text-white">{{ $stats['total_patients'] }}</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                                <p class="text-xs text-purple-200">With Reports</p>
                                <p class="text-2xl font-bold text-white">{{ $stats['total_with_reports'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Alert --}}
            @if($showAlert)
            <div
                class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
            {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
               ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-yellow-100 border border-yellow-400 text-yellow-700') }}">
                <span>{{ $alertMessage }}</span>
                <button wire:click="closeAlert" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            @endif

            {{-- Filters --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search
                            Patient</label>
                        <input type="text" wire:model.live="search" placeholder="Name or card number..."
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                        <input type="date" wire:model.live="dateFrom"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                        <input type="date" wire:model.live="dateTo"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Per Page</label>
                        <select wire:model.live="perPage"
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                            <option value="10">10 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Patients Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($therapies as $therapy)
                @php
                $patient = $therapy->encounter->patient;
                $completedSessions = $therapy->sessions->where('treatment_status', 'completed')->count();
                $totalSessions = $therapy->sessions->count();
                $hasReports = $therapy->sessions->where('report', '!=', null)->count();
                @endphp

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div
                        class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}</h3>
                                <p class="text-xs text-gray-500 mt-1">Card: {{ $patient->card_number ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Total Sessions:</span>
                            <span class="text-sm font-medium">{{ $totalSessions }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Completed:</span>
                            <span class="text-sm font-medium text-green-600">{{ $completedSessions }}/{{ $totalSessions
                                }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Reports Available:</span>
                            <span class="text-sm font-medium">{{ $hasReports }}/{{ $totalSessions }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Order Date:</span>
                            <span class="text-sm">{{ $therapy->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/30 px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="viewResult({{ $therapy->id }})"
                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg>
                            View Details
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No completed treatments found</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Complete a treatment session to see results
                        here</p>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $therapies->links() }}
            </div>

            {{-- Result Modal with Sessions and Treatments --}}
            @if($showResultModal && $therapyDetails)
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                x-data="{ open: true, activeSession: 0 }" x-show="open"
                x-on:keydown.escape.window="open = false; $wire.closeModal()">

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100">

                    {{-- Modal Header --}}
                    <div
                        class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Treatment Results</h3>
                            <p class="text-sm text-gray-500">{{ $therapyDetails['patient']->name ?? 'N/A' }} (Card: {{
                                $therapyDetails['patient']->card_number ?? 'N/A' }})</p>
                        </div>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-6">
                        {{-- Session Tabs --}}
                        <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                            @foreach($therapyDetails['sessions'] as $index => $sessionData)
                            <button @click="activeSession = {{ $index }}"
                                :class="{ 'bg-purple-600 text-white': activeSession == {{ $index }}, 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300': activeSession != {{ $index }} }"
                                class="px-4 py-2 rounded-lg text-sm font-medium transition">
                                Session #{{ $sessionData['session']->session_number }}
                                @if($sessionData['session']->treatment_status === 'completed')
                                <span class="ml-1 text-xs">✓</span>
                                @endif
                            </button>
                            @endforeach
                        </div>

                        {{-- Active Session Content --}}
                        @foreach($therapyDetails['sessions'] as $index => $sessionData)
                        <div x-show="activeSession == {{ $index }}" x-cloak class="space-y-6">
                            {{-- Session Info --}}
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-4">
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500">Session Date</p>
                                        <p class="font-semibold">{{
                                            \Carbon\Carbon::parse($sessionData['session']->session_date)->format('F d,
                                            Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Package</p>
                                        <p class="font-semibold">{{ $sessionData['package_name'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Status</p>
                                        <p class="font-semibold capitalize">{{ $sessionData['session']->treatment_status
                                            }}</p>
                                    </div>
                                    @if($sessionData['session']->treatment_completed_at)
                                    <div>
                                        <p class="text-xs text-gray-500">Completed At</p>
                                        <p class="font-semibold">{{
                                            \Carbon\Carbon::parse($sessionData['session']->treatment_completed_at)->format('M
                                            d, Y h:i A') }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Treatments Performed --}}
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold">Treatments Performed
                                    ({{ count($sessionData['treatments']) }})</div>
                                <div class="p-4">
                                    @if(!empty($sessionData['treatments']))
                                    <div class="space-y-2">
                                        @foreach($sessionData['treatments'] as $treatment)
                                        <div
                                            class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                            <div>
                                                <span class="font-medium">{{ $treatment['type_name'] ?? 'N/A' }}</span>
                                                <span class="text-sm text-gray-500 ml-2">on {{
                                                    $treatment['location_name'] ?? 'N/A' }}</span>
                                            </div>
                                            <span class="text-sm font-semibold text-purple-600">ETB {{
                                                number_format($treatment['price'] ?? 0, 2) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-gray-500 text-center py-4">No treatments recorded</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Materials Used --}}
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold">Materials Used</div>
                                <div class="p-4">
                                    @if(!empty($sessionData['materials_consumed']))
                                    <div class="space-y-2">
                                        @foreach($sessionData['materials_consumed'] as $material)
                                        <div
                                            class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                                            <span class="font-medium">{{ $material['item_name'] ?? 'N/A' }}</span>
                                            <span class="text-sm">Quantity: {{ $material['collected_quantity'] ??
                                                $material['quantity'] ?? 0 }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @else
                                    <p class="text-gray-500 text-center py-4">No materials recorded</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Treatment Report --}}
                            <div class="border rounded-lg overflow-hidden">
                                <div class="bg-gray-100 dark:bg-gray-700 px-4 py-2 font-semibold">Treatment Report</div>
                                <div class="p-4 space-y-3">
                                    @if($sessionData['report'])
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Treatment
                                            Notes</p>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                                            {{ $sessionData['report']->report_text ?? 'No notes' }}</p>
                                    </div>
                                    @if($sessionData['report']->observations)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Observations</p>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                                            {{ $sessionData['report']->observations }}</p>
                                    </div>
                                    @endif
                                    @if($sessionData['report']->recommendations)
                                    <div>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Recommendations</p>
                                        <p
                                            class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/30 p-3 rounded-lg">
                                            {{ $sessionData['report']->recommendations }}</p>
                                    </div>
                                    @endif
                                    <div class="text-xs text-gray-400 mt-2">
                                        Reported by: {{ $sessionData['report']->createdBy->name ?? 'N/A' }} on {{
                                        \Carbon\Carbon::parse($sessionData['report']->created_at)->format('M d, Y h:i
                                        A') }}
                                    </div>
                                    @else
                                    <p class="text-gray-500 text-center py-4">No report available for this session</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Modal Footer --}}
                    <div
                        class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end">
                        <button wire:click="closeModal"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 transition">
                            Close
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

</div>
