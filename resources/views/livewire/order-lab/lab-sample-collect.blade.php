<div>
    <div class="p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Collect Sample</h3>
    
    <div class="mb-4">
        <div class="text-sm text-gray-600 mb-2">Test: <span class="font-medium">{{ $labOrder->labTest->name }}</span></div>
        <div class="text-sm text-gray-600 mb-2">Patient: <span class="font-medium">{{ $labOrder->order->encounter->patient->full_name }}</span></div>
        <div class="text-sm text-gray-600">Sample Type: <span class="font-medium">{{ $labOrder->labTest->sample_type }}</span></div>
    </div>

    <form wire:submit.prevent="save">
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sample Status</label>
                <select wire:model="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="collected">Collected</option>
                    <option value="accepted">Accepted for Processing</option>
                    <option value="rejected">Reject Sample</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            @if($status === 'rejected')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea wire:model="rejectionReason"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Enter reason for rejection..."></textarea>
                @error('rejectionReason') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>

        <div class="flex justify-end space-x-4 mt-6 pt-4 border-t">
            <button type="button"
                    wire:click="$dispatch('close-modal')"
                    class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Save Sample Status
            </button>
        </div>
    </form>
</div>
</div>