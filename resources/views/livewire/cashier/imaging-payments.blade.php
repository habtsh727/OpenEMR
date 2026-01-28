<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-100 dark:bg-blue-900/30">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Imaging Payments
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                Manage pending imaging payments and billing
                            </p>
                        </div>
                    </div>
                </div>
                
                {{-- Summary Stats --}}
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $summaryStats['total'] }}</div>
                        <div class="text-sm font-medium text-blue-500 dark:text-blue-300">Pending Orders</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">ETB{{ number_format($summaryStats['total_amount'], 2) }}</div>
                        <div class="text-sm font-medium text-green-500 dark:text-green-300">Total Amount</div>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 border border-orange-100 dark:border-orange-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ $summaryStats['urgent'] }}</div>
                        <div class="text-sm font-medium text-orange-500 dark:text-orange-300">Urgent Orders</div>
                    </div>
                </div>
            </div>
            
            {{-- Filters --}}
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                   placeholder="Search by patient name, phone, or order ID..." 
                                   class="pl-10 pr-4 py-2.5 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        {{-- Priority Filter --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Priority:</span>
                            <select wire:model.live="selectedPriority" 
                                    class="border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white px-3 py-2 text-sm transition-all duration-200">
                                @foreach($priorityOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range Filter --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Date:</span>
                            <select wire:model.live="dateRange" 
                                    class="border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white px-3 py-2 text-sm transition-all duration-200">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pending Orders Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Pending Imaging Payments</h2>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $pendingOrders->count() }} order(s) • ETB{{ number_format($summaryStats['total_amount'], 2) }} total
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($pendingOrders->count())
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Order Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Patient Info
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Service Details
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Payment
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pendingOrders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    Order #{{ $order->id }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Ordered: {{ $order->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ $order->encounter->patient->first_name ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                @if($order->encounter->patient->date_of_birth ?? false)
                                                    {{ \Carbon\Carbon::parse($order->encounter->patient->date_of_birth)->age }} yrs
                                                @endif
                                                @if($order->encounter->patient->gender ?? false)
                                                    • {{ ucfirst($order->encounter->patient->gender) }}
                                                @endif
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $order->encounter->patient->phone_number1 ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                    {{ $order->imagingType->name }}
                                                </span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $order->bodyPart->name }}
                                                </span>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Priority:</span>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $order->priority == 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                                                       ($order->priority == 'stat' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300' : 
                                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                                    {{ ucfirst($order->priority) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="space-y-2">
                                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                                {{ number_format($order->amount, 2) }}Birr
                                            </div>
                                            <div class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Due: {{ $order->created_at->addDays(30)->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2">
                                            <button wire:click="markAsPaid({{ $order->id }})" 
                                                    wire:confirm="Mark order #{{ $order->id }} as paid for ${{ number_format($order->amount, 2) }}?"
                                                    class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                Mark as Paid
                                            </button>
                                            <div class="flex space-x-2">
                                                <button wire:click="showPaymentModal({{ $order->id }})" 
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9a2 2 0 10-4 0v5a2 2 0 01-2 2h6m-6-4h4m8 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Process
                                                </button>
                                                <button 
                                                        class="flex-1 inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 text-xs">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    Receipt
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-green-100 dark:bg-green-900/30 mb-4">
                            <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">All payments processed!</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                            {{ $search || $selectedPriority || $dateRange !== 'all' ? 'No pending payments match your filters' : 'No pending imaging payments at the moment' }}
                        </p>
                        @if($search || $selectedPriority || $dateRange !== 'all')
                            <button wire:click="$set(['search' => '', 'selectedPriority' => '', 'dateRange' => 'all'])" 
                                    class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Clear filters
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        {{-- Quick Stats Footer --}}
        @if($pendingOrders->count())
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Average Amount</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ number_format($pendingOrders->avg('amount') ?? 0, 2) }}Birr
                            </p>
                        </div>
                        <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Urgent Priority Amount</p>
                            <p class="text-xl font-semibold text-orange-600 dark:text-orange-400">
                                {{ number_format($summaryStats['urgent_amount'], 2) }}Birr
                            </p>
                        </div>
                        <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.346 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Oldest Pending</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $pendingOrders->max('created_at')?->diffForHumans() ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>