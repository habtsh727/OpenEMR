<div>
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Collect Sample</h3>
    
    <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            <span class="font-medium dark:text-white">Test:</span> 
            <span class="ml-2 text-gray-800 dark:text-white">{{ $labOrder->labTest->name }}</span>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            <span class="font-medium dark:text-white">Patient:</span> 
            <span class="ml-2 text-gray-800 dark:text-white">{{ $labOrder->order->encounter->patient->full_name }}</span>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
            <span class="font-medium dark:text-white">Order ID:</span> 
            <span class="ml-2 text-gray-800 dark:text-white">#{{ $labOrder->id }}</span>
        </div>
        <div class="text-sm text-gray-600 dark:text-gray-400">
            <span class="font-medium dark:text-white">Sample Type:</span> 
            <span class="ml-2 text-gray-800 dark:text-white">{{ $labOrder->labTest->sample_type }}</span>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <div class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    Sample Status
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Collected Option -->
                    <label class="relative flex cursor-pointer">
                        <input type="radio" 
                               wire:model="status" 
                               value="collected"
                               class="sr-only peer">
                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:ring-2 peer-checked:ring-blue-500 transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-blue-100 dark:bg-blue-800 rounded-full flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Collected</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Sample has been collected</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Accepted Option -->
                    <label class="relative flex cursor-pointer">
                        <input type="radio" 
                               wire:model="status" 
                               value="accepted"
                               class="sr-only peer">
                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:ring-2 peer-checked:ring-green-500 transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-green-100 dark:bg-green-800 rounded-full flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Accepted</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Accepted for processing</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Pending Option -->
                    <label class="relative flex cursor-pointer">
                        <input type="radio" 
                               wire:model="status" 
                               value="pending"
                               class="sr-only peer">
                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 peer-checked:ring-2 peer-checked:ring-yellow-500 transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-yellow-100 dark:bg-yellow-800 rounded-full flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Pending</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Awaiting collection</div>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- Rejected Option -->
                    <label class="relative flex cursor-pointer">
                        <input type="radio" 
                               wire:model="status" 
                               value="rejected"
                               class="sr-only peer">
                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:ring-2 peer-checked:ring-red-500 transition-all">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-red-100 dark:bg-red-800 rounded-full flex items-center justify-center mr-3">
                                    <svg class="h-4 w-4 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">Rejected</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Reject sample</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                
                @error('status') 
                <div class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </div>
                @enderror
            </div>

            @if($status === 'rejected')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Rejection Reason
                    <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="rejectionReason"
                          rows="3"
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 dark:focus:ring-red-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors"
                          placeholder="Enter reason for sample rejection..."></textarea>
                @error('rejectionReason') 
                <div class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $message }}
                </div>
                @enderror
            </div>
            @endif

            <!-- Current Status Info -->
            @php
                $currentSample = $labOrder->labSamples()->first();
            @endphp
            @if($currentSample)
            <div class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Current sample status: 
                    <span class="ml-2 px-2 py-1 text-xs font-semibold rounded-full 
                        @if($currentSample->status === 'collected') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                        @elseif($currentSample->status === 'accepted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($currentSample->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @endif">
                        {{ ucfirst($currentSample->status) }}
                    </span>
                </div>
                @if($currentSample->collected_at)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Collected: {{ $currentSample->collected_at->format('M d, Y H:i') }}
                </div>
                @endif
            </div>
            @endif
        </div>

        <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
            <button type="button"
                    wire:click="$dispatch('closeModal')"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800 transition-colors">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-600 dark:hover:to-indigo-700 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 font-medium transition-colors">
                <span wire:loading.remove wire:target="save">
                    Update Sample Status
                </span>
                <span wire:loading wire:target="save">
                    <svg class="animate-spin h-4 w-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </button>
        </div>
    </form>
</div>