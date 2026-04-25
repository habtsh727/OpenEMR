<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Rehabilitation Finance Report</h1>
                            <p class="text-blue-100 mt-1">Track payments, collections, and financial performance</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <div class="flex items-center space-x-2 text-blue-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="flex gap-3">
                        <button wire:click="exportToExcel"
                            class="px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Excel
                        </button>
                        <button wire:click="exportToPDF"
                            class="px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 flex items-center gap-2 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            PDF
                        </button>
                    </div> --}}
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">ETB {{ number_format($totalAmount, 2) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Collected</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">ETB {{ number_format($totalPaid, 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Amount</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">ETB {{ number_format($totalPending, 2) }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Collection Rate</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">{{ $collectionRate }}%</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Statistics Row --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            {{-- Payment Status Breakdown --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Payment Status Breakdown
                </h3>
                <div class="space-y-3">
                    @foreach(['paid', 'partial', 'pending', 'overdue'] as $status)
                        @php $stats = $statsByStatus[$status] ?? ['count' => 0, 'amount' => 0]; @endphp
                        <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full
                                    @if($status === 'paid') bg-green-500
                                    @elseif($status === 'partial') bg-yellow-500
                                    @elseif($status === 'pending') bg-blue-500
                                    @else bg-red-500 @endif">
                                </span>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">{{ $status }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $stats['count'] }} orders)</span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">ETB {{ number_format($stats['amount'], 2) }}</div>
                                @if($status !== 'overdue' && $stats['amount'] > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Paid: ETB {{ number_format($stats['paid'] ?? 0, 2) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Payment Type Breakdown --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Payment Type Breakdown
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Full Payment</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $statsByPaymentType['full']['count'] }} orders)</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">ETB {{ number_format($statsByPaymentType['full']['amount'], 2) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Collected: ETB {{ number_format($statsByPaymentType['full']['paid'], 2) }}</div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Installment Plan</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">({{ $statsByPaymentType['installment']['count'] }} orders)</span>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-900 dark:text-white">ETB {{ number_format($statsByPaymentType['installment']['amount'], 2) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Collected: ETB {{ number_format($statsByPaymentType['installment']['paid'], 2) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Installment Stats Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 mb-8">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Installment Summary
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div class="text-center p-3 bg-gray-50 dark:bg-gray-700/30 rounded-lg">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $installmentStats['total_installments'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Total Installments</p>
                </div>
                <div class="text-center p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $installmentStats['paid_installments'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Paid</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $installmentStats['pending_installments'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                </div>
                <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $installmentStats['partial_installments'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Partial</p>
                </div>
                <div class="text-center p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $installmentStats['overdue_installments'] }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Overdue</p>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filters</h2>
                <button wire:click="$toggle('showFilters')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 text-sm">
                    {{ $showFilters ? 'Hide' : 'Show' }} Filters
                </button>
            </div>

            @if($showFilters)
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                        <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                        <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Status</label>
                        <select wire:model.live="paymentStatus" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="all">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="partial">Partial</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Type</label>
                        <select wire:model.live="paymentType" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="all">All Types</option>
                            <option value="full">Full Payment</option>
                            <option value="installment">Installment Plan</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 lg:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by patient name, card number, or order ID..."
                            class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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

        {{-- Orders Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" wire:click="sortBy('created_at')">
                                Order # @if($sortBy === 'created_at') <span class="text-blue-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" wire:click="sortBy('total_amount')">
                                Total @if($sortBy === 'total_amount') <span class="text-blue-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-600" wire:click="sortBy('paid_amount')">
                                Paid @if($sortBy === 'paid_amount') <span class="text-blue-500">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Pending</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($orders as $order)
                            @php
                                $pendingAmount = $order->total_amount - $order->paid_amount;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-4 py-3">
                                    <span class="font-mono font-medium text-gray-900 dark:text-white">#{{ $order->id }}</span>
                                 </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->encounter->encounter->patient->first_name ?? 'N/A' }} {{ $order->encounter->encounter->patient->last_name ?? '' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Card: {{ $order->encounter->encounter->patient->card_number ?? 'N/A' }}</div>
                                 </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">ETB {{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600 dark:text-green-400">ETB {{ number_format($order->paid_amount, 2) }}</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-red-600 dark:text-red-400">ETB {{ number_format($pendingAmount, 2) }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($order->payment_status === 'paid')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">Paid</span>
                                    @elseif($order->payment_status === 'partial')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">Partial</span>
                                    @elseif($order->payment_type === 'installment' && $order->paymentInstallments->where('due_date', '<', now())->where('status', 'pending')->isNotEmpty())
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">Overdue</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">Pending</span>
                                    @endif
                                 </td>
                             </tr>

                             {{-- Installment details for this order --}}
                             @if($order->paymentInstallments->isNotEmpty() && $order->payment_status !== 'paid')
                             <tr class="bg-gray-50 dark:bg-gray-800/50">
                                 <td colspan="7" class="px-4 py-2 pl-10">
                                     <div class="text-xs text-gray-500 dark:text-gray-400">
                                         <span class="font-medium">Installments:</span>
                                         @foreach($order->paymentInstallments as $installment)
                                             @php
                                                 $installmentStatus = $installment->status;
                                                 if ($installmentStatus === 'pending' && $installment->due_date < now()) {
                                                     $installmentStatus = 'overdue';
                                                 }
                                             @endphp
                                             <div class="inline-flex items-center gap-2 ml-4">
                                                 <span class="font-medium">#{{ $installment->installment_number }}</span>
                                                 <span>ETB {{ number_format($installment->amount, 2) }}</span>
                                                 <span class="text-green-600">Paid: ETB {{ number_format($installment->paid_amount, 2) }}</span>
                                                 <span class="text-xs">Due: {{ $installment->due_date->format('M d, Y') }}</span>
                                                 <span class="px-1.5 py-0.5 rounded-full text-xs
                                                     @if($installmentStatus === 'paid') bg-green-100 text-green-700
                                                     @elseif($installmentStatus === 'partial') bg-yellow-100 text-yellow-700
                                                     @elseif($installmentStatus === 'overdue') bg-red-100 text-red-700
                                                     @else bg-gray-100 text-gray-700 @endif">
                                                     {{ ucfirst($installmentStatus) }}
                                                 </span>
                                             </div>
                                         @endforeach
                                     </div>
                                 </td>
                             </tr>
                             @endif
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    No orders found
                                 </td>
                             </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
