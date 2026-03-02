{{-- resources/views/livewire/appointment/upcoming-appointments.blade.php --}}
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Upcoming Appointments</h1>
                <p class="text-indigo-100 mt-1">Future scheduled appointments</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('doctor.appointments.create') }}" 
                   class="px-4 py-2 bg-white text-indigo-600 rounded-lg hover:bg-gray-100">
                    + New Appointment
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-indigo-100 text-sm">Total Upcoming</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total_upcoming'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-indigo-100 text-sm">Next 48 Hours</p>
                <p class="text-2xl font-bold text-yellow-300">{{ $stats['next_48_hours'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-indigo-100 text-sm">This Week</p>
                <p class="text-2xl font-bold text-green-300">{{ $stats['this_week'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-indigo-100 text-sm">This Month</p>
                <p class="text-2xl font-bold text-blue-300">{{ $stats['this_month'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="relative">
                <input type="text" wire:model.live.debounce="search" 
                       placeholder="Search patient..." 
                       class="w-full pl-10 pr-4 py-2 border rounded-lg dark:bg-gray-700">
                <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <div>
                <select wire:model.live="doctorId" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="status" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
                    <option value="">All Status</option>
                    <option value="scheduled">Scheduled</option>
                    <option value="rescheduled">Rescheduled</option>
                </select>
            </div>

            <div>
                <input type="date" wire:model.live="dateFrom" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
            </div>

            <div>
                <input type="date" wire:model.live="dateTo" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
            </div>
        </div>
        
        <div class="flex justify-end mt-3">
            <button wire:click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-800">
                Reset Filters
            </button>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900/50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 
                    {{ $highlightNext48 && $this->isNext48Hours($appointment->appointment_date) ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->appointment_time->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                        <div class="text-sm text-gray-500">Card: {{ $appointment->patient->card_number }}</div>
                    </td>
                    <td class="px-6 py-4">Dr. {{ $appointment->doctor->name }}</td>
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
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            <button wire:click="openRescheduleModal({{ $appointment->id }})"
                                    class="p-1 text-yellow-600 hover:bg-yellow-50 rounded"
                                    title="Reschedule">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </button>
                            <button wire:click="openCancelModal({{ $appointment->id }})"
                                    class="p-1 text-red-600 hover:bg-red-50 rounded"
                                    title="Cancel">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
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

    <!-- Reschedule Modal -->
    @if($showRescheduleModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6">
                <h3 class="text-lg font-semibold mb-4">Reschedule Appointment</h3>
                
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

                    <!-- Available Time Slots -->
                    @if($availableSlots && count($availableSlots) > 0)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Times</label>
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($availableSlots as $slot)
                                    @if($slot['available'] && !$slot['is_past'])
                                        <button wire:click="selectSlot('{{ $slot['time'] }}')"
                                                class="px-3 py-2 text-sm border rounded-lg transition-colors
                                                    {{ $selectedSlot === $slot['time'] 
                                                        ? 'bg-yellow-600 text-white border-yellow-600' 
                                                        : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
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
                        @error('rescheduleReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="$set('showRescheduleModal', false)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Cancel
                    </button>
                    <button wire:click="reschedule"
                            class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg">
                        Reschedule
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Cancel Modal -->
    @if($showCancelModal && $selectedAppointment)
        <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cancel Appointment</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Current Appointment Info -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $selectedAppointment->appointment_date->format('M d, Y') }} at {{ $selectedAppointment->appointment_time->format('h:i A') }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white mt-1">
                            Dr. {{ $selectedAppointment->doctor->name }}
                        </p>
                    </div>

                    <!-- Reason -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Cancellation</label>
                        <textarea wire:model="cancellationReason" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                  placeholder="Please provide a reason..."></textarea>
                        @error('cancellationReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button wire:click="$set('showCancelModal', false)"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Close
                    </button>
                    <button wire:click="cancel"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                        Cancel Appointment
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>