<div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
    <div class="max-w-6xl mx-auto">
        
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Chief Complaint</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Patient: {{ $encounter->patient->name }}</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Checkboxes Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 mb-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Select Complaints</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($templates as $template)
                    @php $isSelected = in_array($template->id, $selected) @endphp
                    <button type="button"
                            wire:click="toggleComplaint({{ $template->id }})"
                            class="border rounded-lg p-4 text-left transition-all duration-200 ease-in-out
                                   {{ $isSelected 
                                        ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-700' 
                                        : 'border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                        <div class="flex items-center">
                            <div class="h-5 w-5 border rounded mr-3 flex items-center justify-center flex-shrink-0
                                      {{ $isSelected 
                                        ? 'bg-blue-600 dark:bg-blue-500 border-blue-600 dark:border-blue-500' 
                                        : 'border-gray-400 dark:border-gray-500' }}">
                                @if($isSelected)
                                    <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                    </svg>
                                @endif
                            </div>
                            <span class="{{ $isSelected 
                                ? 'font-medium text-blue-900 dark:text-blue-300' 
                                : 'text-gray-900 dark:text-gray-300' }}">
                                {{ $template->name }}
                            </span>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Details for Selected Complaints -->
        @if(count($selected) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 mb-6 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Complaint Details</h2>
                
                @foreach($selected as $id)
                    @php $template = $templates->find($id) @endphp
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4 last:mb-0">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-3">{{ $template->name }}</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <!-- Duration -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                                <input type="text"
                                       wire:model="details.{{ $id }}.duration"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                              bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                              focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent
                                              placeholder:text-gray-500 dark:placeholder:text-gray-400"
                                       placeholder="e.g., 3 days">
                            </div>
                            
                            <!-- Severity Select -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Severity</label>
                                <select wire:model="details.{{ $id }}.severity"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                               bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                               focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
                                    <option value="mild">Mild</option>
                                    <option value="moderate">Moderate</option>
                                    <option value="severe">Severe</option>
                                </select>
                            </div>
                            
                            <!-- Severity Visual Indicator -->
                            <div class="flex items-center space-x-3">
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="h-2 rounded-full transition-all duration-300
                                                {{ $details[$id]['severity'] == 'mild' ? 'bg-green-500 w-1/3' : 
                                                   ($details[$id]['severity'] == 'moderate' ? 'bg-yellow-500 w-2/3' : 
                                                   'bg-red-500 w-full') }}">
                                    </div>
                                </div>
                                <span class="text-sm font-medium capitalize min-w-[80px]
                                             {{ $details[$id]['severity'] == 'mild' ? 'text-green-600 dark:text-green-400' : 
                                                ($details[$id]['severity'] == 'moderate' ? 'text-yellow-600 dark:text-yellow-400' : 
                                                'text-red-600 dark:text-red-400') }}">
                                    {{ $details[$id]['severity'] ?? 'moderate' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <textarea wire:model="details.{{ $id }}.notes"
                                      rows="3"
                                      class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 
                                             bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100
                                             focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent
                                             placeholder:text-gray-500 dark:placeholder:text-gray-400"
                                      placeholder="Additional details about this complaint..."></textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm dark:shadow-gray-700/20 p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <button wire:click="back"
                        class="px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg 
                               text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700
                               w-full sm:w-auto transition-colors duration-200">
                    ← Back
                </button>
                
                <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                    <button wire:click="save"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 
                                   text-white rounded-lg font-medium transition-colors duration-200
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   w-full sm:w-auto">
                        Save
                    </button>
                    
                    <button wire:click="next"
                            wire:loading.attr="disabled"
                            class="px-5 py-2.5 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 
                                   text-white rounded-lg font-medium transition-colors duration-200
                                   disabled:opacity-50 disabled:cursor-not-allowed
                                   w-full sm:w-auto">
                        Save & Continue to Examination →
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>