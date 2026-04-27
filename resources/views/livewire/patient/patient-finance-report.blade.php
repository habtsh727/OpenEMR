<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200" x-data="{
        showPrintView: @entangle('showPrintView'),
        isPrintMode: @entangle('isPrintMode')
     }" x-init="$watch('isPrintMode', value => {
        if(value) {
            setTimeout(() => {
                window.print();
                $wire.set('isPrintMode', false);
            }, 500);
        }
     })">

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .print-content,
            .print-content * {
                visibility: visible !important;
            }

            .print-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                padding: 20px;
            }

            .no-print {
                display: none !important;
            }

            .print\:hidden {
                display: none !important;
            }

            .print\:block {
                display: block !important;
            }

            @page {
                margin: 1cm;
                size: A4;
            }
        }

        /* Print View Styles */
        .print-content {
            background: white;
            min-height: 100vh;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }

        .print-footer {
            margin-top: 30px;
            border-top: 1px solid #ccc;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .print-table th,
        .print-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .print-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Regular View (Non-Print) -->
        <div x-show="!showPrintView" x-cloak class="no-print space-y-6">

            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Patient Financial Report</h1>
                            <p class="text-emerald-100 mt-1">Complete payment history and invoice summary</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button wire:click="printReport"
                            class="px-4 py-2 bg-white text-emerald-600 rounded-lg hover:bg-gray-100 transition-colors flex items-center gap-2 shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            Print / PDF
                        </button>
                    </div>
                </div>

                <!-- Patient Summary Card -->
                <div class="mt-6 bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <p class="text-emerald-100 text-xs uppercase tracking-wider">Patient Name</p>
                            <p class="text-white font-semibold text-lg">{{ $patient->first_name }} {{
                                $patient->middle_name }} {{ $patient->last_name }}</p>
                        </div>
                        <div>
                            <p class="text-emerald-100 text-xs uppercase tracking-wider">Card Number</p>
                            <p class="text-white font-semibold text-lg">{{ $patient->card_number }}</p>
                        </div>
                        <div>
                            <p class="text-emerald-100 text-xs uppercase tracking-wider">Phone</p>
                            <p class="text-white font-semibold text-lg">{{ $patient->phone_number1 }}</p>
                        </div>
                        <div>
                            <p class="text-emerald-100 text-xs uppercase tracking-wider">Total Payments</p>
                            <p class="text-white font-semibold text-lg">ETB {{ number_format($totalPaid, 2) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-emerald-100 text-sm mb-1">Visit</label>
                        <select wire:model.live="encounterId"
                            class="w-full px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white border border-white/30 focus:ring-2 focus:ring-white">
                            <option value="all">All Visits</option>
                            @foreach($encounters as $encounter)
                            <option value="{{ $encounter->id }}">Visit #{{ $encounter->id }} - {{
                                $encounter->created_at->format('M d, Y') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-emerald-100 text-sm mb-1">From Date</label>
                        <input type="date" wire:model.live="dateFrom"
                            class="w-full px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white border border-white/30 focus:ring-2 focus:ring-white">
                    </div>
                    <div>
                        <label class="block text-emerald-100 text-sm mb-1">To Date</label>
                        <input type="date" wire:model.live="dateTo"
                            class="w-full px-4 py-2 rounded-lg bg-white/20 backdrop-blur-sm text-white border border-white/30 focus:ring-2 focus:ring-white">
                    </div>
                    <div class="flex items-end">
                        <button
                            wire:click="$set('encounterId', 'all'); $set('dateFrom', '{{ now()->startOfMonth()->format('Y-m-d') }}'); $set('dateTo', '{{ now()->format('Y-m-d') }}')"
                            class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-lg hover:bg-white/30 transition-colors">
                            Reset Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Bill</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">ETB {{
                                number_format($grandTotal, 2) }}</p>
                        </div>
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Discount</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">ETB {{
                                number_format($totalDiscount, 2) }}</p>
                        </div>
                        <div class="p-3 bg-green-100 dark:bg-green-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2m-6 6h.01M9 18h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Paid</p>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">ETB {{
                                number_format($totalPaid, 2) }}</p>
                        </div>
                        <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-500" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Balance</p>
                            <p
                                class="text-2xl font-bold {{ ($grandTotal - $totalPaid) > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} mt-2">
                                ETB {{ number_format($grandTotal - $totalPaid, 2) }}
                            </p>
                        </div>
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/20 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 12H4m16 0a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2v-6a2 2 0 012-2m16 0V8a2 2 0 00-2-2H6a2 2 0 00-2 2v4">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments by Encounter -->
            <div class="space-y-6">
                @forelse($financialData as $encounter)
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Visit #{{ $encounter['id'] }}
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 ml-7">{{
                                    \Carbon\Carbon::parse($encounter['date'])->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                            <div class="flex items-center gap-4 ml-7 md:ml-0">
                                <div class="text-right">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Visit Total</p>
                                    <p class="text-xl font-bold text-emerald-600 dark:text-emerald-400">ETB {{
                                        number_format($encounter['encounter_paid'], 2) }}</p>
                                </div>
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                                    @if($encounter['status'] == 'completed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($encounter['status'] == 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $encounter['status'])) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Date</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Type</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Description</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Qty</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Unit Price</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Original</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Discount</th>
                                    <th
                                        class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Paid</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Method</th>
                                    <th
                                        class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                                        Ref</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($encounter['payments'] as $payment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($payment['date'])->format('Y-m-d') }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 text-xs rounded-full {{ $this->getTypeColor($payment['type']) }}">
                                            {!! $this->getTypeIcon($payment['type']) !!}
                                            <span>{{ $payment['type'] }}</span>
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        {{ $payment['description'] }}
                                        @if(isset($payment['details']))
                                        <span class="text-xs text-gray-500 dark:text-gray-400 block">{{
                                            $payment['details'] }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">{{
                                        $payment['quantity'] ?? 1 }}</td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">
                                        @if(isset($payment['unit_price']))
                                        ETB {{ number_format($payment['unit_price'], 2) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-900 dark:text-white">ETB {{
                                        number_format($payment['original_amount'], 2) }}</td>
                                    <td
                                        class="px-4 py-3 text-sm text-right {{ $payment['discount'] > 0 ? 'text-green-600 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                        @if($payment['discount'] > 0)
                                        -ETB {{ number_format($payment['discount'], 2) }}
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td
                                        class="px-4 py-3 text-sm text-right font-medium text-emerald-600 dark:text-emerald-400">
                                        ETB {{ number_format($payment['paid_amount'], 2) }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span
                                            class="px-2 py-1 text-xs rounded-full {{ $this->getPaymentMethodColor($payment['payment_method']) }}">
                                            {{ ucfirst($payment['payment_method']) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500 dark:text-gray-400 font-mono">{{
                                        $payment['reference'] ?? 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <td colspan="5"
                                        class="px-4 py-3 text-right text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Encounter Totals:</td>
                                    <td class="px-4 py-3 text-right text-sm font-bold text-gray-900 dark:text-white">ETB
                                        {{ number_format($encounter['encounter_total'], 2) }}</td>
                                    <td
                                        class="px-4 py-3 text-right text-sm font-bold text-green-600 dark:text-green-400">
                                        ETB {{ number_format($encounter['encounter_discount'], 2) }}</td>
                                    <td
                                        class="px-4 py-3 text-right text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                        ETB {{ number_format($encounter['encounter_paid'], 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                @empty
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Payment Records Found</h3>
                    <p class="text-gray-500 dark:text-gray-400">No payments found for the selected filters.</p>
                </div>
                @endforelse
            </div>

        </div>

        <!-- Print View -->
        <div x-show="showPrintView" x-cloak class="print-content">
            <div style="background: white; padding: 15px; max-width: 100%; margin: 0 auto; font-size: 11px;">

                <!-- Hospital Letterhead -->
                <div
                    style="text-align: center; margin-bottom: 15px; border-bottom: 1px solid #333; padding-bottom: 10px;">
                    <h1 style="font-size: 20px; font-weight: bold; margin: 0;">Firdos Cultural Medical Center</h1>
                    <p style="font-size: 11px; margin: 2px 0;">123 Healthcare Avenue, Addis Ababa, Ethiopia</p>
                    <p style="font-size: 10px; margin: 2px 0;">Tel: +251-XXX-XXXXXX | Email: info@hospital.com</p>
                    <h2 style="font-size: 16px; font-weight: bold; margin-top: 8px;">PATIENT FINANCIAL STATEMENT</h2>
                </div>

                <!-- Patient Info -->
                <div
                    style="margin-bottom: 12px; padding: 8px; border: 1px solid #ddd; border-radius: 4px; display: flex; flex-wrap: wrap; gap: 10px;">
                    <div style="flex: 1 1 200px;">
                        <p style="font-size: 9px; color: #666; margin: 0;">Patient Name:</p>
                        <p style="font-weight: bold; margin: 2px 0; font-size: 11px;">{{ $patient->first_name }} {{
                            $patient->middle_name }} {{ $patient->last_name }}</p>
                    </div>
                    <div style="flex: 1 1 150px;">
                        <p style="font-size: 9px; color: #666; margin: 0;">Card Number:</p>
                        <p style="font-weight: bold; margin: 2px 0; font-size: 11px;">{{ $patient->card_number }}</p>
                    </div>
                    <div style="flex: 1 1 150px;">
                        <p style="font-size: 9px; color: #666; margin: 0;">Phone:</p>
                        <p style="font-weight: bold; margin: 2px 0; font-size: 11px;">{{ $patient->phone_number1 }}</p>
                    </div>
                    <div style="flex: 1 1 150px;">
                        <p style="font-size: 9px; color: #666; margin: 0;">Report Date:</p>
                        <p style="font-weight: bold; margin: 2px 0; font-size: 11px;">{{ now()->format('Y-m-d') }}</p>
                    </div>
                </div>

                <!-- Financial Summary -->
                <div style="margin-bottom: 12px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                    <h3 style="font-size: 13px; font-weight: bold; margin: 0 0 8px;">Payment Summary</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="flex: 1 1 120px;">
                            <p style="font-size: 9px; color: #666; margin: 0;">Total Bill</p>
                            <p style="font-size: 13px; font-weight: bold; margin: 2px 0;">ETB {{
                                number_format($grandTotal, 2) }}</p>
                        </div>
                        <div style="flex: 1 1 120px;">
                            <p style="font-size: 9px; color: #666; margin: 0;">Total Discount</p>
                            <p style="font-size: 13px; font-weight: bold; color: #16a34a; margin: 2px 0;">ETB {{
                                number_format($totalDiscount, 2) }}</p>
                        </div>
                        <div style="flex: 1 1 120px;">
                            <p style="font-size: 9px; color: #666; margin: 0;">Total Paid</p>
                            <p style="font-size: 13px; font-weight: bold; color: #059669; margin: 2px 0;">ETB {{
                                number_format($totalPaid, 2) }}</p>
                        </div>
                        <div style="flex: 1 1 120px;">
                            <p style="font-size: 9px; color: #666; margin: 0;">Balance</p>
                            <p
                                style="font-size: 13px; font-weight: bold; {{ ($grandTotal - $totalPaid) > 0 ? 'color: #dc2626;' : 'color: #16a34a;' }} margin: 2px 0;">
                                ETB {{ number_format($grandTotal - $totalPaid, 2) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payments by Encounter -->
                @forelse($financialData as $encounter)
                <div
                    style="margin-bottom: 12px; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; page-break-inside: avoid;">
                    <div style="background: #f3f4f6; padding: 6px 8px; border-bottom: 1px solid #ddd;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 style="font-weight: bold; margin: 0; font-size: 12px;">Visit #{{ $encounter['id'] }} -
                                {{ \Carbon\Carbon::parse($encounter['date'])->format('Y-m-d') }}</h3>
                            <p style="font-weight: bold; margin: 0; font-size: 12px;">Total: ETB {{
                                number_format($encounter['encounter_paid'], 2) }}</p>
                        </div>
                    </div>

                    <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                        <thead>
                            <tr style="background: #f9fafb;">
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: left; width: 60px;">Date
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: left; width: 70px;">Type
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: left;">Description</th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: right; width: 35px;">Qty
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: right; width: 60px;">Price
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: right; width: 60px;">
                                    Original</th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: right; width: 55px;">Disc
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: right; width: 60px;">Paid
                                </th>
                                <th style="border: 1px solid #ddd; padding: 4px; text-align: left; width: 55px;">Method
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($encounter['payments'] as $payment)
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 4px;">{{
                                    \Carbon\Carbon::parse($payment['date'])->format('Y-m-d') }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px;">{{ substr($payment['type'], 0, 8) }}
                                </td>
                                <td style="border: 1px solid #ddd; padding: 4px;">
                                    {{ Str::limit($payment['description'], 20) }}
                                    @if(isset($payment['details']))
                                    <br><span style="font-size: 8px;">{{ Str::limit($payment['details'], 15) }}</span>
                                    @endif
                                </td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right;">{{
                                    $payment['quantity'] ?? 1 }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right;">
                                    @if(isset($payment['unit_price']))
                                    {{ number_format($payment['unit_price'], 0) }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right;">{{
                                    number_format($payment['original_amount'], 0) }}</td>
                                <td
                                    style="border: 1px solid #ddd; padding: 4px; text-align: right; {{ $payment['discount'] > 0 ? 'color: #16a34a; font-weight: bold;' : '' }}">
                                    @if($payment['discount'] > 0)
                                    -{{ number_format($payment['discount'], 0) }}
                                    @else
                                    -
                                    @endif
                                </td>
                                <td style="border: 1px solid #ddd; padding: 4px; text-align: right; font-weight: bold;">
                                    {{ number_format($payment['paid_amount'], 0) }}</td>
                                <td style="border: 1px solid #ddd; padding: 4px;">{{ substr($payment['payment_method'],
                                    0, 3) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div
                        style="background: #f9fafb; padding: 4px 8px; border-top: 1px solid #ddd; text-align: right; font-size: 9px;">
                        <span style="font-weight: bold;">Subtotal:</span>
                        ETB {{ number_format($encounter['encounter_total'], 0) }} |
                        <span style="color: #16a34a; font-weight: bold;">Discount:</span>
                        ETB {{ number_format($encounter['encounter_discount'], 0) }} |
                        <span style="color: #059669; font-weight: bold;">Paid:</span>
                        ETB {{ number_format($encounter['encounter_paid'], 0) }}
                    </div>
                </div>
                @empty
                <div style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 4px;">
                    <p style="color: #666;">No payment records found</p>
                </div>
                @endforelse

              

                <!-- Print Footer -->
                <div
                    style="margin-top: 15px; text-align: center; font-size: 8px; color: #666; border-top: 1px solid #ddd; padding-top: 8px;">
                    <p style="margin: 0;">Computer-generated statement - No signature required</p>
                    <p style="margin: 2px 0;">Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
            </div>

            <!-- Back Button -->
            <div style="text-align: center; margin-top: 15px;" class="no-print">
                <button wire:click="togglePrintView"
                    style="padding: 8px 16px; background: #4b5563; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 12px;">
                    ← Back
                </button>
            </div>
        </div>
    </div>

    <!-- Script for print trigger -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('print-window', () => {
                setTimeout(() => {
                    window.print();
                }, 500);
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
