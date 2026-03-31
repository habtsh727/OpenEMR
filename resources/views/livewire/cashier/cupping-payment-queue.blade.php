<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6 mb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white flex items-center gap-2">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                        </svg>
                        Cupping Payment Queue
                    </h1>
                    <p class="text-purple-100 mt-1">Process payments for cupping therapy orders</p>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-white/20 rounded-lg p-3 text-center">
                        <p class="text-xs text-white">Pending</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_pending'] }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 text-center">
                        <p class="text-xs text-white">Today's Orders</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total_today'] }}</p>
                    </div>
                    <div class="bg-white/20 rounded-lg p-3 text-center">
                        <p class="text-xs text-white">Revenue Today</p>
                        <p class="text-xl font-bold text-white">{{ number_format($stats['total_revenue_today'], 2) }} ETB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4 shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                        <input type="text" 
                               wire:model.live="search" 
                               placeholder="Search by patient name or order ID..." 
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Payments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm mb-6 border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Pending Payments
                    <span class="ml-2 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">
                        {{ $stats['total_pending'] }} orders
                    </span>
                </h2>
            </div>
            
            @if(count($pendingPayments) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            32
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Encounter</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordered At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($pendingPayments as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $order['id'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $order['encounter']['patient']['name'] ?? 'N/A' }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $order['encounter']['patient']['phone'] ?? 'No phone' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        #{{ $order['encounter_id'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ count($order['items']) }} item(s)
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @foreach(array_slice($order['items'], 0, 2) as $item)
                                                {{ $item['cupping_type']['name'] ?? 'Unknown' }} ({{ $item['qty'] }}x)@if(!$loop->last), @endif
                                            @endforeach
                                            @if(count($order['items']) > 2)
                                                +{{ count($order['items']) - 2 }} more
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format($order['final_amount'], 2) }} ETB
                                        </div>
                                        @if($order['discount'] > 0)
                                            <div class="text-xs text-green-600 dark:text-green-400">
                                                Discount: {{ number_format($order['discount'], 2) }} ETB
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($order['created_at'])->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex space-x-2">
                                            <button wire:click="openPaymentModal({{ $order['id'] }})" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition">
                                                Process Payment
                                            </button>
                                            <button onclick="if(confirm('Are you sure you want to cancel this order?')) @this.cancelOrder({{ $order['id'] }})" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition">
                                                Cancel
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No pending payments</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">All cupping orders have been processed</p>
                </div>
            @endif
        </div>

        <!-- Completed Payments Today -->
        @if(count($completedPayments) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Completed Payments Today
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment Method</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Paid At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Receipt</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($completedPayments as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        #{{ $order['id'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $order['encounter']['patient']['name'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            {{ number_format($order['final_amount'], 2) }} ETB
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                                            @if($order['payment_method'] == 'cash') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @elseif($order['payment_method'] == 'card') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif($order['payment_method'] == 'insurance') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 @endif">
                                            {{ ucfirst($order['payment_method']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($order['paid_at'] ?? $order['updated_at'])->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button wire:click="openReceiptModal({{ $order['id'] }})" 
                                                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition">
                                            View Receipt
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Payment Modal -->
        @if($showPaymentModal && $selectedOrder)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closePaymentModal">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Process Payment</h3>
                        <button wire:click="closePaymentModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Order Details -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Order Details</h4>
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Order #:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedOrder['id'] }}</span>
                                
                                <span class="text-gray-600 dark:text-gray-400">Patient:</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedOrder['encounter']['patient']['name'] ?? 'N/A' }}</span>
                                
                                <span class="text-gray-600 dark:text-gray-400">Total Amount:</span>
                                <span class="font-bold text-green-600 dark:text-green-400">{{ number_format($order_total, 2) }} ETB</span>
                            </div>
                            
                            @if(count($selectedOrder['items']) > 0)
                                <div class="mt-3">
                                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Items:</h5>
                                    <div class="space-y-1">
                                        @foreach($selectedOrder['items'] as $item)
                                            <div class="text-xs text-gray-600 dark:text-gray-400">
                                                • {{ $item['cupping_type']['name'] ?? 'Unknown' }} - 
                                                {{ $item['cupping_location']['name'] ?? 'Unknown' }} - 
                                                {{ $item['qty'] }} x {{ number_format($item['price'], 2) }} = 
                                                {{ number_format($item['total'], 2) }} ETB
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Payment Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Method</label>
                                <select wire:model="payment_method" class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                    <option value="cash">Cash</option>
                                    <option value="card">Card</option>
                                    <option value="insurance">Insurance</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount Paid</label>
                                <input type="number" wire:model="amount_paid" step="0.01" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('amount_paid') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total due: {{ number_format($order_total, 2) }} ETB</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment Reference (Optional)</label>
                                <input type="text" wire:model="payment_reference" 
                                       class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                       placeholder="Transaction ID, Check #, etc.">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (Optional)</label>
                                <textarea wire:model="payment_notes" rows="2" 
                                          class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                          placeholder="Additional notes about payment..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 flex justify-end space-x-3">
                        <button wire:click="closePaymentModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                        <button wire:click="processPayment" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium">
                            Process Payment
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Receipt Modal -->
        @if($showReceiptModal && $selectedOrder)
            <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" wire:click.self="closeReceiptModal">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Payment Receipt</h3>
                        <button wire:click="closeReceiptModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <!-- Receipt Content -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Payment Receipt</h2>
                            <p class="text-gray-600 dark:text-gray-400">Cupping Therapy Order</p>
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Receipt #: {{ $selectedOrder['id'] }}</p>
                        </div>
                        
                        <div class="border-t border-b border-gray-200 dark:border-gray-700 py-4 mb-4">
                            <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($selectedOrder['paid_at'] ?? $selectedOrder['updated_at'])->format('F d, Y H:i') }}</span>
                                
                                <span class="text-gray-600 dark:text-gray-400">Patient:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ $selectedOrder['encounter']['patient']['name'] ?? 'N/A' }}</span>
                                
                                <span class="text-gray-600 dark:text-gray-400">Encounter ID:</span>
                                <span class="text-gray-900 dark:text-gray-100">#{{ $selectedOrder['encounter_id'] }}</span>
                                
                                <span class="text-gray-600 dark:text-gray-400">Payment Method:</span>
                                <span class="text-gray-900 dark:text-gray-100 capitalize">{{ $selectedOrder['payment_method'] }}</span>
                                
                                @if($selectedOrder['payment_reference'])
                                    <span class="text-gray-600 dark:text-gray-400">Reference:</span>
                                    <span class="text-gray-900 dark:text-gray-100">{{ $selectedOrder['payment_reference'] }}</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Items</h4>
                            <div class="space-y-2">
                                @foreach($selectedOrder['items'] as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ $item['cupping_type']['name'] ?? 'Unknown' }} - 
                                            {{ $item['cupping_location']['name'] ?? 'Unknown' }} 
                                            ({{ $item['qty'] }} x {{ number_format($item['price'], 2) }})
                                        </span>
                                        <span class="text-gray-900 dark:text-gray-100">{{ number_format($item['total'], 2) }} ETB</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span class="text-gray-900 dark:text-gray-100">{{ number_format($selectedOrder['total_amount'], 2) }} ETB</span>
                            </div>
                            @if($selectedOrder['discount'] > 0)
                                <div class="flex justify-between mb-2">
                                    <span class="text-gray-600 dark:text-gray-400">Discount:</span>
                                    <span class="text-green-600 dark:text-green-400">-{{ number_format($selectedOrder['discount'], 2) }} ETB</span>
                                </div>
                            @endif
                            <div class="flex justify-between text-lg font-bold pt-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-900 dark:text-gray-100">Total Paid:</span>
                                <span class="text-green-600 dark:text-green-400">{{ number_format($selectedOrder['amount_paid'], 2) }} ETB</span>
                            </div>
                        </div>
                        
                        @if($selectedOrder['payment_notes'])
                            <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedOrder['payment_notes'] }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-500">
                            <p>Thank you for your payment!</p>
                            <p class="text-xs mt-1">This is a computer-generated receipt. No signature required.</p>
                        </div>
                    </div>
                    
                    <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4 flex justify-end">
                        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium mr-2">
                            Print Receipt
                        </button>
                        <button wire:click="closeReceiptModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
    </div>
</div>