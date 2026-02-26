<div class="space-y-6" x-data="{ showPrintView: @entangle('showPrintView') }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Patient Financial Report</h1>
                <p class="text-emerald-100 mt-1">Complete payment history and invoice summary</p>
            </div>
            <div class="flex gap-2">
                <button wire:click="togglePrintView" class="px-4 py-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white rounded-lg transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    {{ $showPrintView ? 'Normal View' : 'Print View' }}
                </button>
                <button wire:click="printReport" onclick="window.print()" class="px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print / PDF
                </button>
            </div>
        </div>

        <!-- Patient Summary -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-white/10 backdrop-blur-sm rounded-xl p-4">
            <div>
                <p class="text-emerald-100 text-xs">Patient Name</p>
                <p class="text-white font-semibold">{{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</p>
            </div>
            <div>
                <p class="text-emerald-100 text-xs">Card Number</p>
                <p class="text-white font-semibold">{{ $patient->card_number }}</p>
            </div>
            <div>
                <p class="text-emerald-100 text-xs">Phone</p>
                <p class="text-white font-semibold">{{ $patient->phone_number1 }}</p>
            </div>
            <div>
                <p class="text-emerald-100 text-xs">Total Payments</p>
                <p class="text-white font-semibold">ETB {{ number_format($totalPaid, 2) }}</p>
            </div>
        </div>

        <!-- Filter by Encounter -->
        @if(!$showPrintView)
        <div class="mt-4 flex items-center gap-4">
            <label class="text-white text-sm">Filter by Visit:</label>
            <select wire:model.live="encounterId" class="px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white border border-white/30 focus:ring-2 focus:ring-white">
                <option value="all">All Visits</option>
                @foreach($encounters as $encounter)
                    <option value="{{ $encounter->id }}">Visit #{{ $encounter->id }} - {{ $encounter->created_at->format('M d, Y') }}</option>
                @endforeach
            </select>
        </div>
        @endif
    </div>

    <!-- Financial Summary Cards (Print View) -->
    @if($showPrintView)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 print:shadow-none print:border">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Payment Summary</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Bill</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($grandTotal, 2) }}</p>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Discount</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">ETB {{ number_format($totalDiscount, 2) }}</p>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">ETB {{ number_format($totalPaid, 2) }}</p>
            </div>
            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-sm text-gray-500 dark:text-gray-400">Balance</p>
                <p class="text-2xl font-bold {{ ($grandTotal - $totalPaid) > 0 ? 'text-red-600' : 'text-green-600' }}">
                    ETB {{ number_format($grandTotal - $totalPaid, 2) }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Payments by Encounter -->
    <div class="space-y-6">
        @forelse($financialData as $encounter)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden print:break-inside-avoid">
            <!-- Encounter Header -->
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Visit #{{ $encounter['id'] }}</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $encounter['date'] }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Visit Total</p>
                        <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">ETB {{ number_format($encounter['encounter_paid'], 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Payments Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Description</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Qty</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Unit Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Original</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Discount</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Method</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($encounter['payments'] as $payment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($payment['date'])->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($payment['type'] == 'Registration Card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($payment['type'] == 'Service') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($payment['type'] == 'Lab Test') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @elseif($payment['type'] == 'Imaging') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                    @elseif($payment['type'] == 'Pharmacy') bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400
                                    @elseif($payment['type'] == 'Rehab Package') bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400
                                    @elseif($payment['type'] == 'Bed') bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400
                                    @endif">
                                    {{ $payment['type'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                {{ $payment['description'] }}
                                @if(isset($payment['details']))
                                    <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ $payment['details'] }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">{{ $payment['quantity'] ?? 1 }}</td>
                            <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">
                                @if(isset($payment['unit_price']))
                                    ETB {{ number_format($payment['unit_price'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">ETB {{ number_format($payment['original_amount'], 2) }}</td>
                            <td class="px-6 py-4 text-sm text-right {{ $payment['discount'] > 0 ? 'text-green-600 font-medium' : 'text-gray-500' }}">
                                @if($payment['discount'] > 0)
                                    -ETB {{ number_format($payment['discount'], 2) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-medium text-emerald-600 dark:text-emerald-400">ETB {{ number_format($payment['paid_amount'], 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($payment['payment_method'] == 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($payment['payment_method'] == 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($payment['payment_method'] == 'insurance') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @elseif($payment['payment_method'] == 'telebirr') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ ucfirst($payment['payment_method']) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <td colspan="5" class="px-6 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">Encounter Totals:</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">ETB {{ number_format($encounter['encounter_total'], 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-green-600 dark:text-green-400">ETB {{ number_format($encounter['encounter_discount'], 2) }}</td>
                            <td class="px-6 py-3 text-right text-sm font-bold text-emerald-600 dark:text-emerald-400">ETB {{ number_format($encounter['encounter_paid'], 2) }}</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Payment Records Found</h3>
            <p class="text-gray-500 dark:text-gray-400">This patient has no payment records.</p>
        </div>
        @endforelse
    </div>

    <!-- Grand Total Footer (Print View) -->
    @if($showPrintView && count($financialData) > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 print:shadow-none print:border mt-6">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Grand Total Summary</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">All visits combined</p>
            </div>
            <div class="text-right">
                <div class="grid grid-cols-3 gap-8">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Bill</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($grandTotal, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Discount</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">ETB {{ number_format($totalDiscount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Paid</p>
                        <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">ETB {{ number_format($totalPaid, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print\\:block, .print\\:block * {
                visibility: visible;
            }
            .print\\:shadow-none {
                box-shadow: none;
            }
            .print\\:border {
                border: 1px solid #e5e7eb;
            }
            .print\\:break-inside-avoid {
                break-inside: avoid;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</div>