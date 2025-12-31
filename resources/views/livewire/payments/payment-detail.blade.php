<div class="min-h-screen bg-white dark:bg-gray-900 p-8">
    <div class="max-w-5xl mx-auto space-y-8">

        <!-- Professional Header Section -->
        <div
            class="bg-gradient-to-r from-sky-700 to-sky-900 dark:from-blue-900 dark:to-blue-800 border-0 shadow-lg rounded-lg p-2">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-6 p-2">
                <div class="space-y-2">
                    <h1 class="text-3xl font-bold text-white">{{ $patient->name }}</h1>
                    <div class="space-y-1">
                        <p class="text-blue-100">
                            <span class="font-semibold">Card ID:</span> {{ $patient->card_number }}
                        </p>
                        <p class="text-blue-100">
                            <span class="font-semibold">Last Visit:</span>
                            {{ $patient->last_visit_at ? $patient->last_visit_at : 'No visits yet' }}
                        </p>
                    </div>
                </div>

                <!-- Total Due Summary -->
                <div class="bg-red-300 dark:bg-red-800 w-full md:w-64 border-0 rounded-md">
                    <div class="text-center space-y-2 p-4">

                        <p class="text-4xl font-bold text-red-800">
                            {{ number_format(
                                ($patient->cardPayments?->where('is_paid', false)->sum('amount') ?? 0) +
                                    ($patient->servicePayments?->where('is_paid', false)->sum('amount') ?? 0),
                                2,
                            ) }}
                            birr
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Payment Section - Full Width Row -->
        <div>
            @php
                $latestCard = $patient->latestUnpaidCardPayment();
            @endphp

            <div
                class="border border-gray-300 dark:border-gray-700 rounded-md p-2 shadow-md hover:shadow-lg transition-shadow">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Card Payment</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your card payment status</p>
                        </div>
                        <flux:badge :variant="$latestCard ? 'warning' : 'success'" size="lg" color="cyan">
                            {{ $latestCard ? 'Pending' : 'Paid' }}
                        </flux:badge>
                    </div>

                    @if ($latestCard)
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 space-y-6 border border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">Amount
                                        Due</p>
                                    <p class="text-3xl font-bold text-red-600 dark:text-red-400">
                                        {{ number_format($latestCard->amount, 2) }} birr
                                    </p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-2">
                                        Due Date</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $latestCard->due_date ? $latestCard->due_date->format('M d, Y') : 'Not specified' }}

                                    </p>
                                </div>
                            </div>



                            <flux:button wire:click="payCard({{ $latestCard->id }})" variant="primary" color="zinc"
                                class="w-full">
                                Pay Now
                            </flux:button>

                        </div>
                    @else
                        <!-- Updated completed state to minimal design -->
                        <div
                            class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-8 text-center border border-gray-200 dark:border-gray-700">
                            <svg class="w-12 h-12 mx-auto text-green-400 dark:text-green-600 mb-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-green-700 dark:text-green-300 font-semibold">Card Payments Completed</p>
                        </div>

                        <flux:button icon="printer" variant="primary" size="sm" onclick="window.print()">
                            Print Payment Receipt
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Service Payments Section - Full Width Row -->
        <div>
            <div
                class="p-2 shadow-md border border-gray-300 dark:border-gray-700 shadow-md hover:shadow-lg transition-shadow">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Service Payments</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track all service
                                charges</p>
                        </div>
                        <flux:badge size="lg" variant="info">
                            {{ $patient->servicePayments->count() }}
                            Service{{ $patient->servicePayments->count() !== 1 ? 's' : '' }}
                        </flux:badge>
                    </div>

                    @if ($patient->servicePayments->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Service</th>
                                        <th
                                            class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Amount</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($patient->servicePayments as $servicePayment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30 transition-colors">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400"
                                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="font-medium text-gray-900 dark:text-white">
                                                        {{ $servicePayment->service->name }}</p>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <p class="font-semibold text-gray-900 dark:text-white">
                                                    ${{ number_format($servicePayment->amount, 2) }}
                                                </p>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if ($servicePayment->is_paid)
                                                    <flux:badge variant="success">Paid</flux:badge>
                                                @else
                                                    <flux:badge variant="warning">Pending</flux:badge>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if (!$servicePayment->is_paid)
                                                    <button wire:click="payService({{ $servicePayment->id }})"
                                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-medium text-sm transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                            </path>
                                                        </svg>
                                                        Pay
                                                    </button>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                                        Completed
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pay All Pending Services Button -->
                        @if ($patient->servicePayments->where('is_paid', false)->count())
                            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="payAll"
                                    class="w-full bg-gray-900 dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-100 text-white dark:text-gray-900 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pay All Pending Services
                                    ({{ $patient->servicePayments->where('is_paid', false)->count() }})
                                </button>
                            </div>
                        @endif
                    @else
                        <!-- Updated empty state to minimal design -->
                        <div class="text-center py-16">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                </path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400 text-lg font-semibold">No services registered
                            </p>
                            <p class="text-gray-400 dark:text-gray-500 text-sm mt-2">Services will appear here once
                                they are added</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        <flux:modal wire:model="showCreatePaymentModal" class="md:w-96">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Card Payment</flux:heading>
                    <flux:text class="mt-2">This will be used to collect card payment</flux:text>
                </div>

                <flux:select wire:model="payment_type" placeholder="Choose Payment Method..." label="Amount">
                    <flux:select.option>--Select--</flux:select.option>
                    <flux:select.option>Cash</flux:select.option>
                    <flux:select.option>Bank</flux:select.option>
                    <flux:select.option>Telebirr</flux:select.option>
                    <flux:select.option>Other</flux:select.option>

                </flux:select>
                <flux:checkbox label="Is Active" value="1" checked />
                <div class="flex">
                    <flux:spacer />


                    <flux:button variant="primary" wire:click="save({{ $paymentId }})">
                        Pay
                    </flux:button>
                </div>
            </div>
        </flux:modal>


    </div>


    <div id="printable-card" class="hidden print:block bg-white text-black">
        <div class="receipt mx-auto">

            <!-- Hospital Header -->
            <div class="text-center mb-3">
                <h1 class="text-lg font-extrabold tracking-wide uppercase">
                    Firdows Medical Center
                </h1>
                <p class="text-xs">Sheger, Oromia, Ethiopia</p>
                <p class="text-xs">Tel: +251 911 000 000</p>
            </div>

            <div class="divider"></div>

            <!-- Receipt Meta -->
            <div class="text-xs space-y-1">
                <div class="flex justify-between">
                    <span>Date</span>
                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Receipt No</span>
                    <span>#RCP-{{ str_pad($patient->id, 5, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Patient Info -->
            <div class="text-xs space-y-1">
                <div class="flex justify-between">
                    <span>Patient</span>
                    <span class="font-semibold">{{ $patient->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span>Card ID</span>
                    <span>{{ $patient->card_number }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Payment Table -->
            <table class="w-full text-xs">
                <thead>
                    <tr class="font-semibold">
                        <th class="text-left pb-1">Description</th>
                        <th class="text-right pb-1">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t border-dashed border-gray-400">
                        <td class="py-1">Registration / Card Fee</td>
                        <td class="text-right py-1">
                            {{ number_format($patient->cardPayments?->where('is_paid', true)->first()?->amount ?? 0, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="divider"></div>

            <!-- Total -->
            <div class="flex justify-between text-sm font-bold">
                <span>TOTAL</span>
                <span>
                    {{ number_format($patient->cardPayments?->where('is_paid', true)->sum('amount') ?? 0, 2) }} Birr
                </span>
            </div>

            <div class="divider"></div>

            <!-- Footer -->
            <div class="text-center text-[10px] mt-3 space-y-1">
                <p class="italic">"Your health, our priority"</p>
                <p>Thank you for visiting</p>
            </div>

            <div class="mt-6 text-center text-xs">
                ------------------------------
                <br>
                Authorized Signature
            </div>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printable-card,
            #printable-card * {
                visibility: visible;
            }

            #printable-card {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            .receipt {
                width: 280px;
                /* 58mm printer */
                margin: auto;
                padding: 10px;
                font-family: 'Courier New', monospace;
            }

            .divider {
                border-top: 1px dashed #000;
                margin: 8px 0;
            }
        }
    </style>



</div>
