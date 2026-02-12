<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('doctor.rehab.queue') }}" wire:navigate class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Rehabilitation Review
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Review questionnaire submitted by rehab staff
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Status Badge -->
            <div class="flex items-center gap-3">
                <span class="px-4 py-2 rounded-full text-sm font-medium {{ $rehabEncounter->status_color }}">
                    {{ $rehabEncounter->status_label }}
                </span>
                
                @if($rehabEncounter->status === 'submitted_to_doctor')
                    <button
                        wire:click="markAsReviewed"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Mark as Reviewed
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Patient Info Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $rehabEncounter->encounter->patient->first_name }} {{ $rehabEncounter->encounter->patient->last_name }}
                    </h2>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">MRN</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $rehabEncounter->encounter->patient->medical_record_number ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Encounter #</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $rehabEncounter->encounter_id }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ordered Date</span>
                            <p class="text-sm text-gray-900 dark:text-white">{{ $rehabEncounter->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attending Doctor</span>
                            <p class="text-sm text-gray-900 dark:text-white">Dr. {{ $rehabEncounter->encounter->doctor->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questionnaire Answers -->
    <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
            <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Questionnaire Responses
            <span class="text-sm font-normal text-gray-500 dark:text-gray-400 ml-2">
                Filled by: {{ $rehabEncounter->filledBy?->name ?? 'Unknown' }} on {{ $rehabEncounter->updated_at->format('M d, Y H:i') }}
            </span>
        </h3>

        @forelse($this->answersGroupedByTemplate as $templateTitle => $answers)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h4 class="font-semibold text-gray-900 dark:text-white">
                        {{ $templateTitle }}
                    </h4>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @foreach($answers as $answer)
                            <div class="pb-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $answer->question->question }}
                                            @if($answer->question->is_required)
                                                <span class="text-red-500 ml-1">*</span>
                                            @endif
                                        </span>
                                        <span class="ml-2 px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 rounded-full text-gray-600 dark:text-gray-400">
                                            {{ ucfirst($answer->question->type) }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="mt-2 text-gray-900 dark:text-white">
                                    @if($answer->answer)
                                        @if($answer->question->type === 'boolean')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $answer->answer === true || $answer->answer === '1' || $answer->answer === 'yes' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                                {{ $answer->answer === true || $answer->answer === '1' || $answer->answer === 'yes' ? 'Yes' : 'No' }}
                                            </span>
                                        @elseif($answer->question->type === 'checkbox')
                                            @php
                                                $selectedOptions = is_array($answer->answer) ? $answer->answer : json_decode($answer->answer, true);
                                            @endphp
                                            <div class="space-y-1">
                                                @foreach($selectedOptions as $option)
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        {{ $option }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($answer->question->type === 'datetime')
                                            {{ \Carbon\Carbon::parse($answer->answer)->format('M d, Y H:i') }}
                                        @elseif($answer->question->type === 'select')
                                            {{ $answer->answer }}
                                        @else
                                            {{ $answer->answer }}
                                        @endif
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500 italic">No answer provided</span>
                                    @endif
                                </div>

                                @if($answer->note)
                                    <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <span class="text-xs font-medium text-yellow-800 dark:text-yellow-400 uppercase tracking-wider">Note</span>
                                        <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">{{ $answer->note }}</p>
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

    <!-- Rehab Notes -->
    @if($rehabEncounter->rehab_notes)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path>
                </svg>
                Rehabilitation Staff Notes
            </h3>
            <div class="prose prose-sm max-w-none text-gray-700 dark:text-gray-300">
                {{ $rehabEncounter->rehab_notes }}
            </div>
        </div>
    @endif

    <!-- Doctor Notes Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-4">
            <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Doctor's Notes
        </h3>
        
        <div class="space-y-4">
            <textarea
                wire:model="doctorNotes"
                rows="4"
                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                placeholder="Add your review notes, recommendations, or comments..."
            ></textarea>
            
            <div class="flex justify-end">
                <button
                    wire:click="addDoctorNote"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-white border-2 border-teal-500 text-teal-600 hover:bg-teal-50 dark:bg-gray-800 dark:text-teal-400 dark:border-teal-600 dark:hover:bg-teal-900/20 font-medium rounded-xl transition-colors flex items-center gap-2"
                >
                    <svg wire:loading wire:target="addDoctorNote" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="addDoctorNote">Save Notes</span>
                    <span wire:loading wire:target="addDoctorNote">Saving...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal for Mark as Reviewed -->
    @if($showConfirmModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Confirm Review
                    </h3>
                </div>
                
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Are you sure you want to mark this rehabilitation questionnaire as reviewed? This will update the status to "Doctor Review".
                </p>
                
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
                        <span wire:loading.remove wire:target="confirmReview">Confirm Review</span>
                        <span wire:loading wire:target="confirmReview">Processing...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast Notification Handler -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                // Use your existing toast notification system
                // This is a placeholder - replace with your actual toast implementation
                alert(event[0].message);
            });
        });
    </script>
</div>