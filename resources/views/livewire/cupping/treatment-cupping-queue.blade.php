<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div
                            class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Treatment Queue</h1>
                            <p class="text-green-100 mt-1">Manage and perform cupping therapy sessions</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-xs text-green-200">Pending Queue</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_pending'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-xs text-green-200">In Progress</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_in_progress'] }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center">
                            <p class="text-xs text-green-200">Today's Sessions</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_today'] }}</p>
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
                    <input type="text" wire:model.live="search" placeholder="Search by name or card..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="statusFilter"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="all">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_queue">In Queue</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Filter</label>
                    <select wire:model.live="dateFilter"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="tomorrow">Tomorrow</option>
                        <option value="this_week">This Week</option>
                    </select>
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

        {{-- Sessions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($sessions as $session)
            @php
            $patient = $session->cuppingTherapy->encounter->patient;
            $statusColors = [
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'in_queue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'in_progress' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            ];
            $statusLabels = [
            'pending' => 'Pending',
            'in_queue' => 'In Queue',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            ];
            @endphp

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                {{-- Card Header --}}
                <div
                    class="bg-gradient-to-r from-green-50 to-teal-50 dark:from-green-900/20 dark:to-teal-900/20 px-5 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Card: {{ $patient->card_number ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            @if($session->queue_position)
                            <span
                                class="inline-block px-2 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-semibold">
                                Position #{{ $session->queue_position }}
                            </span>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">Session #{{ $session->session_number }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Body --}}
                <div class="p-5 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Package:</span>
                        <span class="text-sm font-medium">
                            {{ $session->therapyPackage->package_name_snapshot ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Session Date:</span>
                        <span class="text-sm">{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y')
                            }}</span>
                    </div>

                    @if($session->treatment_started_at)
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Started At:</span>
                        <span class="text-sm">{{ \Carbon\Carbon::parse($session->treatment_started_at)->format('h:i A')
                            }}</span>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $statusColors[$session->treatment_status] }}">
                            {{ $statusLabels[$session->treatment_status] }}
                        </span>
                    </div>

                    @php
                    $materialsConsumed = json_decode($session->materials_consumed ?? '[]', true);
                    $materialsRequired = json_decode($session->therapyPackage->materials_snapshot ?? '[]', true);
                    $materialsCollected = count($materialsConsumed);
                    $totalMaterials = count($materialsRequired);
                    @endphp
                    @if($totalMaterials > 0 && $session->treatment_status !== 'completed')
                    <div class="border-t pt-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Materials Collected:</span>
                            <span
                                class="font-medium {{ $materialsCollected == $totalMaterials ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $materialsCollected }}/{{ $totalMaterials }}
                            </span>
                        </div>
                        @if($materialsCollected < $totalMaterials) <div
                            class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-yellow-500 h-1.5 rounded-full"
                                style="width: {{ ($materialsCollected / $totalMaterials) * 100 }}%"></div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            {{-- Card Footer --}}
            <div class="bg-gray-50 dark:bg-gray-700/30 px-5 py-3 border-t border-gray-200 dark:border-gray-700">
                @if($session->treatment_status === 'completed')
                <div class="text-center text-green-600 text-sm flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Completed
                </div>
                @else
                <button wire:click="startSession({{ $session->id }})"
                    class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $session->treatment_status === 'in_progress' ? 'Continue Treatment' : 'Start Treatment' }}
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 font-medium">No sessions in queue</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Paid sessions will appear here</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $sessions->links() }}
    </div>
</div>
</div>
