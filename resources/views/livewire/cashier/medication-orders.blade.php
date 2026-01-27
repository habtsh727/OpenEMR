<div>
    <div class="p-6">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Medication Orders - Cashier
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Process medication order payments
            </p>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div
                class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="flex items-center">
                    <div
                        class="h-10 w-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Orders</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPending }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex items-center">
                    <div
                        class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Amount Due</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalAmount, 2)
                            }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Search by patient name, MRN, or order ID..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
            </div>
        </div>

        <!-- Orders Table -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Order & Patient
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Items
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Amount
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $order->encounter->patient->first_name }} {{
                                    $order->encounter->patient->last_name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $order->items->count() }} item(s)
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="text-lg font-bold text-gray-900 dark:text-white">
                                    ${{ number_format($order->total_amount, 2) }}
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <button wire:click="selectOrder({{ $order->id }})"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    Process Payment
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No medication orders require payment processing.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="mt-6">
            {{ $orders->links() }}
        </div>
        @endif
    </div>

    <!-- Payment Modal -->
    @if($selectedOrder)
    <x-modal name="view-order" maxWidth="lg">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Process Payment
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Order #{{ $selectedOrder->id }}
                    </p>
                </div>
                <button @click="$dispatch('close-modal', 'view-order')" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Order Summary -->
            <div class="mb-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-gray-600 dark:text-gray-400">Patient:</span>
                        <span class="font-medium text-gray-900 dark:text-white">
                            {{ $selectedOrder->encounter->patient->first_name }} {{
                            $selectedOrder->encounter->patient->last_name }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">
                            ${{ number_format($selectedOrder->total_amount, 2) }}
                        </span>
                    </div>
                </div>

                <!-- Items List -->
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-2 font-medium text-gray-700 dark:text-gray-300">
                        Order Items
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-60 overflow-y-auto">
                        @foreach($selectedOrder->items as $item)
                        <div class="px-4 py-3">
                            <div class="flex justify-between">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $item->custom_name ?? $item->pharmacyItem->name ?? 'Item' }}
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $item->dosage }} × {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        ${{ number_format($item->subtotal, 2) }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        ${{ number_format($item->unit_price, 2) }} each
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button @click="$dispatch('close-modal', 'view-order')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button onclick="
    console.log('Payment button clicked');
    Livewire.dispatch('process-payment');
" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors">
                    Confirm Payment
                </button>
            </div>
        </div>
    </x-modal>
    @endif

    @if(session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg">
        {{ session('success') }}
    </div>
    @endif
</div>