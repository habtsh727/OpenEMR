{{-- resources/views/livewire/appointment/appointment-reports.blade.php --}}
<div class="space-y-6" x-data="{ showFilters: false }">
    
    <!-- Header with Stats -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Appointment Reports</h1>
                    <p class="text-purple-100 mt-1">Analytics and insights for appointments</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Export Buttons -->
                <button wire:click="exportToCsv" 
                        class="px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-xl flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    CSV
                </button>
                
                <button wire:click="exportToPdf" 
                        class="px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-xl flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    PDF
                </button>
                
                <button @click="showFilters = !showFilters" 
                        class="px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-xl flex items-center gap-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                </button>
            </div>
        </div>

        <!-- Quick Stats Row -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <p class="text-purple-100 text-xs">Total Appointments</p>
                <p class="text-2xl font-bold text-white">{{ number_format($summary['total']) }}</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <p class="text-purple-100 text-xs">Completion Rate</p>
                <p class="text-2xl font-bold text-green-300">{{ $summary['completion_rate'] }}%</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <p class="text-purple-100 text-xs">Missed Rate</p>
                <p class="text-2xl font-bold text-red-300">{{ $summary['missed_rate'] }}%</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4">
                <p class="text-purple-100 text-xs">Checked In</p>
                <p class="text-2xl font-bold text-emerald-300">{{ number_format($summary['checked_in']) }}</p>
            </div>
        </div>

        <!-- Trends -->
        <div class="flex items-center gap-4 mt-4 text-sm bg-white/5 rounded-lg p-3">
            <span class="text-purple-200">vs Previous Period:</span>
            <span class="flex items-center gap-1">
                <span class="text-white">Total</span>
                <span class="{{ $trendData['total_change'] >= 0 ? 'text-green-300' : 'text-red-300' }}">
                    {{ $trendData['total_change'] >= 0 ? '+' : '' }}{{ $trendData['total_change'] }}%
                </span>
            </span>
            <span class="flex items-center gap-1">
                <span class="text-white">Completed</span>
                <span class="{{ $trendData['completed_change'] >= 0 ? 'text-green-300' : 'text-red-300' }}">
                    {{ $trendData['completed_change'] >= 0 ? '+' : '' }}{{ $trendData['completed_change'] }}%
                </span>
            </span>
        </div>
    </div>

    <!-- Report Type Tabs -->
    <div class="flex flex-wrap gap-2 border-b border-gray-200 dark:border-gray-700 pb-2">
        <button wire:click="$set('reportType', 'summary')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                    {{ $reportType === 'summary' 
                        ? 'bg-purple-600 text-white shadow-md' 
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Summary
        </button>
        <button wire:click="$set('reportType', 'detailed')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                    {{ $reportType === 'detailed' 
                        ? 'bg-purple-600 text-white shadow-md' 
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Detailed
        </button>
        <button wire:click="$set('reportType', 'doctor')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                    {{ $reportType === 'doctor' 
                        ? 'bg-purple-600 text-white shadow-md' 
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            By Doctor
        </button>
        <button wire:click="$set('reportType', 'type')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                    {{ $reportType === 'type' 
                        ? 'bg-purple-600 text-white shadow-md' 
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            By Visit Type
        </button>
        <button wire:click="$set('reportType', 'trends')"
                class="px-4 py-2 rounded-lg text-sm font-medium transition-all
                    {{ $reportType === 'trends' 
                        ? 'bg-purple-600 text-white shadow-md' 
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
            Trends
        </button>
    </div>

    <!-- Filters Panel (Collapsible) -->
    <div x-show="showFilters" x-collapse class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                <input type="date" wire:model.live="dateFrom" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                <input type="date" wire:model.live="dateTo" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                <select wire:model.live="doctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="">All Doctors</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group By</label>
                <select wire:model.live="groupBy" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    <option value="day">Daily</option>
                    <option value="week">Weekly</option>
                    <option value="month">Monthly</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Summary Report -->
    @if($reportType === 'summary')
        <div class="space-y-6">
            <!-- Key Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($summary['total']) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-xl">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">{{ number_format($summary['completed']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $summary['completion_rate'] }}% of total</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-xl">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Missed</p>
                            <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-2">{{ number_format($summary['missed']) }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $summary['missed_rate'] }}% of total</p>
                        </div>
                        <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-xl">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Checked In</p>
                            <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">{{ number_format($summary['checked_in']) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-xl">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Breakdown -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Status Breakdown</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                        <p class="text-sm text-blue-600 dark:text-blue-400">Scheduled</p>
                        <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($summary['scheduled']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl">
                        <p class="text-sm text-green-600 dark:text-green-400">Completed</p>
                        <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ number_format($summary['completed']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-xl">
                        <p class="text-sm text-red-600 dark:text-red-400">Missed</p>
                        <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ number_format($summary['missed']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">Cancelled</p>
                        <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ number_format($summary['cancelled']) }}</p>
                    </div>
                    <div class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-xl">
                        <p class="text-sm text-orange-600 dark:text-orange-400">Rescheduled</p>
                        <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">{{ number_format($summary['rescheduled']) }}</p>
                    </div>
                </div>
            </div>

            <!-- Peak Hours Chart -->
            @if($peakHours->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Peak Hours</h3>
                <div class="space-y-2">
                    @foreach($peakHours as $hour)
                    <div class="flex items-center gap-2">
                        <span class="w-16 text-sm text-gray-600 dark:text-gray-400">{{ $hour->formatted_hour }}</span>
                        <div class="flex-1 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden">
                            <div class="h-full bg-purple-600 dark:bg-purple-500 flex items-center justify-end px-2 text-xs text-white"
                                 style="width: {{ $hour->percentage }}%">
                                @if($hour->percentage > 8)
                                    {{ $hour->count }} ({{ $hour->percentage }}%)
                                @endif
                            </div>
                        </div>
                        <span class="w-16 text-sm text-gray-600 dark:text-gray-400">{{ $hour->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    @endif

    <!-- Detailed Report -->
    @if($reportType === 'detailed')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-b">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Detailed Appointment List</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Time</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Doctor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Checked In</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->detailedAppointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $appointment->appointment_date->format('M d, Y') }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $appointment->appointment_time->format('h:i A') }}</td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Card: {{ $appointment->patient->card_number }}</div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">Dr. {{ $appointment->doctor->name }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400">
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
                                    <span class="text-green-600 dark:text-green-400">{{ $appointment->checked_in_at->format('h:i A') }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">-</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $this->detailedAppointments->links() }}
            </div>
        </div>
    @endif

    <!-- By Doctor Report -->
    @if($reportType === 'doctor')
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-b">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Performance by Doctor</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Doctor</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Completed</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Missed</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cancelled</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($doctorStats as $stat)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">Dr. {{ $stat->doctor_name }}</td>
                            <td class="px-4 py-3 text-right text-gray-900 dark:text-white">{{ $stat->total }}</td>
                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400 font-medium">{{ $stat->completed }}</td>
                            <td class="px-4 py-3 text-right text-red-600 dark:text-red-400">{{ $stat->missed }}</td>
                            <td class="px-4 py-3 text-right text-yellow-600 dark:text-yellow-400">{{ $stat->cancelled }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="px-2 py-1 text-xs rounded-full {{ $stat->completion_rate >= 70 ? 'bg-green-100 text-green-800' : ($stat->completion_rate >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $stat->completion_rate }}%
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
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($typeStats as $stat)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $stat->visit_type_label }}</h4>
                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400">
                        {{ $stat->total }} total
                    </span>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">Completed</span>
                        <span class="font-medium text-green-600 dark:text-green-400">{{ $stat->completed }} ({{ $stat->completion_rate }}%)</span>
                    </div>
                    
                    <div class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: {{ $stat->completion_rate }}%"></div>
                    </div>
                    
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Missed: {{ $stat->missed }}</span>
                        <span>Cancelled: {{ $stat->cancelled }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif

    <!-- Trends Report -->
    @if($reportType === 'trends')
        <div class="space-y-6">
            <!-- Daily/Weekly/Monthly Trends -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ ucfirst($groupBy) }}ly Trends
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Period</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Completed</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Missed</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Rate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if($groupBy === 'day')
                                @foreach($dailyStats as $stat)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">
                                        {{ $stat->formatted_date }}
                                        <span class="text-xs text-gray-500 ml-2">{{ $stat->day_name }}</span>
                                    </td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-white">{{ $stat->total }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-green-600 dark:text-green-400">{{ $stat->completed }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-red-600 dark:text-red-400">{{ $stat->missed }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-medium">{{ $stat->completion_rate }}%</td>
                                </tr>
                                @endforeach
                            @elseif($groupBy === 'week')
                                @foreach($weeklyStats as $stat)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $stat->week_range }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-white">{{ $stat->total }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-green-600 dark:text-green-400">{{ $stat->completed }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-red-600 dark:text-red-400">{{ $stat->missed }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-medium">{{ $stat->completion_rate }}%</td>
                                </tr>
                                @endforeach
                            @else
                                @foreach($monthlyStats as $stat)
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ $stat->month_name }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-white">{{ $stat->total }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-green-600 dark:text-green-400">{{ $stat->completed }}</td>
                                    <td class="px-4 py-2 text-sm text-right text-red-600 dark:text-red-400">{{ $stat->missed }}</td>
                                    <td class="px-4 py-2 text-sm text-right font-medium">{{ $stat->completion_rate }}%</td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4 text-sm text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-wrap justify-between items-center gap-2">
            <div>
                <span class="font-medium">Generated:</span> {{ now()->format('M d, Y h:i A') }}
            </div>
            <div>
                <span class="font-medium">Period:</span> {{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}
            </div>
            <div>
                <span class="font-medium">Doctor:</span> {{ $doctorId ? $doctors->firstWhere('id', $doctorId)?->name : 'All' }}
            </div>
        </div>
    </div>
</div>