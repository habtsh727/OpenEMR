<div>
    <div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Patient Header Card --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                                    <span class="text-white text-2xl font-bold">{{ substr($patient->name, 0, 1) }}</span>
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-white flex items-center justify-center">
                                    @if($patient->gender === 'Male')
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-white">Examination</h1>
                                <p class="text-blue-100 mt-1">Documenting physical findings for patient</p>
                                <div class="flex items-center space-x-4 mt-2">
                                    <div class="flex items-center space-x-2 text-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $patient->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="text-sm">Card: {{ $patient->card_number }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 text-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm">{{ $patient->age }}y • {{ $patient->gender }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Consultation Status --}}
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <div class="text-center">
                                <div class="text-white text-sm font-medium mb-1">Consultation Status</div>
                                <div class="flex items-center justify-center space-x-2">
                                    <div class="h-3 w-3 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-white font-semibold">In Progress</span>
                                </div>
                                <div class="text-blue-200 text-xs mt-2">Step 3 of 4: Examination</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success/Error Messages --}}
        @if (session()->has('success'))
            <div class="mb-6 animate-fade-in">
                <div class="flex items-center p-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl shadow-lg">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Examination Progress Indicator --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="h-2 w-24 rounded-full bg-green-500"></div>
                    <div class="h-2 w-24 rounded-full bg-green-500"></div>
                    <div class="h-2 w-24 rounded-full bg-blue-500"></div>
                    <div class="h-2 w-24 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">
                    <span class="text-blue-600 dark:text-blue-400">Examination</span> • Next: Diagnosis
                </div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                <span class="text-green-600 dark:text-green-400 font-medium">✓ Medical History</span>
                <span class="text-green-600 dark:text-green-400 font-medium">✓ Chief Complaint</span>
                <span class="text-blue-600 dark:text-blue-400 font-medium">● Examination</span>
                <span>Diagnosis & Treatment</span>
            </div>
        </div>

        {{-- Examination Form --}}
        <form wire:submit.prevent="saveExamination" class="space-y-8">
            {{-- System Navigation Tabs --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex overflow-x-auto -mb-px">
                        @foreach($examinationSystems as $system => $templates)
                            <button type="button"
                                    onclick="document.getElementById('system-{{ Str::slug($system) }}').scrollIntoView({ behavior: 'smooth' })"
                                    class="px-6 py-4 text-sm font-medium whitespace-nowrap border-b-2 transition-colors duration-200
                                        {{ $loop->first ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 hover:border-gray-300' }}">
                                {{ $system }}
                                <span class="ml-2 px-2 py-1 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                    {{ count($templates) }}
                                </span>
                            </button>
                        @endforeach
                    </nav>
                </div>
            </div>

            {{-- Examination Sections --}}
            @foreach($examinationSystems as $system => $templates)
                <div id="system-{{ Str::slug($system) }}"
                     class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    {{-- System Header --}}
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-lg flex items-center justify-center 
                                    {{ $system === 'General' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' :
                                       ($system === 'Vitals' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' :
                                       ($system === 'Respiratory' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' :
                                       ($system === 'Abdomen' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' :
                                       'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'))) }}">
                                    @switch($system)
                                        @case('General')<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>@break
                                        @case('Vitals')<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>@break
                                        @case('Respiratory')<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4 4 0 003 15z"></path></svg>@break
                                        @case('Abdomen')<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>@break
                                        @default<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    @endswitch
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $system }} Examination</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ count($templates) }} parameters to assess</p>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}/{{ count($examinationSystems) }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Examination Fields --}}
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($templates as $template)
                                @php
                                    $existingValue = $template->encounterExaminations->first()->value ?? null;
                                    $isRequired = !in_array($template->field_type, ['number', 'text']);
                                @endphp
                                
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $template->name }}
                                            @if(!$isRequired)
                                                <span class="text-gray-500 text-xs ml-1">(Optional)</span>
                                            @endif
                                        </label>
                                        @if($existingValue)
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                ✓ Recorded
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @switch($template->field_type)
                                        @case('yes_no')
                                            <div class="grid grid-cols-3 gap-2">
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           wire:model="yesNoValues.{{ $template->id }}" 
                                                           value="yes"
                                                           class="sr-only peer">
                                                    <div class="w-full p-3 text-center rounded-lg border-2 border-gray-200 dark:border-gray-700 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 transition-all duration-200">
                                                        <span class="text-green-600 dark:text-green-400 font-medium">Yes</span>
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           wire:model="yesNoValues.{{ $template->id }}" 
                                                           value="no"
                                                           class="sr-only peer">
                                                    <div class="w-full p-3 text-center rounded-lg border-2 border-gray-200 dark:border-gray-700 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 transition-all duration-200">
                                                        <span class="text-red-600 dark:text-red-400 font-medium">No</span>
                                                    </div>
                                                </label>
                                                <label class="cursor-pointer">
                                                    <input type="radio" 
                                                           wire:model="yesNoValues.{{ $template->id }}" 
                                                           value=""
                                                           class="sr-only peer">
                                                    <div class="w-full p-3 text-center rounded-lg border-2 border-gray-200 dark:border-gray-700 peer-checked:border-gray-400 peer-checked:bg-gray-100 dark:peer-checked:bg-gray-700 transition-all duration-200">
                                                        <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                                    </div>
                                                </label>
                                            </div>
                                            @break
                                            
                                        @case('select')
                                            <select wire:model="selectOptions.{{ $template->id }}"
                                                    class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors duration-200">
                                                <option value="" class="text-gray-400">Select finding...</option>
                                                @php
                                                    $options = is_string($template->options) ? json_decode($template->options, true) : $template->options;
                                                    $options = is_array($options) ? $options : [];
                                                @endphp
                                                @foreach($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            @break
                                            
                                        @case('number')
                                            <div class="relative">
                                                <input type="number" 
                                                       step="0.1"
                                                       wire:model="textValues.{{ $template->id }}"
                                                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                                                       placeholder="Enter value...">
                                                @if(str_contains($template->name, 'Temperature'))
                                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 font-medium">°C</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @break
                                            
                                        @case('text')
                                            <textarea wire:model="textValues.{{ $template->id }}"
                                                      rows="2"
                                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-800 dark:text-white transition-colors duration-200"
                                                      placeholder="Describe findings..."></textarea>
                                            @break
                                    @endswitch
                                    
                                    @error("examination.{$template->id}")
                                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Action Buttons --}}
            <div class="sticky bottom-6 z-10">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                    <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                        <div class="flex items-center space-x-3">
                            <button type="button" 
                                    wire:click="backToChiefComplaint"
                                    class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>Back to Chief Complaint</span>
                            </button>
                            
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 transform hover:-translate-y-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Save Examination Findings</span>
                            </button>
                        </div>
                        
                        <button type="button" 
                                wire:click="completeConsultation"
                                wire:loading.attr="disabled"
                                onclick="return confirm('Complete consultation and discharge patient?')"
                                class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Complete & Discharge Patient</span>
                        </button>
                    </div>
                    
                    {{-- Quick Stats --}}
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-center space-x-6 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center space-x-2">
                                <div class="h-2 w-2 rounded-full bg-green-500"></div>
                                <span>Completed: {{ $this->getCompletedCount() ?? '0' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-2 w-2 rounded-full bg-blue-500"></div>
                                <span>In Progress: {{ $this->getInProgressCount() ?? '0' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-600"></div>
                                <span>Pending: {{ $this->getPendingCount() ?? '0' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Add custom animations --}}
    <style>
        .animate-fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .sticky {
            position: -webkit-sticky;
            position: sticky;
        }
    </style>
</div>
</div>