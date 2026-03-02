{{-- resources/views/livewire/appointment/doctor-today-appointments.blade.php --}}
<div class="space-y-6" x-data="{ viewMode: @entangle('viewMode') }">
    
    <!-- Header with Animated Gradient -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 dark:from-blue-800 dark:via-indigo-800 dark:to-purple-800 shadow-2xl">
        <!-- Animated background pattern -->
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:40px_40px]"></div>
        <div class="absolute top-0 right-0 -mt-32 -mr-32 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-32 -ml-32 h-64 w-64 rounded-full bg-purple-500/10 blur-3xl"></div>
        
        <div class="relative px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center border border-white/30 shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Today's Schedule</h1>
                        <p class="text-white/80 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ Carbon\Carbon::now('Africa/Addis_Ababa')->format('l, F j, Y') }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- Digital Clock -->
                    <div class="bg-white/10 backdrop-blur-xl rounded-xl px-6 py-3 border border-white/20">
                        <div class="text-3xl font-bold text-white tracking-wider tabular-nums">
                            {{ Carbon\Carbon::now('Africa/Addis_Ababa')->format('h:i:s A') }}
                        </div>
                        <div class="text-xs text-white/60 text-right mt-1">EAT</div>
                    </div>
                    
                    <a href="{{ route('doctor.appointments.create') }}" 
                       class="group flex items-center gap-2 px-5 py-3 bg-white text-blue-600 rounded-xl hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 font-medium">
                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Appointment
                    </a>
                </div>
            </div>

            <!-- Progress Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Total Today</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $stats['total'] }}</p>
                        </div>
                        <div class="p-3 bg-white/20 rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Scheduled</p>
                            <p class="text-3xl font-bold text-yellow-300 mt-2">{{ $stats['scheduled'] }}</p>
                        </div>
                        <div class="p-3 bg-yellow-500/20 rounded-xl">
                            <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Checked In</p>
                            <p class="text-3xl font-bold text-green-300 mt-2">{{ $stats['checked_in'] }}</p>
                            <p class="text-xs text-white/60 mt-1">{{ $stats['progress_percentage'] }}% of total</p>
                        </div>
                        <div class="p-3 bg-green-500/20 rounded-xl">
                            <svg class="w-6 h-6 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-5 border border-white/20 hover:bg-white/20 transition-all">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/70 text-sm">Completed</p>
                            <p class="text-3xl font-bold text-emerald-300 mt-2">{{ $stats['completed'] }}</p>
                            <p class="text-xs text-white/60 mt-1">{{ $stats['completion_rate'] }}% rate</p>
                        </div>
                        <div class="p-3 bg-emerald-500/20 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-4 bg-white/10 rounded-full h-2 overflow-hidden">
                <div class="h-full bg-gradient-to-r from-green-400 to-emerald-500 rounded-full transition-all duration-500"
                     style="width: {{ $stats['progress_percentage'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Filters & View Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Search patient by name or card number..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
            </div>

            <!-- Status Filter -->
            <div class="md:w-48">
                <select wire:model.live="status" 
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="missed">Missed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <!-- View Toggle -->
            <div class="flex p-1 bg-gray-100 dark:bg-gray-700 rounded-xl">
                <button wire:click="$set('viewMode', 'list')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                            {{ $viewMode === 'list' 
                                ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' 
                                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </button>
                <button wire:click="$set('viewMode', 'timeline')"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200
                            {{ $viewMode === 'timeline' 
                                ? 'bg-white dark:bg-gray-800 text-blue-600 dark:text-blue-400 shadow-sm' 
                                : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- List View -->
    @if($viewMode === 'list')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Notes</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($appointments as $appointment)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="w-2 h-2 rounded-full {{ $appointment->appointment_time->isPast() ? 'bg-gray-400' : 'bg-green-500' }}"></div>
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            {{ $appointment->appointment_time->format('h:i A') }}
                                        </span>
                                    </div>
                                    @if($appointment->checked_in_at)
                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                            ✓ Checked in at {{ $appointment->checked_in_at->format('h:i A') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-medium shadow-sm">
                                            {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Card: {{ $appointment->patient->card_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 font-medium">
                                        {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full {{ $appointment->status_color }} font-medium">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    @if($appointment->doctor_notes)
                                        <div class="text-sm text-gray-600 dark:text-gray-300 truncate group-hover:text-clip">
                                                            {{ $appointment->doctor_notes }}
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500 italic">No notes</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        @if($appointment->status === 'scheduled')
                                            @if(!$appointment->checked_in_at)
                                                <button wire:click="openCheckInModal({{ $appointment->id }})"
                                                        class="p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                                        title="Check In">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            @else
                                                <button wire:click="openCompleteModal({{ $appointment->id }})"
                                                        class="p-2 text-emerald-600 hover:bg-emerald-50 dark:text-emerald-400 dark:hover:bg-emerald-900/20 rounded-lg transition-colors"
                                                        title="Complete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            @endif
                                            <button wire:click="openNotesModal({{ $appointment->id }})"
                                                    class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                    title="Notes">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                                    class="p-2 text-yellow-600 hover:bg-yellow-50 dark:text-yellow-400 dark:hover:bg-yellow-900/20 rounded-lg transition-colors"
                                                    title="Reschedule">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>
                                            <button wire:click="openCancelModal({{ $appointment->id }})"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                    title="Cancel">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                            @if($appointment->appointment_time->isPast())
                                                <button wire:click="markMissed({{ $appointment->id }})"
                                                        class="p-2 text-gray-600 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700 rounded-lg transition-colors"
                                                        title="Mark Missed">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                                            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointments today</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">You have no appointments scheduled for today.</p>
                                        <a href="{{ route('doctor.appointments.create') }}" 
                                           class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                            Create New Appointment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($appointments->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $appointments->links() }}
                </div>
            @endif
        </div>
    @endif

    <!-- Timeline View -->
    @if($viewMode === 'timeline')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($appointmentsBySlot as $slotData)
                    <div class="flex hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <!-- Time Column -->
                        <div class="w-28 px-4 py-4 border-r border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            <div class="text-sm font-semibold {{ $slotData['slot']['is_past'] ? 'text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $slotData['slot']['display'] }}
                            </div>
                            @if($slotData['count'] > 0)
                                <span class="text-xs px-2 py-1 mt-1 inline-block bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">
                                    {{ $slotData['count'] }}
                                </span>
                            @endif
                        </div>

                        <!-- Appointments Column -->
                        <div class="flex-1 px-4 py-2">
                            @if($slotData['appointments']->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach($slotData['appointments'] as $appointment)
                                        <div class="flex items-center justify-between p-3 rounded-lg border-l-4 {{ $appointment->status === 'scheduled' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/10' : ($appointment->status === 'completed' ? 'border-green-500 bg-green-50 dark:bg-green-900/10' : 'border-gray-500 bg-gray-50 dark:bg-gray-800/50') }}">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-sm text-gray-900 dark:text-white">
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                                <button wire:click="openCheckInModal({{ $appointment->id }})"
                                                        class="p-1 text-green-600 hover:bg-green-100 dark:text-green-400 dark:hover:bg-green-900/20 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-400 dark:text-gray-600 italic py-3">
                                    No appointments
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Modals (with dark mode support) -->
    
    <!-- Check In Modal -->
    @if($showCheckInModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Check In Patient</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Appointment Time</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $selectedAppointment->appointment_time->format('h:i A') }}</span>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCheckInModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button wire:click="confirmCheckIn" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow-lg shadow-green-500/20 hover:shadow-xl transition-all disabled:opacity-50">
                            <span wire:loading.remove>Confirm Check In</span>
                            <span wire:loading>Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Complete Modal -->
    @if($showCompleteModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Complete Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <textarea wire:model="doctorNotes" 
                              rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all mb-4"
                              placeholder="Enter consultation notes..."></textarea>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCompleteModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button wire:click="confirmComplete" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-lg shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all">
                            Complete Appointment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notes Modal -->
    @if($showNotesModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Doctor's Notes</h3>
                    
                    <textarea wire:model="doctorNotes" 
                              rows="5" 
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all mb-4"
                              placeholder="Enter your clinical notes..."></textarea>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showNotesModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button wire:click="saveNotes" 
                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all">
                            Save Notes
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Reschedule Modal -->
    @if($showRescheduleModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reschedule Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Current Appointment -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Current Appointment</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $selectedAppointment->appointment_date->format('M d, Y') }} at {{ $selectedAppointment->appointment_time->format('h:i A') }}
                            </p>
                        </div>

                        <!-- New Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Date</label>
                            <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                        </div>

                        <!-- Available Slots -->
                        @if($availableSlots)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Times</label>
                                @if($isLoading)
                                    <div class="text-center py-4">
                                        <svg class="animate-spin h-5 w-5 mx-auto text-yellow-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                @else
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach($availableSlots as $slot)
                                            @if($slot['available'] && !$slot['is_past'])
                                                <button wire:click="selectSlot('{{ $slot['time'] }}')"
                                                        class="px-3 py-2 text-sm rounded-xl border transition-all
                                                            {{ $selectedSlot === $slot['time'] 
                                                                ? 'bg-yellow-600 text-white border-yellow-600' 
                                                                : 'border-gray-200 dark:border-gray-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 text-gray-700 dark:text-gray-300' }}">
                                                    {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason</label>
                            <textarea wire:model="rescheduleReason" 
                                      rows="2" 
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                                      placeholder="Reason for rescheduling..."></textarea>
                            @error('rescheduleReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="$set('showRescheduleModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button wire:click="reschedule" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white rounded-lg shadow-lg shadow-yellow-500/20 hover:shadow-xl transition-all">
                            Reschedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Modal -->
    @if($showCancelModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cancel Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <textarea wire:model="cancellationReason" 
                              rows="3" 
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent mb-4"
                              placeholder="Reason for cancellation..."></textarea>
                    @error('cancellationReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCancelModal', false)" 
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Keep Appointment
                        </button>
                        <button wire:click="cancel" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg shadow-lg shadow-red-500/20 hover:shadow-xl transition-all">
                            Cancel Appointment
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-xl shadow-2xl z-[100] transform transition-all duration-300 ' + 
                    (event[0].type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600');
                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${event[0].type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' 
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'}
                        </svg>
                        <span class="text-white font-medium">${event[0].message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            });
        });
    </script>
</div>