<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header with Stats -->
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 dark:from-emerald-800 dark:to-teal-800 rounded-2xl shadow-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Calendar View</h1>
                        <p class="text-emerald-100 dark:text-emerald-200 mt-1">Manage appointments by date and doctor</p>
                    </div>
                </div>
                
                <!-- Today's Quick Stats -->
                <div class="flex items-center gap-3">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-emerald-100 text-xs">Today</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_today'] }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-emerald-100 text-xs">Week</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_week'] }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg px-4 py-2">
                        <p class="text-emerald-100 text-xs">Month</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_month'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Doctor Stats -->
            @if($doctorStats->isNotEmpty())
                <div class="mt-4 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2">
                    @foreach($doctorStats as $stat)
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 text-center">
                            <p class="text-emerald-100 text-xs truncate">{{ $stat['doctor_name'] }}</p>
                            <p class="text-white font-semibold">{{ $stat['scheduled'] }}/{{ $stat['total'] }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Calendar Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <!-- Navigation -->
                <div class="flex items-center gap-2">
                    <button wire:click="previous" 
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white min-w-[200px] text-center">
                        @if($view === 'month')
                            {{ $currentDate->format('F Y') }}
                        @elseif($view === 'week')
                            {{ $currentDate->startOfWeek()->format('M d') }} - {{ $currentDate->endOfWeek()->format('M d, Y') }}
                        @else
                            {{ $currentDate->format('l, F j, Y') }}
                        @endif
                    </h2>
                    <button wire:click="next" 
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button wire:click="goToToday" 
                            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-lg transition-colors ml-2">
                        Today
                    </button>
                </div>

                <!-- View Toggle -->
                <div class="flex items-center gap-2">
                    <select wire:model.live="selectedDoctor" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                        <button wire:click="setView('month')" 
                                class="px-4 py-2 text-sm font-medium transition-colors
                                    {{ $view === 'month' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                            Month
                        </button>
                        <button wire:click="setView('week')" 
                                class="px-4 py-2 text-sm font-medium transition-colors
                                    {{ $view === 'week' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                            Week
                        </button>
                        <button wire:click="setView('day')" 
                                class="px-4 py-2 text-sm font-medium transition-colors
                                    {{ $view === 'day' ? 'bg-emerald-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600' }}">
                            Day
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Month View -->
        @if($view === 'month')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                        <div class="px-4 py-3 text-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- Calendar Grid -->
                <div class="grid grid-cols-7 divide-x divide-gray-200 dark:divide-gray-700">
                    @foreach($calendarDays as $day)
                        <div wire:click="selectDate('{{ $day['formatted'] }}')"
                             class="min-h-[120px] p-2 cursor-pointer transition-all hover:bg-gray-50 dark:hover:bg-gray-700/50
                                    {{ !$day['is_current_month'] ? 'bg-gray-50/50 dark:bg-gray-800/50 text-gray-400 dark:text-gray-600' : '' }}
                                    {{ $day['is_today'] ? 'ring-2 ring-emerald-500 ring-inset' : '' }}
                                    {{ $day['is_weekend'] ? 'bg-gray-50/30 dark:bg-gray-800/30' : '' }}">
                            
                            <!-- Date Number -->
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-sm font-medium {{ $day['is_today'] ? 'text-emerald-600 dark:text-emerald-400 font-bold' : '' }}">
                                    {{ $day['day'] }}
                                </span>
                                @if($day['total'] > 0)
                                    <span class="text-xs px-1.5 py-0.5 bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 rounded-full">
                                        {{ $day['total'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Appointment Indicators -->
                            @if($day['has_appointments'])
                                <div class="space-y-1">
                                    <!-- Status Distribution Bar -->
                                    <div class="flex h-1.5 rounded-full overflow-hidden">
                                        @if($day['status_counts']['scheduled'] > 0)
                                            <div class="bg-blue-500 h-full" 
                                                 style="width: {{ ($day['status_counts']['scheduled'] / $day['total']) * 100 }}%"></div>
                                        @endif
                                        @if($day['status_counts']['completed'] > 0)
                                            <div class="bg-green-500 h-full" 
                                                 style="width: {{ ($day['status_counts']['completed'] / $day['total']) * 100 }}%"></div>
                                        @endif
                                        @if($day['status_counts']['missed'] > 0)
                                            <div class="bg-red-500 h-full" 
                                                 style="width: {{ ($day['status_counts']['missed'] / $day['total']) * 100 }}%"></div>
                                        @endif
                                    </div>

                                    <!-- Quick Stats -->
                                    <div class="flex flex-wrap gap-1">
                                        @if($day['status_counts']['scheduled'] > 0)
                                            <span class="flex items-center gap-0.5">
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $day['status_counts']['scheduled'] }}</span>
                                            </span>
                                        @endif
                                        @if($day['status_counts']['completed'] > 0)
                                            <span class="flex items-center gap-0.5">
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">{{ $day['status_counts']['completed'] }}</span>
                                            </span>
                                        @endif
                                    </div>

                                    @if($day['peak_hours'])
                                        <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                            Peak: {{ $day['peak_hours'] }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Show first appointment time (if any) -->
                            @if($day['appointments']->isNotEmpty())
                                @php $firstAppt = $day['appointments']->sortBy('appointment_time')->first(); @endphp
                                <div class="mt-2 text-xs text-emerald-600 dark:text-emerald-400 truncate">
                                    {{ $firstAppt->appointment_time->format('h:i A') }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Week View -->
        @if($view === 'week')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Weekday Headers -->
                <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                    @foreach($weekDays as $day)
                        <div class="px-4 py-3 text-center {{ $day['is_today'] ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}">
                            <div class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $day['short_name'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $day['day'] }}</div>
                        </div>
                    @endforeach
                </div>

                <!-- Week Grid -->
                <div class="grid grid-cols-7 divide-x divide-gray-200 dark:divide-gray-700">
                    @foreach($weekDays as $day)
                        <div class="min-h-[600px] p-2 {{ $day['is_today'] ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}">
                            <!-- Date Header -->
                            <div wire:click="selectDate('{{ $day['formatted'] }}')" 
                                 class="text-center mb-3 cursor-pointer hover:text-emerald-600 dark:hover:text-emerald-400">
                                <span class="text-sm font-medium {{ $day['is_today'] ? 'text-emerald-600 dark:text-emerald-400' : '' }}">
                                    {{ $day['name'] }}
                                </span>
                                <div class="text-xs text-gray-500 dark:text-gray-500">{{ $day['date']->format('M d') }}</div>
                            </div>

                            <!-- Hour Slots -->
                            <div class="space-y-2">
                                @foreach($hourSlots as $slot)
                                    @php
                                        $slotAppointments = $day['appointments_by_hour']->get($slot['hour'] . ':00', collect());
                                    @endphp
                                    
                                    <div class="relative">
                                        <!-- Time Label -->
                                        <div class="text-xs text-gray-400 dark:text-gray-600 mb-1">{{ $slot['display'] }}</div>
                                        
                                        <!-- Appointments in this hour -->
                                        <div class="space-y-1 max-h-[100px] overflow-y-auto">
                                            @foreach($slotAppointments as $appointment)
                                                <div wire:click="selectAppointment({{ $appointment->id }})"
                                                     class="p-2 rounded-lg cursor-pointer transition-all hover:shadow-md
                                                            {{ $appointment->status === 'scheduled' ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : '' }}
                                                            {{ $appointment->status === 'completed' ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500' : '' }}
                                                            {{ $appointment->status === 'missed' ? 'bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500' : '' }}">
                                                    <div class="flex justify-between items-start">
                                                        <span class="text-xs font-medium text-gray-900 dark:text-white truncate max-w-[80px]">
                                                            {{ $appointment->patient->first_name }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $appointment->appointment_time->format('h:i') }}
                                                        </span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                        Dr. {{ substr($appointment->doctor->name ?? '', 0, 10) }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Day View -->
        @if($view === 'day')
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <!-- Day Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-white">{{ $currentDate->format('l, F j, Y') }}</h2>
                            <p class="text-emerald-100 mt-1">Schedule for the day</p>
                        </div>
                        <div class="flex gap-2">
                            <button wire:click="selectDate('{{ $currentDate->copy()->subDay()->format('Y-m-d') }}')"
                                    class="px-3 py-1 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                                ← Previous
                            </button>
                            <button wire:click="selectDate('{{ $currentDate->copy()->addDay()->format('Y-m-d') }}')"
                                    class="px-3 py-1 bg-white/20 hover:bg-white/30 text-white rounded-lg transition-colors">
                                Next →
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @php
                        $dayAppointments = Appointment::with(['patient', 'doctor'])
                            ->whereDate('appointment_date', $currentDate->toDateString())
                            ->when($selectedDoctor, fn($q) => $q->where('doctor_id', $selectedDoctor))
                            ->orderBy('appointment_time')
                            ->get();
                    @endphp

                    @forelse($dayAppointments as $appointment)
                        <div wire:click="selectAppointment({{ $appointment->id }})"
                             class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                            <div class="flex items-center gap-4">
                                <!-- Time -->
                                <div class="w-24 text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->appointment_time->format('h:i A') }}
                                    </div>
                                </div>

                                <!-- Patient Avatar -->
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($appointment->patient->first_name ?? 'N', 0, 1) }}{{ substr($appointment->patient->last_name ?? 'A', 0, 1) }}
                                </div>

                                <!-- Details -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3">
                                        <span class="font-semibold text-gray-900 dark:text-white">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </span>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $this->getStatusBadgeColor($appointment->status) }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-3 mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        <span>Dr. {{ $appointment->doctor->name }}</span>
                                        <span>•</span>
                                        <span class="capitalize">{{ str_replace('-', ' ', $appointment->visit_type) }}</span>
                                    </div>
                                </div>

                                <!-- View Button -->
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointments scheduled</h3>
                            <p class="text-gray-500 dark:text-gray-400">There are no appointments for this day.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Day Details Modal -->
        @if($showDayDetails && $selectedDate)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                {{ $selectedDate->format('l, F j, Y') }}
                            </h2>
                            <button wire:click="$set('showDayDetails', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Day Summary -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 text-center">
                                <p class="text-xs text-blue-600 dark:text-blue-400">Scheduled</p>
                                <p class="text-xl font-bold text-blue-700 dark:text-blue-300">
                                    {{ $dayAppointments->where('status', 'scheduled')->count() }}
                                </p>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 text-center">
                                <p class="text-xs text-green-600 dark:text-green-400">Completed</p>
                                <p class="text-xl font-bold text-green-700 dark:text-green-300">
                                    {{ $dayAppointments->where('status', 'completed')->count() }}
                                </p>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 text-center">
                                <p class="text-xs text-red-600 dark:text-red-400">Missed</p>
                                <p class="text-xl font-bold text-red-700 dark:text-red-300">
                                    {{ $dayAppointments->where('status', 'missed')->count() }}
                                </p>
                            </div>
                        </div>

                        <!-- Appointments List -->
                        <div class="space-y-3">
                            @foreach($dayAppointments as $appointment)
                                <div wire:click="selectAppointment({{ $appointment->id }})"
                                     class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold">
                                                {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ $appointment->appointment_time->format('h:i A') }} • Dr. {{ $appointment->doctor->name }}
                                                </div>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 text-xs rounded-full {{ $this->getStatusBadgeColor($appointment->status) }}">
                                            {{ ucfirst($appointment->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Appointment Details Modal -->
        @if($showAppointmentDetails && $selectedAppointment)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Appointment Details</h2>
                            <button wire:click="$set('showAppointmentDetails', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Patient Info -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 mb-4">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($selectedAppointment->patient->first_name ?? 'N', 0, 1) }}{{ substr($selectedAppointment->patient->last_name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Card: {{ $selectedAppointment->patient->card_number }}
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $selectedAppointment->patient->phone_number1 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Gender/Age</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ ucfirst($selectedAppointment->patient->gender) }},
                                        {{ \Carbon\Carbon::parse($selectedAppointment->patient->date_of_birth)->age }} yrs
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Appointment Info -->
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Date & Time</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAppointment->appointment_date->format('M d, Y') }} at {{ $selectedAppointment->appointment_time->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Doctor</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">Dr. {{ $selectedAppointment->doctor->name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Visit Type</span>
                                <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ ucfirst(str_replace('-', ' ', $selectedAppointment->visit_type)) }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                <span class="px-3 py-1 text-xs rounded-full {{ $this->getStatusBadgeColor($selectedAppointment->status) }}">
                                    {{ ucfirst($selectedAppointment->status) }}
                                </span>
                            </div>
                            @if($selectedAppointment->additional_notes)
                                <div class="py-2">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block mb-1">Notes</span>
                                    <p class="text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        {{ $selectedAppointment->additional_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showAppointmentDetails', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Close
                            </button>
                            @if($selectedAppointment->isCheckInAvailable())
                                <button wire:click="checkIn({{ $selectedAppointment->id }})"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg">
                                    Check In
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>