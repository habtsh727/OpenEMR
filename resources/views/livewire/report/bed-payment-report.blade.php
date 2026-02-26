<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl shadow-xl p-6">
        <h1 class="text-2xl font-bold text-white">Bed Payment Report</h1>
        <p class="text-amber-100 mt-1">Track revenue and statistics for bed assignments</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Selections</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalSelections) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Paid Selections</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalPaidSelections) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bed Days</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalDays) }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg. Price/Day</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($averagePricePerDay, 2) }}</p>
                </div>
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Bed Class Breakdown -->
    @if($bedClassStats->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue by Bed Class</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($bedClassStats as $stat)
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="font-medium text-gray-900 dark:text-white">{{ $stat->bed_class_name }}</p>
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Selections:</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $stat->selection_count }}</p>
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total Days:</span>
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $stat->total_days }}</p>
                    </div>
                </div>
                <div class="flex justify-between mt-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Revenue:</span>
                    <span class="text-sm font-bold text-amber-600 dark:text-amber-400">ETB {{ number_format($stat->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between mt-1">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Avg Price/Day:</span>
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($stat->avg_price_per_day, 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Payment Method & Doctor Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Method Breakdown -->
        @if(count($paymentMethodStats) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Methods</h3>
            <div class="space-y-3">
                @foreach(['cash', 'card', 'insurance'] as $method)
                    @php
                        $stats = $paymentMethodStats[$method] ?? null;
                        if (!$stats) continue;
                    @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $method }}</span>
                            <span class="text-sm px-2 py-1 rounded-full 
                                @if($method === 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($method === 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                {{ $stats->bed_count }} beds
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-2">
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Orders:</span>
                                <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $stats->order_count }}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Revenue:</span>
                                <p class="text-sm font-bold text-amber-600 dark:text-amber-400">ETB {{ number_format($stats->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Doctor Breakdown -->
        @if($doctorStats->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Doctors by Bed Revenue</h3>
            <div class="space-y-3">
                @foreach($doctorStats as $stat)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <span class="font-medium text-gray-900 dark:text-white">Dr. {{ $stat->doctor_name }}</span>
                        <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-full">
                            {{ $stat->bed_count }} beds
                        </span>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-2 text-sm">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Orders:</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $stat->order_count }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Days:</span>
                            <p class="font-bold text-gray-900 dark:text-white">{{ $stat->total_days }}</p>
                        </div>
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Revenue:</span>
                            <p class="font-bold text-amber-600 dark:text-amber-400">ETB {{ number_format($stat->total_amount, 2) }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Daily Statistics -->
    @if($dailyStats->count() > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Bed Revenue (Last 30 Days)</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300">Date</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300">Selections</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300">Bed Days</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-300">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($dailyStats as $stat)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($stat->date)->format('M d, Y') }}</td>
                        <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-white">{{ $stat->selection_count }}</td>
                        <td class="px-4 py-2 text-sm text-right text-gray-900 dark:text-white">{{ $stat->total_days }}</td>
                        <td class="px-4 py-2 text-sm text-right font-medium text-amber-600 dark:text-amber-400">ETB {{ number_format($stat->total_amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h2>
            <button wire:click="$toggle('showFilters')" class="text-amber-600 dark:text-amber-400 hover:text-amber-800 text-sm">
                {{ $showFilters ? 'Hide' : 'Show' }} Filters
            </button>
        </div>
        
        @if($showFilters)
        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Bed Class -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bed Class</label>
                    <select wire:model.live="bedClassId" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Classes</option>
                        @foreach($bedClasses as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Method -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select wire:model.live="paymentMethod" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="insurance">Insurance</option>
                    </select>
                </div>

                <!-- Selection Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Selection Status</label>
                    <select wire:model.live="status" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Status</option>
                        <option value="selected">Selected</option>
                        <option value="completed">Completed (Paid)</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Doctor -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                    <select wire:model.live="doctorId" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="resetFilters" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                    Reset Filters
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Bed Selections Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Doctor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bed Class</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Bed Details</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Duration</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Price/Day</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payment</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($selections as $selection)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">{{ $selection->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $selection->rehabEncounter->encounter->patient->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">MRN: {{ $selection->rehabEncounter->encounter->patient->medical_record_number ?? 'N/A' }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">Dr. {{ $selection->rehabOrder->doctor->name ?? 'N/A' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 rounded-full">
                                {{ $selection->bedClass->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            @if($selection->bed)
                                <div class="text-sm">
                                    <span class="text-gray-900 dark:text-white">{{ $selection->bed->bed_number }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">
                                        {{ $selection->bed->room->room_number ?? '' }} - {{ $selection->bed->room->ward->name ?? '' }}
                                    </span>
                                </div>
                            @else
                                <span class="text-xs text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $selection->duration_days }} days</td>
                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">ETB {{ number_format($selection->price_per_day, 2) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-amber-600 dark:text-amber-400">ETB {{ number_format($selection->total_price, 2) }}</td>
                        <td class="px-4 py-3">
                            @if($selection->rehabOrder && $selection->rehabOrder->payment_method)
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($selection->rehabOrder->payment_method === 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($selection->rehabOrder->payment_method === 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                    {{ ucfirst($selection->rehabOrder->payment_method) }}
                                </span>
                                @if($selection->rehabOrder->paid_at)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $selection->rehabOrder->paid_at->format('Y-m-d') }}
                                    </div>
                                @endif
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $this->getStatusBadgeClass($selection->status) }}">
                                {{ ucfirst($selection->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No bed selections found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $selections->links() }}
        </div>
    </div>
</div>