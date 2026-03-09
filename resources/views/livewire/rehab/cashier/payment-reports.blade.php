{{-- resources/views/livewire/rehab/cashier/payment-reports.blade.php --}}

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('cashier.rehab.payments') }}" wire:navigate class="p-2 hover:bg-gray-100 rounded-lg">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Payment Reports</h1>
                            <p class="text-gray-600 mt-1">View and analyze payment data</p>
                        </div>
                    </div>
                    <button wire:click="exportToCsv" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export CSV
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Report Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select wire:model.live="reportType" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="daily">Daily Collection</option>
                        <option value="monthly">Monthly Summary</option>
                        <option value="patient">Patient Summary</option>
                        <option value="installment">Installment Status</option>
                        <option value="method">Payment Methods</option>
                        <option value="doctor">Doctor Performance</option>
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From</label>
                    <input type="date" wire:model.live="dateFrom" class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">To</label>
                    <input type="date" wire:model.live="dateTo" class="w-full p-2 border border-gray-300 rounded-lg">
                </div>

                <!-- Patient Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select wire:model.live="selectedPatient" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="">All Patients</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Doctor</label>
                    <select wire:model.live="selectedDoctor" class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Reset Filters -->
                <div class="flex items-end">
                    <button wire:click="$set('selectedPatient', ''); $set('selectedDoctor', '')" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Total Collected</p>
                <p class="text-2xl font-bold text-green-600">ETB {{ number_format($totalCollected, 2) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Total Orders</p>
                <p class="text-2xl font-bold text-indigo-600">{{ $totalOrders }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Installments Paid</p>
                <p class="text-2xl font-bold text-blue-600">{{ $totalInstallments }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Pending</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingCount }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4">
                <p class="text-sm text-gray-500">Overdue</p>
                <p class="text-2xl font-bold text-red-600">{{ $overdueCount }}</p>
            </div>
        </div>

        <!-- Chart -->
        @if(count($chartLabels) > 0)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                @switch($reportType)
                    @case('daily') Daily Collection @break
                    @case('monthly') Monthly Trends @break
                    @case('method') Payment Methods @break
                    @case('doctor') Top Doctors @break
                    @default Payment Chart
                @endswitch
            </h3>
            <div class="h-64">
                <canvas id="paymentChart" x-data="{
                    chart: null,
                    init() {
                        const ctx = document.getElementById('paymentChart').getContext('2d');
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
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: {{ in_array($reportType, ['method', 'doctor']) ? 'true' : 'false' }}
                                    }
                                }
                            }
                        });
                        
                        Livewire.on('refreshChart', () => {
                            this.chart.destroy();
                            this.init();
                        });
                    }
                }" x-init="init"></canvas>
            </div>
        </div>
        @endif

        <!-- Daily Report Table -->
        @if($reportType === 'daily')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-blue-50 border-b">
                <h3 class="font-semibold text-gray-900">Daily Collection Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Installment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($dailyReport as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $payment->paid_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono">#{{ $payment->rehab_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $payment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $payment->rehabOrder->encounter->encounter->doctor->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">#{{ $payment->installment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-green-600">ETB {{ number_format($payment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap capitalize">{{ $payment->rehabOrder->payment_method ?? 'N/A' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                No payments found in selected date range
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">
                {{ $dailyReport->links() }}
            </div>
        </div>
        @endif

        <!-- Installment Status Report -->
        @if($reportType === 'installment')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-yellow-50 to-orange-50 border-b">
                <h3 class="font-semibold text-gray-900">Installment Status</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Installment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Remaining</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($installmentReport as $installment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="{{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600 font-medium' : '' }}">
                                    {{ $installment->due_date->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $installment->rehabOrder->encounter->encounter->patient->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-mono">#{{ $installment->rehab_order_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">#{{ $installment->installment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">ETB {{ number_format($installment->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600">ETB {{ number_format($installment->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-orange-600">ETB {{ number_format($installment->getRemainingAmount(), 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($installment->status === 'paid')
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                                @elseif($installment->status === 'partial')
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Partial</span>
                                @elseif($installment->status === 'overdue')
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Overdue</span>
                                @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs rounded-full">Pending</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                No pending installments found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">
                {{ $installmentReport->links() }}
            </div>
        </div>
        @endif

        <!-- Patient Summary Report -->
        @if($reportType === 'patient')
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b">
                <h3 class="font-semibold text-gray-900">Patient Payment Summary</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Orders</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Due</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($patientSummary as $summary)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $summary['patient_name'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $summary['total_orders'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-green-600 font-medium">ETB {{ number_format($summary['total_paid'], 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap {{ $summary['total_due'] > 0 ? 'text-orange-600' : 'text-green-600' }}">
                                ETB {{ number_format($summary['total_due'], 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 {{ $summary['total_due'] > 0 ? 'bg-orange-100 text-orange-800' : 'bg-green-100 text-green-800' }} text-xs rounded-full">
                                    {{ $summary['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="$set('selectedPatient', {{ $summary['patient_id'] }})" 
                                    class="text-indigo-600 hover:text-indigo-900">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No patient data found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>