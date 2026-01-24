<div>
<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Patient Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <span class="text-white text-2xl font-bold">{{ substr($patient->name, 0, 1) }}</span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-white flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Clinical Assessment</h1>
                                <p class="text-purple-100 mt-1">Document diagnoses and clinical impressions</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-purple-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $patient->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-purple-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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

        {{-- Messages --}}
        @if (session()->has('message'))
            <div class="mb-6 animate-fade-in">
                <div class="flex items-center p-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('message') }}</span>
                </div>
            </div>
        @endif

        {{-- Assessment Form --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            {{-- Form Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Add Diagnosis</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Select from common diagnoses or enter custom</p>
                    </div>
                    @if($editingId)
                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-sm font-medium rounded-full">
                            Editing Diagnosis
                        </span>
                    @endif
                </div>
            </div>
            
            {{-- Diagnosis Selection --}}
            <div class="p-6">
                <div class="space-y-6">
                    {{-- Template Search --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Common Diagnosis
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" 
                                   wire:model.live.debounce.300ms="searchTerm"
                                   class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                                   placeholder="Search common diagnoses (e.g., Malaria, Pneumonia)...">
                        </div>
                        
                        {{-- Template Results --}}
                        @if($searchTerm)
                            <div class="mt-3 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                @forelse($templates as $template)
                                    <button type="button"
                                            wire:click="$set('selectedTemplateId', '{{ $template->id }}')"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 flex items-center justify-between
                                                   {{ $selectedTemplateId == $template->id ? 'bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500' : '' }}">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $template->diagnosis }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $template->context ?? 'General' }}</div>
                                        </div>
                                        @if($selectedTemplateId == $template->id)
                                            <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @endif
                                    </button>
                                @empty
                                    <div class="px-4 py-3 text-gray-500 dark:text-gray-400 text-center">
                                        No matching diagnoses found
                                    </div>
                                @endforelse
                            </div>
                        @endif
                        
                        {{-- Selected Template --}}
                        @if($selectedTemplateId && $selectedTemplate = $templates->firstWhere('id', $selectedTemplateId))
                            <div class="mt-3 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-medium text-purple-800 dark:text-purple-300">Selected Diagnosis</div>
                                        <div class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $selectedTemplate->diagnosis }}</div>
                                    </div>
                                    <button type="button" 
                                            wire:click="$set('selectedTemplateId', '')"
                                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
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
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">OR</span>
                        </div>
                    </div>
                    
                    {{-- Custom Diagnosis --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Enter Custom Diagnosis
                        </label>
                        <input type="text"
                               wire:model="customDiagnosis"
                               class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                               placeholder="Type custom diagnosis...">
                        @error('diagnosis') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
                            </label>
                            <textarea wire:model="diagnosisNotes"
                                      rows="1"
                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-800 dark:text-white"
                                      placeholder="Additional notes..."></textarea>
                        </div>
                    </div>
                    
                    {{-- Form Actions --}}
                    <div class="flex space-x-3 pt-4">
                        @if($editingId)
                            <button type="button"
                                    wire:click="cancelEdit"
                                    class="px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                Cancel Edit
                            </button>
                        @endif
                        
                        <button type="button"
                                wire:click="addDiagnosis"
                                wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span>{{ $editingId ? 'Update Diagnosis' : 'Add Diagnosis' }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Diagnosis List --}}
        @if(count($diagnoses) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Current Diagnoses</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ count($diagnoses) }} diagnosis(es) recorded</p>
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
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $diagnosis['diagnosis'] }}</h3>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $diagnosis['type'] === 'primary' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' :
                                               ($diagnosis['type'] === 'secondary' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300') }}">
                                            {{ ucfirst($diagnosis['type']) }}
                                        </span>
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $diagnosis['certainty'] === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' :
                                               'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                            {{ ucfirst($diagnosis['certainty']) }}
                                        </span>
                                        @if($diagnosis['is_custom'])
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
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
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button wire:click="deleteDiagnosis({{ $diagnosis['id'] }})"
                                            onclick="return confirm('Remove this diagnosis?')"
                                            class="p-2 text-gray-500 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
        <div class="sticky bottom-6 z-10">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-3">
                        <button type="button" 
                                wire:click="backToExamination"
                                class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 hover:shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span>Back to Examination</span>
                        </button>
                        
                        <button type="button"
                                wire:click="skipAssessment"
                                class="px-6 py-3 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Skip Assessment</span>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        @if(count($diagnoses) > 0)
                            <button type="button"
                                    wire:click="nextToOrders"
                                    class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                <span>Continue to Orders</span>
                            </button>
                        @endif
                        
                        <button type="button" 
                                wire:click="completeConsultation"
                                wire:loading.attr="disabled"
                                onclick="return confirm('Complete consultation and discharge patient?')"
                                class="px-6 py-3 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Complete Consultation</span>
                        </button>
                    </div>
                </div>
                
                {{-- Step Info --}}
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Assessment is <span class="font-medium text-yellow-600 dark:text-yellow-400">optional</span>. 
                        You can skip if no diagnosis needed or proceed to orders.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</div>
</div>
