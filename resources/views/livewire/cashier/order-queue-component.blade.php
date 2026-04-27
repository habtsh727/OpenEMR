<div>
    <div>
<div>
    <!-- Toast Notification -->
     <!-- Simple Toast Notification -->
    @if($toastMessage)
    <div class="fixed top-4 right-4 z-50 w-80" wire:ignore.self id="toast-message">
        <div class="{{ $toastType === 'success' ? 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200' : 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200' }} border rounded-lg shadow-lg dark:shadow-gray-900 p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    @if($toastType === 'success')
                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @else
                    <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endif
                </div>
                <div class="ml-3 w-0 flex-1">
                    <p class="text-sm font-medium">{{ $toastMessage }}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="document.getElementById('toast-message').remove()"
                            class="inline-flex text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-hide script -->
    <script>
        setTimeout(function() {
            const toast = document.getElementById('toast-message');
            if (toast) {
                toast.remove();
                // Also clear the Livewire property
                @this.set('toastMessage', '');
                @this.set('toastType', '');
            }
        }, 5000);
    </script>
    @endif

        <!-- The rest of your existing code... -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900">
        <!-- Header -->
        <div class="px-6 py-4 border-b dark:border-gray-700">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Medication Payments Queue</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Process payments for medication orders submitted by doctors
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text"
                               wire:model.live.debounce.300ms="search"
                               placeholder="Search patient, order ID..."
                               class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                        <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <select wire:model.live="status"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                        <option value="ordered">Pending Payment</option>
                        <option value="paid">Paid Orders</option>
                        <option value="all">All Orders</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Pending Payments -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Payments</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_ordered'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Paid Orders -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Paid Orders</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total_paid'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pending Amount -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Amount</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_pending_amount'], 2) }}Birr</p>
                        </div>
                    </div>
                </div>

                <!-- Collected Amount -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Collected Amount</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($stats['total_collected_amount'], 2) }}Birr</p>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">#{{ $order->id }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $order->items->count() }} items</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $order->encounter->patient->name }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $order->encounter->patient->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $order->encounter->doctor->name ?? 'N/A' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Dr.</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $order->created_at->format('M d, Y h:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Birr{{ number_format($order->payable_amount, 2) }}</div>
                            @if($order->discount_amount > 0)
                            <div class="text-xs text-red-600 dark:text-red-400">-Birr{{ number_format($order->discount_amount, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                {{ $order->status === 'ordered' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' :
                                   ($order->status === 'paid' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' :
                                   'bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($order->status === 'ordered')
                            <button wire:click="selectOrder({{ $order->id }})"
                                 class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-500 dark:hover:to-emerald-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 flex items-center shadow-sm hover:shadow">
                                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Process Payment
                            </button>
                            @endif

                            @if($order->status === 'paid')
                            <button wire:click="viewReceipt({{ $order->id }})"
                                    class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 mr-3 transition-colors">
                                View Receipt
                            </button>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No orders found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $status === 'ordered' ? 'No pending payments' : 'No orders match your criteria' }}
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    <!-- Payment Modal -->
    @if($showPaymentModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                    Process Payment
                                </h3>

                            <form wire:submit.prevent="processPayment" class="mt-4 space-y-4">
                                <!-- Order Details -->
                                @php
                                    $order = \App\Models\MedicationOrder::with(['encounter.patient'])->find($selectedOrderId);
                                @endphp
                                @if($order)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border dark:border-gray-700">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Patient:</span>
                                            <span class="font-medium ml-2 text-gray-900 dark:text-gray-100">{{ $order->encounter->patient->name }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Order Total:</span>
                                            <span class="font-medium ml-2 text-gray-900 dark:text-gray-100">Birr{{ number_format($order->payable_amount, 2) }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Order ID:</span>
                                            <span class="font-medium ml-2 text-gray-900 dark:text-gray-100">#{{ $order->id }}</span>
                                        </div>
                                        <div>
                                            <span class="text-gray-600 dark:text-gray-400">Items:</span>
                                            <span class="font-medium ml-2 text-gray-900 dark:text-gray-100">{{ $order->items->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Payment Amount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Amount (ETB) *</label>
                                    <input type="number" wire:model="paymentAmount" step="0.01" min="0"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm"
                                           placeholder="Enter amount">
                                    @error('paymentAmount') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Additional Discount -->
                                <div>
                                    <div class="flex justify-between items-center">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Discount (ETB)</label>
                                        <button type="button" wire:click="applyAdditionalDiscount"
                                                class="text-xs text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            Apply Discount
                                        </button>
                                    </div>
                                    <input type="number" wire:model="paymentDiscount" step="0.01" min="0"
                                           class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm"
                                           placeholder="Optional">
                                    @error('paymentDiscount') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror

                                    @if($paymentDiscount > 0)
                                    <div class="mt-1 text-sm text-green-600 dark:text-green-400">
                                        Final Amount:ETB{{ number_format($paymentAmount - $paymentDiscount, 2) }}
                                    </div>
                                    @endif
                                </div>

                                <!-- Payment Method -->
                                <div>
    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method *</label>
    <div class="mt-2 grid grid-cols-3 gap-3">
        <label class="relative flex cursor-pointer rounded-lg border {{ $paymentMethod === 'cash' ? 'border-blue-500 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800' }} p-4 shadow-sm focus:outline-none">
            <input type="radio" wire:model.live="paymentMethod" value="cash" class="sr-only">
            <span class="flex flex-1">
                <span class="flex flex-col">
                    <span class="block text-sm font-medium {{ $paymentMethod === 'cash' ? 'text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100' }}">Cash</span>
                </span>
            </span>
            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 {{ $paymentMethod != 'cash' ? 'invisible' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </label>

        <label class="relative flex cursor-pointer rounded-lg border {{ $paymentMethod === 'bank' ? 'border-blue-500 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800' }} p-4 shadow-sm focus:outline-none">
            <input type="radio" wire:model.live="paymentMethod" value="card" class="sr-only">
            <span class="flex flex-1">
                <span class="flex flex-col">
                    <span class="block text-sm font-medium {{ $paymentMethod === 'bank' ? 'text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100' }}">bank</span>
                </span>
            </span>
            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 {{ $paymentMethod != 'card' ? 'invisible' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </label>

        <label class="relative flex cursor-pointer rounded-lg border {{ $paymentMethod === 'insurance' ? 'border-blue-500 dark:border-blue-600 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800' }} p-4 shadow-sm focus:outline-none">
            <input type="radio" wire:model.live="paymentMethod" value="insurance" class="sr-only">
            <span class="flex flex-1">
                <span class="flex flex-col">
                    <span class="block text-sm font-medium {{ $paymentMethod === 'insurance' ? 'text-blue-900 dark:text-blue-100' : 'text-gray-900 dark:text-gray-100' }}">Insurance</span>
                </span>
            </span>
            <svg class="h-5 w-5 text-blue-600 dark:text-blue-400 {{ $paymentMethod != 'insurance' ? 'invisible' : '' }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
        </label>
    </div>
    @error('paymentMethod') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
</div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes (Optional)</label>
                                    <textarea wire:model="paymentNotes" rows="2"
                                              class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 sm:text-sm"
                                              placeholder="Any additional notes..."></textarea>
                                </div>

                                <!-- Payment Summary -->
                                <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">Payment Summary</h4>
                                    <div class="space-y-1 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-blue-700 dark:text-blue-300">Original Amount:</span>
                                            <span class="font-medium text-blue-900 dark:text-blue-100">ETB{{ number_format($order->payable_amount ?? 0, 2) }}</span>
                                        </div>
                                        @if($paymentDiscount > 0)
                                        <div class="flex justify-between">
                                            <span class="text-red-600 dark:text-red-400">Additional Discount:</span>
                                            <span class="font-medium text-red-700 dark:text-red-300">-ETB{{ number_format($paymentDiscount, 2) }}</span>
                                        </div>
                                        @endif
                                        <div class="flex justify-between font-bold pt-1 border-t border-blue-300 dark:border-blue-700">
                                            <span class="text-blue-800 dark:text-blue-200">Final Amount:</span>
                                            <span class="text-green-600 dark:text-green-400">ETB{{ number_format(($paymentAmount - $paymentDiscount), 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="submit"
                                            wire:loading.attr="disabled"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 dark:bg-green-700 text-base font-medium text-white hover:bg-green-700 dark:hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                        <span wire:loading.remove>Confirm & Process Payment</span>
                                        <span wire:loading>Processing...</span>
                                    </button>
                                    <button type="button"
                                            wire:click="$set('showPaymentModal', false)"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
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

    <!-- Receipt Modal -->
    @if($showReceiptModal && $receiptData)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Printable Receipt Area -->
                    <div id="printable-receipt" class="p-6">
                        <!-- Receipt Header -->
                        <div class="text-center border-b-2 border-gray-800 dark:border-gray-600 pb-4 mb-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Firdos Cultural Medical Center</h1>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Addis Ababa Ethiopia, Medical Center</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Phone: 0930-316631 | Email: billing@firdowusmedicalcenter.com/</p>
                        </div>

                        <!-- Receipt Title -->
                        <h2 class="text-xl font-bold text-center underline mb-6 text-gray-900 dark:text-gray-100">PAYMENT RECEIPT</h2>

                        <!-- Receipt Information -->
                        <div class="grid grid-cols-2 gap-8 mb-8">
                            <div>
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Patient Information:</h3>
                                <div class="space-y-1 text-sm">
                                    <div><span class="font-medium">Name:</span> {{ $receiptData['patient']->name }}</div>
                                    <div><span class="font-medium">ID:</span> {{ $receiptData['patient']->id }}</div>
                                    <div><span class="font-medium">Date:</span> {{ $receiptData['payment']->paid_at->format('F d, Y h:i A') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-2">Receipt Details:</h3>
                                <div class="space-y-1 text-sm">
                                    <div><span class="font-medium">Receipt No:</span> #{{ $receiptData['payment']->id }}</div>
                                    <div><span class="font-medium">Order No:</span> #{{ $receiptData['order']->id }}</div>
                                    <div><span class="font-medium">Payment Method:</span> {{ ucfirst($receiptData['payment']->payment_method) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Items Table -->
                        <div class="mb-8">
                            <table class="min-w-full border border-gray-300 dark:border-gray-600">
                                <thead class="bg-gray-100 dark:bg-gray-900">
                                    <tr>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Item Description</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Qty</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Unit Price</th>
                                        <th class="border border-gray-300 dark:border-gray-600 px-4 py-2 text-left">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($receiptData['items'] as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">
                                            <div class="font-medium">{{ $item->drug?->name ?? $item->customMedication?->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $item->dosage }} • {{ $item->frequency->name ?? '' }} • {{ $item->duration }}
                                            </div>
                                        </td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">{{ $item->quantity }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">ETB{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="border border-gray-300 dark:border-gray-600 px-4 py-2">ETB{{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Payment Summary -->
                        <div class="mb-8">
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h3 class="font-bold text-gray-700 dark:text-gray-300 mb-3">Payment Summary</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                        <span class="font-medium">ETB{{ number_format($receiptData['order']->total_amount, 2) }}</span>
                                    </div>
                                    @if($receiptData['order']->discount_amount > 0)
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 dark:text-gray-400">Total Discount:</span>
                                        <span class="font-medium text-red-600 dark:text-red-400">-ETB{{ number_format($receiptData['order']->discount_amount, 2) }}</span>
                                    </div>
                                    @endif
                                    <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-300 dark:border-gray-600">
                                        <span class="text-gray-800 dark:text-gray-200">Total Paid:</span>
                                        <span class="text-green-600 dark:text-green-400">ETB{{ number_format($receiptData['payment']->amount - $receiptData['payment']->discount, 2) }}</span>
                                    </div>
                                    @if($receiptData['payment']->discount > 0)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">(Includes additional cashier discount)</span>
                                        <span class="text-red-600 dark:text-red-400">-ETB{{ number_format($receiptData['payment']->discount, 2) }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Payment Details -->
                        <div class="mb-8 grid grid-cols-2 gap-6">
                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg">
                                <h3 class="font-bold text-blue-800 dark:text-blue-200 mb-2">Payment Details</h3>
                                <div class="space-y-1 text-sm">
                                    <div><span class="font-medium">Method:</span> {{ ucfirst($receiptData['payment']->payment_method) }}</div>
                                    <div><span class="font-medium">Amount Paid:</span> ETB{{ number_format($receiptData['payment']->amount, 2) }}</div>
                                    <div><span class="font-medium">Additional Discount:</span> ETB{{ number_format($receiptData['payment']->discount, 2) }}</div>
                                    <div><span class="font-medium">Net Amount:</span> ETB{{ number_format($receiptData['payment']->amount - $receiptData['payment']->discount, 2) }}</div>
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg">
                                <h3 class="font-bold text-green-800 dark:text-green-200 mb-2">Cashier Details</h3>
                                <div class="space-y-1 text-sm">
                                    <div><span class="font-medium">Cashier:</span> {{ $receiptData['payment']->cashier->name ?? 'N/A' }}</div>
                                    <div><span class="font-medium">Date/Time:</span> {{ $receiptData['payment']->paid_at->format('F d, Y h:i A') }}</div>
                                    @if($receiptData['payment']->notes)
                                    <div><span class="font-medium">Notes:</span> {{ $receiptData['payment']->notes }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="border-t-2 border-gray-800 dark:border-gray-600 pt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                            <p class="font-bold mb-2">Thank you for your payment!</p>
                            <p>This receipt is computer generated and does not require a signature.</p>
                            <p class="mt-1">For any queries, please contact billing@medicalhospital.com</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                                onclick="printReceipt()"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 dark:bg-blue-700 text-base font-medium text-white hover:bg-blue-700 dark:hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Print Receipt
                        </button>
                        <button type="button"
                                wire:click="closeReceiptModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

@script
<script>
    // Clear toast event listener
    Livewire.on('clear-toast', () => {
        setTimeout(() => {
            const toast = document.getElementById('toast-message');
            if (toast) {
                toast.remove();
            }
            // Clear Livewire properties
            @this.set('toastMessage', '');
            @this.set('toastType', '');
        }, 5000);
    });

    // Print receipt function
    window.printReceipt = function() {
        const printContent = document.getElementById('printable-receipt').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <div class="p-8">
                ${printContent}
            </div>
        `;

        window.print();

        // Restore content and reinitialize Livewire
        document.body.innerHTML = originalContent;
        Livewire.rescan();
    };
</script>
@endscript
</div>
</div>
