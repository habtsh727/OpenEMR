<div>
    <div>
        <!-- Toast Notification -->
        <div x-data="{ show: @entangle('showToast').defer, message: @entangle('toastMessage').defer, type: @entangle('toastType').defer }"
            x-show="show && message" x-init="if (show) { setTimeout(() => show = false, 5000) }"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" class="fixed top-4 right-4 z-50 w-80">
            <div x-bind:class="type === 'success' ? 'bg-green-50 border-green-200 text-green-800 dark:bg-green-900/30 dark:border-green-800 dark:text-green-200' : 
                    type === 'error' ? 'bg-red-50 border-red-200 text-red-800 dark:bg-red-900/30 dark:border-red-800 dark:text-red-200' :
                    'bg-blue-50 border-blue-200 text-blue-800 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-200'"
                class="border rounded-lg shadow-lg dark:shadow-gray-900 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg x-show="type === 'success'" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="type === 'error'" class="h-5 w-5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="!['success','error'].includes(type)" class="h-5 w-5" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3 w-0 flex-1">
                        <p class="text-sm font-medium" x-text="message"></p>
                    </div>
                    <div class="ml-4 flex-shrink-0 flex">
                        <button @click="show = false"
                            class="inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900">
            <!-- Header -->
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            <i class="fas fa-pills mr-2"></i> Pharmacy Dashboard
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Manage medication orders, check stock, and dispense medications
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search patient, order ID..."
                                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <select wire:model.live="status"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="paid">Pending Approval</option>
                            <option value="approved">Approved</option>
                            <option value="dispensing">Dispensing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="all">All Orders</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Pending Approval -->
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Approval</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['pending']
                                    ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Approved -->
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['approved']
                                    ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dispensing -->
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Dispensing</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{
                                    $stats['dispensing'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Completed -->
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{
                                    $stats['completed'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Today's Orders -->
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Orders</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{
                                    $stats['today_orders'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Order ID</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Patient</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Items</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Amount</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Pharmacy Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Paid At</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($dispensations as $dispensation)
                        @php
                        $order = $dispensation->order;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $order->id }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $order->created_at->format('M
                                    d, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{
                                    $order->encounter->patient->name ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{
                                    $order->encounter->patient->id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $order->items->count() }} items
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">ETB{{
                                    number_format($order->payable_amount, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                        {{ $dispensation->status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' : 
                           ($dispensation->status === 'approved' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200' :
                           'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200') }}">
                                    {{ ucfirst($dispensation->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $order->payment->paid_at->format('M d, H:i') ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="viewOrder({{ $order->id }})"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        View
                                    </button>

                                    @if($dispensation->status === 'pending')
                                    <button wire:click="viewOrder({{ $order->id }})"
                                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Process
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <!-- No orders message -->
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- <!-- Pagination -->
            @if($orders->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $orders->links() }}
            </div>
            @endif --}}
        </div>

        <!-- Order Details Modal -->
        @if($showOrderDetails && $orderDetails)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b dark:border-gray-700">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="p-2 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m7 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="modal-title">
                                        Order #{{ $orderDetails->id }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Patient: {{ $orderDetails->encounter->patient->name ?? 'N/A' }}
                                    </p>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('showOrderDetails', false)"
                                class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                        <!-- Patient & Order Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Patient
                                    Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Name:</span> {{
                                        $orderDetails->encounter->patient->name ?? 'N/A' }}</div>
                                    <div><span class="font-medium">Age/Gender:</span> {{
                                        $orderDetails->encounter->patient->age ?? 'N/A' }} / {{
                                        $orderDetails->encounter->patient->gender ?? 'N/A' }}</div>
                                    <div><span class="font-medium">ID:</span> {{ $orderDetails->encounter->patient->id
                                        ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Order Information
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Order ID:</span> #{{ $orderDetails->id }}</div>
                                    <div><span class="font-medium">Status:</span>
                                        <span
                                            class="px-2 py-1 text-xs rounded-full {{ $orderDetails->status === 'paid' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($orderDetails->status) }}
                                        </span>
                                    </div>
                                    <div><span class="font-medium">Amount:</span> ETB{{
                                        number_format($orderDetails->payable_amount, 2) }}</div>
                                    <div><span class="font-medium">Paid At:</span> {{
                                        $orderDetails->payment->paid_at->format('M d, Y H:i') ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Prescribing Doctor
                                </h4>
                                <div class="space-y-2 text-sm">
                                    <div><span class="font-medium">Name:</span> {{
                                        $orderDetails->encounter->doctor->name ?? 'N/A' }}</div>
                                    <div><span class="font-medium">Department:</span> {{
                                        $orderDetails->encounter->doctor->department ?? 'N/A' }}</div>
                                    <div><span class="font-medium">Order Date:</span> {{
                                        $orderDetails->created_at->format('M d, Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Medications Table -->
                        <div class="mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Medications</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Medication</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Dosage</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Frequency</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Duration</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Qty</th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                                Stock Status</th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($orderDetails->items as $item)
                                        @php
                                        $stockInfo = $stockStatus[$item->id] ?? null;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-3">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $item->drug->name ?? $item->customMedication->name ?? 'Unknown'
                                                    }}
                                                </div>
                                                @if($item->instructions)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{
                                                    $item->instructions }}</div>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{
                                                $item->dosage }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{
                                                $item->frequency->name ?? '-' }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{
                                                $item->duration }}</td>
                                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{
                                                $item->quantity }}</td>
                                            <td class="px-4 py-3">
                                                @if($stockInfo)
                                                @if($stockInfo['type'] === 'custom')
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                                                    Custom
                                                </span>
                                                @elseif($stockInfo['sufficient'])
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                                    In Stock ({{ $stockInfo['available'] }})
                                                </span>
                                                @else
                                                <span
                                                    class="px-2 py-1 text-xs rounded-full bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                                                    Low Stock ({{ $stockInfo['available'] }}/{{ $stockInfo['required']
                                                    }})
                                                </span>
                                                @endif
                                                @else
                                                <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Stock Check Summary -->
                        <div
                            class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-3">Stock Availability
                                Summary</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                @php
                                $totalItems = count($stockStatus);
                                $sufficientItems = collect($stockStatus)->filter(fn($s) => $s['sufficient'])->count();
                                $customItems = collect($stockStatus)->filter(fn($s) => $s['type'] ===
                                'custom')->count();
                                @endphp
                                <div>
                                    <span class="text-blue-700 dark:text-blue-300">Total Items:</span>
                                    <span class="font-medium ml-2">{{ $totalItems }}</span>
                                </div>
                                <div>
                                    <span class="text-green-600 dark:text-green-400">In Stock:</span>
                                    <span class="font-medium ml-2">{{ $sufficientItems }}</span>
                                </div>
                                <div>
                                    <span class="text-purple-600 dark:text-purple-400">Custom Items:</span>
                                    <span class="font-medium ml-2">{{ $customItems }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                        

                            @if($orderDetails->status === 'paid')
                            <button wire:click="approveOrder" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-500 dark:hover:to-emerald-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Approve Order
                            </button>
                            @endif

                            @if($orderDetails->status === 'approved')
                            <button wire:click="startDispensing" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 dark:from-purple-600 dark:to-indigo-700 dark:hover:from-purple-500 dark:hover:to-indigo-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Start Dispensing
                            </button>
                            @endif

                            @if(in_array($orderDetails->status, ['paid', 'approved']))
                            <button wire:click="cancelOrder" wire:confirm="Are you sure you want to cancel this order?"
                                class="px-4 py-2 border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors">
                                Cancel Order
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Dispense Modal -->
        @if($showDispenseModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <!-- Modal Header -->
                                <div class="flex items-center mb-6">
                                    <div
                                        class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 dark:from-purple-600 dark:to-indigo-700 mr-3">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                                            id="modal-title">
                                            Dispense Medications
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Order #{{ $selectedOrderId }}
                                        </p>
                                    </div>
                                </div>

                                <form wire:submit.prevent="completeDispensing" class="space-y-4">
                                    <!-- Dispensed By -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dispensed
                                            By *</label>
                                        <input type="text" wire:model="dispensedBy"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm"
                                            placeholder="Enter pharmacist name">
                                        @error('dispensedBy') <span class="text-red-500 dark:text-red-400 text-xs">{{
                                            $message }}</span> @enderror
                                    </div>

                                    <!-- Dispensed At -->
                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dispensed
                                            Date & Time *</label>
                                        <input type="datetime-local" wire:model="dispensedAt"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm">
                                        @error('dispensedAt') <span class="text-red-500 dark:text-red-400 text-xs">{{
                                            $message }}</span> @enderror
                                    </div>

                                    <!-- Notes -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes
                                            (Optional)</label>
                                        <textarea wire:model="dispenseNotes" rows="3"
                                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-purple-500 dark:focus:ring-purple-600 focus:border-purple-500 dark:focus:border-purple-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm"
                                            placeholder="Any special instructions or notes..."></textarea>
                                    </div>

                                    <!-- Summary -->
                                    <div
                                        class="bg-purple-50 dark:bg-purple-900/30 p-4 rounded-lg border border-purple-200 dark:border-purple-800">
                                        <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200 mb-2">Ready
                                            for Dispensing</h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-300">
                                            All medications have been prepared and are ready for dispensing to the
                                            patient.
                                        </p>
                                    </div>

                                    <!-- Submit Buttons -->
                                    <div
                                        class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="submit" wire:loading.attr="disabled"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 dark:from-purple-600 dark:to-indigo-700 dark:hover:from-purple-500 dark:hover:to-indigo-600 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm transition-all duration-200 flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                            Mark as Dispensed
                                        </button>
                                        <button type="button" wire:click="$set('showDispenseModal', false)"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    @script
    <script>
        // Toast notifications
    Livewire.on('show-toast', (data) => {
        // You can implement a toast notification system here
        console.log('Toast:', data);
        
        // Example: Show a browser notification
        if (data.type === 'success') {
            alert('✅ ' + data.message + '\n' + (data.details || ''));
        } else if (data.type === 'error') {
            alert('❌ ' + data.message + '\n' + (data.details || ''));
        } else {
            alert('ℹ️ ' + data.message + '\n' + (data.details || ''));
        }
    });

    // Print order label
    Livewire.on('print-order-label', (data) => {
        // Implement printing logic here
        console.log('Print label for order:', data.orderId);
        
        // Example: Open print dialog
        window.open(`/pharmacy/orders/${data.orderId}/label`, '_blank');
    });
    </script>
    @endscript
</div>