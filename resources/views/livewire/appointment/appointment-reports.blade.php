{{-- resources/views/livewire/appointment/appointment-reports.blade.php --}}
<div class="space-y-6">
    
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-white">Appointment Reports</h1>
                <p class="text-purple-100 mt-1">Analytics and insights for appointments</p>
            </div>
            <div class="flex gap-2">
                <button wire:click="exportToCsv" 
                        class="px-4 py-2 bg-white text-purple-600 rounded-lg hover:bg-gray-100 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export CSV
                </button>
            </div>
        </div>

        <!-- Report Type Tabs -->
        <div class="flex gap-2 mt-6">
            <button wire:click="$set('reportType', 'summary')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                        {{ $reportType === 'summary' ? 'bg-white text-purple-600' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Summary
            </button>
            <button wire:click="$set('reportType', 'detailed')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                        {{ $reportType === 'detailed' ? 'bg-white text-purple-600' : 'bg-white/20 text-white hover:bg-white/30' }}">
                Detailed
            </button>
            <button wire:click="$set('reportType', 'doctor')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                        {{ $reportType === 'doctor' ? 'bg-white text-purple-600' : 'bg-white/20 text-white hover:bg-white/30' }}">
                By Doctor
            </button>
            <button wire:click="$set('reportType', 'type')"
                    class="px-4 py-2 rounded-lg text-sm font-medium transition-colors
                        {{ $reportType === 'type' ? 'bg-white text-purple-600' : 'bg-white/20 text-white hover:bg-white/30' }}">
                By Visit Type
            </button>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" wire:model.live="dateFrom" 
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" wire:model.live="dateTo" 
                       class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                <select wire:model.live="doctorId" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Period: {{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Summary Report -->
    @if($reportType === 'summary')
        <div class="space-y-6">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Appointments</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($summary['total']) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($summary['completed']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $summary['total'] > 0 ? round(($summary['completed'] / $summary['total']) * 100, 1) : 0 }}% completion rate</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Missed</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($summary['missed']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $summary['total'] > 0 ? round(($summary['missed'] / $summary['total']) * 100, 1) : 0 }}% missed rate</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Checked In</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($summary['checked_in']) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Breakdown</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-blue-600 dark:text-blue-400">Scheduled</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($summary['scheduled']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <p class="text-sm text-green-600 dark:text-green-400">Completed</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format($summary['completed']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <p class="text-sm text-red-600 dark:text-red-400">Missed</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ number_format($summary['missed']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">Cancelled</p>
                        <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($summary['cancelled']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg">
                        <p class="text-sm text-orange-600 dark:text-orange-400">Rescheduled</p>
                        <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">{{ number_format($summary['rescheduled']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Daily Trend -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Trend</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Completed</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Completion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($dailyStats as $stat)
                            <tr>
                                <td class="px-4 py-2 text-sm">{{ Carbon\Carbon::parse($stat->date)->format('M d, Y') }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ $stat->total }}</td>
                                <td class="px-4 py-2 text-sm text-right">{{ $stat->completed }}</td>
                                <td class="px-4 py-2 text-sm text-right">
                                    {{ $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 1) : 0 }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Detailed Report -->
    @if($reportType === 'detailed')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                <h3 class="text-lg font-semibold">Detailed Appointment List</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Checked In</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->appointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 text-sm">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm">{{ $appointment->appointment_time->format('h:i A') }}</td>
                            <td class="px-4 py-3">
                                <div class="font-medium">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                                <div class="text-xs text-gray-500">Card: {{ $appointment->patient->card_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm">Dr. {{ $appointment->doctor->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $appointment->status_color }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($appointment->checked_in_at)
                                    {{ $appointment->checked_in_at->format('h:i A') }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($appointment->doctor_notes)
                                    <span class="text-green-600">✓</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">
                {{ $this->appointments->links() }}
            </div>
        </div>
    @endif

    <!-- By Doctor Report -->
    @if($reportType === 'doctor')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                <h3 class="text-lg font-semibold">Performance by Doctor</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Completed</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Missed</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Cancelled</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Completion Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($doctorStats as $stat)
                        <tr>
                            <td class="px-4 py-3 font-medium">Dr. {{ $stat->doctor_name }}</td>
                            <td class="px-4 py-3 text-right">{{ $stat->total }}</td>
                            <td class="px-4 py-3 text-right">{{ $stat->scheduled }}</td>
                            <td class="px-4 py-3 text-right text-green-600 font-medium">{{ $stat->completed }}</td>
                            <td class="px-4 py-3 text-right text-red-600">{{ $stat->missed }}</td>
                            <td class="px-4 py-3 text-right text-gray-600">{{ $stat->cancelled }}</td>
                            <td class="px-4 py-3 text-right font-medium">
                                @php
                                    $rate = $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 1) : 0;
                                @endphp
                                <span class="{{ $rate >= 70 ? 'text-green-600' : ($rate >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $rate }}%
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- By Visit Type Report -->
    @if($reportType === 'type')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                <h3 class="text-lg font-semibold">Visit Type Analysis</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-6">
                @foreach($typeStats as $stat)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $stat->visit_type_label }}</h4>
                    <div class="mt-3 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Total:</span>
                            <span class="font-medium">{{ $stat->total }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Completed:</span>
                            <span class="font-medium text-green-600">{{ $stat->completed }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full" 
                                 style="width: {{ $stat->total > 0 ? ($stat->completed / $stat->total) * 100 : 0 }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 text-right">
                            {{ $stat->total > 0 ? round(($stat->completed / $stat->total) * 100, 1) : 0 }}% completion
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Summary Footer -->
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 text-sm text-gray-500 dark:text-gray-400">
        <div class="flex justify-between items-center">
            <div>
                <span class="font-medium">Report generated:</span> {{ now()->format('M d, Y h:i A') }}
            </div>
            <div>
                <span class="font-medium">Period:</span> {{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </div>
        </div>
    </div>
</div>