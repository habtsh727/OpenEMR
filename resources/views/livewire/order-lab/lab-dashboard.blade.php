<div>
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Laboratory Dashboard</h2>
            <div class="flex space-x-4">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tests..."
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select wire:model.live="filterStatus"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="sample_collected">Sample Collected</option>
                    <option value="processing">Processing</option>
                    <option value="reported">Reported</option>
                </select>
                <select wire:model.live="filterPriority"
                    class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Priorities</option>
                    <option value="routine">Routine</option>
                    <option value="urgent">Urgent</option>
                    <option value="stat">STAT</option>
                </select>
            </div>
        </div>

        @if(session()->has('message'))
        <div class="mb-4 p-4 bg-green-50 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
        @endif

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Patient & Test
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Sample Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Priority
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ordered
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($labOrders as $order)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $order->order->encounter->patient->full_name }}
                            </div>
                            <div class="text-sm text-gray-900">{{ $order->labTest->name }}</div>
                            <div class="text-sm text-gray-500">
                                {{ $order->labTest->code }} • {{ $order->labTest->sample_type }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $sample = $order->labSamples->first();
                            $sampleColors = [
                            'pending' => 'bg-gray-100 text-gray-800',
                            'collected' => 'bg-blue-100 text-blue-800',
                            'accepted' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $sampleColors[$sample->status ?? 'pending'] }}">
                                {{ ucfirst($sample->status ?? 'pending') }}
                            </span>
                            @if($sample && $sample->collected_at)
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $sample->collected_at->format('M d, H:i') }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $priorityColors = [
                            'routine' => 'bg-blue-100 text-blue-800',
                            'urgent' => 'bg-yellow-100 text-yellow-800',
                            'stat' => 'bg-red-100 text-red-800',
                            ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$order->priority] }}">
                                {{ ucfirst($order->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                            'pending' => 'bg-gray-100 text-gray-800',
                            'sample_collected' => 'bg-blue-100 text-blue-800',
                            'processing' => 'bg-yellow-100 text-yellow-800',
                            'reported' => 'bg-green-100 text-green-800',
                            ];
                            @endphp
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] }}">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if($order->status === 'pending' || $order->status === 'sample_collected')
                            <button wire:click="$set('selectedLabOrder', {{ $order->id }})" wire:loading.attr="disabled"
                                wire:target="selectedLabOrder" x-data @click="$dispatch('open-modal', 'collect-sample')"
                                class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                Collect Sample
                            </button>
                            @endif

                            @if($order->status === 'processing' || $order->status === 'sample_collected')
                            <button wire:click="$set('selectedLabOrder', {{ $order->id }})" wire:loading.attr="disabled"
                                wire:target="selectedLabOrder" x-data @click="$dispatch('open-modal', 'add-result')"
                                class="px-3 py-1 bg-purple-600 text-white rounded-md hover:bg-purple-700 text-sm">
                                Add Result
                            </button>
                            @endif

                            @if($order->status === 'processing' && $order->labResults->count() > 0)
                            <button wire:click="markCompleted({{ $order->id }})"
                                wire:confirm="Mark this lab order as completed?"
                                class="px-3 py-1 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                                Mark Complete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No paid lab orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $labOrders->links() }}
        </div>

        <!-- Modals -->
        @if($selectedLabOrder)
        <!-- Collect Sample Modal -->
        <x-modal name="collect-sample">
            @livewire('order-lab.lab-sample-collect', ['labOrder' => \App\Models\LabOrder::find($selectedLabOrder)])
        </x-modal>

        <!-- Add Result Modal -->
        <x-modal name="add-result">
            @livewire('order-lab.lab-result-create', ['labOrder' => \App\Models\LabOrder::find($selectedLabOrder)])
        </x-modal>
        @endif
    </div>
</div>