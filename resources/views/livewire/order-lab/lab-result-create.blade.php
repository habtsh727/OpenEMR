<div>
    <div class="p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Lab Results</h3>
    
    <div class="mb-4">
        <div class="text-sm text-gray-600 mb-2">Test: <span class="font-medium">{{ $labOrder->labTest->name }}</span></div>
        <div class="text-sm text-gray-600 mb-2">Patient: <span class="font-medium">{{ $labOrder->order->encounter->patient->full_name }}</span></div>
        <div class="text-sm text-gray-600">Status: <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $labOrder->status)) }}</span></div>
    </div>

    <!-- Existing Results -->
    @if(count($results) > 0)
    <div class="mb-6">
        <h4 class="text-md font-medium text-gray-700 mb-3">Existing Results</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Value</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Flag</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($results as $result)
                    <tr>
                        <td class="px-4 py-2 text-sm">{{ $result['parameter'] }}</td>
                        <td class="px-4 py-2 text-sm">{{ $result['value'] }}</td>
                        <td class="px-4 py-2 text-sm">{{ $result['unit'] }}</td>
                        <td class="px-4 py-2 text-sm">{{ $result['reference_range'] }}</td>
                        <td class="px-4 py-2 text-sm">
                            @php
                                $flagColors = [
                                    'normal' => 'bg-green-100 text-green-800',
                                    'abnormal' => 'bg-yellow-100 text-yellow-800',
                                    'critical' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $flagColors[$result['flag']] }}">
                                {{ ucfirst($result['flag']) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-sm">
                            <button wire:click="removeResult({{ $result['id'] }})"
                                    class="text-red-500 hover:text-red-700">
                                Remove
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Add New Result Form -->
    <form wire:submit.prevent="addResult" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Parameter</label>
                <input type="text"
                       wire:model="parameter"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., Hb, WBC">
                @error('parameter') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Value</label>
                <input type="text"
                       wire:model="value"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., 14.5">
                @error('value') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <input type="text"
                       wire:model="unit"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., g/dL">
                @error('unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reference Range</label>
                <input type="text"
                       wire:model="referenceRange"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="e.g., 13.0-17.0">
                @error('referenceRange') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Flag</label>
                <select wire:model="flag"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="normal">Normal</option>
                    <option value="abnormal">Abnormal</option>
                    <option value="critical">Critical</option>
                </select>
                @error('flag') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Add Result
            </button>
        </div>
    </form>

    @if(session()->has('message'))
    <div class="mb-4 p-3 bg-green-50 text-green-700 rounded-lg text-sm">
        {{ session('message') }}
    </div>
    @endif

    <div class="flex justify-between space-x-4 pt-4 border-t">
        <button type="button"
                wire:click="$dispatch('close-modal')"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Cancel
        </button>
        @if(count($results) > 0)
        <button wire:click="saveAndReport"
                wire:confirm="Are you sure you want to report these results? This will mark the lab order as completed."
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            Save and Report
        </button>
        @endif
    </div>
</div>
</div>