<div class="bg-white dark:bg-gray-900 transition-colors duration-200">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-6">Add Lab Results</h3>
        
        <!-- Lab Order Information -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div class="space-y-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Test</p>
                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $labOrder->labTest->name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Patient</p>
                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ $labOrder->order->encounter->patient->first_name }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</p>
                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">{{ ucfirst(str_replace('_', ' ', $labOrder->status)) }}</p>
                </div>
            </div>
        </div>

        <!-- Existing Results -->
        @if(count($results) > 0)
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300">Existing Results</h4>
                <span class="text-xs px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full">
                    {{ count($results) }} result(s)
                </span>
            </div>
            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Parameter</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Value</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Reference</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Flag</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($results as $result)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $result['parameter'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100 font-mono">{{ $result['value'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">{{ $result['unit'] }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 font-mono">{{ $result['reference_range'] }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                    $flagStyles = [
                                        'normal' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                                        'abnormal' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                                        'critical' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $flagStyles[$result['flag']] }}">
                                    {{ ucfirst($result['flag']) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <button wire:click="removeResult({{ $result['id'] }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:underline transition-colors text-sm font-medium"
                                        title="Remove this result">
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
        <div class="mb-8">
            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-4">Add New Result</h4>
            <form wire:submit.prevent="addResult" class="space-y-6">
                <!-- Input Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Parameter
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="parameter"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                               placeholder="e.g., Hemoglobin">
                        @error('parameter') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Value
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="value"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                               placeholder="e.g., 14.5">
                        @error('value') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unit
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               wire:model="unit"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                               placeholder="e.g., g/dL">
                        @error('unit') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reference Range
                        </label>
                        <input type="text"
                               wire:model="referenceRange"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                               placeholder="e.g., 13.0-17.0">
                        @error('referenceRange') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Flag
                            <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="flag"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="normal" class="text-gray-900 dark:text-gray-100">Normal</option>
                            <option value="abnormal" class="text-gray-900 dark:text-gray-100">Abnormal</option>
                            <option value="critical" class="text-gray-900 dark:text-gray-100">Critical</option>
                        </select>
                        @error('flag') 
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors">
                        Add Result
                    </button>
                </div>
            </form>
        </div>

        <!-- Success Message -->
        @if(session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm text-green-700 dark:text-green-300">{{ session('message') }}</span>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-between space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button"
                    wire:click="$dispatch('close-modal')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors">
                Cancel
            </button>
            
            @if(count($results) > 0)
            <button wire:click="saveAndReport"
                    wire:confirm="Are you sure you want to report these results? This will mark the lab order as completed."
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-900 transition-colors flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Save and Report
            </button>
            @endif
        </div>
    </div>
</div>