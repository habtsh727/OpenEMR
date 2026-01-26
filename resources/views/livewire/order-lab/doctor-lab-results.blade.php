<div class="p-6 bg-white rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Lab Results</h2>
        <div class="flex space-x-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by test or patient..."
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">

            <select wire:model.live="filterStatus"
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="reported">Reported</option>
                <option value="verified">Verified</option>
            </select>

            <select wire:model.live="filterPatient"
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-48">
                <option value="">All Patients</option>
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}">
                    {{ $patient->full_name }} ({{ $patient->medical_record_number ?? 'N/A' }})
                </option>
                @endforeach
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
                        Patient
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Test
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Ordered Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Reported Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Priority
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
                        <div class="text-sm text-gray-500">
                            MRN: {{ $order->order->encounter->patient->medical_record_number ?? 'N/A' }}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $order->labTest->name }}</div>
                        <div class="text-sm text-gray-500">{{ $order->labTest->code }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        @if($order->status === 'reported' || $order->status === 'verified')
                        {{ $order->updated_at->format('M d, Y H:i') }}
                        @else
                        <span class="text-gray-400">Pending</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                        $statusColors = [
                        'reported' => 'bg-green-100 text-green-800',
                        'verified' => 'bg-blue-100 text-blue-800',
                        'pending' => 'bg-gray-100 text-gray-800',
                        ];
                        @endphp
                        <span
                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($order->status) }}
                        </span>
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
                    <!-- In the actions column: -->
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button wire:click="viewResult({{ $order->id }})" x-data
                            x-on:click="$dispatch('open-modal', 'view-result')"
                            class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                            View Results
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No lab results found.
    </div>
    </tr>
    @endforelse
    </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $labOrders->links() }}
</div>

<!-- View Result Modal -->
@if($selectedResult)
<x-modal name="view-result" maxWidth="3xl">
    @livewire('order-lab.view-lab-result', ['labOrder' => \App\Models\LabOrder::find($selectedResult)])
</x-modal>
@endif
</div>