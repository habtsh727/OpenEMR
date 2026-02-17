<div class="max-w-7xl mx-auto p-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Rehab Payment Queue</h1>
                <p class="text-gray-600 mt-1">Process and manage rehabilitation payments</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                    Pending: {{ App\Models\RehabOrder::where('status', 'sent_to_cashier')->count() }}
                </span>
                <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">
                    Processed: {{ App\Models\RehabOrder::where('status', 'paid')->count() }}
                </span>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-4 mt-6 border-b border-gray-200">
            <button wire:click="$set('tab', 'pending')" 
                class="px-4 py-2 text-sm font-medium {{ $tab === 'pending' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                Pending Payments
            </button>
            <button wire:click="$set('tab', 'processed')" 
                class="px-4 py-2 text-sm font-medium {{ $tab === 'processed' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-gray-500 hover:text-gray-700' }}">
                Processed Payments
            </button>
        </div>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <div class="relative">
            <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by patient name..." 
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Packages</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#{{ $order->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $order->encounter->encounter->patient->name }}</div>
                        <div class="text-xs text-gray-500">ID: {{ $order->encounter->encounter->patient->id }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $order->encounter->encounter->doctor->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs rounded-full">
                            {{ $order->packages->count() }} packages
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($order->status === 'sent_to_cashier')
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Pending</span>
                        @elseif($order->status === 'paid')
                            <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Paid</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $order->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <button wire:click="viewOrder({{ $order->id }})" 
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>

                            @if($order->status === 'sent_to_cashier')
                                <a href="{{ route('cashier.rehab.payment', $order->id) }}" wire:navigate 
                                    class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Process Payment">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </a>
                            @elseif($order->status === 'paid')
                                <button wire:click="recheckPayment({{ $order->id }})" 
                                    class="p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="Recheck Payment">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        No orders found.
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
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                <h2 class="text-xl font-bold text-gray-900">Order Details #{{ $selectedOrder->id }}</h2>
                <button wire:click="closeModal" class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                <!-- Patient Info -->
                <div class="bg-gray-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Patient Information</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Name</p>
                            <p class="font-medium">{{ $selectedOrder->encounter->encounter->patient->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Doctor</p>
                            <p class="font-medium">{{ $selectedOrder->encounter->encounter->doctor->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Order Date</p>
                            <p class="font-medium">{{ $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <p class="font-medium">
                                @if($selectedOrder->status === 'sent_to_cashier')
                                    <span class="text-yellow-600">Pending Payment</span>
                                @elseif($selectedOrder->status === 'paid')
                                    <span class="text-green-600">Paid</span>
                                @endif
                            </p>
                        </div>
                        @if($selectedOrder->paid_at)
                        <div>
                            <p class="text-sm text-gray-500">Paid At</p>
                            <p class="font-medium">{{ $selectedOrder->paid_at->format('M d, Y H:i') }}</p>
                        </div>
                        @endif
                        @if($selectedOrder->payment_method)
                        <div>
                            <p class="text-sm text-gray-500">Payment Method</p>
                            <p class="font-medium capitalize">{{ $selectedOrder->payment_method }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Packages -->
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Ordered Packages</h3>
                    <div class="space-y-4">
                        @foreach($selectedOrder->packages as $package)
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900">{{ $package->package_name }}</h4>
                                <span class="font-bold text-indigo-600">${{ number_format($package->final_price, 2) }}</span>
                            </div>
                            
                            @if($package->items->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                @foreach($package->items as $item)
                                <div class="text-sm p-2 bg-gray-50 rounded">
                                    <div class="flex justify-between">
                                        <span class="font-medium">{{ $item->item_name }}</span>
                                        <span class="text-xs text-gray-500">{{ str_replace('_', ' ', $item->item_type) }}</span>
                                    </div>
                                    <div class="grid grid-cols-4 gap-2 mt-1 text-xs text-gray-600">
                                        @if($item->dosage)<div>Dosage: {{ $item->dosage }}</div>@endif
                                        @if($item->frequency)<div>Freq: {{ $item->frequency }}</div>@endif
                                        @if($item->duration)<div>Duration: {{ $item->duration }}</div>@endif
                                        @if($item->bed_duration_days)<div>Bed: {{ $item->bed_duration_days }} days</div>@endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total -->
                <div class="border-t pt-4 flex justify-between items-center">
                    <span class="text-lg font-semibold text-gray-900">Total Amount</span>
                    <span class="text-2xl font-bold text-indigo-600">${{ number_format($selectedOrder->total_amount, 2) }}</span>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3">
                <button wire:click="closeModal" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100">
                    Close
                </button>
                @if($selectedOrder->status === 'sent_to_cashier')
                <a href="{{ route('cashier.rehab.payment', $selectedOrder->id) }}" wire:navigate 
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Process Payment
                </a>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>