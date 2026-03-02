{{-- resources/views/livewire/appointment/calendar-view.blade.php --}}
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Appointment Calendar</h1>
            <div class="flex items-center gap-2">
                <select wire:model.live="selectedDoctor" class="px-4 py-2 rounded-lg border-0 bg-white/20 text-white">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <button wire:click="previous" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <h2 class="text-xl font-semibold min-w-[200px] text-center">
                    @if($view === 'month')
                        {{ $currentDate->format('F Y') }}
                    @else
                        {{ $currentDate->startOfWeek()->format('M d') }} - {{ $currentDate->endOfWeek()->format('M d, Y') }}
                    @endif
                </h2>
                <button wire:click="next" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button wire:click="goToToday" class="px-4 py-2 bg-emerald-600 text-white rounded-lg ml-2">
                    Today
                </button>
            </div>

            <div class="flex rounded-lg border overflow-hidden">
                <button wire:click="setView('month')" 
                        class="px-4 py-2 {{ $view === 'month' ? 'bg-emerald-600 text-white' : 'bg-gray-100' }}">
                    Month
                </button>
                <button wire:click="setView('week')" 
                        class="px-4 py-2 {{ $view === 'week' ? 'bg-emerald-600 text-white' : 'bg-gray-100' }}">
                    Week
                </button>
            </div>
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-4 mt-4 pt-4 border-t">
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                <span class="text-xs">Scheduled</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                <span class="text-xs">Completed</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                <span class="text-xs">Missed</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-yellow-500 rounded-full"></span>
                <span class="text-xs">Rescheduled</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                <span class="text-xs">Cancelled</span>
            </div>
        </div>
    </div>

    <!-- Month View -->
    @if($view === 'month')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 bg-gray-50 dark:bg-gray-700 border-b">
                @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                    <div class="px-4 py-3 text-center text-sm font-semibold">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 divide-x divide-y">
                @foreach($calendarDays as $day)
                    <div wire:click="selectDate('{{ $day['formatted'] }}')"
                         class="min-h-[120px] p-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition
                                {{ !$day['is_current_month'] ? 'bg-gray-50/50 text-gray-400' : '' }}
                                {{ $day['is_today'] ? 'ring-2 ring-emerald-500 ring-inset' : '' }}
                                {{ $day['is_weekend'] ? 'bg-gray-50/30' : '' }}">
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-medium {{ $day['is_today'] ? 'text-emerald-600 font-bold' : '' }}">
                                {{ $day['day'] }}
                            </span>
                            @if($day['total'] > 0)
                                <span class="text-xs px-1.5 py-0.5 bg-emerald-100 text-emerald-800 rounded-full">
                                    {{ $day['total'] }}
                                </span>
                            @endif
                        </div>

                        @if($day['has_appointments'])
                            <div class="mt-2 space-y-1">
                                <div class="flex h-1.5 rounded-full overflow-hidden">
                                    @foreach($day['status_counts'] as $status => $count)
                                        @if($count > 0)
                                            <div class="{{ $this->getStatusColor($status) }} h-full" 
                                                 style="width: {{ ($count / $day['total']) * 100 }}%"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Week View -->
    @if($view === 'week')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="grid grid-cols-7 border-b">
                @foreach($weekDays as $day)
                    <div class="px-4 py-3 text-center {{ $day['is_today'] ? 'bg-emerald-50' : '' }}">
                        <div class="font-semibold">{{ $day['short_name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $day['day'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="grid grid-cols-7 divide-x min-h-[600px]">
                @foreach($weekDays as $day)
                    <div wire:click="selectDate('{{ $day['formatted'] }}')" 
                         class="p-2 {{ $day['is_today'] ? 'bg-emerald-50/50' : '' }} cursor-pointer">
                        
                        @foreach($day['appointments'] as $appointment)
                            <div wire:click.stop="selectAppointment({{ $appointment->id }})"
                                 class="mb-2 p-2 rounded-lg text-xs cursor-pointer hover:shadow-md transition
                                        @if($appointment->status === 'scheduled') bg-blue-100 border-l-4 border-blue-500
                                        @elseif($appointment->status === 'completed') bg-green-100 border-l-4 border-green-500
                                        @elseif($appointment->status === 'missed') bg-red-100 border-l-4 border-red-500
                                        @elseif($appointment->status === 'rescheduled') bg-yellow-100 border-l-4 border-yellow-500
                                        @else bg-gray-100 border-l-4 border-gray-500 @endif">
                                <div class="font-medium">{{ $appointment->appointment_time->format('h:i A') }}</div>
                                <div class="truncate">{{ $appointment->patient->first_name }}</div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Day Details Modal -->
    @if($showDayModal && $selectedDate)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">{{ $selectedDate->format('l, F j, Y') }}</h2>
                    <button wire:click="$set('showDayModal', false)" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-3">
                    @foreach($dayAppointments as $appointment)
                        <div wire:click="selectAppointment({{ $appointment->id }})"
                             class="border rounded-lg p-4 hover:bg-gray-50 cursor-pointer">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-semibold">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                                    <div class="text-sm text-gray-600">{{ $appointment->appointment_time->format('h:i A') }} • Dr. {{ $appointment->doctor->name }}</div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Appointment Details Modal -->
    @if($showAppointmentModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold">Appointment Details</h2>
                    <button wire:click="$set('showAppointmentModal', false)" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Patient:</span>
                        <span class="font-semibold">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Doctor:</span>
                        <span class="font-semibold">Dr. {{ $selectedAppointment->doctor->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold">{{ $selectedAppointment->appointment_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Time:</span>
                        <span class="font-semibold">{{ $selectedAppointment->appointment_time->format('h:i A') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Type:</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100">
                            {{ ucfirst(str_replace('-', ' ', $selectedAppointment->visit_type)) }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full {{ $selectedAppointment->status_color }}">
                            {{ ucfirst($selectedAppointment->status) }}
                        </span>
                    </div>
                    @if($selectedAppointment->doctor_notes)
                        <div class="pt-3 border-t">
                            <span class="text-gray-600">Notes:</span>
                            <p class="mt-1 text-sm">{{ $selectedAppointment->doctor_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>