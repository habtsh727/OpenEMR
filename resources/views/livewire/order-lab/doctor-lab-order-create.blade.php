<div>
    {{-- Stop trying to control. --}}
    <div class="p-6 bg-white rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Create Lab Order</h2>
            <div>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    Patient: {{ $encounter->patient->full_name }}
                </span>
            </div>
        </div>

        <!-- Selected Tests -->
        @if(count($selectedTestIds) > 0)
        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Selected Tests ({{ count($selectedTestIds) }})</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-4">
                @foreach($selectedTests as $test)
                <div class="flex justify-between items-center p-3 bg-white border rounded-lg">
                    <div>
                        <span class="font-medium">{{ $test->name }}</span>
                        <span class="text-sm text-gray-500 ml-2">({{ $test->code }})</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="text-green-600 font-medium">${{ number_format($test->price, 2) }}</span>
                        <button wire:click="removeTest({{ array_search($test->id, $selectedTestIds) }})"
                            class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Only show total if we have tests -->
            @if(count($selectedTestIds) > 0)
            <div class="flex justify-between items-center">
                <div class="text-lg font-bold text-gray-800">
                    Total: ${{ number_format($this->totalAmount, 2) }}
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Order Details Form -->
        <form wire:submit.prevent="submitOrder">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                    <select wire:model="priority"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT</option>
                    </select>
                    @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea wire:model="notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Add any additional notes..."></textarea>
                </div>
            </div>

            <!-- Test Search and Selection -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-700">Available Lab Tests</h3>
                    <div class="w-64">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search tests..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="overflow-x-auto bg-white rounded-lg shadow">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Test
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Code
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Sample Type
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Department
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($labTests as $test)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $test->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        {{ $test->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $test->sample_type }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-500">{{ $test->department }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-green-600">${{ number_format($test->price, 2)
                                        }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if(in_array($test->id, $selectedTestIds))
                                    <span class="text-green-600">Selected</span>
                                    @else
                                    <button type="button" wire:click="addTest({{ $test->id }})"
                                        class="text-blue-600 hover:text-blue-900">
                                        Add
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    No lab tests found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $labTests->links() }}
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-4">
                {{-- <a href="{{ route('encounters.show', $encounter) }}"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a> --}}
                <button type="submit" @if(count($selectedTestIds)===0) disabled @endif
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    Create Lab Order
                </button>
            </div>
        </form>
    </div>
</div>