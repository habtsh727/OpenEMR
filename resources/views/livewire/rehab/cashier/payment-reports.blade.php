{{-- resources/views/livewire/rehab/cashier/payment-reports.blade.php --}}

<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Gradient -->
        <div class="mb-6">
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-800 dark:via-purple-800 dark:to-pink-800 rounded-2xl shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                
                <div class="relative p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <a href="{{ route('cashier.rehab.payments') }}" wire:navigate 
                               class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </a>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center gap-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    Payment Reports
                                </h1>
                                <p class="text-indigo-100 dark:text-indigo-200 mt-1">View and analyze payment data</p>
                            </div>
                        </div>
                        <button wire:click="exportToCsv" 
                                class="px-4 py-2 bg-white/20 backdrop-blur-xl text-white rounded-xl hover:bg-white/30 transition-all duration-200 flex items-center gap-2 border border-white/30">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 transition-colors duration-200">
            <div class="flex items-center gap-2 mb-4">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Filter Reports</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</label>
                    <select wire:model.live="reportType" 
                            class="w-full p-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="daily">📊 Daily Collection</option>
                        <option value="monthly">📈 Monthly Summary</option>
                        <option value="patient">👤 Patient Summary</option>
                        <option value="installment">💳 Installment Status</option>
                        <option value="method">💵 Payment Methods</option>
                        <option value="doctor">👨‍⚕️ Doctor Performance</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">From</label>
                    <input type="date" wire:model.live="dateFrom" 
                           class="w-full p-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">To</label>
                    <input type="date" wire:model.live="dateTo" 
                           class="w-full p-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                </div>

                <!-- Patient Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient</label>
                    <select wire:model.live="selectedPatient" 
                            class="w-full p-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor</label>
                    <select wire:model.live="selectedDoctor" 
                            class="w-full p-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Filters -->
                <div class="flex items-end">
                    <button wire:click="$set('selectedPatient', ''); $set('selectedDoctor', '')" 
                            class="w-full px-4 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <!-- Total Collected -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 transition-colors duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Collected</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($totalCollected, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 transition-colors duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Orders</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <!-- Installments Paid -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 transition-colors duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Installments Paid</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalInstallments }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 transition-colors duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pending</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <!-- Overdue -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 transition-colors duration-200 hover:shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Overdue</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">{{ $overdueCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Card -->
        @if(count($chartLabels) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6 transition-colors duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        @switch($reportType)
                            @case('daily') Daily Collection Trends @break
                            @case('monthly') Monthly Performance Overview @break
                            @case('method') Payment Method Distribution @break
                            @case('doctor') Top Performing Doctors @break
                            @default Payment Analytics
                        @endswitch
                    </h3>
                </div>
                <div class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded-full text-xs text-gray-600 dark:text-gray-400">
                    {{ $dateFrom }} to {{ $dateTo }}
                </div>
            </div>
            
            <div class="h-80">
                <canvas id="paymentChart" 
                        x-data="{
                            chart: null,
                            init() {
                                const ctx = document.getElementById('paymentChart').getContext('2d');
                                const isDark = document.documentElement.classList.contains('dark');
                                
                                this.chart = new Chart(ctx, {
                                    type: '{{ in_array($reportType, ['method', 'doctor']) ? 'pie' : 'bar' }}',
                                    data: {
                                        labels: @js($chartLabels),
                                        datasets: [{
                                            label: 'Amount (ETB)',
                                            data: @js($chartData),
                                            backgroundColor: [
                                                'rgba(99, 102, 241, 0.8)',
                                                'rgba(34, 197, 94, 0.8)',
                                                'rgba(249, 115, 22, 0.8)',
                                                'rgba(239, 68, 68, 0.8)',
                                                'rgba(168, 85, 247, 0.8)',
                                            ],
                                            borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                            borderWidth: 1
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: {{ in_array($reportType, ['method', 'doctor']) ? 'true' : 'false' }},
                                                labels: {
                                                    color: isDark ? '#e5e7eb' : '#374151'
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                grid: {
                                                    color: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)'
                                                },
                                                ticks: {
                                                    color: isDark ? '#e5e7eb' : '#374151'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                },
                                                ticks: {
                                                    color: isDark ? '#e5e7eb' : '#374151'
                                                }
                                            }
                                        }
                                    }
                                });
                                
                                Livewire.on('refreshChart', () => {
                                    if (this.chart) {
                                        this.chart.destroy();
                                    }
                                    this.init();
                                });
                            }
                        }"
                        x-init="init"></canvas>
            </div>
        </div>
        @endif

        <!-- Daily Report Table -->
        @if($reportType === 'daily')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Daily Collection Details</h3>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Installment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Method</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($dailyReport as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $payment->paid_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-indigo-600 dark:text-indigo-400">
                                #{{ $payment->rehab_order_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                #{{ $payment->installment_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                ETB {{ number_format($payment->paid_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($payment->rehabOrder->payment_method === 'cash')
                                        bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($payment->rehabOrder->payment_method === 'card')
                                        bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($payment->rehabOrder->payment_method === 'insurance')
                                        bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @else
                                        bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($payment->rehabOrder->payment_method ?? 'N/A') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p>No payments found in selected date range</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($dailyReport->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $dailyReport->links() }}
            </div>
            @endif
        </div>
        @endif

        <!-- Installment Status Report -->
        @if($reportType === 'installment')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Installment Status Overview</h3>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Installment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Remaining</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($installmentReport as $installment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm {{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-900 dark:text-white' }}">
                                    {{ $installment->due_date->format('M d, Y') }}
                                    @if($installment->due_date->isPast() && $installment->status !== 'paid')
                                        <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Overdue
                                        </span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $installment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono text-sm text-indigo-600 dark:text-indigo-400">
                                #{{ $installment->rehab_order_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                #{{ $installment->installment_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                ETB {{ number_format($installment->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 dark:text-green-400 font-medium">
                                ETB {{ number_format($installment->paid_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm {{ $installment->getRemainingAmount() > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }} font-medium">
                                ETB {{ number_format($installment->getRemainingAmount(), 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'paid' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'partial' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'overdue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                        'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
                                    ];
                                    $statusClass = $statusClasses[$installment->status] ?? $statusClasses['pending'];
                                @endphp
                                <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
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
                                    <p>No pending installments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($installmentReport->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                {{ $installmentReport->links() }}
            </div>
            @endif
        </div>
        @endif

        <!-- Patient Summary Report -->
        @if($reportType === 'patient')
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-200">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Patient Payment Summary</h3>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Orders</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Paid</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Due</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($patientSummary as $summary)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-medium text-sm">
                                        {{ substr($summary['patient_name'], 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $summary['patient_name'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $summary['total_orders'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 dark:text-green-400">
                                ETB {{ number_format($summary['total_paid'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium {{ $summary['total_due'] > 0 ? 'text-orange-600 dark:text-orange-400' : 'text-green-600 dark:text-green-400' }}">
                                ETB {{ number_format($summary['total_due'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $summary['total_due'] > 0 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400' : 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' }}">
                                    {{ $summary['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <button wire:click="$set('selectedPatient', {{ $summary['patient_id'] }})" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
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
        </div>
        @endif
    </div>

    <!-- Chart.js Script -->
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush
</div>