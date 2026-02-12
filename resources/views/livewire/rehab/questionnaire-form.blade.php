<div class="space-y-6" x-data="{ 
    showNotes: false,
    autoSaveTimer: null
}" x-init="
    autoSaveTimer = setInterval(() => {
        $wire.saveProgress()
    }, 30000) // Auto-save every 30 seconds
    
    $wire.on('redirect-to-queue', () => {
        setTimeout(() => {
            window.location.href = '{{ route('rehab.queue') }}'
        }, 2000)
    })
    
    $wire.on('scroll-to-question', (event) => {
        const element = document.getElementById('question-' + event.questionId)
        if (element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' })
            element.classList.add('ring-2', 'ring-red-500', 'bg-red-50', 'dark:bg-red-900/20')
            setTimeout(() => {
                element.classList.remove('ring-2', 'ring-red-500', 'bg-red-50', 'dark:bg-red-900/20')
            }, 3000)
        }
    })
" x-on:beforeunload.window="
    if ($wire.answers) {
        return 'You have unsaved changes. Are you sure you want to leave?'
    }
">

    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 sticky top-0 z-10 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('rehab.queue') }}" wire:navigate class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-7 h-7 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Rehabilitation Questionnaire
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Complete the assessment for 
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ $rehabEncounter->encounter->patient->first_name }} {{ $rehabEncounter->encounter->patient->last_name }}
                        </span>
                    </p>
                </div>
            </div>
            
            <!-- Status Badge & Progress -->
            <div class="flex items-center gap-4">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $rehabEncounter->status_color }}">
                    {{ $rehabEncounter->status_label }}
                </span>
                
                <!-- Auto-save indicator -->
                <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 animate-pulse text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Auto-saving...</span>
                </div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="mt-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Progress
                </span>
                <span class="text-sm font-semibold text-teal-600 dark:text-teal-500">
                    Step {{ $currentStep }} of {{ $totalSteps }}
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-teal-500 to-cyan-600 h-2.5 rounded-full transition-all duration-500"
                     style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Patient Info Card -->
    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-2xl shadow-sm border border-teal-100 dark:border-teal-800 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Patient Name</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $rehabEncounter->encounter->patient->first_name }} {{ $rehabEncounter->encounter->patient->last_name }}
                </p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">MRN</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                    {{ $rehabEncounter->encounter->patient->medical_record_number ?? 'N/A' }}
                </p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Doctor</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    Dr. {{ $rehabEncounter->encounter->doctor->name }}
                </p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Ordered Date</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $rehabEncounter->created_at->format('M d, Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Questionnaire Form -->
    <form wire:submit.prevent="submitQuestionnaire">
        <div class="space-y-6">
            @foreach($questions as $index => $question)
                <div id="question-{{ $question->id }}" 
                     wire:key="question-{{ $question->id }}"
                     class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-md">
                    
                    <!-- Question Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start gap-3">
                            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 text-white text-xs font-bold">
                                {{ $loop->iteration + (($currentStep - 1) * 5) }}
                            </span>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                    {{ $question->question }}
                                    @if($question->is_required)
                                        <span class="text-red-500 ml-1">*</span>
                                    @endif
                                </h3>
                                @if($question->type === 'checkbox' && $question->options)
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        Select all that apply
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Question Type Badge -->
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            {{ ucfirst($question->type) }}
                        </span>
                    </div>

                    <!-- Answer Input -->
                    <div class="space-y-4">
                        @switch($question->type)
                            @case('boolean')
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" 
                                               wire:model.live="answers.{{ $question->id }}.value"
                                               name="question_{{ $question->id }}"
                                               value="1"
                                               class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Yes</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" 
                                               wire:model.live="answers.{{ $question->id }}.value"
                                               name="question_{{ $question->id }}"
                                               value="0"
                                               class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">No</span>
                                    </label>
                                </div>
                                @break

                            @case('checkbox')
                                @if($question->options)
                                    <div class="space-y-2">
                                        @foreach($question->options as $option)
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" 
                                                       wire:model.live="answers.{{ $question->id }}.value"
                                                       value="{{ $option }}"
                                                       class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                                @break

                            @case('textarea')
                                <textarea 
                                    wire:model.live="answers.{{ $question->id }}.value"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="Enter your answer..."
                                ></textarea>
                                @break

                            @case('number')
                                <input type="number"
                                       wire:model.live="answers.{{ $question->id }}.value"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Enter number...">
                                @break

                            @case('datetime')
                                <input type="datetime-local"
                                       wire:model.live="answers.{{ $question->id }}.value"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white">
                                @break

                            @case('select')
                                <select 
                                    wire:model.live="answers.{{ $question->id }}.value"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">Select an option...</option>
                                    @if($question->options)
                                        @foreach($question->options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @break

                            @default
                                <input type="text"
                                       wire:model.live="answers.{{ $question->id }}.value"
                                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                                       placeholder="Enter your answer...">
                        @endswitch

                        <!-- Add Note Toggle -->
                        <div x-show="!showNotes" class="flex justify-end">
                            <button type="button" 
                                    @click="showNotes = !showNotes"
                                    class="text-sm text-teal-600 hover:text-teal-700 dark:text-teal-500 dark:hover:text-teal-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Add Note
                            </button>
                        </div>

                        <!-- Note Field -->
                        <div x-show="showNotes || @js(!empty($answers[$question->id]['note']))" 
                             x-collapse
                             class="mt-4 p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-yellow-800 dark:text-yellow-400 mb-1">
                                        Additional Notes
                                    </label>
                                    <textarea 
                                        wire:model.live="answers.{{ $question->id }}.note"
                                        rows="2"
                                        class="w-full px-3 py-2 text-sm border border-yellow-300 dark:border-yellow-700 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:bg-gray-800 dark:text-white"
                                        placeholder="Add any additional notes or observations..."
                                    ></textarea>
                                </div>
                                @if(empty($answers[$question->id]['note']))
                                    <button type="button" 
                                            @click="showNotes = false"
                                            class="p-1 hover:bg-yellow-100 dark:hover:bg-yellow-800 rounded-lg transition-colors">
                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>

                        <!-- Error Message -->
                        @error("answers.{$question->id}.value")
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endforeach

            <!-- Rehab Notes Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                    </svg>
                    Rehabilitation Notes
                </h3>
                <textarea 
                    wire:model.live="rehabNotes"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                    placeholder="Add overall rehabilitation notes, observations, or recommendations..."
                ></textarea>
            </div>

            <!-- Navigation Buttons -->
            <div class="sticky bottom-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        @if($currentStep > 1)
                            <button type="button"
                                    wire:click="previousStep"
                                    class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                <span>Previous</span>
                            </button>
                        @endif
                        
                        <button type="button"
                                wire:click="saveProgress"
                                class="px-6 py-3 border-2 border-teal-500 dark:border-teal-600 rounded-xl text-sm font-semibold text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-900/30 hover:bg-teal-100 dark:hover:bg-teal-900/40 transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            <span>Save Progress</span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($currentStep < $totalSteps)
                            <button type="button"
                                    wire:click="nextStep"
                                    class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                <span>Next Step</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Submit to Doctor</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Submit Questionnaire
                    </h3>
                </div>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to submit this questionnaire to the doctor? 
                    You won't be able to make changes after submission.
                </p>
                
                <div class="flex justify-end gap-3">
                    <button
                        wire:click="$set('showConfirmModal', false)"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 font-medium transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        wire:click="confirmSubmit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="confirmSubmit" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="confirmSubmit">Confirm Submission</span>
                        <span wire:loading wire:target="confirmSubmit">Submitting...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>