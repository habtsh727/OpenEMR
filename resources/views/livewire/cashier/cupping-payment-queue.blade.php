<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">

    <!-- Alert Notification (same as before) -->
    <div x-data="{ show: @entangle('showAlert') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-2" class="fixed top-4 right-4 z-50 max-w-md w-full">
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
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @elseif($alertType === 'error')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
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
            <div
                class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 rounded-2xl shadow-2xl overflow-hidden relative">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32 blur-3xl">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-24 -translate-x-24 blur-2xl">
                </div>

                <div class="relative p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="h-16 w-16 rounded-full bg-white/10 backdrop-blur-xl flex items-center justify-center border-2 border-white/20 shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-500 border-2 border-white dark:border-gray-900 animate-pulse">
                                </div>
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

        <!-- Queue Statistics -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Patients</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_patients'] }}
                        </p>
                    </div>
                    <div class="h-12 w-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pending Sessions</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['pending_sessions']
                            }}</p>
                    </div>
                    <div
                        class="h-12 w-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Amount Due</p>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{
                            number_format($stats['total_due'], 2) }}</p>
                        <p class="text-xs text-gray-400 mt-1">ETB</p>
                    </div>
                    <div
                        class="h-12 w-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Per Page</p>
                        <select wire:model.live="perPage"
                            class="mt-1 text-lg font-bold border rounded-lg px-3 py-1 bg-white dark:bg-gray-700">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                    <div
                        class="h-12 w-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by patient name, card number, or phone number..."
                    class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 focus:border-gray-500 dark:text-white transition-all duration-200 shadow-sm">
            </div>
        </div>

        <!-- Patients Table - Grouped by Patient -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-1 bg-gray-700 dark:bg-gray-400 rounded-full"></div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Patients Waiting for Payment
                        </h2>
                        <span
                            class="px-2 py-0.5 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-full text-xs">{{
                            $patients->total() }} patients</span>
                    </div>
                    @if($search)
                    <button wire:click="$set('search', '')"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        Clear search
                    </button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Position</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Patient</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Card Number</th>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Phone</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Pending Sessions</th>
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Total Due (ETB)</th>
                            <th
                                class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($patients as $patientData)
                        @php
                        $patient = $patientData['patient'];
                        $sessions = $patientData['sessions'];
                        $totalDue = $patientData['total_due'];
                        $position = $patientData['oldest_position'];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-sm font-bold">
                                    #{{ $position }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                        <span class="text-sm font-bold text-gray-600 dark:text-gray-400">
                                            {{ strtoupper(substr($patient->first_name ?? 'N', 0, 1)) }}{{
                                            strtoupper(substr($patient->last_name ?? 'A', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $patient->first_name
                                            ?? '' }} {{ $patient->last_name ?? '' }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $patient->card_number ??
                                            'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $patient->card_number ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                {{ $patient->phone_number1 ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium">
                                    {{ count($sessions) }} session(s)
                                </span>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-right font-bold text-red-600 dark:text-red-400 text-lg">
                                {{ number_format($totalDue, 2) }} ETB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        wire:click="viewPatientPayments({{ $patient->id }}, '{{ addslashes($patient->name) }}')"
                                        class="px-4 py-2 bg-gradient-to-r from-gray-700 to-gray-800 hover:from-gray-800 hover:to-gray-900 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        View Payments
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="h-20 w-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No pending
                                        payments</h3>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        {{ $search ? 'No matching patients found. Try adjusting your search.' : 'All
                                        caught up! New orders will appear here automatically.' }}
                                    </p>
                                    @if($search)
                                    <button wire:click="$set('search', '')"
                                        class="mt-4 text-blue-600 hover:text-blue-800 dark:text-blue-400">
                                        Clear search
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($patients->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        Showing <span class="font-medium">{{ $patients->firstItem() ?? 0 }}</span> to
                        <span class="font-medium">{{ $patients->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $patients->total() }}</span> patients
                    </div>
                    <div>
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Patient Complete History View - Shows ALL Sessions (Both Paid & Unpaid) -->
        @if($selectedPatient && !$showPaymentForm)
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <button wire:click="backToQueue"
                            class="p-2 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </button>
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $selectedPatient['name'] }}
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Complete payment history - Showing ALL
                                sessions (paid & unpaid)</p>
                        </div>
                    </div>

                    <!-- Summary Stats Cards -->
                    @php
                    $totalAmount = $patientAllSessions->sum('session_amount');
                    $totalPaid = $patientAllSessions->sum('paid_amount');
                    $totalDue = $totalAmount - $totalPaid;
                    $paidCount = $patientAllSessions->where('payment_status', 'paid')->count();
                    $unpaidCount = $patientAllSessions->where('payment_status', '!=', 'paid')->count();
                    $partialCount = $patientAllSessions->where('payment_status', 'partial')->count();
                    @endphp
                    <div class="flex gap-3">
                        <div class="text-center px-4 py-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <p class="text-xs text-green-600 dark:text-green-400">Fully Paid</p>
                            <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ $paidCount }}</p>
                        </div>
                        <div class="text-center px-4 py-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                            <p class="text-xs text-yellow-600 dark:text-yellow-400">Partial</p>
                            <p class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ $partialCount }}</p>
                        </div>
                        <div class="text-center px-4 py-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                            <p class="text-xs text-red-600 dark:text-red-400">Unpaid</p>
                            <p class="text-xl font-bold text-red-700 dark:text-red-300">{{ $unpaidCount - $partialCount
                                }}</p>
                        </div>
                        <div class="text-center px-4 py-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                            <p class="text-xs text-blue-600 dark:text-blue-400">Total Due</p>
                            <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ number_format($totalDue, 2)
                                }} ETB</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-0">
                @if(count($patientAllSessions) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700/50 sticky top-0">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Session</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Date</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Items</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Amount (ETB)</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Paid (ETB)</th>
                                <th
                                    class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Due (ETB)</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Payment Status</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Treatment Status</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($patientAllSessions as $session)
                            <tr
                                class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors 
                                            {{ $session->payment_status === 'paid' ? 'bg-green-50/50 dark:bg-green-900/10' : '' }}
                                            {{ $session->payment_status === 'partial' ? 'bg-yellow-50/50 dark:bg-yellow-900/10' : '' }}
                                            {{ $session->payment_status === 'unpaid' ? 'bg-red-50/50 dark:bg-red-900/10' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-gray-900 dark:text-white">
                                            Session {{ $session->session_number }}/{{
                                            $session->cuppingTherapy->total_sessions }}
                                        </span>
                                        @if($session->payment_status === 'paid')
                                        <span class="inline-flex items-center gap-1 text-green-600 dark:text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($session->items as $item)
                                        <span
                                            class="inline-block text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                            {{ $item->cuppingType->name }} ({{ $item->cuppingLocation->name }})
                                        </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right font-medium text-gray-900 dark:text-white">
                                    {{ number_format($session->session_amount, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-green-600 dark:text-green-400 font-medium">
                                    {{ number_format($session->paid_amount, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right font-bold 
                                                {{ $session->remaining_amount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600' }}">
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
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ 
                                                    $session->treatment_status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 
                                                    ($session->treatment_status === 'in_progress' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 
                                                    ($session->treatment_status === 'in_queue' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : 
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'))
                                                }}">
                                        {{ ucfirst(str_replace('_', ' ', $session->treatment_status)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($session->remaining_amount > 0)
                                    <button wire:click="processPayment({{ $session->id }})"
                                        class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                        Pay Now
                                    </button>
                                    @else
                                    <span
                                        class="inline-flex items-center gap-1 text-green-600 dark:text-green-400 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Completed
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot
                            class="bg-gray-100 dark:bg-gray-800 sticky bottom-0 border-t-2 border-gray-300 dark:border-gray-600">
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white text-lg">
                                    GRAND TOTALS:
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-gray-900 dark:text-white text-lg">
                                    {{ number_format($patientAllSessions->sum('session_amount'), 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-green-600 dark:text-green-400 text-lg">
                                    {{ number_format($patientAllSessions->sum('paid_amount'), 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-red-600 dark:text-red-400 text-lg">
                                    {{ number_format($patientAllSessions->sum('session_amount') -
                                    $patientAllSessions->sum('paid_amount'), 2) }}
                                </td>
                                <td colspan="3" class="px-6 py-4"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">No sessions found for this patient</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Payment Form Modal -->
        @if($showPaymentForm && $selectedSession)
        <div
            class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
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
        [x-cloak] {
            display: none !important;
        }

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