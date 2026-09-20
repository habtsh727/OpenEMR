<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200" x-data="{
        showAlert: @entangle('showAlert'),
        alertMessage: @entangle('alertMessage'),
        alertType: @entangle('alertType')
     }">

    <!-- Alert Notification with Dark/Light Theme -->
    <div x-show="showAlert" x-cloak x-init="setTimeout(() => showAlert = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4" :class="{
                    'bg-gradient-to-r from-green-500 to-emerald-600 dark:from-green-600 dark:to-emerald-700': alertType === 'success',
                    'bg-gradient-to-r from-red-500 to-rose-600 dark:from-red-600 dark:to-rose-700': alertType === 'error',
                    'bg-gradient-to-r from-yellow-500 to-orange-600 dark:from-yellow-600 dark:to-orange-700': alertType === 'warning',
                    'bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700': alertType === 'info'
                 }">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <template x-if="alertType === 'success'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'error'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'warning'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'info'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div>
                        <p class="font-medium text-white" x-text="alertMessage"></p>
                    </div>
                </div>
                <button @click="showAlert = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Theme Toggle -->
        <div class="mb-8">
            <div
                class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-800 dark:via-purple-800 dark:to-pink-800 rounded-2xl shadow-2xl overflow-hidden relative">
                <div
                    class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl">
                </div>
                <div
                    class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl">
                </div>

                <div class="relative p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 border-2 border-white dark:border-gray-800 animate-pulse">
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Payment Queue</h1>
                                <p class="text-indigo-100 dark:text-indigo-200 mt-1">Process rehabilitation payments</p>
                            </div>
                        </div>

                        <!-- Theme Toggle Button -->
                        <button onclick="toggleTheme()"
                            class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group">
                            <svg class="w-6 h-6 text-white dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg class="w-6 h-6 text-white hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Total Orders</p>
                            <p class="text-white text-2xl font-bold">{{ App\Models\RehabOrder::count() }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Pending</p>
                            <p class="text-yellow-300 text-2xl font-bold">{{ App\Models\RehabOrder::where('status',
                                'sent_to_cashier')->count() }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Processed</p>
                            <p class="text-green-300 text-2xl font-bold">{{ App\Models\RehabOrder::where('status',
                                'paid')->count() }}</p>
                        </div>
                    </div>

                    <!-- Upcoming Payments Section -->
                    @if(isset($upcomingPayments) && count($upcomingPayments) > 0)
                    <div class="mt-6">
                        <div
                            class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 rounded-xl border border-amber-200 dark:border-amber-800 overflow-hidden">
                            <div
                                class="px-6 py-4 bg-amber-100 dark:bg-amber-900/30 border-b border-amber-200 dark:border-amber-800">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="font-semibold text-amber-800 dark:text-amber-300">Upcoming Payments (Next
                                        7 Days)</h3>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="space-y-3">
                                    @foreach($upcomingPayments as $payment)
                                    <div
                                        class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                                <span class="font-bold text-amber-600 dark:text-amber-400">#{{
                                                    $payment->installment_number }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $payment->rehabOrder->encounter->encounter->patient->name ??
                                                    'Unknown' }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    Order #{{ $payment->rehab_order_id }} • Due: {{
                                                    $payment->due_date->format('M d, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-bold text-amber-600 dark:text-amber-400">ETB {{
                                                number_format($payment->amount, 2) }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ now()->diffInDays($payment->due_date) }} days left
                                            </p>
                                        </div>
                                        <button wire:click="openPaymentModal({{ $payment->rehab_order_id }})"
                                            class="px-3 py-1 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700">
                                            Pay Now
                                        </button>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <!-- Tabs -->
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('tab', 'pending')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                {{ $tab === 'pending'
                    ? 'bg-indigo-600 text-white shadow-md'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Pending
        </button>
        <button wire:click="$set('tab', 'partial')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                {{ $tab === 'partial'
                    ? 'bg-yellow-600 text-white shadow-md'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Partial Payments
            @php
                $partialCount = \App\Models\RehabOrder::where('payment_status', 'partial')
                    ->where('status', 'sent_to_cashier')
                    ->count();
            @endphp
            @if($partialCount > 0)
                <span class="ml-2 bg-white/20 px-2 py-0.5 rounded-full text-xs">
                    {{ $partialCount }}
                </span>
            @endif
        </button>
        <button wire:click="$set('tab', 'overdue')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                {{ $tab === 'overdue'
                    ? 'bg-red-600 text-white shadow-md'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Overdue
        </button>
        <button wire:click="$set('tab', 'processed')"
            class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200
                {{ $tab === 'processed'
                    ? 'bg-green-600 text-white shadow-md'
                    : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
            Completed
        </button>
    </div>
</div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by patient name..."
                    class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white transition-all duration-200">
            </div>
        </div>

        <!-- Orders Grid/Cards for Mobile -->
        <div class="lg:hidden space-y-4">
            @forelse($orders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Order #{{ $order->id }}</span>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $order->encounter->encounter->patient->first_name ?? '' }} {{ $order->encounter->encounter->patient->last_name ?? '' }}</h3>
                    </div>
                    @if($order->status === 'sent_to_cashier')
                    <span
                        class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                    @else
                    <span
                        class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">Paid</span>
                    @endif
                </div>

                <div class="space-y-2 mb-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Doctor:</span>
                        <span class="text-gray-900 dark:text-white">{{ $order->encounter->encounter->doctor->name ??
                            'N/A' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Packages:</span>
                        <span class="text-gray-900 dark:text-white">{{ $order->packages->count() }}</span>
                    </div>

                    @if($order->bed_cost > 0)
                    <div class="flex justify-between text-purple-600 dark:text-purple-400">
                        <span class="font-medium">Bed ({{ $order->bed_class_name }}):</span>
                        <span>{{ $order->bed_duration }} days</span>
                    </div>
                    <div class="flex justify-between text-purple-600 dark:text-purple-400">
                        <span class="font-medium">Bed Cost:</span>
                        <span>ETB {{ number_format($order->bed_cost, 2) }}</span>
                    </div>
                    @endif

                    <div
                        class="flex justify-between text-lg font-bold border-t border-gray-200 dark:border-gray-700 pt-2 mt-2">
                        <span class="text-gray-900 dark:text-white">Grand Total:</span>
                        <span class="text-indigo-600 dark:text-indigo-400">ETB {{ number_format($order->grand_total, 2)
                            }}</span>
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Order Date: {{ $order->created_at->format('M d, Y') }}
                    </div>
                </div>

                <div class="flex gap-2">
                    <button wire:click="viewOrder({{ $order->id }})"
                        class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        View Details
                    </button>
                    @if(in_array($order->status, ['sent_to_cashier', 'bed_selected']))
                    <button wire:click="openPaymentModal({{ $order->id }})"
                        class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                        Pay
                    </button>
                    @else
                    <button wire:click="recheckPayment({{ $order->id }})"
                        class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700 transition-colors">
                        Recheck
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div
                class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No orders found</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div
            class="hidden lg:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Order</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Bed Details</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Package Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Bed Cost</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Grand Total</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">#{{ $order->id
                            }}</td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{
    $order->encounter->encounter->patient->first_name ?? '' }} {{ $order->encounter->encounter->patient->last_name ?? '' }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{
                                $order->encounter->encounter->patient->id }}</div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->bed_cost > 0)
                            <div class="text-sm">
                                <span class="font-medium text-purple-600 dark:text-purple-400">{{ $order->bed_class_name
                                    }}</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400 block">{{ $order->bed_duration }}
                                    days</span>
                            </div>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">No bed</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                            ETB {{ number_format($order->total_amount, 2) }}
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->bed_cost > 0)
                            <span class="text-sm font-medium text-purple-600 dark:text-purple-400">
                                ETB {{ number_format($order->bed_cost, 2) }}
                            </span>
                            @else
                            <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap font-bold text-indigo-600 dark:text-indigo-400">
                            ETB {{ number_format($order->grand_total, 2) }}
                        </td>

                       <td class="px-6 py-4 whitespace-nowrap">
    @if($order->status === 'bed_selected')
        <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs rounded-full flex items-center gap-1 w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Bed Selected
        </span>

    @elseif($order->payment_status === 'partial')
        @php
            $totalInstallments = $order->paymentInstallments->count();
            $paidInstallments = $order->paymentInstallments->where('status', 'paid')->count();
        @endphp
        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full flex items-center gap-1 w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            @if($totalInstallments > 0)
                Partial ({{ $paidInstallments }}/{{ $totalInstallments }})
            @else
                Partial Payment
            @endif
        </span>

    @elseif($order->payment_status === 'overdue')
        <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs rounded-full flex items-center gap-1 w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Overdue
        </span>

    @elseif($order->status === 'sent_to_cashier' && ($order->payment_status === 'pending' || !$order->payment_status))
        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full flex items-center gap-1 w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Pending
        </span>

    @elseif($order->status === 'paid' && $order->payment_status === 'paid')
        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full flex items-center gap-1 w-fit">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Fully Paid
        </span>

    @else
        <span class="px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs rounded-full">
            {{ ucfirst($order->status) }}
        </span>
    @endif
</td>

                        <!-- In your queue table, replace the view button -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('rehab.cashier.payment-details', $order->id) }}"
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                    title="View Payment Plan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                        </path>
                                    </svg>
                                </a>
                                <!-- Keep the existing pay button -->
                                @if(in_array($order->status, ['sent_to_cashier', 'bed_selected']))
                                <button wire:click="openPaymentModal({{ $order->id }})"
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors"
                                    title="Process Payment">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

        <!-- View Details Modal -->
        @if($showDetailsModal && $selectedOrder)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Order Details #{{ $selectedOrder->id }}
                    </h2>
                    <button wire:click="closeModal"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Patient Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{
                                    $selectedOrder->encounter->encounter->patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Doctor</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{
                                    $selectedOrder->encounter->encounter->doctor->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Order Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{
                                    $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p class="font-medium">
                                    @if($selectedOrder->status === 'sent_to_cashier')
                                    <span class="text-yellow-600 dark:text-yellow-400">Pending Payment</span>
                                    @else
                                    <span class="text-green-600 dark:text-green-400">Paid</span>
                                    @endif
                                </p>
                            </div>
                            @if($selectedOrder->paid_at)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Paid At</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{
                                    $selectedOrder->paid_at->format('M d, Y H:i') }}</p>
                            </div>
                            @endif
                            @if($selectedOrder->payment_method)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                <p class="font-medium text-gray-900 dark:text-white capitalize">{{
                                    $selectedOrder->payment_method }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bed Selection Info (if exists) -->
                    @if($bedSelection)
                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-800">
                        <h3 class="font-semibold text-purple-800 dark:text-purple-300 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                            Bed Selection Details
                        </h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <p class="text-xs text-purple-600 dark:text-purple-400">Bed Class</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->bedClass->name ??
                                    'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-purple-600 dark:text-purple-400">Bed Number</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->bed->bed_number
                                    ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-purple-600 dark:text-purple-400">Duration</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $bedSelection->duration_days ??
                                    0 }} days</p>
                            </div>
                            <div>
                                <p class="text-xs text-purple-600 dark:text-purple-400">Price per Day</p>
                                <p class="font-medium text-gray-900 dark:text-white">ETB {{
                                    number_format($bedSelection->price_per_day ?? 0, 2) }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-purple-200 dark:border-purple-800">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-purple-800 dark:text-purple-300">Total Bed Cost:</span>
                                <span class="text-xl font-bold text-purple-600 dark:text-purple-400">ETB {{
                                    number_format($bedCost, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Packages -->
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Ordered Packages</h3>
                        @foreach($selectedOrder->packages as $package)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $package->package_name }}</h4>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">ETB {{
                                    number_format($package->final_price, 2) }}</span>
                            </div>
                            @if($package->items->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                @foreach($package->items as $item)
                                <div class="text-sm p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $item->item_name
                                            }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ str_replace('_', ' ',
                                            $item->item_type) }}</span>
                                    </div>
                                    <div
                                        class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        @if($item->dosage)<div>Dosage: {{ $item->dosage }}</div>@endif
                                        @if($item->frequency)<div>Freq: {{ $item->frequency }}</div>@endif
                                        @if($item->duration)<div>Duration: {{ $item->duration }}</div>@endif
                                        @if($item->bed_duration_days)<div>Bed: {{ $item->bed_duration_days }} days</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Payment Installments Section -->
                    @if($selectedOrder && $selectedOrder->paymentInstallments &&
                    $selectedOrder->paymentInstallments->count() > 0)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Payment Schedule</h3>
                        <div class="space-y-3">
                            @foreach($selectedOrder->paymentInstallments as $installment)
                            <div class="border rounded-lg p-4 {{ $installment->status === 'paid' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' :
                                ($installment->due_date->isPast() && $installment->status !== 'paid' ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' :
                                'bg-gray-50 dark:bg-gray-700/50 border-gray-200 dark:border-gray-700') }}">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900 dark:text-white">Installment #{{
                                                $installment->installment_number }}</span>
                                            @if($installment->status === 'paid')
                                            <span
                                                class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">Paid</span>
                                            @elseif($installment->due_date->isPast())
                                            <span
                                                class="px-2 py-0.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs rounded-full">Overdue</span>
                                            @else
                                            <span
                                                class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            Amount: ETB {{ number_format($installment->amount, 2) }}
                                        </p>
                                        @if($installment->paid_amount > 0)
                                        <p class="text-xs text-green-600 dark:text-green-400">
                                            Paid: ETB {{ number_format($installment->paid_amount, 2) }}
                                        </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Due Date</p>
                                        <p
                                            class="font-medium {{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                            {{ $installment->due_date->format('M d, Y') }}
                                        </p>
                                        @if(!$installment->due_date->isPast() && $installment->status !== 'paid')
                                        <p class="text-xs text-gray-500">
                                            {{ now()->diffInDays($installment->due_date) }} days left
                                        </p>
                                        @endif
                                    </div>
                                </div>

                                @if($installment->status !== 'paid' && $installment->paid_amount > 0)
                                <!-- Progress Bar for Partial Payments -->
                                <div class="mt-3">
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                        <span class="font-medium text-indigo-600 dark:text-indigo-400">
                                            {{ round(($installment->paid_amount / $installment->amount) * 100, 1) }}%
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                        <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full"
                                            style="width: {{ ($installment->paid_amount / $installment->amount) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Total -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Package Total:</span>
                            <span class="font-medium text-gray-900 dark:text-white">ETB {{
                                number_format($selectedOrder->total_amount, 2) }}</span>
                        </div>
                        @if($bedCost > 0)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600 dark:text-gray-400">Bed Cost:</span>
                            <span class="font-medium text-purple-600 dark:text-purple-400">ETB {{
                                number_format($bedCost, 2) }}</span>
                        </div>
                        @endif
                        <div
                            class="flex justify-between items-center text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-900 dark:text-white">Grand Total:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">ETB {{
                                number_format($selectedOrder->total_amount + $bedCost, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div
                    class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Close
                    </button>
                    @if(in_array($selectedOrder->status, ['sent_to_cashier', 'bed_selected']))
                    <button wire:click="openPaymentModal({{ $selectedOrder->id }})"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Process Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Modal with Installment Support -->
        @if($showPaymentModal && $paymentOrder)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div
                    class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Process Payment</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Order #{{ $paymentOrder->id }} - {{
                            $paymentOrder->encounter->encounter->patient->name }}</p>
                    </div>
                    <button wire:click="closePaymentModal"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <!-- Payment Summary Card -->
                    <div
                        class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-xl p-5 border border-indigo-100 dark:border-indigo-800">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                </path>
                            </svg>
                            Payment Summary
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Package Total</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">ETB {{
                                    number_format($packageTotal, 2) }}</p>
                            </div>
                            @if($bedCost > 0)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Bed Cost</p>
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400">ETB {{
                                    number_format($bedCost, 2) }}</p>
                            </div>
                            @endif
                            <div class="col-span-2 pt-2 border-t border-indigo-200 dark:border-indigo-800">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-gray-900 dark:text-white">Grand Total:</span>
                                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">ETB {{
                                        number_format($grandTotal, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Type Info -->
                    @if($paymentOrder->payment_type === 'installment')
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                        <div class="flex items-center gap-2 text-blue-800 dark:text-blue-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="font-medium">Installment Plan - {{ $paymentOrder->installment_count }}
                                Payments</span>
                        </div>
                    </div>
                    @endif

                    <!-- Installment Selection -->
                    @if(count($paymentInstallments) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Select
                            Installment to Pay</label>
                        <div class="space-y-3">
                            @foreach($paymentInstallments as $installment)
                            <div class="relative">
                                <input type="radio" wire:click="selectInstallment({{ $installment->id }})"
                                    name="installment" value="{{ $installment->id }}"
                                    id="installment_{{ $installment->id }}" {{ $selectedInstallment &&
                                    $selectedInstallment->id === $installment->id ? 'checked' : '' }}
                                class="peer hidden">
                                <label for="installment_{{ $installment->id }}"
                                    class="block p-4 border-2 rounded-xl cursor-pointer transition-all duration-200
                                        {{ $selectedInstallment && $selectedInstallment->id === $installment->id
                                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-200 dark:ring-indigo-800'
                                            : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700' }}">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <span class="font-medium text-gray-900 dark:text-white">Installment #{{
                                                $installment->installment_number }}</span>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-sm text-gray-600 dark:text-gray-400">Amount:</span>
                                                <span class="text-lg font-bold text-indigo-600 dark:text-indigo-400">ETB
                                                    {{ number_format($installment->amount, 2) }}</span>
                                            </div>
                                            @if($installment->paid_amount > 0)
                                            <div class="mt-1 text-sm">
                                                <span class="text-green-600 dark:text-green-400">Paid: ETB {{
                                                    number_format($installment->paid_amount, 2) }}</span>
                                                <span class="text-gray-400 mx-2">|</span>
                                                <span class="text-yellow-600 dark:text-yellow-400">Remaining: ETB {{
                                                    number_format($installment->getRemainingAmount(), 2) }}</span>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm text-gray-500 dark:text-gray-400 block">Due Date</span>
                                            <span
                                                class="font-medium {{ $installment->due_date->isPast() && $installment->status !== 'paid' ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">
                                                {{ $installment->due_date->format('M d, Y') }}
                                            </span>
                                            @if($installment->status === 'overdue')
                                            <span
                                                class="block mt-1 text-xs text-red-600 dark:text-red-400 font-semibold">⚠️
                                                OVERDUE</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($selectedInstallment)
                    <!-- Selected Installment Details -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Payment Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Installment Amount:</span>
                                <span class="font-medium text-gray-900 dark:text-white">ETB {{
                                    number_format($selectedInstallment->amount, 2) }}</span>
                            </div>
                            @if($selectedInstallment->paid_amount > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Already Paid:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">ETB {{
                                    number_format($selectedInstallment->paid_amount, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between text-sm border-t border-gray-200 dark:border-gray-600 pt-2">
                                <span class="font-semibold text-gray-900 dark:text-white">Remaining to Pay:</span>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">ETB {{
                                    number_format($selectedInstallment->getRemainingAmount(), 2) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Form -->
                    <div class="space-y-4">
                        <!-- Payment Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment
                                Method</label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @php
                                $methods = ['cash' => 'Cash', 'card' => 'Card', 'insurance' => 'Insurance',
                                'bank_transfer' => 'Bank Transfer'];
                                @endphp
                                @foreach($methods as $value => $label)
                                <button type="button" wire:click="$set('paymentMethod', '{{ $value }}')"
                                    class="p-3 border-2 rounded-lg text-center transition-all duration-200
                                        {{ $paymentMethod === $value
                                            ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20'
                                            : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700' }}">
                                    <span class="block text-sm font-medium text-gray-900 dark:text-white capitalize">{{
                                        $label }}</span>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Amount Received -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Amount Received <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span
                                    class="absolute left-3 top-3 text-gray-500 dark:text-gray-400 font-medium">ETB</span>
                                <input type="number" step="0.01" wire:model.live="paymentAmount"
                                    class="w-full pl-14 pr-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white transition-all duration-200"
                                    placeholder="0.00" min="1" max="{{ $selectedInstallment->getRemainingAmount() }}">
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Min: 1 ETB</span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Max: {{
                                    number_format($selectedInstallment->getRemainingAmount(), 2) }} ETB</span>
                            </div>
                            @error('paymentAmount')
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Progress Bar for Partial Payment -->
                        @if($selectedInstallment->paid_amount > 0 || $paymentAmount > 0)
                        <div class="pt-2">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600 dark:text-gray-400">Payment Progress</span>
                                <span class="font-medium text-indigo-600 dark:text-indigo-400">
                                    {{ round((($selectedInstallment->paid_amount + $paymentAmount) /
                                    $selectedInstallment->amount) * 100, 1) }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2.5 rounded-full transition-all duration-300"
                                    style="width: {{ (($selectedInstallment->paid_amount + $paymentAmount) / $selectedInstallment->amount) * 100 }}%">
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Change Due -->
                        @if($changeAmount > 0)
                        <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-green-800 dark:text-green-400 flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Change Due:
                                </span>
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">ETB {{
                                    number_format($changeAmount, 2) }}</span>
                            </div>
                        </div>
                        @endif

                        <!-- Validation Summary -->
                        @if($paymentAmount > $selectedInstallment->getRemainingAmount())
                        <div
                            class="p-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Amount exceeds remaining balance
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Modal Actions -->
                <div
                    class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closePaymentModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="processPayment" @if(!$selectedInstallment || $paymentAmount <=0 ||
                        $paymentAmount> $selectedInstallment->getRemainingAmount()) disabled @endif
                        class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium
                        rounded-lg hover:from-green-700 hover:to-emerald-700 disabled:opacity-50
                        disabled:cursor-not-allowed transition-all shadow-lg hover:shadow-xl flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Plan Creation Modal -->
        @if($showPaymentPlanModal && $paymentOrder)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <!-- Modal Header -->
                <div
                    class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Create Payment Plan</h2>
                    <button wire:click="closePaymentPlanModal"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Order Summary</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Package Total:</span>
                                <span class="font-medium text-gray-900 dark:text-white">ETB {{
                                    number_format($packageTotal, 2) }}</span>
                            </div>
                            @if($bedCost > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Bed Cost:</span>
                                <span class="font-medium text-purple-600 dark:text-purple-400">ETB {{
                                    number_format($bedCost, 2) }}</span>
                            </div>
                            @endif
                            <div
                                class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-900 dark:text-white">Grand Total:</span>
                                <span class="text-indigo-600 dark:text-indigo-400">ETB {{ number_format($grandTotal, 2)
                                    }}</span>
                            </div>
                            @if($totalDurationDays > 0)
                            <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                Total Duration: {{ $totalDurationDays }} days
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Type Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Payment
                            Type</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" wire:click="$set('paymentType', 'full')"
                                class="p-4 border-2 rounded-xl text-center transition-all duration-200
                                    {{ $paymentType === 'full'
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-200 dark:ring-indigo-800'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700' }}">
                                <svg class="w-8 h-8 mx-auto mb-2 {{ $paymentType === 'full' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                    </path>
                                </svg>
                                <span
                                    class="block font-medium {{ $paymentType === 'full' ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">
                                    Full Payment
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Pay entire amount now</span>
                            </button>

                            <button type="button" wire:click="$set('paymentType', 'installment')"
                                class="p-4 border-2 rounded-xl text-center transition-all duration-200
                                    {{ $paymentType === 'installment'
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 ring-2 ring-indigo-200 dark:ring-indigo-800'
                                        : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700' }}">
                                <svg class="w-8 h-8 mx-auto mb-2 {{ $paymentType === 'installment' ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-400 dark:text-gray-500' }}"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                </svg>
                                <span
                                    class="block font-medium {{ $paymentType === 'installment' ? 'text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">
                                    Installment Plan
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">Split into multiple
                                    payments</span>
                            </button>
                        </div>
                    </div>

                    @if($paymentType === 'installment')
                    <!-- Installment Configuration -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Number of
                                Installments</label>
                            <div class="flex items-center gap-2">
                                <button type="button" wire:click="removeInstallment"
                                    class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                    {{ $installmentCount <=1 ? 'disabled' : '' }}>
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <span class="w-12 text-center font-bold text-xl text-gray-900 dark:text-white">{{
                                    $installmentCount }}</span>
                                <button type="button" wire:click="addInstallment"
                                    class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Installment Schedule -->
                        <div class="space-y-3">
                            <h4 class="font-medium text-gray-900 dark:text-white">Payment Schedule</h4>
                            @foreach($customInstallments as $index => $installment)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-medium text-gray-700 dark:text-gray-300">Installment #{{
                                        $installment['number'] }}</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $installment['percentage']
                                        }}% of total</span>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Amount
                                            (ETB)</label>
                                        <input type="number" step="0.01"
                                            wire:input="updateInstallmentAmount({{ $index }}, $event.target.value)"
                                            value="{{ $installment['amount'] }}"
                                            class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Due
                                            Date</label>
                                        <input type="date" wire:model="customInstallments.{{ $index }}.due_date"
                                            class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Total Verification -->
                        <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-4">
                            <div class="flex justify-between items-center">
                                <span class="font-medium text-indigo-800 dark:text-indigo-300">Total of
                                    Installments:</span>
                                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">
                                    ETB {{ number_format(array_sum(array_column($customInstallments, 'amount')), 2) }}
                                </span>
                            </div>
                            @if(abs(array_sum(array_column($customInstallments, 'amount')) - $grandTotal) > 0.01)
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">
                                Total does not match grand total. Please adjust.
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Summary Preview -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3">Payment Summary</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Type:</span>
                                <span class="font-medium text-gray-900 dark:text-white capitalize">{{ $paymentType
                                    }}</span>
                            </div>
                            @if($paymentType === 'installment')
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Installments:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $installmentCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">First Payment:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    ETB {{ number_format($customInstallments[0]['amount'] ?? 0, 2) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">First Due Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ isset($customInstallments[0]['due_date']) ?
                                    \Carbon\Carbon::parse($customInstallments[0]['due_date'])->format('M d, Y') : 'N/A'
                                    }}
                                </span>
                            </div>
                            @else
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Due Date:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ now()->format('M d, Y')
                                    }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div
                    class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closePaymentPlanModal"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="createInstallmentSchedule" @if($paymentType==='installment' &&
                        abs(array_sum(array_column($customInstallments, 'amount' )) - $grandTotal)> 0.01) disabled
                        @endif
                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-medium
                        rounded-lg
                        hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed
                        transition-all shadow-lg hover:shadow-xl">
                        Create Payment Plan
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Theme Toggle Script -->
    <script>
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Check for saved theme preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
