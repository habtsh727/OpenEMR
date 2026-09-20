<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200" 
     x-data="{ expandedSections: @entangle('expandedSections') }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Gradient -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-800 dark:via-purple-800 dark:to-pink-800 rounded-2xl shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                
                <div class="relative p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('cashier.rehab.payments') }}" wire:navigate 
                               class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group border border-white/30">
                                <svg class="w-6 h-6 text-white group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Payment Reports</h1>
                                <p class="text-indigo-100 dark:text-indigo-200 mt-1">Full & Installment Payment Analysis</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <button wire:click="expandAll" 
                                    class="p-2 bg-white/20 backdrop-blur-xl rounded-lg hover:bg-white/30 transition-all duration-200 text-white"
                                    title="Expand All">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </button>
                            <button wire:click="collapseAll" 
                                    class="p-2 bg-white/20 backdrop-blur-xl rounded-lg hover:bg-white/30 transition-all duration-200 text-white"
                                    title="Collapse All">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4M4 4v16M20 4v16"></path>
                                </svg>
                            </button>
                            <button wire:click="exportToCsv" 
                                    class="px-4 py-2 bg-white/20 backdrop-blur-xl text-white rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2 border border-white/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Export CSV
                            </button>
                        </div>
                    </div>

                    <!-- Quick Stats Pills -->
                    <div class="flex flex-wrap gap-3 mt-6">
                        <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                            <span class="text-white/70 text-sm">Date Range:</span>
                            <span class="text-white font-medium ml-2">{{ $dateFrom }} to {{ $dateTo }}</span>
                        </div>
                        @if($selectedPatient)
                        <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                            <span class="text-white/70 text-sm">Patient:</span>
                            <span class="text-white font-medium ml-2">{{ collect($patients)->firstWhere('id', $selectedPatient)['name'] ?? 'Selected' }}</span>
                        </div>
                        @endif
                        @if($selectedDoctor)
                        <div class="px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20">
                            <span class="text-white/70 text-sm">Doctor:</span>
                            <span class="text-white font-medium ml-2">{{ collect($doctors)->firstWhere('id', $selectedDoctor)['name'] ?? 'Selected' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8 transition-all duration-200 hover:shadow-xl">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2.5 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Reports</h2>
                <div class="flex-1 text-right">
                    <button wire:click="$set('selectedPatient', ''); $set('selectedDoctor', ''); $set('paymentTypeFilter', 'all'); $set('paymentStatusFilter', 'all')" 
                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors flex items-center gap-1 inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset All Filters
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Date Range -->
                <div class="lg:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">From</label>
                    <input type="date" wire:model.live="dateFrom" 
                           class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>
                <div class="lg:col-span-1">
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">To</label>
                    <input type="date" wire:model.live="dateTo" 
                           class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                </div>

                <!-- Payment Type Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Payment Type</label>
                    <select wire:model.live="paymentTypeFilter" 
                            class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="all">All Payments</option>
                        <option value="full">💵 Full Payments Only</option>
                        <option value="installment">📊 Installment Payments</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</label>
                    <select wire:model.live="paymentStatusFilter" 
                            class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="all">All Status</option>
                        <option value="paid">✅ Paid</option>
                        <option value="partial">🟡 Partial</option>
                        <option value="pending">⏳ Pending</option>
                        <option value="overdue">⚠️ Overdue</option>
                    </select>
                </div>

                <!-- Patient Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Patient</label>
                    <select wire:model.live="selectedPatient" 
                            class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Filter -->
                <div>
                    <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Doctor</label>
                    <select wire:model.live="selectedDoctor" 
                            class="w-full px-3 py-2.5 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <!-- Total Collected -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-xl hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Collected</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($totalCollected, 2) }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $totalInstallments }} installments paid</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Full Payments -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-xl hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Full Payments</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $fullPaymentsCount }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">One-time payments</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Installment Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-xl hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Installment Orders</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $installmentOrdersCount }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $totalInstallments }} installments paid</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-xl hover:scale-105">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Orders</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">All rehab orders</p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-gray-500 to-gray-700 rounded-2xl shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <!-- Pending -->
            <div class="bg-gradient-to-br from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-2xl p-6 border border-yellow-200 dark:border-yellow-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300">Pending</h3>
                        </div>
                        <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-400">{{ $pendingCount }}</p>
                        <p class="text-sm text-yellow-600 dark:text-yellow-500 mt-1">Awaiting payment</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-500">{{ $pendingCount > 0 ? round(($pendingCount / max(1, $pendingCount + $partialCount + $overdueCount)) * 100, 1) : 0 }}%</div>
                        <p class="text-xs text-yellow-600 dark:text-yellow-500">of outstanding</p>
                    </div>
                </div>
            </div>

            <!-- Partial -->
            <div class="bg-gradient-to-br from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 rounded-2xl p-6 border border-orange-200 dark:border-orange-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-orange-800 dark:text-orange-300">Partial</h3>
                        </div>
                        <p class="text-3xl font-bold text-orange-700 dark:text-orange-400">{{ $partialCount }}</p>
                        <p class="text-sm text-orange-600 dark:text-orange-500 mt-1">Partially paid</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-orange-600 dark:text-orange-500">{{ $partialCount > 0 ? round(($partialCount / max(1, $pendingCount + $partialCount + $overdueCount)) * 100, 1) : 0 }}%</div>
                        <p class="text-xs text-orange-600 dark:text-orange-500">of outstanding</p>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl p-6 border border-red-200 dark:border-red-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-red-800 dark:text-red-300">Overdue</h3>
                        </div>
                        <p class="text-3xl font-bold text-red-700 dark:text-red-400">{{ $overdueCount }}</p>
                        <p class="text-sm text-red-600 dark:text-red-500 mt-1">Past due date</p>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-red-600 dark:text-red-500">{{ $overdueCount > 0 ? round(($overdueCount / max(1, $pendingCount + $partialCount + $overdueCount)) * 100, 1) : 0 }}%</div>
                        <p class="text-xs text-red-600 dark:text-red-500">of outstanding</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method Distribution -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
            <!-- Cash Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Cash Payments</h4>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">ETB {{ number_format($paymentMethodSummary['cash'] ?? 0, 2) }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ array_sum($paymentMethodSummary) > 0 ? (($paymentMethodSummary['cash'] ?? 0) / array_sum($paymentMethodSummary)) * 100 : 0 }}%"></div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ array_sum($paymentMethodSummary) > 0 ? round((($paymentMethodSummary['cash'] ?? 0) / array_sum($paymentMethodSummary)) * 100, 1) : 0 }}% of total</p>
            </div>

            <!-- Card Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Card Payments</h4>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">ETB {{ number_format($paymentMethodSummary['card'] ?? 0, 2) }}</p>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ array_sum($paymentMethodSummary) > 0 ? (($paymentMethodSummary['card'] ?? 0) / array_sum($paymentMethodSummary)) * 100 : 0 }}%"></div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ array_sum($paymentMethodSummary) > 0 ? round((($paymentMethodSummary['card'] ?? 0) / array_sum($paymentMethodSummary)) * 100, 1) : 0 }}% of total</p>
            </div>
        </div>

        <!-- Daily Summary Table - Collapsible -->
        @if(count($dailyTotals) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('daily')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400 transform transition-transform duration-300 {{ $expandedSections['daily'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daily Collection Summary</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($dailyTotals) }} days</span>
                </div>
            </div>
            
            @if($expandedSections['daily'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Payments</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Installment Payments</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-indigo-50/50 dark:bg-indigo-900/10">Daily Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($dailyTotals as $daily)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $daily['label'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400 font-medium">ETB {{ number_format($daily['full'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-purple-600 dark:text-purple-400 font-medium">ETB {{ number_format($daily['installment'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/10">ETB {{ number_format($daily['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Total</td>
                            <td class="px-6 py-4 text-sm text-right text-green-600 dark:text-green-400">ETB {{ number_format(collect($dailyTotals)->sum('full'), 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-purple-600 dark:text-purple-400">ETB {{ number_format(collect($dailyTotals)->sum('installment'), 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format(collect($dailyTotals)->sum('total'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        @endif

        <!-- Monthly Summary Table - Collapsible -->
        @if(count($monthlyTotals) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('monthly')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 transform transition-transform duration-300 {{ $expandedSections['monthly'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Collection Summary</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">Last 6 months</span>
                </div>
            </div>
            
            @if($expandedSections['monthly'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Month</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full Payments</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Installment Payments</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-purple-50/50 dark:bg-purple-900/10">Monthly Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($monthlyTotals as $monthly)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $monthly['label'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400 font-medium">ETB {{ number_format($monthly['full'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-purple-600 dark:text-purple-400 font-medium">ETB {{ number_format($monthly['installment'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-indigo-600 dark:text-indigo-400 bg-purple-50/50 dark:bg-purple-900/10">ETB {{ number_format($monthly['total'], 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700 font-medium">
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">Total</td>
                            <td class="px-6 py-4 text-sm text-right text-green-600 dark:text-green-400">ETB {{ number_format(collect($monthlyTotals)->sum('full'), 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right text-purple-600 dark:text-purple-400">ETB {{ number_format(collect($monthlyTotals)->sum('installment'), 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format(collect($monthlyTotals)->sum('total'), 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>
        @endif

        <!-- Full Payments Table - Collapsible -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('full_payments')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 transform transition-transform duration-300 {{ $expandedSections['full_payments'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Full Payments</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $fullPayments->total() }} payments</span>
                </div>
            </div>
            
            @if($expandedSections['full_payments'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($fullPayments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $payment->paid_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-600 dark:text-indigo-400 font-medium">#{{ $payment->rehab_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-400 to-purple-400 flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr($payment->rehabOrder->encounter->encounter->patient->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">ETB {{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($payment->rehabOrder->payment_method === 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($payment->rehabOrder->payment_method === 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($payment->rehabOrder->payment_method === 'insurance') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($payment->rehabOrder->payment_method === 'bank_transfer') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $payment->rehabOrder->payment_method ? ucfirst($payment->rehabOrder->payment_method) : 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>No full payments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($fullPayments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $fullPayments->links() }}
            </div>
            @endif
            @endif
        </div>

        <!-- Installment Payments Table - Collapsible -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('installment_payments')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 transform transition-transform duration-300 {{ $expandedSections['installment_payments'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Installment Payments</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $installmentPayments->total() }} payments</span>
                </div>
            </div>
            
            @if($expandedSections['installment_payments'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inst #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($installmentPayments as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $payment->paid_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-600 dark:text-indigo-400 font-medium">#{{ $payment->rehab_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">#{{ $payment->installment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-400 to-purple-400 flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr($payment->rehabOrder->encounter->encounter->patient->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-purple-600 dark:text-purple-400">ETB {{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($payment->rehabOrder->payment_method === 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($payment->rehabOrder->payment_method === 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($payment->rehabOrder->payment_method === 'insurance') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($payment->rehabOrder->payment_method === 'bank_transfer') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $payment->rehabOrder->payment_method ? ucfirst($payment->rehabOrder->payment_method) : 'N/A' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p>No installment payments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($installmentPayments->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $installmentPayments->links() }}
            </div>
            @endif
            @endif
        </div>

        <!-- Outstanding Installments Table - Collapsible -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('outstanding')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 transform transition-transform duration-300 {{ $expandedSections['outstanding'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Outstanding Installments</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $outstandingReport->total() }} pending</span>
                </div>
            </div>
            
            @if($expandedSections['outstanding'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Inst</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remaining</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($outstandingReport as $installment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm {{ $installment->due_date->isPast() ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-900 dark:text-white' }}">
                                    {{ $installment->due_date->format('M d, Y') }}
                                    @if($installment->due_date->isPast())
                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Overdue
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-indigo-600 dark:text-indigo-400 font-medium">#{{ $installment->rehab_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">#{{ $installment->installment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-400 to-purple-400 flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr($installment->rehabOrder->encounter->encounter->patient->name ?? '?', 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $installment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900 dark:text-white">ETB {{ number_format($installment->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-green-600 dark:text-green-400">ETB {{ number_format($installment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $installment->getRemainingAmount() > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }} font-medium">
                                ETB {{ number_format($installment->getRemainingAmount(), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'partial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    ];
                                    $statusClass = $statusClasses[$installment->status] ?? $statusClasses['pending'];
                                @endphp
                                <span class="px-3 py-1 text-xs rounded-full font-medium {{ $statusClass }}">
                                    {{ ucfirst($installment->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p>No outstanding installments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($outstandingReport->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $outstandingReport->links() }}
            </div>
            @endif
            @endif
        </div>

        <!-- Patient Summary Table - Collapsible -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-pink-50 to-rose-50 dark:from-pink-900/20 dark:to-rose-900/20 border-b border-gray-200 dark:border-gray-700 cursor-pointer hover:bg-opacity-80 transition-colors"
                 wire:click="toggleSection('patient_summary')">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-pink-600 dark:text-pink-400 transform transition-transform duration-300 {{ $expandedSections['patient_summary'] ? 'rotate-90' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Patient Payment Summary</h3>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ count($patientSummary) }} patients</span>
                </div>
            </div>
            
            @if($expandedSections['patient_summary'])
            <div class="overflow-x-auto transition-all duration-300">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Full/Inst</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Installments</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Paid</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Due</th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($patientSummary as $summary)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-9 w-9 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-medium text-sm shadow-lg">
                                        {{ substr($summary['patient_name'], 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $summary['patient_name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-white">{{ $summary['total_orders'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="text-green-600 dark:text-green-400 font-medium">{{ $summary['full_payments'] }}</span>
                                <span class="text-gray-400 dark:text-gray-600 mx-1">/</span>
                                <span class="text-purple-600 dark:text-purple-400 font-medium">{{ $summary['installment_orders'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <span class="text-green-600 dark:text-green-400 font-medium">{{ $summary['paid_installments'] }}</span>
                                    <span class="text-gray-400 dark:text-gray-600">/</span>
                                    <span class="text-gray-900 dark:text-white">{{ $summary['total_installments'] }}</span>
                                    @if($summary['overdue_installments'] > 0)
                                        <span class="ml-1 text-xs text-red-600 dark:text-red-400 font-medium">({{ $summary['overdue_installments'] }} overdue)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-green-600 dark:text-green-400">ETB {{ number_format($summary['total_paid'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right {{ $summary['total_due'] > 0 ? 'text-orange-600 dark:text-orange-400 font-medium' : 'text-green-600 dark:text-green-400' }}">
                                ETB {{ number_format($summary['total_due'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 text-xs rounded-full font-medium {{ $summary['total_due'] > 0 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ $summary['status'] }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p>No patient data found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>