<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Alert Messages at Top -->
        @if(session()->has('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-green-800 dark:text-green-200">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-red-800 dark:text-red-200">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Patient Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Doctor Consultation</h1>
                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $encounter->patient->name }}
                        </span>
                        <span>MR#: {{ $encounter->patient->medical_record_number }}</span>
                        <span>Encounter: #{{ $encounter->id }}</span>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Consulting Doctor</div>
                    <div class="font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                </div>
            </div>

            <!-- Stepper -->
            <div class="mt-8">
                <div class="flex items-center">
                    <!-- Step 1: Vital Signs -->
                    <button wire:click="goToStep(1)" class="flex items-center">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center 
                            {{ $currentStep >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            @if($currentStep > 1)
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                            @else
                            1
                            @endif
                        </div>
                        <div class="ml-3">
                            <div
                                class="text-sm font-medium {{ $currentStep == 1 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                Vital Signs
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Review patient vitals</div>
                        </div>
                    </button>

                    <!-- Connector -->
                    <div
                        class="flex-1 h-0.5 mx-4 {{ $currentStep > 1 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' }}">
                    </div>

                    <!-- Step 2: Medical History -->
                    <button wire:click="goToStep(2)" class="flex items-center">
                        <div
                            class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center 
                            {{ $currentStep >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500 dark:text-gray-400' }}">
                            2
                        </div>
                        <div class="ml-3">
                            <div
                                class="text-sm font-medium {{ $currentStep == 2 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                                Medical History
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Record medical history</div>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <!-- Step 1: Vital Signs -->
        @if($currentStep == 1)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Vital Signs</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Review patient's current vital signs</p>
                </div>
                <button wire:click="nextStep"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Continue to Medical History
                    <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Vital Signs Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                    <div class="flex items-center">
                        <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-blue-800 dark:text-blue-300">Blood Pressure</div>
                            <div class="text-2xl font-bold text-blue-900 dark:text-blue-200">
                                {{ $encounter->bp_systolic ?? '--' }}/{{ $encounter->bp_diastolic ?? '--' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                    <div class="flex items-center">
                        <div class="p-2 bg-green-100 dark:bg-green-800 rounded-lg">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-green-800 dark:text-green-300">Temperature</div>
                            <div class="text-2xl font-bold text-green-900 dark:text-green-200">
                                {{ $encounter->temperature ?? '--' }}°C
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                    <div class="flex items-center">
                        <div class="p-2 bg-purple-100 dark:bg-purple-800 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-purple-800 dark:text-purple-300">Pulse Rate</div>
                            <div class="text-2xl font-bold text-purple-900 dark:text-purple-200">
                                {{ $encounter->pulse ?? '--' }} bpm
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-amber-50 dark:bg-amber-900/20 p-4 rounded-lg border border-amber-100 dark:border-amber-800">
                    <div class="flex items-center">
                        <div class="p-2 bg-amber-100 dark:bg-amber-800 rounded-lg">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-amber-800 dark:text-amber-300">SpO₂ Level</div>
                            <div class="text-2xl font-bold text-amber-900 dark:text-amber-200">
                                {{ $encounter->spo2 ?? '--' }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Taken By</div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            {{ $encounter->triageBy->name ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Priority</div>
                        <div class="font-medium">
                            <span
                                class="px-2 py-1 rounded-full text-xs {{ $this->getPriorityClass($encounter->priority) }}">
                                {{ strtoupper($encounter->priority ?? 'NOT SET') }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <div class="text-gray-500 dark:text-gray-400">Time Recorded</div>
                        <div class="font-medium text-gray-900 dark:text-white">
                            {{ $encounter->updated_at->format('h:i A, M d, Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Step 2: Medical History -->
        @if($currentStep == 2)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Medical History</h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Complete patient's medical history</p>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ count($medicalHistories) }} items
                    </div>
                </div>
            </div>

            <form wire:submit.prevent="saveMedicalHistory" class="p-6">
                <div class="space-y-6">
                    @foreach($medicalHistories as $history)
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0">
                        <label class="block text-base font-medium text-gray-900 dark:text-white mb-3">
                            {{ $history['name'] }}
                        </label>

                        @switch($history['field_type'])
                        @case('yes_no')
                        <div class="flex space-x-6">
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="medicalHistories.{{ $history['template_id'] }}.value"
                                    value="yes"
                                    class="h-4 w-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="medicalHistories.{{ $history['template_id'] }}.value"
                                    value="no"
                                    class="h-4 w-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">No</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" wire:model="medicalHistories.{{ $history['template_id'] }}.value"
                                    value="unknown"
                                    class="h-4 w-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-600 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                <span class="ml-2 text-gray-700 dark:text-gray-300">Unknown</span>
                            </label>
                        </div>
                        @break

                        @case('text')
                        <textarea wire:model.lazy="medicalHistories.{{ $history['template_id'] }}.value" rows="2"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400"
                            placeholder="{{ $history['placeholder'] }}"></textarea>
                        @break

                        @case('number')
                        <input type="number" wire:model.lazy="medicalHistories.{{ $history['template_id'] }}.value"
                            class="mt-1 block w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400"
                            placeholder="{{ $history['placeholder'] }}">
                        @break

                        @case('date')
                        <input type="date" wire:model.lazy="medicalHistories.{{ $history['template_id'] }}.value"
                            max="{{ now()->format('Y-m-d') }}"
                            class="mt-1 block w-48 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 dark:focus:border-blue-400 focus:ring-blue-500 dark:focus:ring-blue-400">
                        @break
                        @endswitch
                    </div>
                    @endforeach
                </div>

                <!-- Form Actions -->
                <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-between">
                    <div>
                        <button type="button" wire:click="previousStep"
                            class="inline-flex items-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Vital Signs
                        </button>
                    </div>

                    <div class="flex space-x-3">
                        <button type="submit" wire:loading.attr="disabled"
                            class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                            <svg wire:loading wire:target="saveMedicalHistory"
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Save Medical History
                        </button>

                        <button type="button" wire:click="nextToChiefComplaint" wire:loading.attr="disabled"
                            class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-lg text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                            Save & Continue to Chief Complaint
                            <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Help Text -->
        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>All fields are optional. Fill only the relevant medical history information.</p>
        </div>
        @endif
    </div>
</div>

@script
<script>
    // Auto-save when leaving the page
    window.addEventListener('beforeunload', function (e) {
        if (@this.hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endscript