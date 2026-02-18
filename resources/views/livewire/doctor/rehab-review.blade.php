<div class="space-y-6" 
     x-data="{ 
        activeTab: 'questionnaire',
        showNotes: true
     }">
    
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-0 z-10 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('rehab.queue') }}" wire:navigate 
                   class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-7 h-7 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Rehabilitation Review
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Review questionnaire submitted by rehabilitation staff
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Status Badge -->
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $rehabEncounter->status_color }}">
                    {{ $rehabEncounter->status_label }}
                </span>
                
                <!-- Action Buttons -->
                @if(in_array($rehabEncounter->status, ['submitted_to_doctor', 'doctor_review']))
                    <!-- Mark as Reviewed Button -->
                    <button
                        wire:click="markAsReviewed"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="markAsReviewed" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="markAsReviewed">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $rehabEncounter->status === 'doctor_review' ? 'Update Review' : 'Mark as Reviewed' }}
                        </span>
                        <span wire:loading wire:target="markAsReviewed">Processing...</span>
                    </button>
                @endif

                <!-- Proceed to Order Button - Show after review -->
                @if($rehabEncounter->status === 'doctor_review')
                    <button
                        wire:click="proceedToOrder"
                        class="px-6 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Proceed to Order Packages
                    </button>
                @endif
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $patient->first_name ?? '' }} {{ $patient->last_name ?? '' }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    DOB: {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') : 'N/A' }}
                </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">MRN / Encounter</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white font-mono">
                    {{ $patient->medical_record_number ?? 'N/A' }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Encounter #{{ $rehabEncounter->encounter_id }}
                </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Submitted By</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $rehabStaff->name ?? 'N/A' }}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ $rehabEncounter->updated_at->format('M d, Y H:i') }}
                </p>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time Elapsed</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{-- {{ $timeElapsed }} --}}
                </p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    Since submission
                </p>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="mt-6 flex items-center justify-between">
            <div class="flex items-center flex-1">
                <div class="flex items-center relative">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-semibold text-sm">1</div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Questionnaire</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Completed by Rehab</p>
                    </div>
                </div>
                <div class="flex-1 mx-4 h-0.5 bg-green-500"></div>
                <div class="flex items-center relative">
                    <div class="w-8 h-8 {{ $rehabEncounter->status === 'doctor_review' ? 'bg-green-500' : 'bg-indigo-500' }} rounded-full flex items-center justify-center text-white font-semibold text-sm">2</div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Doctor Review</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $rehabEncounter->status === 'doctor_review' ? 'Completed' : 'In Progress' }}</p>
                    </div>
                </div>
                <div class="flex-1 mx-4 h-0.5 {{ $rehabEncounter->status === 'doctor_review' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                <div class="flex items-center relative">
                    <div class="w-8 h-8 {{ $rehabEncounter->status === 'doctor_review' ? 'bg-indigo-500' : 'bg-gray-300 dark:bg-gray-600' }} rounded-full flex items-center justify-center text-white font-semibold text-sm">3</div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Order Packages</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Pending</p>
                    </div>
                </div>
                <div class="flex-1 mx-4 h-0.5 {{ $rehabEncounter->status === 'sent_to_cashier' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }}"></div>
                <div class="flex items-center relative">
                    <div class="w-8 h-8{{ $rehabEncounter->status === 'sent_to_cashier' ? 'bg-green-500' : 'bg-gray-300 dark:bg-gray-600' }} bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center text-gray-600 dark:text-gray-400 font-semibold text-sm">4</div>
                    <div class="ml-2">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Payment</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Cashier</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="flex space-x-8">
            <button @click="activeTab = 'questionnaire'"
                    :class="{ 'border-purple-500 text-purple-600 dark:text-purple-400': activeTab === 'questionnaire', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'questionnaire' }"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Questionnaire
            </button>
            <button @click="activeTab = 'notes'"
                    :class="{ 'border-purple-500 text-purple-600 dark:text-purple-400': activeTab === 'notes', 'border-transparent text-gray-500 dark:text-gray-400': activeTab !== 'notes' }"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Doctor's Notes
                @if($rehabEncounter->doctor_notes)
                    <span class="ml-2 px-2 py-0.5 text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 rounded-full">
                        Added
                    </span>
                @endif
            </button>
        </nav>
    </div>

    <!-- Tab Content: Questionnaire -->
    <div x-show="activeTab === 'questionnaire'" x-cloak>
        <!-- Single Unified Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Rehab Staff Notes - Integrated Section -->
            @if($rehabEncounter->rehab_notes)
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <div class="p-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="p-2.5 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        Clinical Assessment Notes
                                    </h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900/30 dark:text-teal-400">
                                        Rehabilitation Staff
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        • {{ $rehabStaff->name ?? 'Rehabilitation Specialist' }}
                                    </span>
                                </div>
                                <div class="prose prose-sm max-w-none">
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                            {{ nl2br(e($rehabEncounter->rehab_notes)) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Questionnaire Responses -->
            @forelse($answersGrouped as $templateTitle => $answers)
                <!-- Section Header -->
                <div class="{{ !$loop->first ? 'border-t border-gray-200 dark:border-gray-700' : '' }}">
                    <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            </div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                {{ $templateTitle }}
                            </h4>
                            <span class="ml-auto text-xs text-gray-500 dark:text-gray-400">
                                {{ $answers->count() }} {{ Str::plural('response', $answers->count()) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Response Items -->
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($answers as $answer)
                                <div class="group {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700 pb-6' : '' }}">
                                    <!-- Question Header -->
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-start gap-2 flex-1">
                                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $answer->question->question }}
                                                @if($answer->question->is_required)
                                                    <span class="text-red-500 ml-1" title="Required field">*</span>
                                                @endif
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ ucfirst($answer->question->type_label ?? $answer->question->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Response Value -->
                                    <div class="mt-2">
                                        @if($answer->answer)
                                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                                                @switch($answer->question->type)
                                                    @case('boolean')
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-sm font-medium
                                                            {{ in_array($answer->answer, [true, '1', 'yes', 'true'], true) 
                                                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' 
                                                                : 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400' }}">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                @if(in_array($answer->answer, [true, '1', 'yes', 'true'], true))
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                @else
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                @endif
                                                            </svg>
                                                            {{ in_array($answer->answer, [true, '1', 'yes', 'true'], true) ? 'Yes' : 'No' }}
                                                        </span>
                                                        @break
                                                        
                                                    @case('checkbox')
                                                        @php
                                                            $selectedOptions = is_array($answer->answer) 
                                                                ? $answer->answer 
                                                                : (json_decode($answer->answer, true) ?: []);
                                                        @endphp
                                                        <div class="space-y-2">
                                                            @forelse($selectedOptions as $option)
                                                                <div class="flex items-center gap-2.5 text-sm">
                                                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                    <span class="text-gray-900 dark:text-white">{{ $option }}</span>
                                                                </div>
                                                            @empty
                                                                <p class="text-gray-500 dark:text-gray-400 italic text-sm">No options selected</p>
                                                            @endforelse
                                                        </div>
                                                        @break
                                                        
                                                    @case('datetime')
                                                        <p class="text-gray-900 dark:text-white font-mono text-sm">
                                                            <time datetime="{{ $answer->answer }}">
                                                                {{ \Carbon\Carbon::parse($answer->answer)->format('F j, Y · g:i A') }}
                                                            </time>
                                                        </p>
                                                        @break
                                                        
                                                    @case('select')
                                                        <p class="text-gray-900 dark:text-white font-medium">
                                                            {{ $answer->answer }}
                                                        </p>
                                                        @break
                                                        
                                                    @case('scale')
                                                        <div class="flex items-center gap-3">
                                                            <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                                                {{ $answer->answer }}
                                                            </span>
                                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                                / {{ $answer->question->scale_max ?? 10 }}
                                                            </span>
                                                        </div>
                                                        @break
                                                        
                                                    @default
                                                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap text-sm leading-relaxed">
                                                            {{ $answer->answer }}
                                                        </p>
                                                @endswitch
                                            </div>
                                        @else
                                            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-4 border border-gray-100 dark:border-gray-700">
                                                <p class="text-gray-400 dark:text-gray-500 italic text-sm flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                    No response provided
                                                </p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Clinical Note Attachment -->
                                    @if($answer->note)
                                        <div class="mt-3">
                                            <div class="flex items-start gap-2.5 p-3 bg-amber-50 dark:bg-amber-900/10 rounded-lg border border-amber-100 dark:border-amber-800/30">
                                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                <div class="flex-1">
                                                    <span class="text-xs font-semibold text-amber-800 dark:text-amber-400 uppercase tracking-wider">
                                                        Clinical Commentary
                                                    </span>
                                                    <p class="mt-0.5 text-sm text-amber-700 dark:text-amber-300">
                                                        {{ $answer->note }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State - Integrated -->
                <div class="flex flex-col items-center justify-center py-16 px-4 text-center">
                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        No questionnaire responses available
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400 max-w-md">
                        The rehabilitation assessment has not been completed. Please check back once the evaluation is submitted.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Physician Review Notes Tab Content -->
    <div x-show="activeTab === 'notes'" x-cloak>
        <!-- Single Unified Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-start gap-3">
                    <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Physician Review Notes
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Document your clinical assessment, recommendations, and treatment plan
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                <!-- Existing Notes -->
                @if($rehabEncounter->doctor_notes)
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center gap-1.5 px-2 py-1 bg-purple-100 dark:bg-purple-900/30 rounded-md">
                                <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-xs font-medium text-purple-800 dark:text-purple-400">
                                    Previous Assessment
                                </span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $rehabEncounter->updated_at->format('M j, Y · g:i A') }}
                            </span>
                        </div>
                        <div class="prose prose-sm max-w-none">
                            <div class="bg-purple-50 dark:bg-purple-900/10 rounded-lg p-4 border border-purple-100 dark:border-purple-800/30">
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ nl2br(e($rehabEncounter->doctor_notes)) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Notes Form -->
                <div class="space-y-4">
                    <div>
                        <label for="physicianNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $rehabEncounter->doctor_notes ? 'Update Clinical Notes' : 'Add Clinical Notes' }}
                        </label>
                        <textarea
                            id="physicianNotes"
                            wire:model="doctorNotes"
                            rows="6"
                            class="w-full px-4 py-3 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white shadow-sm transition-colors resize-vertical"
                            placeholder="Document your assessment, interpretation of findings, and recommended treatment plan..."
                        ></textarea>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Include specific recommendations, follow-up interval, and any modifications to treatment plan.
                        </p>
                    </div>
                    
                    <div class="flex justify-end">
                                                <button wire:click="saveDoctorNotes" class="inline-flex items-center px-5 py-2.5 bg-purple-600 hover:bg-purple-700 dark:bg-purple-500 dark:hover:bg-purple-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            {{ $rehabEncounter->doctor_notes ? 'Update Notes' : 'Save Notes' }}
                        </button>
                    </div>
                </div>

                <!-- Documentation Standards -->
                <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-100 dark:border-blue-800/30">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <span class="text-xs font-semibold text-blue-800 dark:text-blue-400 uppercase tracking-wider">
                                Documentation Standards
                            </span>
                            <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1.5">
                                <li class="flex items-start gap-2">
                                    <span class="select-none">•</span>
                                    <span>Verify completeness of assessment findings and objective measurements</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="select-none">•</span>
                                    <span>Document clinical reasoning and medical necessity</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="select-none">•</span>
                                    <span>Specify functional goals, timeframe, and follow-up plan</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="select-none">•</span>
                                    <span>Note any activity restrictions, precautions, or modifications</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $rehabEncounter->status === 'doctor_review' ? 'Update Review' : 'Confirm Review' }}
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $rehabEncounter->status === 'doctor_review' 
                                ? 'Are you sure you want to update this review?' 
                                : 'Are you sure you want to mark this questionnaire as reviewed?' }}
                        </p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300">
                            <p class="font-medium mb-1">Before confirming:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Ensure you've reviewed all questionnaire responses</li>
                                <li>Add your notes and recommendations above</li>
                                <li>This will update the status to "Doctor Review"</li>
                                <li>You can then proceed to order packages</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('showConfirmModal', false)"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="confirmReview"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="confirmReview" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmReview">
                            {{ $rehabEncounter->status === 'doctor_review' ? 'Update Review' : 'Confirm Review' }}
                        </span>
                        <span wire:loading wire:target="confirmReview">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Bottom Navigation -->
    <div class="sticky bottom-6 z-10">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-4 backdrop-blur-xl bg-white/80 dark:bg-gray-800/80">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium">Current Step:</span> 
                    @if($rehabEncounter->status === 'doctor_review')
                        <span class="text-green-600 dark:text-green-400 font-medium">Review Complete</span>
                    @else
                        <span class="text-indigo-600 dark:text-indigo-400 font-medium">Review in Progress</span>
                    @endif
                </div>
                
                <div class="flex gap-3">
                    @if($rehabEncounter->status === 'doctor_review')
                        <button
                            wire:click="proceedToOrder"
                            class="px-8 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center gap-2"
                        >
                            <span>Continue to Order Packages</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js for tab persistence -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Notification handler -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                // Create a simple notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ' + 
                    (event[0].type === 'success' ? 'bg-green-500' : 'bg-red-500') + ' text-white';
                notification.textContent = event[0].message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            });
        });
    </script>
</div>