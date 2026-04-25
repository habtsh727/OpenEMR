<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
        <h1 class="text-2xl font-bold text-white">Pharmacy Payment Report</h1>
        <p class="text-blue-100 mt-1">Track payments for prescription and walk-in orders</p>
    </div>

    <!-- Tab Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="flex border-b border-gray-200 dark:border-gray-700">
            <button wire:click="$set('reportTab', 'prescription')"
                class="px-6 py-3 text-sm font-medium transition-all duration-200 {{ $reportTab === 'prescription' ? 'text-blue-600 border-b-2 border-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Prescription Orders
                </div>
            </button>
            <button wire:click="$set('reportTab', 'walkin')"
                class="px-6 py-3 text-sm font-medium transition-all duration-200 {{ $reportTab === 'walkin' ? 'text-green-600 border-b-2 border-green-600 dark:text-green-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Walk-in Orders (OTC)
                </div>
            </button>
        </div>
    </div>

    <!-- PRESCRIPTION ORDERS TAB -->
    @if($reportTab === 'prescription')
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Payments</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($totalPayments, 2) }}</p>
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
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Discount</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($totalDiscount, 2) }}</p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average per Order</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ $totalOrders > 0 ? number_format($totalPayments / $totalOrders, 2) : '0.00' }}</p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Medication Type Breakdown -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Standard Medications</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($medicationTypeStats['standard']['count'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-500">ETB {{ number_format($medicationTypeStats['standard']['total'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Compound Medications</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($medicationTypeStats['compound']['count'] ?? 0) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-500">ETB {{ number_format($medicationTypeStats['compound']['total'] ?? 0, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Method Breakdown -->
    @if(count($paymentMethodStats) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Method Breakdown</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach(['cash', 'card', 'insurance'] as $method)
                @php $stats = $paymentMethodStats[$method] ?? ['count' => 0, 'total' => 0]; @endphp
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $method }}</p>
                    <div class="flex justify-between mt-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Transactions:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $stats['count'] }}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($stats['total'] ?? 0, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Type</label>
                    <select wire:model.live="orderType" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Types</option>
                        <option value="internal">Internal</option>
                        <option value="external">External</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medication Type</label>
                    <select wire:model.live="medicationType" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Medications</option>
                        <option value="standard">Standard Only</option>
                        <option value="compound">Compound Only</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select wire:model.live="paymentMethod" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="insurance">Insurance</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Status</label>
                    <select wire:model.live="status" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Status</option>
                        <option value="draft">Draft</option>
                        <option value="ordered">Ordered</option>
                        <option value="paid">Paid</option>
                        <option value="dispensed">Dispensed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cashier</label>
                    <select wire:model.live="cashierId" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Cashiers</option>
                        @foreach($cashiers as $cashier)
                            <option value="{{ $cashier->id }}">{{ $cashier->name }}</option>
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

    <!-- Prescription Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Medications</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cashier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($prescriptionOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">#{{ $order->id }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $order->encounter->patient->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            @php
                                $standardCount = $order->items->whereNotNull('drug_id')->count();
                                $compoundCount = $order->items->whereNotNull('custom_medication_id')->count();
                            @endphp
                            <div class="flex flex-wrap gap-1">
                                @if($standardCount > 0)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">{{ $standardCount }} Standard</span>
                                @endif
                                @if($compoundCount > 0)
                                    <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded-full">{{ $compoundCount }} Compound</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-700">ETB {{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-bold text-indigo-600">ETB {{ number_format($order->payable_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($order->payments->isNotEmpty())
                                @foreach($order->payments as $payment)
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ ucfirst($payment->payment_method) }}</span>
                                    <div class="text-xs text-gray-500 mt-1">ETB {{ number_format($payment->amount, 2) }}</div>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500">No payment</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->status === 'paid') bg-green-100 text-green-800
                                @elseif($order->status === 'dispensed') bg-blue-100 text-blue-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $order->payments->first()->cashier->name ?? 'N/A' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">No prescription orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $prescriptionOrders->links() }}
        </div>
    </div>
    @endif

    <!-- WALK-IN ORDERS TAB -->
    @if($reportTab === 'walkin')
    <!-- Walk-in Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($walkinTotalOrders) }}</p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($walkinTotalRevenue, 2) }}</p>
                </div>
                <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Items Sold</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($walkinTotalItems) }}</p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Order</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{ number_format($walkinAvgOrderValue, 2) }}</p>
                </div>
                <div class="p-3 bg-amber-100 dark:bg-amber-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Walk-in Payment Method Breakdown -->
    @if(count($walkinPaymentMethodStats) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Method Breakdown</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach(['cash', 'mobile_money', 'card', 'bank_transfer'] as $method)
                @php $stats = $walkinPaymentMethodStats[$method] ?? ['count' => 0, 'total' => 0]; @endphp
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                    <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $method === 'mobile_money' ? 'Mobile Money' : ucfirst($method) }}</p>
                    <div class="flex justify-between mt-2">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Transactions:</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $stats['count'] }}</span>
                    </div>
                    <div class="flex justify-between mt-1">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                        <span class="text-sm font-bold text-green-600 dark:text-green-400">ETB {{ number_format($stats['total'] ?? 0, 2) }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Walk-in Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Walk-in Order Filters</h2>
        </div>
        <div class="p-4 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input type="date" wire:model.live="walkinDateFrom" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input type="date" wire:model.live="walkinDateTo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Order Status</label>
                    <select wire:model.live="walkinStatus" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Status</option>
                        <option value="pending_payment">Pending Payment</option>
                        <option value="paid">Paid</option>
                        <option value="dispensed">Dispensed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select wire:model.live="walkinPaymentMethod" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="card">Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Staff / Cashier</label>
                    <select wire:model.live="walkinCashierId" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">All Staff</option>
                        @foreach($walkinCashiers as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" wire:model.live="walkinSearch" placeholder="Order #, Customer, Phone..." class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="flex justify-end">
                <button wire:click="resetWalkinFilters" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                    Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Walk-in Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Customer</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Items</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Payment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Processed By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($walkinOrders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $order->created_at->format('Y-m-d') }}<br><span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span></td>
                        <td class="px-6 py-4 text-sm font-mono font-medium text-gray-900 dark:text-white">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                            @if($order->customer_phone)<div class="text-xs text-gray-500">{{ $order->customer_phone }}</div>@endif
                        </td>
                        <td class="px-6 py-4 text-right text-sm text-gray-700">{{ $order->items->sum('quantity') }} items</td>
                        <td class="px-6 py-4 text-right text-sm font-semibold text-green-600 dark:text-green-400">ETB {{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($order->payments->isNotEmpty())
                                @foreach($order->payments as $payment)
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($payment->payment_method === 'cash') bg-green-100 text-green-800
                                        @elseif($payment->payment_method === 'mobile_money') bg-purple-100 text-purple-800
                                        @elseif($payment->payment_method === 'card') bg-blue-100 text-blue-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $payment->payment_method === 'mobile_money' ? 'Mobile Money' : ucfirst($payment->payment_method) }}
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">ETB {{ number_format($payment->amount, 2) }}</div>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-500">No payment</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($order->status === 'dispensed') bg-green-100 text-green-800
                                @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                @elseif($order->status === 'pending_payment') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $order->status === 'pending_payment' ? 'Pending' : ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">
                            @if($order->dispenser)
                                {{ $order->dispenser->name }} <span class="text-xs text-gray-500">(Dispensed)</span>
                            @elseif($order->cashier)
                                {{ $order->cashier->name }} <span class="text-xs text-gray-500">(Paid)</span>
                            @else
                                {{ $order->creator->name ?? 'N/A' }}
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">No walk-in orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $walkinOrders->links() }}
        </div>
    </div>
    @endif
</div>
