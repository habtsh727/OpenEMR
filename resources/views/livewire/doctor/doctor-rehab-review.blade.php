<div>
    <!-- Header -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Rehabilitation Review</h2>
                <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-600">
                        Patient: <span class="font-medium">{{ $rehabEncounter->encounter->patient->name }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        MRN: <span class="font-medium">{{ $rehabEncounter->encounter->patient->medical_record_number }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Submitted by: <span class="font-medium">{{ $rehabEncounter->filledBy->name ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <x-status-badge :status="$rehabEncounter->status" />
                <button 
                    wire:click="completeReview"
                    wire:confirm="Mark this rehabilitation case as reviewed?"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="completeReview">Complete Review</span>
                    <span wire:loading wire:target="completeReview">Completing...</span>
                </button>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Questionnaire Answers -->
        <div class="lg:col-span-2 space-y-6">
            @forelse($answers as $templateTitle => $templateAnswers)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $templateTitle }}</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($templateAnswers as $answer)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h4 class="font-medium text-gray-900 mb-2">{{ $answer->question->question }}</h4>
                                
                                @switch($answer->question->type)
                                    @case('boolean')
                                        <p class="text-gray-700">
                                            {{ $answer->answer ? 'Yes' : 'No' }}
                                        </p>
                                        @break
                                        
                                    @case('checkbox')
                                        <ul class="list-disc pl-5 text-gray-700">
                                            @foreach($answer->answer as $item)
                                                <li>{{ $item }}</li>
                                            @endforeach
                                        </ul>
                                        @break
                                        
                                    @case('select')
                                    @case('text')
                                    @case('number')
                                        <p class="text-gray-700">{{ $answer->answer }}</p>
                                        @break
                                        
                                    @case('textarea')
                                        <p class="text-gray-700 whitespace-pre-line">{{ $answer->answer }}</p>
                                        @break
                                        
                                    @case('datetime')
                                        <p class="text-gray-700">{{ \Carbon\Carbon::parse($answer->answer)->format('M d, Y H:i') }}</p>
                                        @break
                                @endswitch
                                
                                @if($answer->note)
                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <p class="text-sm text-gray-600">
                                            <span class="font-medium">Note:</span> {{ $answer->note }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No questionnaire answers found</h3>
                    <p class="mt-1 text-sm text-gray-500">The rehabilitation staff has not submitted any questionnaire yet.</p>
                </div>
            @endforelse
        </div>
        
        <!-- Doctor Notes Panel -->
        <div class="space-y-6">
            <!-- Rehab Staff Notes -->
            @if($rehabEncounter->rehab_notes)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Rehab Staff Notes</h3>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-700 whitespace-pre-line">{{ $rehabEncounter->rehab_notes }}</p>
                    </div>
                </div>
            @endif
            
            <!-- Doctor Notes Form -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Doctor Notes</h3>
                </div>
                <form wire:submit="saveNotes" class="p-6">
                    <div>
                        <label for="doctor_notes" class="sr-only">Doctor Notes</label>
                        <textarea 
                            wire:model="doctor_notes"
                            id="doctor_notes"
                            rows="8"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                            placeholder="Enter your notes here..."
                        ></textarea>
                        @error('doctor_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mt-4">
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="saveNotes">Save Notes</span>
                            <span wire:loading wire:target="saveNotes">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>