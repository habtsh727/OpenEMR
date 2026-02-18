<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Payments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalPayments) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Amount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($totalAmount, 2) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Payment</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ $totalPayments > 0 ? number_format($totalAmount / $totalPayments, 2) : '0.00' }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Type Breakdown -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach(['cash', 'bank', 'telebirr', 'insurance'] as $type)
            @php
                $stats = $paymentTypeStats[$type] ?? ['count' => 0, 'total' => 0];
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 capitalize">{{ $type }}</p>
                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($stats['count'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">ETB {{ number_format($stats['total'] ?? 0, 2) }}</p>
            </div>
        @endforeach
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h2>
            <button wire:click="$toggle('showFilters')" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 text-sm">
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

                <!-- Payment Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Type</label>
                    <select wire:model.live="paymentType" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Types</option>
                        <option value="cash">Cash</option>
                        <option value="bank">Bank</option>
                        <option value="telebirr">Telebirr</option>
                        <option value="insurance">Insurance</option>
                    </select>
                </div>

                <!-- Processed By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Processed By</label>
                    <select wire:model.live="processedBy" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Users</option>
                        @foreach($processors as $processor)
                            <option value="{{ $processor->id }}">{{ $processor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select wire:model.live="status" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
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

    <!-- Export Buttons -->
    <div class="flex justify-end gap-3">
        <button wire:click="exportToExcel" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export Excel
        </button>
        <button wire:click="exportToPDF" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            Export PDF
        </button>
    </div>

    <!-- Payments Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Processed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->patient->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $payment->patient->id ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($payment->amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($payment->payment_type === 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @elseif($payment->payment_type === 'bank') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                @elseif($payment->payment_type === 'telebirr') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                {{ ucfirst($payment->payment_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($payment->is_paid) bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                {{ $payment->is_paid ? 'Paid' : 'Unpaid' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $payment->processor->name ?? 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No payments found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $payments->links() }}
        </div>
    </div>
</div>