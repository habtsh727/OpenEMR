<div>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Sales Report</h1>
                            <p class="text-emerald-100 mt-1">Track cupping therapy sales and revenue</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <div class="flex items-center space-x-2 text-emerald-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">{{ Carbon\Carbon::parse($dateFrom)->format('M d, Y') }} - {{ Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        {{-- <button wire:click="exportToExcel"
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
                        </button> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if($showAlert)
        <div class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
            {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
               ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                ($alertType === 'info' ? 'bg-blue-100 border border-blue-400 text-blue-700' : 'bg-yellow-100 border border-yellow-400 text-yellow-700')) }}">
            <span>{{ $alertMessage }}</span>
            <button wire:click="closeAlert" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">ETB {{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sessions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalSessions) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Patients Treated</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($totalPatients) }}</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Average per Session</p>
                        <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 mt-1">ETB {{ number_format($averagePerSession, 2) }}</p>
                    </div>
                    <div class="p-3 bg-indigo-100 dark:bg-indigo-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Collection Rate</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">{{ $collectionRate }}%</p>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Report Type</label>
                    <select wire:model.live="reportType" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="daily">Daily</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From Date</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To Date</label>
                    <input type="date" wire:model.live="dateTo" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                    <select wire:model.live="paymentMethod" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="all">All Methods</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                        <option value="mobile_money">Mobile Money</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Per Page</label>
                    <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
                <div class="md:col-span-5">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Patient</label>
                    <input type="text" wire:model.live="search" placeholder="Search by patient name or card number..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <button wire:click="resetFilters" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200">
                    Reset Filters
                </button>
            </div>
        </div>

        {{-- Chart Section --}}
        {{-- @if(count($chartData) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Trend</h2>
            </div>
            <div class="p-6">
                <canvas id="salesChart" class="w-full" style="height: 350px;"></canvas>
            </div>
        </div>
        @endif --}}

        {{-- Top Packages --}}
        @if(count($topPackages) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Top Selling Packages</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Package Name</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Sessions</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($topPackages as $package)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-3 text-sm font-medium text-gray-900 dark:text-white">{{ $package['package_name_snapshot'] }}</td>
                                <td class="px-6 py-3 text-right text-sm text-gray-600 dark:text-gray-400">{{ number_format($package['session_count']) }}</td>
                                <td class="px-6 py-3 text-right text-sm font-semibold text-green-600">ETB {{ number_format($package['total_revenue'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        {{-- Payment Method Breakdown --}}
        @if(count($paymentMethodStats) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Method Breakdown</h2>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-6">
                @foreach($paymentMethodStats as $stat)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 text-center">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 capitalize">{{ $stat['payment_method'] ?? 'Unknown' }}</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($stat['count']) }}</p>
                        <p class="text-sm font-semibold text-green-600">ETB {{ number_format($stat['total'], 2) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Sessions Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Session Details</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Package</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Payment</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($sessions as $session)
                            @php
                                $patient = $session->cuppingTherapy->encounter->patient;
                                $payment = $session->payments->first();
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-3 text-sm text-gray-900 dark:text-white">{{ Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}</td>
                                <td class="px-6 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">Card: {{ $patient->card_number ?? 'N/A' }}</div>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $session->therapyPackage->package_name_snapshot ?? 'N/A' }}</td>
                                <td class="px-6 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">ETB {{ number_format($session->session_amount, 2) }}</td>
                                <td class="px-6 py-3 text-sm">
                                    @if($payment)
                                        <span class="px-2 py-1 text-xs rounded-full
                                            @if($payment->payment_method === 'cash') bg-green-100 text-green-700
                                            @elseif($payment->payment_method === 'card') bg-blue-100 text-blue-700
                                            @elseif($payment->payment_method === 'mobile_money') bg-purple-100 text-purple-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            {{ ucfirst($payment->payment_method ?? 'N/A') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-500">No payment</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($session->treatment_status === 'completed') bg-green-100 text-green-700
                                        @elseif($session->treatment_status === 'in_progress') bg-blue-100 text-blue-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucfirst($session->treatment_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    No sessions found for the selected period
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $sessions->links() }}
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('livewire:initialized', function () {
        let chart = null;

        function initChart() {
            const chartData = @json($chartData);
            if (chartData && chartData.length > 0) {
                const ctx = document.getElementById('salesChart');
                if (!ctx) return;

                if (chart) {
                    chart.destroy();
                }

                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: chartData.map(item => item.label),
                        datasets: [
                            {
                                label: 'Revenue (ETB)',
                                data: chartData.map(item => item.revenue),
                                borderColor: 'rgb(16, 185, 129)',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y',
                            },
                            {
                                label: 'Sessions',
                                data: chartData.map(item => item.sessions),
                                borderColor: 'rgb(59, 130, 246)',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4,
                                fill: true,
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.dataset.label.includes('Revenue')) {
                                            label += 'ETB ' + context.parsed.y.toLocaleString();
                                        } else {
                                            label += context.parsed.y.toLocaleString();
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                title: {
                                    display: true,
                                    text: 'Revenue (ETB)'
                                },
                                ticks: {
                                    callback: function(value) {
                                        return 'ETB ' + value.toLocaleString();
                                    }
                                }
                            },
                            y1: {
                                position: 'right',
                                title: {
                                    display: true,
                                    text: 'Number of Sessions'
                                },
                                grid: {
                                    drawOnChartArea: false,
                                }
                            }
                        }
                    }
                });
            }
        }

        initChart();

        Livewire.on('render', () => {
            setTimeout(initChart, 100);
        });
    });
</script>
    {{-- In work, do what you enjoy. --}}
</div>
