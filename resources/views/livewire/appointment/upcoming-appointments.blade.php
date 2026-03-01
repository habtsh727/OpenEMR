<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Upcoming Appointments</h1>
                <p class="text-emerald-100 mt-1">Future scheduled appointments and visits</p>
            </div>
            <a href="{{ route('appointments.create') }}"
                class="px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                New Appointment
            </a>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-emerald-100 text-sm">Total Upcoming</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total_upcoming'] }}</p>
            </div>
            <div
                class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 {{ now()->format('H') < 17 ? 'animate-pulse' : '' }}">
                <p class="text-emerald-100 text-sm">Today</p>
                <p class="text-2xl font-bold text-white">{{ $stats['today'] }}</p>
                <p class="text-xs text-emerald-200 mt-1">Remaining today</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-emerald-100 text-sm">Tomorrow</p>
                <p class="text-2xl font-bold text-white">{{ $stats['tomorrow'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-emerald-100 text-sm">Next 48 Hours</p>
                <p class="text-2xl font-bold text-white">{{ $stats['next_48_hours'] }}</p>
            </div>
        </div>

        <!-- Next 48 Hours Highlight (if enabled) -->
        @if($highlightNext48 && $stats['next_48_hours'] > 0)
        <div class="mt-4 p-3 bg-yellow-500/20 border border-yellow-400/30 rounded-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.516-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-yellow-100 text-sm">
                    <span class="font-bold">{{ $stats['next_48_hours'] }}</span> appointments in the next 48 hours
                </p>
            </div>
        </div>
        @endif
    </div>

    <!-- Filters and Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model.live.debounce="search" placeholder="Search patient..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Doctor Filter -->
            <div>
                <select wire:model.live="doctorId"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Visit Type Filter -->
            <div>
                <select wire:model.live="visitType"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Types</option>
                    @foreach($visitTypes as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Group By -->
            <div>
                <select wire:model.live="groupBy"
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-emerald-500 dark:bg-gray-700 dark:text-white">
                    <option value="date">Group by Date</option>
                    <option value="doctor">Group by Doctor</option>
                    <option value="type">Group by Visit Type</option>
                </select>
            </div>
        </div>

        <!-- Active Filters -->
        <div class="flex flex-wrap items-center gap-2 mt-3">
            @if($search)
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                Search: {{ $search }}
                <button wire:click="$set('search', '')" class="ml-2 hover:text-emerald-900">×</button>
            </span>
            @endif
            @if($doctorId)
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                Doctor: {{ $doctors->firstWhere('id', $doctorId)?->name }}
                <button wire:click="$set('doctorId', '')" class="ml-2 hover:text-emerald-900">×</button>
            </span>
            @endif
            @if($visitType)
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400">
                Type: {{ $visitTypes[$visitType] ?? $visitType }}
                <button wire:click="$set('visitType', '')" class="ml-2 hover:text-emerald-900">×</button>
            </span>
            @endif
            @if($search || $doctorId || $visitType)
            <button wire:click="resetFilters"
                class="text-sm text-emerald-600 hover:text-emerald-800 dark:text-emerald-400">
                Clear All
            </button>
            @endif
        </div>
    </div>

    <!-- Appointments Display -->
    @if($groupBy === 'date')
    <!-- Grouped by Date View -->
    <div class="space-y-6">
        @forelse($groupedAppointments as $group)
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden 
                    {{ $group['in_next_48'] && $highlightNext48 ? 'ring-2 ring-yellow-400 dark:ring-yellow-600' : '' }}">
            <!-- Date Header -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($group['is_today'])
                        <span
                            class="px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full text-sm font-medium">
                            Today
                        </span>
                        @elseif($group['is_tomorrow'])
                        <span
                            class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-sm font-medium">
                            Tomorrow
                        </span>
                        @elseif($group['in_next_48'])
                        <span
                            class="px-3 py-1 bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 rounded-full text-sm font-medium">
                            Next 48h
                        </span>
                        @endif
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $group['formatted_date'] }}
                        </h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $group['count'] }} appointment{{ $group['count'] != 1 ? 's' : '' }}
                    </span>
                </div>
            </div>

            <!-- Appointments List -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($group['appointments'] as $appointment)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <!-- Time -->
                            <div class="w-20 text-center">
                                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $appointment->appointment_time->format('h:i A') }}
                                </div>
                            </div>

                            <!-- Patient Info -->
                            <div class="flex items-center space-x-3">
                                <div
                                    class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-medium">
                                    {{ substr($appointment->patient->first_name, 0, 1) }}{{
                                    substr($appointment->patient->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Card: {{ $appointment->patient->card_number }}
                                    </div>
                                </div>
                            </div>

                            <!-- Doctor -->
                            <div class="ml-6">
                                <div class="text-sm text-gray-500 dark:text-gray-400">Doctor</div>
                                <div class="font-medium text-gray-900 dark:text-white">
                                    Dr. {{ $appointment->doctor->name }}
                                </div>
                            </div>

                            <!-- Visit Type -->
                            <div>
                                <span
                                    class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                </span>
                            </div>

                            <!-- Status -->
                            <div>
                                <span class="px-3 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            @if($appointment->isCheckInAvailable())
                            <button wire:click="checkIn({{ $appointment->id }})"
                                wire:confirm="Are you sure you want to check in this patient?"
                                class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Check In
                            </button>
                            @endif
                            <button wire:click="reschedule({{ $appointment->id }})"
                                class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                title="Reschedule">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                            <button wire:click="cancel({{ $appointment->id }})"
                                class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                title="Cancel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    @if($appointment->additional_notes)
                    <div class="mt-2 ml-24 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Notes:</span> {{ $appointment->additional_notes }}
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @empty
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No upcoming appointments</h3>
            <p class="text-gray-500 dark:text-gray-400">No future appointments scheduled.</p>
            <a href="{{ route('appointments.create') }}"
                class="inline-block mt-4 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                Schedule New Appointment
            </a>
        </div>
        @endforelse
    </div>

    @elseif($groupBy === 'doctor')
    <!-- Grouped by Doctor View -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($groupedAppointments as $group)
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-teal-600">
                <h3 class="text-lg font-semibold text-white">Dr. {{ $group['doctor_name'] }}</h3>
                <p class="text-emerald-100 text-sm mt-1">
                    {{ $group['count'] }} appointment{{ $group['count'] != 1 ? 's' : '' }}
                </p>
                <p class="text-emerald-100 text-xs">
                    {{ Carbon::parse($group['first_appointment'])->format('M d') }} -
                    {{ Carbon::parse($group['last_appointment'])->format('M d, Y') }}
                </p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($group['appointments']->take(5) as $appointment)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $appointment->appointment_date->format('M d, Y') }} at {{
                                $appointment->appointment_time->format('h:i A') }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
                @if($group['count'] > 5)
                <div class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">
                    +{{ $group['count'] - 5 }} more appointments
                </div>
                @endif
            </div>
        </div>
        @empty
        <div
            class="col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400">No upcoming appointments found</p>
        </div>
        @endforelse
    </div>

    @elseif($groupBy === 'type')
    <!-- Grouped by Visit Type View -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($groupedAppointments as $group)
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-500 to-indigo-600">
                <h3 class="text-lg font-semibold text-white">{{ $group['type_label'] }}</h3>
                <p class="text-purple-100 text-sm mt-1">{{ $group['count'] }} appointment{{ $group['count'] != 1 ? 's' :
                    '' }}</p>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($group['appointments']->take(5) as $appointment)
                <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium text-gray-900 dark:text-white">
                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Dr. {{ $appointment->doctor->name }} •
                                {{ $appointment->appointment_date->format('M d') }} at {{
                                $appointment->appointment_time->format('h:i A') }}
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
                @if($group['count'] > 5)
                <div class="p-3 text-center text-sm text-gray-500 dark:text-gray-400">
                    +{{ $group['count'] - 5 }} more
                </div>
                @endif
            </div>
        </div>
        @empty
        <div
            class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <p class="text-gray-500 dark:text-gray-400">No upcoming appointments found</p>
        </div>
        @endforelse
    </div>
    @endif


    <!-- Pagination (for non-grouped view) -->
    @if($groupBy === 'date' && method_exists($appointments, 'links'))
    <div class="mt-6">
        {{ $appointments->links() }}
    </div>
    @endif
    {{-- <livewire:appointment.check-in /> --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (event) => {
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ' + 
                (event[0].type === 'success' ? 'bg-green-500' : 'bg-red-500') + ' text-white';
            notification.textContent = event[0].message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        });
    });
    </script>
</div>