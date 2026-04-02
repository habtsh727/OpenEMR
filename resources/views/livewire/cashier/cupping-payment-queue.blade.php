<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert') }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
            <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                    'bg-gradient-to-r from-yellow-500 to-orange-600') }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($alertType === 'error')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $alertMessage }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(!$showPaymentForm && !$selectedPatient)
            <!-- Header - Black & White Theme -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 rounded-2xl shadow-2xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                    
                    <div class="relative p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div class="h-16 w-16 rounded-full bg-white/10 backdrop-blur-xl flex items-center justify-center border-2 border-white/20 shadow-xl">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-gray-900 animate-pulse"></div>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-white">Payment Queue</h1>
                                    <p class="text-gray-300 mt-1">Process patient payments and manage transactions</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="bg-white/10 backdrop-blur-xl rounded-lg px-4 py-2">
                                    <span class="text-sm text-gray-300">Cashier Dashboard</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Queue Statistics - Full Width Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Patients</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ count($queueItems) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pending Sessions</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                @php
                                    $totalSessions = 0;
                                    foreach($queueItems as $group) {
                                        $totalSessions += count($group['items']);
                                    }
                                @endphp
                                {{ $totalSessions }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount Due</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                                @php
                                    $totalDue = 0;
                                    foreach($queueItems as $group) {
                                        foreach($group['items'] as $item) {
                                            $totalDue += $item->cuppingSession->remaining_amount;
                                        }
                                    }
                                @endphp
                                {{ number_format($totalDue, 2) }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">ETB</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Today's Collections</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">0.00</p>
                            <p class="text-xs text-gray-400 mt-1">ETB</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Patient Cards - Full Width -->
            @if(count($queueItems) > 0)
                <div class="space-y-6">
                    @foreach($queueItems as $patientId => $group)
                        @php
                            $patient = $group['patient'];
                            $items = $group['items'];
                            $totalDue = 0;
                            $sessionCount = count($items);
                            foreach($items as $item) {
                                $totalDue += $item->cuppingSession->remaining_amount;
                            }
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200">
                            <!-- Patient Header -->
                            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-14 w-14 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 dark:from-gray-600 dark:to-gray-800 flex items-center justify-center shadow-md">
                                            <span class="text-xl font-bold text-white">
                                                {{ strtoupper(substr($patient->name ?? 'N/A', 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-gray-900 dark:text-white">{{ $patient->name ?? 'Unknown Patient' }}</h3>
                                            <div class="flex items-center gap-3 mt-1">
                                                <p class="text-sm text-gray-500 dark:text-gray-400">ID: #{{ $patient->id ?? 'N/A' }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Phone: {{ $patient->phone ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-left md:text-right">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount Due</p>
                                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ number_format($totalDue, 2) }} ETB</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $sessionCount }} pending session(s)</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Sessions Table - Full Width -->
                            <div class="p-0">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Session</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Items</th>
                                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Paid</th>
                                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Due</th>
                                                <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach($items as $item)
                                                @php $session = $item->cuppingSession; @endphp
                                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-xs font-medium">
                                                            Session {{ $session->session_number }}/{{ $session->cuppingTherapy->total_sessions }}
                                                        </span>
                                                     </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                        {{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}
                                                     </td>
                                                    <td class="px-6 py-4">
                                                        <div class="flex flex-wrap gap-1">
                                                            @foreach($session->items as $therapyItem)
                                                                <span class="inline-block text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                                    {{ $therapyItem->cuppingType->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                     </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ number_format($session->session_amount, 2) }}
                                                     </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-green-600 dark:text-green-400">
                                                        {{ number_format($session->paid_amount, 2) }}
                                                     </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold {{ $session->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600' }}">
                                                        {{ number_format($session->remaining_amount, 2) }}
                                                     </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <button wire:click="processPayment({{ $session->id }})"
                                                            class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                            Pay Now
                                                        </button>
                                                     </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Footer Button -->
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-700">
                                <button wire:click="viewPatientPayments({{ $patient->id }}, '{{ $patient->name }}')"
                                    class="w-full md:w-auto px-6 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm font-medium transition-colors flex items-center justify-center gap-2 shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Complete Payment History
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col items-center">
                        <div class="h-24 w-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No pending payments</h3>
                        <p class="text-gray-500 dark:text-gray-400">All caught up! New orders will appear here automatically.</p>
                    </div>
                </div>
            @endif
        @endif

        <!-- Patient Sessions View - Full Width Table -->
        @if($selectedPatient && !$showPaymentForm)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <button wire:click="backToQueue" class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                            </button>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $selectedPatient['name'] }}</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Complete payment history and pending sessions</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-0">
                    @if(count($patientSessions) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Session</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount (ETB)</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Paid (ETB)</th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Due (ETB)</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($patientSessions as $session)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    Session {{ $session->session_number }}/{{ $session->cuppingTherapy->total_sessions }}
                                                </span>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                                {{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}
                                             </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($session->items as $item)
                                                        <span class="inline-block text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                            {{ $item->cuppingType->name }} ({{ $item->cuppingLocation->name }})
                                                        </span>
                                                    @endforeach
                                                </div>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900 dark:text-white">
                                                {{ number_format($session->session_amount, 2) }}
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-green-600 dark:text-green-400 font-medium">
                                                {{ number_format($session->paid_amount, 2) }}
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold {{ $session->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600' }}">
                                                {{ number_format($session->remaining_amount, 2) }}
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ 
                                                    $session->payment_status === 'paid' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                                    ($session->payment_status === 'partial' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' : 
                                                    'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400')
                                                }}">
                                                    {{ ucfirst($session->payment_status) }}
                                                </span>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if($session->remaining_amount > 0)
                                                    <button wire:click="processPayment({{ $session->id }})"
                                                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                        Pay Now
                                                    </button>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 text-sm">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Paid
                                                    </span>
                                                @endif
                                             </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">No payment records found for this patient</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Payment Form Modal -->
        @if($showPaymentForm && $selectedSession)
            <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                <div class="max-w-2xl w-full animate-fade-in-up">
                    @livewire('cashier.cupping-payment-form', ['session' => $selectedSession], key($selectedSession->id))
                    <div class="mt-4 text-center">
                        <button wire:click="closePaymentForm" 
                            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors shadow-lg">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert-shown', () => {
                setTimeout(() => {
                    @this.dispatch('closeAlert');
                }, 5000);
            });
            
            Livewire.on('close-form-delayed', () => {
                setTimeout(() => {
                    @this.dispatch('closePaymentForm');
                }, 2000);
            });
            
            Livewire.on('payment-processed', () => {
                @this.dispatch('closePaymentForm');
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>
</div>