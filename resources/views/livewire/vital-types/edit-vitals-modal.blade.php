<div>
    @if($showModal)
    <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Vital Signs</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Encounter #{{ $encounter->id }} - {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                    </p>
                </div>
                <button wire:click="closeModal" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-6">
                @if(!$editable)
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-sm text-red-700 dark:text-red-400">
                            You don't have permission to edit these vitals.
                        </p>
                    </div>
                </div>
                @endif

                <!-- Vitals Form -->
                <div class="space-y-4">
                    @foreach($vitals as $vitalTypeId => $vital)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center p-4 {{ $vital['has_changes'] ? 'bg-amber-50 dark:bg-amber-900/20 rounded-lg' : '' }}">
                        <div>
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ $vital['name'] }}
                                @if($vital['unit'])
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">({{ $vital['unit'] }})</span>
                                @endif
                            </label>
                        </div>

                        <div class="md:col-span-2">
                            @if($vital['data_type'] === 'number')
                                <input type="number" 
                                    step="any"
                                    wire:model.live="vitals.{{ $vitalTypeId }}.value"
                                    @if(!$editable) disabled @endif
                                    class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800 
                                        @if($vital['has_changes']) border-amber-300 dark:border-amber-700 @else border-gray-300 dark:border-gray-600 @endif
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white
                                        @if(!$editable) bg-gray-100 dark:bg-gray-700 cursor-not-allowed @endif">
                            
                            @elseif($vital['data_type'] === 'boolean')
                                <div class="flex gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" 
                                            wire:model.live="vitals.{{ $vitalTypeId }}.value" 
                                            value="1"
                                            @if(!$editable) disabled @endif
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2">
                                        <input type="radio" 
                                            wire:model.live="vitals.{{ $vitalTypeId }}.value" 
                                            value="0"
                                            @if(!$editable) disabled @endif
                                            class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">No</span>
                                    </label>
                                </div>
                            
                            @elseif($vital['data_type'] === 'select' && $vital['options'])
                                <select wire:model.live="vitals.{{ $vitalTypeId }}.value"
                                    @if(!$editable) disabled @endif
                                    class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800
                                        @if($vital['has_changes']) border-amber-300 dark:border-amber-700 @else border-gray-300 dark:border-gray-600 @endif
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white
                                        @if(!$editable) bg-gray-100 dark:bg-gray-700 cursor-not-allowed @endif">
                                    <option value="">Select option</option>
                                    @foreach(is_array($vital['options']) ? $vital['options'] : json_decode($vital['options'], true) as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            
                            @else
                                <input type="text" 
                                    wire:model.live="vitals.{{ $vitalTypeId }}.value"
                                    @if(!$editable) disabled @endif
                                    class="w-full px-3 py-2 border rounded-lg bg-white dark:bg-gray-800
                                        @if($vital['has_changes']) border-amber-300 dark:border-amber-700 @else border-gray-300 dark:border-gray-600 @endif
                                        focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:text-white
                                        @if(!$editable) bg-gray-100 dark:bg-gray-700 cursor-not-allowed @endif">
                            @endif

                            @error("vitals.{$vitalTypeId}.value")
                                <p class="mt-1 text-xs text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Edit Reason -->
                @if($editable)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Reason for editing <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        wire:model="editReason"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white"
                        placeholder="Please explain why you are editing these vitals..."></textarea>
                    @error('editReason')
                        <p class="mt-1 text-xs text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            <!-- Modal Footer -->
            <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                <button wire:click="closeModal" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                
                @if($editable)
                <button wire:click="saveVitals" wire:loading.attr="disabled"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors">
                    <span wire:loading.remove wire:target="saveVitals">Save Changes</span>
                    <span wire:loading wire:target="saveVitals">Saving...</span>
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>