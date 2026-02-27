<div class="space-y-6">
    <!-- Header with Current Time -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Today's Appointments</h1>
                <p class="text-amber-100 mt-1">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold text-white">{{ now()->format('h:i A') }}</div>
                <p class="text-amber-100 text-sm">Current Time</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-amber-100 text-sm">Total Today</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-amber-100 text-sm">Pending Check-in</p>
                <p class="text-2xl font-bold text-yellow-300">{{ $stats['pending_checkin'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-amber-100 text-sm">Completed</p>
                <p class="text-2xl font-bold text-green-300">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                <p class="text-amber-100 text-sm">Checked In</p>
                <p class="text-2xl font-bold text-blue-300">{{ $stats['checked_in'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters and View Controls -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-col md:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" wire:model.live.debounce="search" 
                       placeholder="Search patient..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Doctor Filter -->
            <div class="w-full md:w-48">
                <select wire:model.live="doctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div class="w-full md:w-40">
                <select wire:model.live="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-amber-500 dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <!-- View Mode Toggle -->
            <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                <button wire:click="$set('viewMode', 'timeline')" 
                        class="px-3 py-2 {{ $viewMode === 'timeline' ? 'bg-amber-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    Timeline
                </button>
                <button wire:click="$set('viewMode', 'list')" 
                        class="px-3 py-2 {{ $viewMode === 'list' ? 'bg-amber-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    List
                </button>
                <button wire:click="$set('viewMode', 'grid')" 
                        class="px-3 py-2 {{ $viewMode === 'grid' ? 'bg-amber-600 text-white' : 'bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                    Grid
                </button>
            </div>
        </div>
    </div>

    <!-- Timeline View -->
    @if($viewMode === 'timeline')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($timelineSlots as $slot)
                    <div class="flex hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors
                        {{ $slot['is_current'] ? 'bg-amber-50 dark:bg-amber-900/10' : '' }}">
                        <!-- Time Column -->
                        <div class="w-24 px-4 py-3 border-r border-gray-200 dark:border-gray-700">
                            <div class="font-medium {{ $slot['is_past'] ? 'text-gray-400' : 'text-gray-900 dark:text-white' }}">
                                {{ $slot['time'] }}
                            </div>
                            @if($slot['is_current'])
                                <span class="text-xs text-amber-600 dark:text-amber-400">Now</span>
                            @endif
                        </div>
                        
                        <!-- Appointments Column -->
                        <div class="flex-1 px-4 py-2">
                            @if($slot['has_appointments'])
                                <div class="space-y-2">
                                    @foreach($slot['appointments'] as $appointment)
                                        <div class="flex items-center justify-between p-2 rounded-lg border {{ $appointment->status === 'completed' ? 'bg-green-50 dark:bg-green-900/10 border-green-200 dark:border-green-800' : 'border-gray-200 dark:border-gray-700' }}">
                                            <div class="flex items-center space-x-3">
                                                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">
                                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        Dr. {{ $appointment->doctor->name }} • {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                                    {{ ucfirst($appointment->status) }}
                                                </span>
                                                @if($appointment->isCheckInAvailable())
                                                    <button wire:click="checkIn({{ $appointment->id }})"
                                                            class="px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors">
                                                        Check In
                                                    </button>
                                                @endif
                                                <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                                        class="p-1 text-blue-600 hover:bg-blue-50 dark:text-blue-400 rounded">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-sm text-gray-400 dark:text-gray-600 italic py-2">
                                    No appointments
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <p class="text-gray-500 dark:text-gray-400">No appointments scheduled for today</p>
                    </div>
                @endforelse
            </div>
        </div>

    <!-- List View -->
    @elseif($viewMode === 'list')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Check In</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->appointment_time->format('h:i A') }}
                                </div>
                                @if($appointment->checked_in_at)
                                    <div class="text-xs text-green-600 dark:text-green-400">
                                        Checked in: {{ $appointment->checked_in_at->format('h:i A') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                                        {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Card: {{ $appointment->patient->card_number }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">Dr. {{ $appointment->doctor->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($appointment->checked_in_at)
                                    <span class="text-green-600 dark:text-green-400 text-sm">✓ Checked In</span>
                                @elseif($appointment->status === 'scheduled')
                                    <button wire:click="checkIn({{ $appointment->id }})"
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg transition-colors">
                                        Check In
                                    </button>
                                @else
                                    <span class="text-gray-400 text-sm">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex space-x-2">
                                    <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                            class="p-1 text-blue-600 hover:bg-blue-50 dark:text-blue-400 rounded"
                                            title="Reschedule">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                    </button>
                                    <button wire:click="openCancelModal({{ $appointment->id }})"
                                            class="p-1 text-red-600 hover:bg-red-50 dark:text-red-400 rounded"
                                            title="Cancel">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    @if($appointment->status === 'scheduled' && !$appointment->checked_in_at && $appointment->appointment_time->isPast())
                                        <button wire:click="markMissed({{ $appointment->id }})"
                                                class="p-1 text-gray-600 hover:bg-gray-50 dark:text-gray-400 rounded"
                                                title="Mark Missed">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No appointments scheduled for today
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    <!-- Grid View -->
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($appointments as $appointment)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <!-- Card Header with Time -->
                    <div class="px-4 py-3 bg-gradient-to-r from-amber-500 to-orange-600">
                        <div class="flex justify-between items-center">
                            <span class="text-white font-bold">{{ $appointment->appointment_time->format('h:i A') }}</span>
                            <span class="px-2 py-1 text-xs rounded-full bg-white/20 text-white">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="p-4">
                        <div class="flex items-center mb-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Card: {{ $appointment->patient->card_number }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Doctor:</span>
                                <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $appointment->doctor->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500 dark:text-gray-400">Type:</span>
                                <span class="px-2 py-0.5 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                    {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                </span>
                            </div>
                            @if($appointment->checked_in_at)
                                <div class="flex justify-between">
                                    <span class="text-gray-500 dark:text-gray-400">Checked In:</span>
                                    <span class="text-green-600 dark:text-green-400">{{ $appointment->checked_in_at->format('h:i A') }}</span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Actions -->
                        <div class="mt-4 pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-2">
                            @if($appointment->isCheckInAvailable())
                                <button wire:click="checkIn({{ $appointment->id }})"
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg">
                                    Check In
                                </button>
                            @endif
                            <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                    class="p-1 text-blue-600 hover:bg-blue-50 dark:text-blue-400 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                            <button wire:click="openCancelModal({{ $appointment->id }})"
                                    class="p-1 text-red-600 hover:bg-red-50 dark:text-red-400 rounded">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <p class="text-gray-500 dark:text-gray-400">No appointments scheduled for today</p>
                </div>
            @endforelse
        </div>
    @endif

    <!-- Reschedule Modal -->
    @if($showRescheduleModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reschedule Appointment</h2>
                    
                    <div class="space-y-4">
                        <!-- Current Appointment Info -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current Appointment</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }} with Dr. {{ $selectedAppointment->doctor->name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                {{ $selectedAppointment->appointment_date->format('M d, Y') }} at {{ $selectedAppointment->appointment_time->format('h:i A') }}
                            </p>
                        </div>

                        <!-- New Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Date</label>
                            <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        </div>

                        <!-- New Doctor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Doctor</label>
                            <select wire:model.live="newDoctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Available Time Slots -->
                        @if($availableSlots)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Time</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($availableSlots as $slot)
                                        @if($slot['available'])
                                            <button wire:click="$set('newTime', '{{ $slot['time'] }}')"
                                                    class="px-3 py-2 text-sm rounded-lg {{ $newTime === $slot['time'] ? 'bg-amber-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rescheduling</label>
                            <textarea wire:model="rescheduleReason" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                      placeholder="Please provide a reason..."></textarea>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="$set('showRescheduleModal', false)"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button wire:click="reschedule"
                                class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                            Reschedule
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Modal -->
    @if($showCancelModal)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Cancel Appointment</h2>
                    
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to cancel the appointment for 
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedAppointment?->patient->first_name }} {{ $selectedAppointment?->patient->last_name }}
                        </span>?
                    </p>

                    <textarea wire:model="rescheduleReason" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white mb-4"
                              placeholder="Reason for cancellation (optional)"></textarea>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCancelModal', false)"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            No, Keep It
                        </button>
                        <button wire:click="cancel('{{ $rescheduleReason }}')"
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Yes, Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>