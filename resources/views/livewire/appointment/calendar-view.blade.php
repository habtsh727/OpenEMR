{{-- resources/views/livewire/appointment/calendar-view.blade.php --}}
<div class="space-y-6" x-data="{ view: @entangle('view') }">
    
    <!-- Enterprise Header with Stats -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 dark:from-slate-950 dark:via-purple-950 dark:to-slate-950 shadow-2xl">
        <!-- Animated background pattern -->
        <div class="absolute inset-0 bg-grid-white/[0.02] bg-[size:50px_50px]"></div>
        <div class="absolute top-0 right-0 -mt-20 -mr-20 h-64 w-64 rounded-full bg-white/5 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 h-64 w-64 rounded-full bg-purple-500/5 blur-3xl"></div>
        
        <div class="relative px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-white/10 backdrop-blur-xl flex items-center justify-center border border-white/20 shadow-2xl">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Calendar</h1>
                        <p class="text-sm text-white/70 mt-0.5">Schedule & appointment overview</p>
                    </div>
                </div>
                
                <!-- Doctor Filter with Enterprise styling -->
                <div class="relative">
                    <select wire:model.live="selectedDoctor" 
                            class="pl-4 pr-10 py-2.5 bg-white/10 backdrop-blur-xl border border-white/20 rounded-xl text-white appearance-none cursor-pointer hover:bg-white/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-white/50">
                        <option value="" class="bg-slate-900 text-white">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" class="bg-slate-900 text-white">Dr. {{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Strip -->
            <div class="flex items-center gap-6 mt-6 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                    <span class="text-white/80">Today: <span class="font-semibold text-white">{{ App\Models\Appointment::forToday()->count() }}</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-blue-400"></div>
                    <span class="text-white/80">This Week: <span class="font-semibold text-white">{{ App\Models\Appointment::whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</span></span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-purple-400"></div>
                    <span class="text-white/80">This Month: <span class="font-semibold text-white">{{ App\Models\Appointment::whereMonth('appointment_date', now()->month)->count() }}</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Calendar Controls - Professional Toolbar -->
    <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Navigation -->
            <div class="flex items-center gap-2">
                <button wire:click="previous" 
                        class="p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all duration-200 group">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                
                <div class="px-6 py-1.5 min-w-[240px] text-center">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-white tracking-tight">
                        @if($view === 'month')
                            {{ $currentDate->format('F Y') }}
                        @else
                            <span class="text-emerald-600 dark:text-emerald-400">{{ $currentDate->startOfWeek()->format('M d') }}</span>
                            <span class="mx-2 text-slate-400">—</span>
                            <span class="text-emerald-600 dark:text-emerald-400">{{ $currentDate->endOfWeek()->format('M d, Y') }}</span>
                        @endif
                    </h2>
                </div>

                <button wire:click="next" 
                        class="p-2.5 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all duration-200 group">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                <button wire:click="goToToday" 
                        class="ml-2 px-5 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all duration-200">
                    Today
                </button>
            </div>

            <!-- View Toggle - Professional Segmented Control -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                <button wire:click="setView('month')" 
                        class="px-5 py-2 text-sm font-medium rounded-lg transition-all duration-200
                            {{ $view === 'month' 
                                ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    Month
                </button>
                <button wire:click="setView('week')" 
                        class="px-5 py-2 text-sm font-medium rounded-lg transition-all duration-200
                            {{ $view === 'week' 
                                ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-sm' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white' }}">
                    Week
                </button>
            </div>
        </div>

        <!-- Legend - Professional Status Indicators -->
        <div class="flex flex-wrap items-center gap-6 mt-4 pt-4 border-t border-slate-200 dark:border-slate-700">
            <span class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status:</span>
            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 bg-blue-500 rounded-full ring-4 ring-blue-500/20"></span>
                <span class="text-sm text-slate-600 dark:text-slate-300">Scheduled</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 bg-green-500 rounded-full ring-4 ring-green-500/20"></span>
                <span class="text-sm text-slate-600 dark:text-slate-300">Completed</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 bg-red-500 rounded-full ring-4 ring-red-500/20"></span>
                <span class="text-sm text-slate-600 dark:text-slate-300">Missed</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 bg-yellow-500 rounded-full ring-4 ring-yellow-500/20"></span>
                <span class="text-sm text-slate-600 dark:text-slate-300">Rescheduled</span>
            </div>
            <div class="flex items-center gap-1">
                <span class="w-2.5 h-2.5 bg-slate-500 rounded-full ring-4 ring-slate-500/20"></span>
                <span class="text-sm text-slate-600 dark:text-slate-300">Cancelled</span>
            </div>
        </div>
    </div>

    <!-- Month View - Enterprise Grade -->
    @if($view === 'month')
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
            <!-- Weekday Headers -->
            <div class="grid grid-cols-7 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                    <div class="px-4 py-4 text-center">
                        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            {{ $day }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Calendar Grid -->
            <div class="grid grid-cols-7 divide-x divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($calendarDays as $day)
                    <div wire:click="selectDate('{{ $day['formatted'] }}')"
                         class="min-h-[140px] p-3 cursor-pointer transition-all duration-200 hover:bg-slate-50 dark:hover:bg-slate-800/50 group
                                {{ !$day['is_current_month'] ? 'bg-slate-50/50 dark:bg-slate-800/20' : '' }}
                                {{ $day['is_today'] ? 'ring-2 ring-emerald-500 ring-inset' : '' }}
                                {{ $day['is_weekend'] ? 'bg-slate-50/30 dark:bg-slate-800/10' : '' }}">
                        
                        <!-- Date Header with Professional Styling -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold {{ $day['is_today'] 
                                ? 'text-emerald-600 dark:text-emerald-400' 
                                : ($day['is_current_month'] ? 'text-slate-900 dark:text-white' : 'text-slate-400 dark:text-slate-600') }}">
                                {{ $day['day'] }}
                            </span>
                            @if($day['total'] > 0)
                                <span class="px-2 py-0.5 text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 rounded-full">
                                    {{ $day['total'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Status Distribution Bars -->
                        @if($day['has_appointments'])
                            <div class="space-y-2">
                                <div class="flex h-1.5 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-700">
                                    @foreach($day['status_counts'] as $status => $count)
                                        @if($count > 0)
                                            @php
                                                $barColor = match($status) {
                                                    'scheduled' => 'bg-blue-500',
                                                    'completed' => 'bg-green-500',
                                                    'missed' => 'bg-red-500',
                                                    'rescheduled' => 'bg-yellow-500',
                                                    'cancelled' => 'bg-slate-500',
                                                    default => 'bg-slate-400'
                                                };
                                            @endphp
                                            <div class="{{ $barColor }} h-full transition-all duration-300 group-hover:opacity-80" 
                                                 style="width: {{ ($count / $day['total']) * 100 }}%"></div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Preview Icons -->
                                <div class="flex gap-1">
                                    @foreach($day['appointments']->take(3) as $appt)
                                        <div class="w-1.5 h-1.5 rounded-full {{ match($appt->status) {
                                            'scheduled' => 'bg-blue-500',
                                            'completed' => 'bg-green-500',
                                            'missed' => 'bg-red-500',
                                            'rescheduled' => 'bg-yellow-500',
                                            'cancelled' => 'bg-slate-500',
                                            default => 'bg-slate-400'
                                        } }}"></div>
                                    @endforeach
                                    @if($day['total'] > 3)
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500">+{{ $day['total'] - 3 }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Week View - Professional Timeline -->
    @if($view === 'week')
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-lg border border-slate-200 dark:border-slate-800 overflow-hidden">
            <!-- Weekday Headers with Dates -->
            <div class="grid grid-cols-7 border-b border-slate-200 dark:border-slate-700">
                @foreach($weekDays as $day)
                    <div class="px-4 py-4 text-center {{ $day['is_today'] ? 'bg-emerald-50/50 dark:bg-emerald-900/10' : '' }}">
                        <div class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $day['short_name'] }}</div>
                        <div class="text-2xl font-bold {{ $day['is_today'] 
                            ? 'text-emerald-600 dark:text-emerald-400' 
                            : 'text-slate-900 dark:text-white' }}">
                            {{ $day['day'] }}
                        </div>
                        @if($day['total'] > 0)
                            <div class="mt-1 text-xs font-medium text-slate-500 dark:text-slate-400">
                                {{ $day['total'] }} appointments
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Timeline Grid -->
            <div class="grid grid-cols-7 divide-x divide-slate-200 dark:divide-slate-700 min-h-[600px]">
                @foreach($weekDays as $dayIndex => $day)
                    <div wire:click="selectDate('{{ $day['formatted'] }}')" 
                         class="relative p-2 {{ $day['is_today'] ? 'bg-emerald-50/30 dark:bg-emerald-900/5' : '' }} cursor-pointer hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-200">
                        
                        <!-- Time Slot Lines -->
                        <div class="absolute inset-0 pointer-events-none">
                            @for($hour = 8; $hour <= 17; $hour++)
                                <div class="absolute w-full border-t border-slate-100 dark:border-slate-800" style="top: {{ (($hour - 8) / 9) * 100 }}%"></div>
                            @endfor
                        </div>

                        <!-- Appointments -->
                        <div class="relative space-y-1.5">
                            @foreach($day['appointments'] as $appointment)
                                @php
                                    $startHour = (int)$appointment->appointment_time->format('H');
                                    $startMin = (int)$appointment->appointment_time->format('i');
                                    $topPosition = (($startHour - 8) * 60 + $startMin) / (9 * 60) * 100;
                                @endphp
                                
                                <div wire:click.stop="selectAppointment({{ $appointment->id }})"
                                     class="absolute left-0 right-0 mx-1 p-2 rounded-lg text-xs cursor-pointer transition-all duration-200 hover:scale-[1.02] hover:shadow-lg group"
                                     style="top: {{ $topPosition }}%; min-height: 45px;">
                                    
                                    <!-- Status-based styling -->
                                    <div class="relative h-full w-full rounded-lg overflow-hidden shadow-sm
                                        @if($appointment->status === 'scheduled') bg-gradient-to-r from-blue-500 to-blue-600
                                        @elseif($appointment->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-600
                                        @elseif($appointment->status === 'missed') bg-gradient-to-r from-red-500 to-rose-600
                                        @elseif($appointment->status === 'rescheduled') bg-gradient-to-r from-yellow-500 to-amber-600
                                        @else bg-gradient-to-r from-slate-500 to-slate-600 @endif">
                                        
                                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/5 transition-colors"></div>
                                        
                                        <div class="p-2 text-white">
                                            <div class="font-medium">{{ $appointment->appointment_time->format('h:i A') }}</div>
                                            <div class="text-xs text-white/90 truncate mt-0.5">
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Day Details Modal - Professional Design -->
    @if($showDayModal && $selectedDate)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
                <!-- Modal Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-800 border-b border-slate-200 dark:border-slate-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $selectedDate->format('l, F j, Y') }}</h2>
                                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                    {{ count($dayAppointments) }} appointment(s) scheduled
                                </p>
                            </div>
                        </div>
                        <button wire:click="$set('showDayModal', false)" 
                                class="p-2 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
                    <div class="space-y-3">
                        @forelse($dayAppointments as $appointment)
                            <div wire:click="selectAppointment({{ $appointment->id }})"
                                 class="group relative overflow-hidden rounded-xl border border-slate-200 dark:border-slate-700 hover:border-emerald-500 dark:hover:border-emerald-500 transition-all duration-200 cursor-pointer">
                                
                                <!-- Status Bar -->
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ match($appointment->status) {
                                    'scheduled' => 'bg-blue-500',
                                    'completed' => 'bg-green-500',
                                    'missed' => 'bg-red-500',
                                    'rescheduled' => 'bg-yellow-500',
                                    'cancelled' => 'bg-slate-500',
                                    default => 'bg-slate-400'
                                } }}"></div>
                                
                                <div class="p-4 pl-6 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <span class="font-semibold text-slate-900 dark:text-white">
                                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                </span>
                                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-4 text-sm">
                                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ $appointment->appointment_time->format('h:i A') }}
                                                </div>
                                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    Dr. {{ $appointment->doctor->name }}
                                                </div>
                                                <div class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                                    </svg>
                                                    {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                                </div>
                                            </div>
                                        </div>
                                        
                                                        <!-- Chevron Indicator -->
                                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="inline-flex p-4 bg-slate-100 dark:bg-slate-800 rounded-2xl mb-4">
                                    <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">No Appointments</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">There are no appointments scheduled for this day.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Appointment Details Modal - Professional Design -->
    @if($showAppointmentModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[60] p-4">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
                <!-- Modal Header with Status Gradient -->
                <div class="px-6 py-5 {{ match($selectedAppointment->status) {
                    'scheduled' => 'bg-gradient-to-r from-blue-500 to-blue-600',
                    'completed' => 'bg-gradient-to-r from-green-500 to-emerald-600',
                    'missed' => 'bg-gradient-to-r from-red-500 to-rose-600',
                    'rescheduled' => 'bg-gradient-to-r from-yellow-500 to-amber-600',
                    'cancelled' => 'bg-gradient-to-r from-slate-500 to-slate-600',
                    default => 'bg-gradient-to-r from-slate-500 to-slate-600'
                } }}">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/20 backdrop-blur-sm rounded-xl">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-white">Appointment Details</h2>
                                <p class="text-sm text-white/80 mt-0.5">ID: #{{ $selectedAppointment->id }}</p>
                            </div>
                        </div>
                        <button wire:click="$set('showAppointmentModal', false)" 
                                class="p-2 bg-white/10 hover:bg-white/20 rounded-xl transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Patient Info Card -->
                    <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-5 border border-slate-200 dark:border-slate-700">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-slate-700 to-slate-900 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                {{ substr($selectedAppointment->patient->first_name, 0, 1) }}{{ substr($selectedAppointment->patient->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-900 dark:text-white text-lg">
                                    {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                                </h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Card: {{ $selectedAppointment->patient->card_number }}</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Phone</p>
                                <p class="font-medium text-slate-900 dark:text-white">{{ $selectedAppointment->patient->phone_number1 }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Gender/Age</p>
                                <p class="font-medium text-slate-900 dark:text-white">
                                    {{ ucfirst($selectedAppointment->patient->gender) }},
                                    {{ \Carbon\Carbon::parse($selectedAppointment->patient->date_of_birth)->age }} yrs
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Appointment Details Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Date</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $selectedAppointment->appointment_date->format('M d, Y') }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Time</p>
                            <p class="font-semibold text-slate-900 dark:text-white">{{ $selectedAppointment->appointment_time->format('h:i A') }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Doctor</p>
                            <p class="font-semibold text-slate-900 dark:text-white">Dr. {{ $selectedAppointment->doctor->name }}</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Visit Type</p>
                            <span class="px-3 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400">
                                {{ ucfirst(str_replace('-', ' ', $selectedAppointment->visit_type)) }}
                            </span>
                        </div>
                    </div>

                    <!-- Status & Payment -->
                    <div class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-800/50 rounded-lg">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Status</p>
                            <span class="px-3 py-1.5 text-xs rounded-full {{ $selectedAppointment->status_color }}">
                                {{ ucfirst($selectedAppointment->status) }}
                            </span>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-1">Payment</p>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1.5 text-xs rounded-full {{ $selectedAppointment->payment_status_color }}">
                                    {{ ucfirst($selectedAppointment->payment_status) }}
                                </span>
                                @if($selectedAppointment->payment_amount)
                                    <span class="font-semibold text-slate-900 dark:text-white">
                                        ETB {{ number_format($selectedAppointment->payment_amount, 2) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Notes Section -->
                    @if($selectedAppointment->doctor_notes)
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <p class="text-xs text-slate-500 dark:text-slate-400 mb-2">Doctor's Notes</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $selectedAppointment->doctor_notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Modal Footer -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showAppointmentModal', false)" 
                                class="px-5 py-2.5 border border-slate-300 dark:border-slate-600 rounded-xl text-slate-700 dark:text-slate-300 font-medium hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors">
                            Close
                        </button>
                        @if($selectedAppointment->isCheckInAvailable())
                            <button wire:click="checkIn({{ $selectedAppointment->id }})"
                                    class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all duration-200">
                                Check In
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Loading Overlay (optional) -->
    <div wire:loading wire:target="previous,next,goToToday,setView,selectDate"
         class="fixed inset-0 bg-white/50 dark:bg-black/50 backdrop-blur-sm flex items-center justify-center z-[100]">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl p-6 flex items-center gap-4">
            <svg class="animate-spin h-6 w-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-slate-700 dark:text-slate-300 font-medium">Loading calendar...</span>
        </div>
    </div>
</div>