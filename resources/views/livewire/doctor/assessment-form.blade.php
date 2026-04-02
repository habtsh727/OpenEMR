<div>
    {{-- Alert Notification --}}
    @if($showAlert)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-6 right-6 z-50 max-w-sm w-full">
        <div class="rounded-xl shadow-xl overflow-hidden">
            <div class="flex items-center justify-between p-4 
                @if($alertType === 'success') bg-gradient-to-r from-green-500 to-emerald-600
                @elseif($alertType === 'error') bg-gradient-to-r from-red-500 to-rose-600
                @elseif($alertType === 'warning') bg-gradient-to-r from-amber-500 to-orange-600
                @else bg-gradient-to-r from-blue-500 to-blue-600
                @endif">
                <div class="flex items-center space-x-3">
                    @if($alertType === 'success')
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @elseif($alertType === 'error')
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @elseif($alertType === 'warning')
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @else
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
            {{-- Progress bar --}}
            <div class="h-1 w-full bg-gray-200">
                <div x-data="{ width: 100 }" x-init="width = 100; 
                             let interval = setInterval(() => { 
                                 width -= 2; 
                                 if(width <= 0) { 
                                     clearInterval(interval); 
                                     show = false; 
                                 } 
                             }, 100)" :style="`width: ${width}%`"
                    class="h-full bg-white/40 transition-all duration-100">
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Patient Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div
                                    class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <span class="text-white text-2xl font-bold">{{ substr($patient->name, 0, 1)
                                        }}</span>
                                </div>
                                <div
                                    class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-white flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Clinical Assessment</h1>
                                <p class="text-purple-100 mt-1">Document diagnoses and clinical impressions</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-purple-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span class="text-sm">{{ $patient->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-purple-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm">Step 4 of 5: Assessment</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Optional Step Indicator --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-center">
                                <div class="text-white text-sm font-medium mb-1">Assessment Status</div>
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="h-3 w-3 rounded-full bg-yellow-400 animate-pulse"></div>
                                    <span class="text-white font-semibold">Optional Step</span>
                                </div>
                                <div class="text-purple-200 text-xs mt-2">You may skip if no diagnosis</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Assessment Form --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            {{-- Form Header --}}
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $editingId ? 'Edit Diagnosis' : 'Add Diagnosis' }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Select multiple common diagnoses or enter custom diagnosis
                        </p>
                    </div>
                    @if($editingId)
                    <span
                        class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-sm font-medium rounded-full">
                        Editing Diagnosis
                    </span>
                    @endif
                </div>
            </div>

            {{-- Diagnosis Selection --}}
            <div class="p-6">
                <div class="space-y-6">
                    {{-- Template Search with Selection Controls --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Common Diagnoses (Multiple)
                            </label>
                            @if(count($templates) > 0 && !$editingId)
                            <button type="button" wire:click="selectAllVisible"
                                class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">
                                Select All {{ count($templates) }} shown
                            </button>
                            @endif
                        </div>

                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="searchTerm"
                                class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                                placeholder="Search common diagnoses...">
                        </div>

                        {{-- Template Results - Always Show Checkboxes for Multiple Selection --}}
                        @if(count($templates) > 0)
                        <div
                            class="mt-3 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden max-h-80 overflow-y-auto">
                            @foreach($templates as $template)
                            <label
                                class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 cursor-pointer
                                           {{ in_array($template->id, $selectedTemplateIds) ? 'bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500' : '' }}">
                                <input type="checkbox" wire:model="selectedTemplateIds" value="{{ $template->id }}"
                                    class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <div class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900 dark:text-white">{{ $template->diagnosis }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $template->context ??
                                        'General' }}</div>
                                </div>
                                @if(in_array($template->id, $selectedTemplateIds))
                                <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                @endif
                            </label>
                            @endforeach
                        </div>
                        @elseif($searchTerm)
                        <div
                            class="mt-3 p-4 text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700 rounded-lg">
                            No matching diagnoses found
                        </div>
                        @endif

                        {{-- Selected Templates Summary --}}
                        @if(count($selectedTemplateIds) > 0)
                        <div class="mt-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-medium text-sm text-gray-700 dark:text-gray-300">
                                    Selected: {{ count($selectedTemplateIds) }} diagnosis(es)
                                </div>
                                @if(count($selectedTemplateIds) > 0)
                                <button type="button" wire:click="clearSelection"
                                    class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                    Clear All
                                </button>
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-2">
                                @foreach($selectedTemplates as $template)
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">
                                    {{ $template->diagnosis }}
                                    <button type="button" wire:click="toggleTemplate({{ $template->id }})"
                                        class="ml-1.5 text-purple-400 hover:text-purple-600">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </span>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- OR Divider --}}
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white dark:bg-gray-800 text-gray-500 font-medium">OR</span>
                        </div>
                    </div>

                    {{-- Custom Diagnosis --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter Custom Diagnosis
                            <span class="text-gray-500 text-xs ml-1">(Will clear template selections)</span>
                        </label>
                        <input type="text" wire:model="customDiagnosis" wire:change="clearSelection"
                            class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                            placeholder="Type custom diagnosis...">
                        @error('customDiagnosis')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Diagnosis Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Diagnosis Type
                            </label>
                            <select wire:model="diagnosisType"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white">
                                <option value="primary">Primary Diagnosis</option>
                                <option value="secondary">Secondary Diagnosis</option>
                                <option value="differential">Differential Diagnosis</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Certainty
                            </label>
                            <select wire:model="diagnosisCertainty"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white">
                                <option value="provisional">Provisional</option>
                                <option value="confirmed">Confirmed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Clinical Notes
                                <span class="text-gray-500 text-xs ml-1">(Applies to all selected)</span>
                            </label>
                            <textarea wire:model="diagnosisNotes" rows="2"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                placeholder="Additional notes for all selected diagnoses..."></textarea>
                        </div>
                    </div>

                    {{-- Form Actions --}}
                    <div class="flex flex-wrap gap-3 pt-4">
                        @if($editingId)
                        <button type="button" wire:click="cancelEdit"
                            class="px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                            Cancel Edit
                        </button>
                        @endif

                        <div class="flex flex-wrap gap-3">
                            <button type="button" wire:click="addDiagnosis" wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>{{ $editingId ? 'Update Diagnosis' : 'Add Diagnosis' }}</span>
                            </button>

                            {{-- Save Assessment Button --}}
                            <button type="button" wire:click="saveAndContinue" wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Save Assessment</span>
                            </button>

                            {{-- Clear Form Button --}}
                            <button type="button" wire:click="resetForm"
                                class="px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                Clear Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diagnosis List --}}
        @if(count($diagnoses) > 0)
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div
                class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Current Diagnoses</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ count($diagnoses) }} diagnosis(es)
                            recorded</p>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        {{ count(array_filter($diagnoses, fn($d) => $d['type'] === 'primary')) }} primary
                    </div>
                </div>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($diagnoses as $diagnosis)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{
                                    $diagnosis['diagnosis'] }}</h3>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $diagnosis['type'] === 'primary' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                               ($diagnosis['type'] === 'secondary' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300') }}">
                                    {{ ucfirst($diagnosis['type']) }}
                                </span>
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $diagnosis['certainty'] === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                    {{ ucfirst($diagnosis['certainty']) }}
                                </span>
                                @if($diagnosis['is_custom'])
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    Custom
                                </span>
                                @endif
                            </div>

                            @if($diagnosis['notes'])
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $diagnosis['notes'] }}</p>
                            @endif
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            <button wire:click="editDiagnosis({{ $diagnosis['id'] }})"
                                class="p-2 text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                            </button>
                            <button wire:click="deleteDiagnosis({{ $diagnosis['id'] }})"
                                onclick="return confirm('Remove this diagnosis?')"
                                class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Navigation Actions --}}
       {{-- Navigation Actions --}}
<div class="sticky bottom-6 z-10">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
        <div class="flex flex-col xl:flex-row items-stretch xl:items-center justify-between gap-6">
            {{-- Left: Navigation & Primary Actions --}}
            <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4 flex-wrap">
                {{-- Back Navigation --}}
                <button type="button" wire:click="backToExamination"
                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center justify-center space-x-2 hover:shadow-md whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    <span>Back</span>
                </button>

                {{-- Primary Action Group --}}
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" wire:click="skipImaging"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        <span>Order Medication</span>
                    </button>

                    {{-- Order Rehab Button --}}
                    <button type="button" wire:click="orderRehabilitation"
                        class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 dark:from-teal-500 dark:to-cyan-600 dark:hover:from-teal-600 dark:hover:to-cyan-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>Order Rehabilitation</span>
                    </button>

                    {{-- Order Cupping Button --}}
                    <button type="button" wire:click="orderCupping"
                        class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 dark:from-amber-700 dark:to-orange-700 dark:hover:from-amber-800 dark:hover:to-orange-800 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 7H9L8 4zM6 13h12v4H6v-4z"></path>
                        </svg>
                        <span>Order Cupping</span>
                    </button>

                    {{-- Order Referral Button --}}
                    <button type="button" wire:click="orderReferral"
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 dark:from-purple-500 dark:to-pink-500 dark:hover:from-purple-600 dark:hover:to-pink-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center justify-center space-x-2 whitespace-nowrap">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span>Order Referral</span>
                    </button>

                    {{-- Lab Orders Button --}}
                    <button type="button" wire:click="nextToOrders"
                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center justify-center space-x-2 transform hover:-translate-y-0.5 active:translate-y-0 min-w-[160px] whitespace-nowrap">
                        <span>Lab Orders</span>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    {{-- Imaging Order Button --}}
                    <button type="button" wire:click="imagingOrder"
                        class="px-6 py-3 border-2 border-yellow-400 dark:border-yellow-600 rounded-xl text-sm font-semibold text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/30 hover:bg-yellow-100 dark:hover:bg-yellow-900/40 transition-all duration-200 flex items-center justify-center space-x-2 hover:shadow-md whitespace-nowrap">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Imaging Order</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Step Info --}}
        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap items-center justify-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <span class="inline-flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-500 dark:text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Assessment is <span class="font-semibold text-yellow-600 dark:text-yellow-400 ml-1">optional</span>
                </span>
                <span class="mx-2 text-gray-400 dark:text-gray-600">•</span>
                <span>You can skip if no diagnosis needed or proceed to orders.</span>
            </div>
        </div>
    </div>
</div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</div>
</div>