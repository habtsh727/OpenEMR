<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">

    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert') }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
            <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' :
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' :
                    'bg-gradient-to-r from-yellow-500 to-orange-600') }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($alertType === 'error')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $alertMessage }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header with Stats -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-600 dark:from-emerald-800 dark:via-teal-800 dark:to-cyan-800 rounded-2xl shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>

                <div class="relative p-6 md:p-8">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </div>
                            <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 border-2 border-white dark:border-gray-800 animate-pulse"></div>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Rehabilitation Treatment Queue</h1>
                            <p class="text-emerald-100 dark:text-emerald-200 mt-1">Manage and document patient rehabilitation progress</p>
                        </div>
                    </div>

                    <!-- Stats Cards -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Total Patients</p>
                            <p class="text-white text-2xl font-bold">{{ $stats['total'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Awaiting Treatment</p>
                            <p class="text-yellow-300 text-2xl font-bold">{{ $stats['sent_to_rehab'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">In Progress</p>
                            <p class="text-blue-300 text-2xl font-bold">{{ $stats['treatment_in_progress'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Completed</p>
                            <p class="text-green-300 text-2xl font-bold">{{ $stats['completed'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Status Filter -->
                <div class="flex-1">
                    <select wire:model.live="statusFilter"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="all">All Statuses</option>
                        <option value="sent_to_rehab">Awaiting Treatment</option>
                        <option value="treatment_in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>

                <!-- Search -->
                <div class="flex-1 relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text"
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search by patient name or MRN..."
                           class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>
            </div>
        </div>

        <!-- Queue Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">MRN</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Bed Assignment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($encounters as $encounter)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-medium shadow-sm">
                                        {{ substr($encounter->encounter->patient->first_name ?? 'N/A', 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $encounter->encounter->patient->first_name ?? '' }} {{ $encounter->encounter->patient->last_name ?? '' }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $encounter->encounter->patient->date_of_birth ? \Carbon\Carbon::parse($encounter->encounter->patient->date_of_birth)->age : '?' }} yrs
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-mono text-gray-900 dark:text-white">
                                    {{ $encounter->encounter->patient->card_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">Dr. {{ $encounter->encounter->doctor->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($encounter->bed_info)
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $encounter->bed_info['ward'] }} → {{ $encounter->bed_info['room'] }} → {{ $encounter->bed_info['bed'] }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $encounter->bed_info['class'] }}
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 dark:text-gray-500">No bed assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $encounter->bed_duration ? $encounter->bed_duration . ' days' : 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'sent_to_rehab' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'treatment_in_progress' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    ];
                                    $statusLabels = [
                                        'sent_to_rehab' => 'Awaiting Treatment',
                                        'treatment_in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$encounter->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusLabels[$encounter->status] ?? ucfirst(str_replace('_', ' ', $encounter->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($encounter->status === 'sent_to_rehab')
                                    <button wire:click="startTreatment({{ $encounter->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Start Treatment
                                    </button>
                                @else
                                    <button wire:click="viewTreatment({{ $encounter->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm transition-all hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-4">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No patients in treatment queue</h3>
                                    <p class="text-gray-500 dark:text-gray-400 max-w-md text-center">
                                        Patients will appear here after payment is processed and they are sent to rehab.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($encounters->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $encounters->links() }}
                </div>
            @endif
        </div>

        <!-- Quick Guide Card -->
        <div class="mt-6 bg-gradient-to-br from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800 p-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-emerald-200 dark:bg-emerald-800 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-700 dark:text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-emerald-800 dark:text-emerald-300 mb-1">Treatment Queue Guide</h4>
                    <p class="text-sm text-emerald-700 dark:text-emerald-400">
                        <span class="font-medium">Awaiting Treatment:</span> Ready to start rehabilitation<br>
                        <span class="font-medium">In Progress:</span> Active treatment, can add progress notes<br>
                        <span class="font-medium">Completed:</span> Treatment finished, view-only mode
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert-shown', () => {
                setTimeout(() => {
                    @this.dispatch('closeAlert');
                }, 5000);
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
