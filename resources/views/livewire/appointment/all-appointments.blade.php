{{-- resources/views/livewire/appointment/all-appointments.blade.php --}}
<div class="space-y-6" x-data="{ showFilters: @entangle('showFilters') }">

    <!-- Header with Animated Gradient -->
    <div
        class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-800 via-slate-900 to-slate-950 dark:from-slate-900 dark:via-slate-950 dark:to-black shadow-2xl">
        <!-- Animated background pattern -->
        <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:40px_40px]"></div>
        <div class="absolute top-0 right-0 -mt-32 -mr-32 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-32 -ml-32 h-64 w-64 rounded-full bg-purple-500/10 blur-3xl"></div>

        <div class="relative px-8 py-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div
                        class="h-16 w-16 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-2xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">All Appointments</h1>
                        <p class="text-white/80 mt-1">Complete appointment history and analytics</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Export Button -->
                    <button wire:click="exportToCsv"
                        class="px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-xl flex items-center gap-2 transition-all border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Export CSV
                    </button>

                    <!-- Filter Toggle -->
                    <button @click="showFilters = !showFilters"
                        class="px-4 py-2.5 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-xl flex items-center gap-2 transition-all border border-white/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        <span x-text="showFilters ? 'Hide Filters' : 'Show Filters'"></span>
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-6">
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-white mt-1">{{ number_format($stats['total']) }}</p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Scheduled</p>
                    <p class="text-2xl font-bold text-yellow-300 mt-1">{{ number_format($stats['scheduled']) }}</p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Completed</p>
                    <p class="text-2xl font-bold text-green-300 mt-1">{{ number_format($stats['completed']) }}</p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Missed</p>
                    <p class="text-2xl font-bold text-red-300 mt-1">{{ number_format($stats['missed']) }}</p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Cancelled</p>
                    <p class="text-2xl font-bold text-orange-300 mt-1">{{ number_format($stats['cancelled']) }}</p>
                </div>
                <div
                    class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20 hover:bg-white/20 transition-all">
                    <p class="text-white/60 text-xs uppercase tracking-wider">Checked In</p>
                    <p class="text-2xl font-bold text-emerald-300 mt-1">{{ number_format($stats['checked_in']) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div x-show="showFilters" x-collapse
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Search by patient name or card..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                </div>

                <!-- Doctor Filter -->
                <div>
                    <select wire:model.live="doctorId"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <select wire:model.live="status"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Status</option>
                        @foreach($statuses as $statusOption)
                        <option value="{{ $statusOption }}">{{ ucfirst($statusOption) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Visit Type Filter -->
                <div>
                    <select wire:model.live="visitType"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        @foreach($visitTypes as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Date Range Presets -->
            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Date Range</label>
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="setDatePreset('today')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'today' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Today
                    </button>
                    <button type="button" wire:click="setDatePreset('yesterday')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'yesterday' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Yesterday
                    </button>
                    <button type="button" wire:click="setDatePreset('this_week')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'this_week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        This Week
                    </button>
                    <button type="button" wire:click="setDatePreset('last_week')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'last_week' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Last Week
                    </button>
                    <button type="button" wire:click="setDatePreset('this_month')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'this_month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        This Month
                    </button>
                    <button type="button" wire:click="setDatePreset('last_month')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'last_month' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        Last Month
                    </button>
                    <button type="button" wire:click="setDatePreset('this_year')"
                        class="px-4 py-2 text-sm rounded-lg border transition-all
                                {{ $datePreset === 'this_year' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        This Year
                    </button>
                </div>
            </div>

            <!-- Custom Date Range -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From Date</label>
                    <input type="date" wire:model.live="dateFrom"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To Date</label>
                    <input type="date" wire:model.live="dateTo"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium">{{ $appointments->total() }}</span> appointments found
                </div>
                <div class="flex gap-3">
                    <button wire:click="resetFilters"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer group"
                            wire:click="sortBy('appointment_date')">
                            <div class="flex items-center gap-1">
                                Date
                                @if($sortField === 'appointment_date')
                                <span class="text-blue-600 dark:text-blue-400 ml-1">
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                                @else
                                <span
                                    class="text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity ml-1">↕</span>
                                @endif
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Time</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Patient</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Doctor</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Type</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Checked In</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($appointments as $appointment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="w-2 h-2 rounded-full {{ $appointment->appointment_date->isPast() ? 'bg-gray-400' : 'bg-green-500' }} mr-2">
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->appointment_date->format('M d, Y') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            {{ $appointment->appointment_time->format('h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div
                                    class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold mr-3">
                                    {{ substr($appointment->patient->first_name ?? 'N', 0, 1) }}{{
                                    substr($appointment->patient->last_name ?? 'A', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->first_name ?? '' }} {{
                                        $appointment->patient->last_name ?? '' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Card: {{ $appointment->patient->card_number ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            Dr. {{ $appointment->doctor->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 font-medium">
                                {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs rounded-full {{ $appointment->status_color }} font-medium">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($appointment->checked_in_at)
                            <span class="text-green-600 dark:text-green-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                {{ $appointment->checked_in_at->format('h:i A') }}
                            </span>
                            @else
                            <span class="text-gray-400 dark:text-gray-500">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            @if($appointment->doctor_notes)
                            <div class="text-sm text-gray-600 dark:text-gray-300 truncate group-hover:text-clip"
                                title="{{ $appointment->doctor_notes }}">
                                {{ $appointment->doctor_notes }}
                            </div>
                            @else
                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">No notes</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="p-4 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointments found
                                </h3>
                                <p class="text-gray-500 dark:text-gray-400 max-w-md text-center">
                                    No appointments match your current filters. Try adjusting your search criteria.
                                </p>
                                <button wire:click="resetFilters"
                                    class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                    Clear Filters
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($appointments->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>

    <!-- Summary Footer -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $appointments->total() }}</span> total
                    appointments
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-green-600 dark:text-green-400">{{ $stats['completed'] }}</span>
                    completed
                </span>
                <span class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-yellow-600 dark:text-yellow-400">{{ $stats['scheduled'] }}</span>
                    scheduled
                </span>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: {{ now()->format('M d, Y h:i A') }}
            </div>
        </div>
    </div>
</div>