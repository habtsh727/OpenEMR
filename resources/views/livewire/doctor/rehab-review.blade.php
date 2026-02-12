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
                
                <!-- Mark as Reviewed Button -->
                @if(in_array($rehabEncounter->status, ['submitted_to_doctor', 'doctor_review']))
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
        <div class="space-y-6">
            <!-- Rehab Staff Notes -->
            @if($rehabEncounter->rehab_notes)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-start gap-3">
                        <div class="p-2 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                Rehabilitation Staff Notes
                                <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                                    by {{ $rehabStaff->name ?? 'Rehab Staff' }}
                                </span>
                            </h3>
                            <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl">
                                {{ nl2br(e($rehabEncounter->rehab_notes)) }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Questionnaire Answers -->
            @forelse($answersGrouped as $templateTitle => $answers)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h4 class="font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            {{ $templateTitle }}
                        </h4>
                    </div>
                    <div class="p-6">
                        <div class="space-y-6">
                            @foreach($answers as $answer)
                                <div class="pb-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex items-start gap-2">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                {{ $answer->question->question }}
                                                @if($answer->question->is_required)
                                                    <span class="text-red-500 ml-1">*</span>
                                                @endif
                                            </span>
                                            <span class="px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-400">
                                                {{ ucfirst($answer->question->type) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        @if($answer->answer)
                                            @if($answer->question->type === 'boolean')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                    {{ $answer->answer === true || $answer->answer === '1' || $answer->answer === 'yes' 
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                                        : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        @if($answer->answer === true || $answer->answer === '1' || $answer->answer === 'yes')
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        @else
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        @endif
                                                    </svg>
                                                    {{ $answer->answer === true || $answer->answer === '1' || $answer->answer === 'yes' ? 'Yes' : 'No' }}
                                                </span>
                                            @elseif($answer->question->type === 'checkbox')
                                                @php
                                                    $selectedOptions = is_array($answer->answer) ? $answer->answer : json_decode($answer->answer, true);
                                                @endphp
                                                <div class="space-y-2">
                                                    @foreach($selectedOptions as $option)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            <span class="text-gray-900 dark:text-white">{{ $option }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($answer->question->type === 'datetime')
                                                <p class="text-gray-900 dark:text-white">
                                                    {{ \Carbon\Carbon::parse($answer->answer)->format('F d, Y h:i A') }}
                                                </p>
                                            @elseif($answer->question->type === 'select')
                                                <p class="text-gray-900 dark:text-white font-medium">
                                                    {{ $answer->answer }}
                                                </p>
                                            @else
                                                <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                                                    {{ $answer->answer }}
                                                </p>
                                            @endif
                                        @else
                                            <p class="text-gray-400 dark:text-gray-500 italic">
                                                No answer provided
                                            </p>
                                        @endif
                                    </div>

                                    @if($answer->note)
                                        <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                </svg>
                                                <div>
                                                    <span class="text-xs font-medium text-yellow-800 dark:text-yellow-400 uppercase tracking-wider">Staff Note</span>
                                                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">{{ $answer->note }}</p>
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
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No questionnaire responses</h3>
                        <p class="text-gray-500 dark:text-gray-400">The rehabilitation staff hasn't filled the questionnaire yet.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab Content: Doctor's Notes -->
    <div x-show="activeTab === 'notes'" x-cloak>
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-start gap-3 mb-6">
                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Doctor's Review Notes
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Add your clinical assessment, recommendations, and treatment plan
                    </p>
                </div>
            </div>

            <!-- Current/Prior Notes Display -->
            @if($rehabEncounter->doctor_notes)
                <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-medium text-purple-800 dark:text-purple-400">
                            Current Notes - {{ $rehabEncounter->updated_at->format('M d, Y H:i') }}
                        </span>
                    </div>
                    <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                        {{ nl2br(e($rehabEncounter->doctor_notes)) }}
                    </div>
                </div>
            @endif

            <!-- Notes Form -->
            <div class="space-y-4">
                <div>
                    <label for="doctorNotes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ $rehabEncounter->doctor_notes ? 'Update Notes' : 'Add Notes' }}
                    </label>
                    <textarea
                        id="doctorNotes"
                        wire:model="doctorNotes"
                        rows="6"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Enter your review notes, clinical findings, recommendations, or treatment plan..."
                    ></textarea>
                </div>
                
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="saveDoctorNotes"
                        wire:loading.attr="disabled"
                        class="px-6 py-2.5 bg-white border-2 border-purple-500 text-purple-600 hover:bg-purple-50 dark:bg-gray-800 dark:text-purple-400 dark:border-purple-600 dark:hover:bg-purple-900/20 font-medium rounded-xl transition-colors flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="saveDoctorNotes" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveDoctorNotes">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            {{ $rehabEncounter->doctor_notes ? 'Update Notes' : 'Save Notes' }}
                        </span>
                        <span wire:loading wire:target="saveDoctorNotes">Saving...</span>
                    </button>
                </div>
            </div>

            <!-- Tips for Doctor Notes -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <span class="text-xs font-medium text-blue-800 dark:text-blue-400 uppercase tracking-wider">Review Tips</span>
                        <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 list-disc list-inside space-y-1">
                            <li>Confirm the assessment findings are complete</li>
                            <li>Add your clinical interpretation and recommendations</li>
                            <li>Specify any follow-up requirements</li>
                            <li>Note any restrictions or precautions</li>
                        </ul>
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

    <!-- Alpine.js for tab persistence -->
    <style>
        [x-cloak] { display: none !important; }
    </style>

    <!-- Redirect handler -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('redirect-to-queue', () => {
                setTimeout(() => {
                    window.location.href = '{{ route('rehab.queue') }}';
                }, 2000);
            });
            
            Livewire.on('notify', (event) => {
                // Use your existing notification system
                // This is a placeholder - replace with your actual toast implementation
                if (window.showToast) {
                    window.showToast(event[0].message, event[0].type);
                } else {
                    alert(event[0].message);
                }
            });
        });
    </script>
</div>