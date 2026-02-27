<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Appointments</h1>
                <p class="text-blue-100 mt-1">Manage and track all patient appointments</p>
            </div>
            <a href="{{ route('appointments.create') }}" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-gray-100">
                + New Appointment
            </a>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100">Total</p>
                <p class="text-2xl font-bold text-white">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100">Scheduled</p>
                <p class="text-2xl font-bold text-white">{{ $stats['scheduled'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100">Completed</p>
                <p class="text-2xl font-bold text-white">{{ $stats['completed'] }}</p>
            </div>
            <div class="bg-white/10 rounded-lg p-4">
                <p class="text-blue-100">Missed</p>
                <p class="text-2xl font-bold text-white">{{ $stats['missed'] }}</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <input type="text" wire:model.live.debounce="search" placeholder="Search patient..." 
                       class="w-full border rounded-lg px-3 py-2">
            </div>
            
            <!-- Date Range -->
            <div>
                <input type="date" wire:model.live="dateFrom" class="w-full border rounded-lg px-3 py-2">
            </div>
            <div>
                <input type="date" wire:model.live="dateTo" class="w-full border rounded-lg px-3 py-2">
            </div>
            
            <!-- Doctor Filter -->
            <div>
                <select wire:model.live="doctorId" class="w-full border rounded-lg px-3 py-2">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select wire:model.live="status" class="w-full border rounded-lg px-3 py-2">
                    <option value="">All Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Visit Type -->
            <div>
                <select wire:model.live="visitType" class="w-full border rounded-lg px-3 py-2">
                    <option value="">All Types</option>
                    @foreach($visitTypes as $type)
                        <option value="{{ $type }}">{{ ucfirst(str_replace('-', ' ', $type)) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase cursor-pointer"
                        wire:click="sortBy('appointment_date')">
                        Date/Time
                        @if($sortField === 'appointment_date')
                            <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                        @endif
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($appointments as $appointment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                        <div class="text-sm text-gray-500">{{ $appointment->appointment_time->format('h:i A') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                        <div class="text-sm text-gray-500">Card: {{ $appointment->patient->card_number }}</div>
                    </td>
                    <td class="px-6 py-4">Dr. {{ $appointment->doctor->name }}</td>
                    <td class="px-6 py-4">{{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status_color }}">
                            {{ ucfirst($appointment->payment_status) }}
                        </span>
                        @if($appointment->payment_amount)
                            <div class="text-sm mt-1">ETB {{ number_format($appointment->payment_amount, 2) }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-2">
                            @if($appointment->isCheckInAvailable())
                                <button wire:click="checkIn({{ $appointment->id }})" 
                                        class="text-green-600 hover:text-green-900">Check In</button>
                            @endif
                            <button wire:click="$dispatch('openReschedule', { id: {{ $appointment->id }} })"
                                    class="text-blue-600 hover:text-blue-900">Reschedule</button>
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
</div>