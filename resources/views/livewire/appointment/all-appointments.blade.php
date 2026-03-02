{{-- resources/views/livewire/appointment/all-appointments.blade.php --}}
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-2xl shadow-xl p-6">
        <h1 class="text-2xl font-bold text-white">All Appointments</h1>
        <p class="text-gray-300 mt-1">Complete appointment history</p>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Total</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Scheduled</p>
                <p class="text-2xl font-bold text-yellow-300">{{ $stats['scheduled'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Completed</p>
                <p class="text-2xl font-bold text-green-300">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-gray-300 text-sm">Cancelled</p>
                <p class="text-2xl font-bold text-red-300">{{ $stats['cancelled'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
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
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <select wire:model.live="visitType" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700">
                    <option value="">All Types</option>
                    @foreach($visitTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
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
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                            wire:click="sortBy('appointment_date')">
                            Date
                            @if($sortField === 'appointment_date')
                                <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                            @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4">
                            {{ $appointment->appointment_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $appointment->appointment_time->format('h:i A') }}
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
                        <td class="px-6 py-4 max-w-xs">
                            @if($appointment->doctor_notes)
                                <div class="text-sm truncate">{{ $appointment->doctor_notes }}</div>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $appointments->links() }}
        </div>
    </div>
</div>