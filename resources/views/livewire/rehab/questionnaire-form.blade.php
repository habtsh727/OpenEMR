<div class="space-y-6" 
     x-data="{ 
        showNoteFor: null,
        autoSaveTimer: null
     }"
     x-init="
        autoSaveTimer = setInterval(() => {
            if ($wire.autoSaveEnabled) {
                $wire.saveProgress(false)
            }
        }, 30000)
        
        $wire.on('redirect-to-queue', () => {
            setTimeout(() => {
                window.location.href = '{{ route('rehab.queue') }}'
            }, 2000)
        })
        
        $wire.on('scroll-to-question', (event) => {
            const element = document.getElementById('question-' + event.questionId)
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' })
                element.classList.add('ring-4', 'ring-red-300', 'bg-red-50', 'dark:bg-red-900/20')
                setTimeout(() => {
                    element.classList.remove('ring-4', 'ring-red-300', 'bg-red-50', 'dark:bg-red-900/20')
                }, 3000)
            }
        })
     "
     x-on:beforeunload.window="
        if ($wire.autoSaveEnabled) {
            $wire.saveProgress(false)
        }
     ">

    <!-- Header with Progress -->
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
                        <svg class="w-7 h-7 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Rehabilitation Assessment
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Complete the assessment for 
                        <span class="font-semibold text-gray-900 dark:text-white">
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </span>
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <!-- Status Badge -->
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $rehabEncounter->status_color }}">
                    {{ $rehabEncounter->status_label }}
                </span>
                
                <!-- Auto-save Toggle -->
                <button type="button"
                        wire:click="toggleAutoSave"
                        class="p-2 rounded-lg transition-colors {{ $autoSaveEnabled ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-400' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                </button>

                <!-- Last Saved Indicator -->
                @if($lastSaved)
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Saved {{ $lastSaved->diffForHumans() }}
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Progress Bar -->
        @php
            $totalQuestions = $questions->count();
            $answeredQuestions = collect($this->answers)
                ->filter(fn($a) => !empty($a['value']) && $a['value'] !== '' && $a['value'] !== [])
                ->count();
            $progress = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100) : 0;
        @endphp
        <div class="mt-6">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Completion Progress
                </span>
                <span class="text-sm font-semibold text-teal-600 dark:text-teal-500">
                    {{ $answeredQuestions }}/{{ $totalQuestions }} questions
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-teal-500 to-cyan-600 h-2.5 rounded-full transition-all duration-500"
                     style="width: {{ $progress }}%"></div>
            </div>
        </div>
    </div>

    <!-- Patient Context Card -->
    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 rounded-2xl shadow-sm border border-teal-100 dark:border-teal-800 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Patient</span>
                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $patient->first_name }} {{ $patient->last_name }}
                </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
    DOB: @if($patient->date_of_birth)
        @if($patient->date_of_birth instanceof \Carbon\Carbon)
            {{ $patient->date_of_birth->format('M d, Y') }}
        @else
            {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('M d, Y') }}
        @endif
    @else
        N/A
    @endif
</p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">MRN</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                    {{ $patient->medical_record_number ?? 'N/A' }}
                </p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Doctor</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    Dr. {{ $doctor->name ?? 'N/A' }}
                </p>
            </div>
            <div>
                <span class="text-xs font-medium text-teal-600 dark:text-teal-500 uppercase tracking-wider">Ordered</span>
                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                    {{ $rehabEncounter->created_at->format('M d, Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Questionnaire Form -->
    <form wire:submit.prevent="submitQuestionnaire" class="space-y-6">
        @foreach($questions as $question)
            <div id="question-{{ $question->id }}" 
                 wire:key="question-{{ $question->id }}"
                 class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 transition-all duration-200 hover:shadow-md">
                
                <!-- Question Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-start gap-3 flex-1">
                        <span class="flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-br from-teal-500 to-cyan-600 text-white text-xs font-bold">
                            {{ $loop->iteration }}
                        </span>
                        <div class="flex-1">
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
                    
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            {{ ucfirst($question->type) }}
                        </span>
                        
                        <!-- Required indicator -->
                        @if($question->is_required && empty($answers[$question->id]['value']))
                            <span class="text-xs text-red-500 font-medium">Required</span>
                        @endif
                    </div>
                </div>

                <!-- Answer Input -->
                <div class="space-y-4">
                    @switch($question->type)
                        @case('boolean')
                            <div class="flex gap-6">
                                <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="radio" 
                                           wire:model.live="answers.{{ $question->id }}.value"
                                           name="question_{{ $question->id }}"
                                           value="1"
                                           class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Yes</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <input type="radio" 
                                           wire:model.live="answers.{{ $question->id }}.value"
                                           name="question_{{ $question->id }}"
                                           value="0"
                                           class="w-4 h-4 text-teal-600 border-gray-300 focus:ring-teal-500">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">No</span>
                                </label>
                            </div>
                            @break

                        @case('checkbox')
                            @if($question->options)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
                                   placeholder="Enter number..."
                                   min="0"
                                   step="any">
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

                    <!-- Add Note Button -->
                    <div x-show="showNoteFor !== {{ $question->id }}" 
                         x-cloak
                         class="flex justify-end">
                        <button type="button" 
                                @click="showNoteFor = {{ $question->id }}"
                                class="text-sm text-teal-600 hover:text-teal-700 dark:text-teal-500 dark:hover:text-teal-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Add Note
                        </button>
                    </div>

                    <!-- Note Field -->
                    <div x-show="showNoteFor === {{ $question->id }} || @js(!empty($answers[$question->id]['note']))" 
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
                                        @click="showNoteFor = null"
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
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        @endforeach

        <!-- Rehabilitation Notes Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                </svg>
                Overall Rehabilitation Notes
            </h3>
            <textarea 
                wire:model.live="rehabNotes"
                rows="3"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                placeholder="Add overall observations, recommendations, or notes about this rehabilitation case..."
            ></textarea>
        </div>

        <!-- Action Buttons -->
        <div class="sticky bottom-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 backdrop-blur-sm bg-white/90 dark:bg-gray-800/90">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('rehab.queue') }}" 
                       wire:navigate
                       class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Back to Queue</span>
                    </a>
                    
                    <button type="button"
                            wire:click="saveProgress"
                            wire:loading.attr="disabled"
                            class="px-6 py-3 border-2 border-teal-500 dark:border-teal-600 rounded-xl text-sm font-semibold text-teal-600 dark:text-teal-500 bg-teal-50 dark:bg-teal-900/30 hover:bg-teal-100 dark:hover:bg-teal-900/40 transition-all duration-200 flex items-center space-x-2">
                        <svg wire:loading wire:target="saveProgress" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveProgress">Save Progress</span>
                        <span wire:loading wire:target="saveProgress">Saving...</span>
                    </button>
                </div>

                <button type="submit"
                        wire:loading.attr="disabled"
                        class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2 min-w-[200px] justify-center">
                    <svg wire:loading wire:target="submitQuestionnaire" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="submitQuestionnaire">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Submit to Doctor
                    </span>
                    <span wire:loading wire:target="submitQuestionnaire">Submitting...</span>
                </button>
            </div>
            
            <!-- Help Text -->
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    <span class="text-red-500">*</span> Required fields • 
                    Your progress is auto-saved every 30 seconds • 
                    After submission, you cannot edit this questionnaire
                </p>
            </div>
        </div>
    </form>

    <!-- Submission Confirmation Modal -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-full">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Submit to Doctor
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to submit this questionnaire?
                        </p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300">
                            <p class="font-medium mb-1">After submission:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>You cannot make any further changes</li>
                                <li>The doctor will be notified to review</li>
                                <li>Status will change to "Submitted to Doctor"</li>
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

    <!-- Auto-save Notification (hidden but used for Alpine) -->
    <div x-data="{ show: false, message: '' }"
         x-on:notify.window="
            show = true;
            message = $event.detail.message;
            setTimeout(() => show = false, 3000);
         "
         x-show="show"
         x-transition
         class="fixed bottom-6 right-6 bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg flex items-center gap-2 z-50"
         style="display: none;">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span x-text="message"></span>
    </div>
</div>