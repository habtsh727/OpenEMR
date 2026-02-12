<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <svg class="w-8 h-8 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    Rehabilitation Queue
                </h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    View and manage rehabilitation orders from doctors
                </p>
            </div>

            <!-- Status Summary -->
            <div class="flex flex-wrap gap-2">
                @php
                $statusConfig = [
                'pending_questionnaire' => ['label' => 'Pending', 'bg' => 'bg-yellow-50 text-yellow-700
                dark:bg-yellow-900/30 dark:text-yellow-400', 'border' => 'border-yellow-200'],
                'questionnaire_in_progress' => ['label' => 'In Progress', 'bg' => 'bg-blue-50 text-blue-700
                dark:bg-blue-900/30 dark:text-blue-400', 'border' => 'border-blue-200'],
                'submitted_to_doctor' => ['label' => 'Submitted', 'bg' => 'bg-purple-50 text-purple-700
                dark:bg-purple-900/30 dark:text-purple-400', 'border' => 'border-purple-200'],
                'doctor_review' => ['label' => 'Reviewed', 'bg' => 'bg-green-50 text-green-700 dark:bg-green-900/30
                dark:text-green-400', 'border' => 'border-green-200'],
                ];
                @endphp

                @foreach($statusConfig as $status => $config)
                @php $count = $statusCounts[$status] ?? 0; @endphp
                <div
                    class="px-4 py-2 rounded-lg border {{ $config['bg'] }} {{ $config['border'] }} flex items-center gap-2">
                    <span class="text-sm font-medium">{{ $config['label'] }}</span>
                    <span class="text-lg font-bold">{{ $count }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by patient name or MRN..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white">
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="w-full sm:w-48">
                <select wire:model.live="statusFilter"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Statuses</option>
                    <option value="pending_questionnaire">Pending Questionnaire</option>
                    <option value="questionnaire_in_progress">In Progress</option>
                    <option value="submitted_to_doctor">Submitted to Doctor</option>
                    <option value="doctor_review">Doctor Review</option>
                </select>
            </div>

            <!-- Per Page -->
            <div class="w-full sm:w-32">
                <select wire:model.live="perPage"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <!-- Reset Filters -->
            @if($search || $statusFilter)
            <button wire:click="resetFilters"
                class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2 text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Clear
            </button>
            @endif
        </div>
    </div>

    <!-- Queue Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th wire:click="sortBy('id')"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300">
                            <div class="flex items-center gap-1">
                                ID
                                @if($sortField === 'id')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M{{ $sortDirection === 'asc' ? '5 15l7-7 7 7' : '19 9l-7 7-7-7' }}"></path>
                                </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Patient Information
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Doctor
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th wire:click="sortBy('created_at')"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300">
                            <div class="flex items-center gap-1">
                                Ordered Date
                                @if($sortField === 'created_at')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M{{ $sortDirection === 'asc' ? '5 15l7-7 7 7' : '19 9l-7 7-7-7' }}"></path>
                                </svg>
                                @endif
                            </div>
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Assigned To
                        </th>
                        <th
                            class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($rehabEncounters as $rehab)
                    <tr wire:key="{{ $rehab->id }}"
                        class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            #{{ $rehab->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($rehab->encounter->patient->first_name ?? '?', 0, 1) .
                                    substr($rehab->encounter->patient->last_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $rehab->encounter->patient->first_name ?? '' }} {{
                                        $rehab->encounter->patient->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        MRN: {{ $rehab->encounter->patient->medical_record_number ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-white">
                                Dr. {{ $rehab->encounter->doctor->name ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rehab->status_color }}">
                                {{ $rehab->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $rehab->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-500 dark:text-gray-500">
                                {{ $rehab->created_at->diffForHumans() }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            @if($rehab->filledBy)
                            <div class="flex items-center">
                                <div
                                    class="w-6 h-6 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center text-xs font-medium text-gray-700 dark:text-gray-300">
                                    {{ strtoupper(substr($rehab->filledBy->name, 0, 1)) }}
                                </div>
                                <span class="ml-2">{{ $rehab->filledBy->name }}</span>
                            </div>
                            @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($rehab->status === 'pending_questionnaire')
                            <button wire:click="startQuestionnaire({{ $rehab->id }})" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                                <svg wire:loading wire:target="startQuestionnaire({{ $rehab->id }})"
                                    class="w-4 h-4 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                <span wire:loading.remove wire:target="startQuestionnaire({{ $rehab->id }})">Start
                                    Questionnaire</span>
                                <span wire:loading wire:target="startQuestionnaire({{ $rehab->id }})">Starting...</span>
                            </button>
                            @elseif($rehab->status === 'questionnaire_in_progress')
                            @if($rehab->filledBy && $rehab->filledBy->id === auth()->id())
                            <button wire:click="continueQuestionnaire({{ $rehab->id }})"
                                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Continue
                            </button>
                            @else
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-blue-700 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                In Progress
                            </span>
                            @endif
                            @elseif($rehab->status === 'submitted_to_doctor')
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-purple-700 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Submitted
                            </span>
                            @elseif($rehab->status === 'doctor_review')
                            <span
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-5m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Reviewed
                            </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No rehabilitation
                                    cases</h3>
                                <p class="text-gray-500 dark:text-gray-400">
                                    @if($search || $statusFilter)
                                    No results match your filters.
                                    <button wire:click="resetFilters"
                                        class="text-teal-600 hover:text-teal-700 font-medium">Clear filters</button>
                                    @else
                                    No rehabilitation orders have been placed yet.
                                    @endif
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
            {{ $rehabEncounters->links() }}
        </div>
    </div>

    <!-- Auto-refresh for real-time updates -->
    <div wire:poll.30s></div>
</div>