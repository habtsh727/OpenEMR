{{-- resources/views/livewire/appointment/doctor-today-appointments.blade.php --}}
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Today's Appointments</h1>
                <p class="text-blue-100 mt-1">{{ Carbon\Carbon::now('Africa/Addis_Ababa')->format('l, F j, Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-4xl font-bold text-white">{{ Carbon\Carbon::now('Africa/Addis_Ababa')->format('h:i A') }}</div>
                    <p class="text-blue-100 text-sm">EAT (Ethiopian Time)</p>
                </div>
                <a href="{{ route('doctor.appointments.create') }}" 
                   class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-100 transition-colors">
                    + New Appointment
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100 text-sm">Total Today</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100 text-sm">Scheduled</p>
                <p class="text-2xl font-bold text-yellow-300">{{ $stats['scheduled'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100 text-sm">Checked In</p>
                <p class="text-2xl font-bold text-green-300">{{ $stats['checked_in'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100 text-sm">Completed</p>
                <p class="text-2xl font-bold text-emerald-300">{{ $stats['completed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex gap-4">
            <div class="flex-1 relative">
                <input type="text" wire:model.live.debounce="search" 
                       placeholder="Search patient by name or card number..." 
                       class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="w-48">
                <select wire:model.live="status" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="completed">Completed</option>
                    <option value="missed">Missed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium">{{ $appointment->appointment_time->format('h:i A') }}</div>
                        @if($appointment->checked_in_at)
                            <div class="text-xs text-green-600">✓ {{ $appointment->checked_in_at->format('h:i A') }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                        <div class="text-sm text-gray-500">Card: {{ $appointment->patient->card_number }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                            {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 max-w-xs">
                        @if($appointment->doctor_notes)
                            <div class="text-sm truncate">{{ $appointment->doctor_notes }}</div>
                        @else
                            <span class="text-sm text-gray-400">No notes</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            @if($appointment->status === 'scheduled')
                                @if(!$appointment->checked_in_at)
                                    <button wire:click="openCheckInModal({{ $appointment->id }})"
                                            class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg">
                                        Check In
                                    </button>
                                @else
                                    <button wire:click="openCompleteModal({{ $appointment->id }})"
                                            class="px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs rounded-lg">
                                        Complete
                                    </button>
                                @endif
                                <button wire:click="openNotesModal({{ $appointment->id }})"
                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-lg">
                                    Notes
                                </button>
                                <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                        class="px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs rounded-lg">
                                    Reschedule
                                </button>
                                <button wire:click="openCancelModal({{ $appointment->id }})"
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded-lg">
                                    Cancel
                                </button>
                                @if($appointment->appointment_time->isPast())
                                    <button wire:click="markMissed({{ $appointment->id }})"
                                            class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-lg">
                                        Missed
                                    </button>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="px-6 py-4 border-t">
            {{ $appointments->links() }}
        </div>
    </div>

    <!-- Check In Modal -->
    @if($showCheckInModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Check In Patient</h3>
                <p class="mb-4">Check in <span class="font-semibold">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</span>?</p>
                <p class="text-sm text-gray-600 mb-4">Time: {{ $selectedAppointment->appointment_time->format('h:i A') }}</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showCheckInModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button wire:click="confirmCheckIn" class="px-4 py-2 bg-green-600 text-white rounded-lg">Check In</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Complete Modal -->
    @if($showCompleteModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Complete Appointment</h3>
                <p class="mb-4">Add consultation notes for <span class="font-semibold">{{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}</span></p>
                <textarea wire:model="doctorNotes" rows="4" 
                          class="w-full border rounded-lg p-3 mb-4"
                          placeholder="Enter your clinical notes..."></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showCompleteModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button wire:click="confirmComplete" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Complete</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Notes Modal -->
    @if($showNotesModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Doctor's Notes</h3>
                <textarea wire:model="doctorNotes" rows="5" 
                          class="w-full border rounded-lg p-3 mb-4"
                          placeholder="Enter your clinical notes..."></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showNotesModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button wire:click="saveNotes" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Notes</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Reschedule Modal -->
    @if($showRescheduleModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Reschedule Appointment</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Current Appointment</label>
                        <p class="text-sm">{{ $selectedAppointment->appointment_date->format('M d, Y') }} at {{ $selectedAppointment->appointment_time->format('h:i A') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">New Date</label>
                        <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>

                    @if($availableSlots)
                        <div>
                            <label class="block text-sm font-medium mb-2">Available Times</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($availableSlots as $slot)
                                    @if($slot['available'] && !$slot['is_past'])
                                        <button wire:click="selectSlot('{{ $slot['time'] }}')"
                                                class="px-3 py-2 text-sm border rounded-lg {{ $newTime === $slot['time'] ? 'bg-yellow-600 text-white' : 'hover:bg-gray-100' }}">
                                            {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium mb-1">Reason</label>
                        <textarea wire:model="rescheduleReason" rows="2" 
                                  class="w-full border rounded-lg px-3 py-2"
                                  placeholder="Reason for rescheduling..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button wire:click="$set('showRescheduleModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                    <button wire:click="reschedule" class="px-4 py-2 bg-yellow-600 text-white rounded-lg">Reschedule</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Modal -->
    @if($showCancelModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Cancel Appointment</h3>
                <p class="mb-4">Are you sure you want to cancel this appointment?</p>
                <textarea wire:model="cancellationReason" rows="2" 
                          class="w-full border rounded-lg p-3 mb-4"
                          placeholder="Reason for cancellation..."></textarea>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 border rounded-lg">No</button>
                    <button wire:click="cancel" class="px-4 py-2 bg-red-600 text-white rounded-lg">Yes, Cancel</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ' + 
                    (event[0].type === 'success' ? 'bg-green-500' : 'bg-red-500') + ' text-white';
                notification.textContent = event[0].message;
                document.body.appendChild(notification);
                
                setTimeout(() => notification.remove(), 3000);
            });
        });
    </script>
</div>