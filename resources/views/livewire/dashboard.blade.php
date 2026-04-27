<div>
   <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header with Hospital Name --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-700 to-teal-700 rounded-2xl shadow-xl overflow-hidden">
                <div class="relative">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div class="relative px-6 py-8 md:py-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                            <div class="flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">{{ $hospitalName }}</h1>
                                    <p class="text-emerald-100 mt-1">Healthcare Management Dashboard</p>
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <div class="flex gap-3">
                                    <select wire:model.live="dateRange" class="px-4 py-2 rounded-lg bg-white/20 text-white border border-white/30 text-sm">
                                        <option value="today">Today</option>
                                        <option value="yesterday">Yesterday</option>
                                        <option value="this_week">This Week</option>
                                        <option value="this_month">This Month</option>
                                        <option value="custom">Custom Date</option>
                                    </select>
                                    @if($dateRange === 'custom')
                                        <input type="date" wire:model.live="selectedDate" class="px-4 py-2 rounded-lg bg-white/20 text-white border border-white/30">
                                    @endif
                                </div>
                                <div class="text-emerald-100 text-sm flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>Last updated: {{ $currentTime }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Summary Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Daily Sales Summary
                </h2>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ ucfirst($dateRange) }} Report
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3">
                <!-- Card Sales -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Card Fee</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($cardSales, 2) }}</p>
                </div>

                <!-- Pharmacy -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Pharmacy</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($pharmacySales, 2) }}</p>
                </div>

                <!-- Cupping -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Cupping</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($cuppingSales, 2) }}</p>
                </div>

                <!-- Rehab -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Rehab</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($rehabSales, 2) }}</p>
                </div>

                <!-- Bed -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-rose-100 dark:bg-rose-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Bed Charges</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($bedSales, 2) }}</p>
                </div>

                <!-- Radiology -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Radiology</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($radiologySales, 2) }}</p>
                </div>

                <!-- Laboratory -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg group-hover:scale-110 transition">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <span class="text-xs text-gray-400">Laboratory</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($labSales, 2) }}</p>
                </div>
            </div>

            {{-- Total Sales Banner --}}
            <div class="mt-4 bg-gradient-to-r from-emerald-600 to-teal-600 rounded-xl shadow-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-100">Total Revenue</p>
                        <p class="text-2xl font-bold text-white">ETB {{ number_format($totalSales, 2) }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="text-emerald-100 text-sm">All departments</div>
                        <div class="p-2 bg-white/20 rounded-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sales Trend Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Sales Trend (Last 7 Days)
                </h2>
            </div>
            <div class="p-6">
                <canvas id="salesTrendChart" class="w-full" style="height: 380px;"></canvas>
            </div>
        </div>

        {{-- Healthcare KPIs --}}
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Total Patients</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPatients) }}</p>
                        <p class="text-xs text-green-600">+{{ $newPatientsToday }} today</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H9.5V0h1v1.5zm-8 8h1.5v1H2.5v-1zm15 0h1.5v1H17.5v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Bed Occupancy</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $bedOccupancyRate }}%</p>
                        <p class="text-xs text-gray-500">{{ $occupiedBeds }}/{{ $totalBeds }} beds</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2 4 4 0 00-4 4v9a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 000 2 2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Pending Admissions</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $pendingAdmissions }}</p>
                        <p class="text-xs text-orange-600">Avg wait: 2.5 hrs</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Doctors On Duty</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $doctorsOnDuty }}</p>
                        <p class="text-xs text-green-600">✓ Fully staffed</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 17v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Surgeries Today</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $surgeriesToday }}</p>
                        <p class="text-xs text-red-600">4 in progress</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bed Class Breakdown --}}
        @if(count($bedClassStats) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                    Bed Class Occupancy
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($bedClassStats as $className => $stats)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $className }}</h3>
                                <span class="text-sm font-semibold text-emerald-600">{{ $stats['percentage'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-3">
                                <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $stats['percentage'] }}%"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Occupied: {{ $stats['occupied'] }}</span>
                                <span class="text-gray-500">Total: {{ $stats['total'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Department Status & Recent Transactions --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Department Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                    Department Status
                </h2>
                <div class="space-y-4">
                    @foreach($departmentStatus as $dept => $data)
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $dept }}</span>
                                <span class="text-sm font-semibold text-{{ $data['color'] }}-600">{{ $data['status'] }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-{{ $data['color'] }}-500 h-2 rounded-full" style="width: {{ $data['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Recent Transactions
                </h2>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($recentSales as $sale)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/50 transition">
                            <div class="flex items-center gap-3">
                                <div class="p-2 rounded-lg
                                    @if($sale->type == 'Card Fee') bg-blue-100 text-blue-600
                                    @elseif($sale->type == 'Pharmacy') bg-green-100 text-green-600
                                    @elseif($sale->type == 'Radiology') bg-cyan-100 text-cyan-600
                                    @elseif($sale->type == 'Laboratory') bg-indigo-100 text-indigo-600
                                    @else bg-purple-100 text-purple-600 @endif">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $sale->type }}</p>
                                    <p class="text-xs text-gray-500">{{ $sale->patient_name }}</p>
                                    <p class="text-xs text-gray-400">{{ $sale->date }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-green-600">ETB {{ number_format($sale->amount, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">No recent transactions</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Recent Patients --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Recently Registered Patients
                </h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-800/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Card #</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Patient Name</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Gender</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Registered</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($recentPatients as $patient)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-3 text-sm font-mono text-gray-900 dark:text-white">{{ $patient->card_number }}</td>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $patient->gender }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $patient->phone_number1 }}</td>
                                <td class="px-6 py-3 text-sm text-gray-500">{{ $patient->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">No patients found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Footer --}}
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center text-xs text-gray-500">
            <p>© {{ date('Y') }} Firdos Cultural Medical Center. All rights reserved.</p>
            <p>System Status: <span class="text-green-600 font-semibold">All Systems Operational</span></p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', function () {
        let chart = null;

        function initChart() {
            const salesData = @json($salesData);
            const dateLabels = @json($dateLabels);

            if (salesData && salesData.length > 0) {
                const ctx = document.getElementById('salesTrendChart');
                if (!ctx) return;

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: dateLabels,
                        datasets: [
                            { label: 'Card Fees', data: salesData.map(d => d.card), borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,0.1)', tension: 0.4, fill: true },
                            { label: 'Pharmacy', data: salesData.map(d => d.pharmacy), borderColor: '#22c55e', backgroundColor: 'rgba(34,197,94,0.1)', tension: 0.4, fill: true },
                            { label: 'Cupping', data: salesData.map(d => d.cupping), borderColor: '#a855f7', backgroundColor: 'rgba(168,85,247,0.1)', tension: 0.4, fill: true },
                            { label: 'Rehab', data: salesData.map(d => d.rehab), borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.1)', tension: 0.4, fill: true },
                            { label: 'Bed Charges', data: salesData.map(d => d.bed), borderColor: '#e11d48', backgroundColor: 'rgba(225,29,72,0.1)', tension: 0.4, fill: true },
                            { label: 'Radiology', data: salesData.map(d => d.radiology), borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,0.1)', tension: 0.4, fill: true },
                            { label: 'Laboratory', data: salesData.map(d => d.lab), borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,0.1)', tension: 0.4, fill: true },
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { callbacks: { label: (ctx) => `${ctx.dataset.label}: ETB ${ctx.parsed.y.toLocaleString()}` } }
                        },
                        scales: { y: { title: { display: true, text: 'Amount (ETB)' }, ticks: { callback: (val) => 'ETB ' + val.toLocaleString() } } }
                    }
                });
            }
        }

        initChart();
        Livewire.on('render', () => setTimeout(initChart, 100));
    });
</script>
</div>
