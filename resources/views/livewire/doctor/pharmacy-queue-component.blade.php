<div>
    <div class="bg-white rounded-lg shadow">
    <!-- Header -->
    <div class="px-6 py-4 border-b">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">Pharmacy Orders Queue</h2>
                <p class="text-sm text-gray-600">Prepare and dispense medications</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Search patient..."
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <select wire:model.live="status" 
                        class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="paid">Ready to Prepare</option>
                    <option value="approved">Ready to Dispense</option>
                    <option value="dispensed">Dispensed</option>
                    <option value="all">All Orders</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="px-6 py-4 bg-gray-50 border-b">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Paid Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_paid'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-yellow-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_approved'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Dispensed</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_dispensed'] }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-4 rounded-lg border">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                            <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_paid'] + $stats['total_approved'] + $stats['total_dispensed'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medications</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $order->id }}</div>
                        <div class="text-sm text-gray-500">
                            @if($order->discount_amount > 0)
                            <span class="text-red-600">-₦{{ number_format($order->discount_amount, 2) }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $order->encounter->patient->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->encounter->patient->age }} yrs, {{ $order->encounter->patient->gender }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">
                            @foreach($order->items->take(2) as $item)
                            <div class="flex items-center mb-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                    {{ $item->drug_id ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                    {{ $item->drug_id ? 'Standard' : 'Custom' }}
                                </span>
                                <span class="ml-2">{{ $item->drug?->name ?? $item->customMedication?->name }}</span>
                            </div>
                            @endforeach
                            @if($order->items->count() > 2)
                            <div class="text-xs text-gray-500">+ {{ $order->items->count() - 2 }} more</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y') }}<br>
                        <span class="text-xs">{{ $order->created_at->format('h:i A') }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            {{ $order->status === 'paid' ? 'bg-yellow-100 text-yellow-800' : 
                               ($order->status === 'approved' ? 'bg-blue-100 text-blue-800' : 
                               ($order->status === 'dispensed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        @if($order->status === 'paid')
                        <button wire:click="selectOrder({{ $order->id }})" 
                                class="text-blue-600 hover:text-blue-900 mr-3">
                            Prepare
                        </button>
                        @elseif($order->status === 'approved')
                        <button wire:click="selectOrder({{ $order->id }})" 
                                class="text-green-600 hover:text-green-900 mr-3">
                            Dispense
                        </button>
                        @endif
                        <a href="{{ route('prescription.view', $order->id) }}" 
                           target="_blank"
                           class="text-purple-600 hover:text-purple-900">
                            Prescription
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ $status === 'paid' ? 'No orders ready for preparation' : 'No orders match your criteria' }}
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t">
        {{ $orders->links() }}
    </div>
    @endif
</div>

<!-- Dispense Modal -->
@if($showDispenseModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            @php
                                $order = \App\Models\MedicationOrder::find($selectedOrderId);
                            @endphp
                            @if($order)
                                {{ $order->status === 'paid' ? 'Prepare Order' : 'Dispense Order' }}
                            @endif
                        </h3>
                        
                        @if($order)
                        <!-- Order Details -->
                        <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600">Patient:</span>
                                    <span class="font-medium ml-2">{{ $order->encounter->patient->name }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Order ID:</span>
                                    <span class="font-medium ml-2">#{{ $order->id }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Total:</span>
                                    <span class="font-medium ml-2">₦{{ number_format($order->payable_amount, 2) }}</span>
                                </div>
                                <div>
                                    <span class="text-gray-600">Payment:</span>
                                    <span class="font-medium ml-2">{{ $order->payment->payment_method ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stock Check -->
                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Medications & Stock Availability</h4>
                            <div class="space-y-3">
                                @foreach($stockItems as $item)
                                <div class="flex items-center justify-between p-3 border rounded-lg {{ $item['has_stock'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $item['name'] }}</div>
                                        <div class="text-sm text-gray-600">
                                            Required: {{ $item['required'] }} 
                                            @if($item['type'] === 'standard' && $item['available'] !== null)
                                            | Available: {{ $item['available'] }}
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($item['type'] === 'standard')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $item['has_stock'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $item['has_stock'] ? 'In Stock' : 'Low Stock' }}
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Custom
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea wire:model="dispensationNotes" rows="3"
                                      class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                      placeholder="Add any preparation or dispensation notes..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            @if($order->status === 'paid')
                            <button wire:click="approveOrder"
                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <span wire:loading.remove>Approve & Prepare</span>
                                <span wire:loading>Checking Stock...</span>
                            </button>
                            @elseif($order->status === 'approved')
                            <button wire:click="dispenseOrder"
                                    wire:loading.attr="disabled"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                                <span wire:loading.remove>Confirm Dispensation</span>
                                <span wire:loading>Updating Stock...</span>
                            </button>
                            @endif
                            <button type="button" 
                                    wire:click="resetDispenseModal"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Stock Warning Modal -->
@if($showStockModal)
<div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L6.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Insufficient Stock
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                The following medications have insufficient stock:
                            </p>
                            <ul class="mt-2 text-sm text-gray-700 list-disc list-inside">
                                @foreach($stockItems as $item)
                                    @if(!$item['has_stock'] && $item['type'] === 'standard')
                                    <li>{{ $item['name'] }} (Required: {{ $item['required'] }}, Available: {{ $item['available'] }})</li>
                                    @endif
                                @endforeach
                            </ul>
                            <p class="mt-3 text-sm text-gray-500">
                                Please update inventory before approving this order.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        wire:click="$set('showStockModal', false)"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Acknowledge
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@script
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('order-approved', (data) => {
            // Show success toast
            showToast('Order approved and ready for dispensation!', 'success');
        });
        
        Livewire.on('order-dispensed', (data) => {
            // Show success toast
            showToast('Medications dispensed successfully!', 'success');
        });
        
        Livewire.on('error', (data) => {
            // Show error toast
            showToast(data.message, 'error');
        });
        
        function showToast(message, type) {
            const bgColor = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
            const icon = type === 'success' ? 
                '<svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>' :
                '<svg class="h-6 w-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.502 0L6.342 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg>';
            
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 ${bgColor} px-4 py-3 rounded shadow-lg`;
            toast.innerHTML = `
                <div class="flex items-center">
                    ${icon}
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    });
</script>
@endscript
</div>