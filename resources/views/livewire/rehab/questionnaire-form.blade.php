<div>
    <!-- Header -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Rehabilitation Questionnaire</h2>
                <div class="mt-2 space-y-1">
                    <p class="text-sm text-gray-600">
                        Patient: <span class="font-medium">{{ $rehabEncounter->encounter->patient->name }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        MRN: <span class="font-medium">{{ $rehabEncounter->encounter->patient->medical_record_number }}</span>
                    </p>
                    <p class="text-sm text-gray-600">
                        Doctor: <span class="font-medium">{{ $rehabEncounter->encounter->doctor->name ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <x-status-badge :status="$rehabEncounter->status" />
                <button 
                    wire:click="saveDraft"
                    wire:loading.attr="disabled"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="saveDraft">Save Draft</span>
                    <span wire:loading wire:target="saveDraft">Saving...</span>
                </button>
            </div>
        </div>
    </div>
    
    <form wire:submit="submit">
        <div class="space-y-6">
            <!-- Template Selection -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Questionnaire Template</h3>
                </div>
                <div class="p-6">
                    <select 
                        wire:model.live="selectedTemplateId"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                    >
                        <option value="">Select a template</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Questionnaire Questions -->
            @if(count($questions) > 0)
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800">Assessment Questions</h3>
                        <p class="mt-1 text-sm text-gray-600">Please fill out the following assessment questions</p>
                    </div>
                    <div class="p-6 space-y-8">
                        @foreach($questions as $question)
                            <x-dynamic-question 
                                :question="$question"
                                wire:model="answers.{{ $question->id }}"
                            />
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Additional Notes -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Additional Notes</h3>
                </div>
                <div class="p-6">
                    <div>
                        <label for="rehab_notes" class="sr-only">Additional Notes</label>
                        <textarea 
                            wire:model="rehab_notes"
                            id="rehab_notes"
                            rows="6"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                            placeholder="Add any additional notes, observations, or recommendations..."
                        ></textarea>
                        @error('rehab_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4">
                    <div class="flex justify-end space-x-3">
                        <a 
                            href="{{ route('rehab.queue') }}"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            Cancel
                        </a>
                        <button 
                            type="submit"
                            wire:loading.attr="disabled"
                            wire:confirm="Submit questionnaire to doctor? This action cannot be undone."
                            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                        >
                            <span wire:loading.remove wire:target="submit">Submit to Doctor</span>
                            <span wire:loading wire:target="submit">Submitting...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>