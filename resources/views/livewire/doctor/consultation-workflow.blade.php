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
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                {{ count($this->collectedVitals) }} vital signs collected
            </p>
        </div>
        <button wire:click="nextStep"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
            Continue to Medical History
            <svg class="ml-2 -mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    <!-- Dynamic Vital Signs Cards -->
    @if(count($this->collectedVitals) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach($this->collectedVitals as $vital)
            @php
                $colorClasses = [
                    'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-100 dark:border-blue-800', 'icon' => 'bg-blue-100 dark:bg-blue-800', 'text' => 'text-blue-600 dark:text-blue-400', 'textDark' => 'text-blue-800 dark:text-blue-300', 'value' => 'text-blue-900 dark:text-blue-200'],
                    'green' => ['bg' => 'bg-green-50 dark:bg-green-900/20', 'border' => 'border-green-100 dark:border-green-800', 'icon' => 'bg-green-100 dark:bg-green-800', 'text' => 'text-green-600 dark:text-green-400', 'textDark' => 'text-green-800 dark:text-green-300', 'value' => 'text-green-900 dark:text-green-200'],
                    'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'border' => 'border-purple-100 dark:border-purple-800', 'icon' => 'bg-purple-100 dark:bg-purple-800', 'text' => 'text-purple-600 dark:text-purple-400', 'textDark' => 'text-purple-800 dark:text-purple-300', 'value' => 'text-purple-900 dark:text-purple-200'],
                    'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-900/20', 'border' => 'border-amber-100 dark:border-amber-800', 'icon' => 'bg-amber-100 dark:bg-amber-800', 'text' => 'text-amber-600 dark:text-amber-400', 'textDark' => 'text-amber-800 dark:text-amber-300', 'value' => 'text-amber-900 dark:text-amber-200'],
                    'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'border' => 'border-indigo-100 dark:border-indigo-800', 'icon' => 'bg-indigo-100 dark:bg-indigo-800', 'text' => 'text-indigo-600 dark:text-indigo-400', 'textDark' => 'text-indigo-800 dark:text-indigo-300', 'value' => 'text-indigo-900 dark:text-indigo-200'],
                    'teal' => ['bg' => 'bg-teal-50 dark:bg-teal-900/20', 'border' => 'border-teal-100 dark:border-teal-800', 'icon' => 'bg-teal-100 dark:bg-teal-800', 'text' => 'text-teal-600 dark:text-teal-400', 'textDark' => 'text-teal-800 dark:text-teal-300', 'value' => 'text-teal-900 dark:text-teal-200'],
                    'red' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'border' => 'border-red-100 dark:border-red-800', 'icon' => 'bg-red-100 dark:bg-red-800', 'text' => 'text-red-600 dark:text-red-400', 'textDark' => 'text-red-800 dark:text-red-300', 'value' => 'text-red-900 dark:text-red-200'],
                    'gray' => ['bg' => 'bg-gray-50 dark:bg-gray-900/20', 'border' => 'border-gray-100 dark:border-gray-800', 'icon' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-600 dark:text-gray-400', 'textDark' => 'text-gray-800 dark:text-gray-300', 'value' => 'text-gray-900 dark:text-gray-200'],
                ];
                
                $color = $colorClasses[$vital['color_class']] ?? $colorClasses['gray'];
            @endphp
            
            <div class="{{ $color['bg'] }} p-4 rounded-lg border {{ $color['border'] }}">
                <div class="flex items-center">
                    <div class="p-2 {{ $color['icon'] }} rounded-lg">
                        <svg class="w-6 h-6 {{ $color['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $vital['icon'] }}" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium {{ $color['textDark'] }}">
                            {{ $vital['name'] }}
                        </div>
                        <div class="text-2xl font-bold {{ $color['value'] }}">
                            {{ $vital['value'] }}
                            @if($vital['unit'])
                                <span class="text-lg">{{ $vital['unit'] }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-8 text-center">
        <svg class="w-12 h-12 text-yellow-500 dark:text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
        <h3 class="text-lg font-medium text-yellow-800 dark:text-yellow-300 mb-2">No Vital Signs Recorded</h3>
        <p class="text-yellow-700 dark:text-yellow-400">No vital signs have been collected for this patient yet.</p>
    </div>
    @endif

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
                        <button type="button"
                                wire:click="skipMedicalHistory"
                                class="px-6 py-3 border-2 border-yellow-300 dark:border-yellow-700 rounded-xl text-sm font-medium text-yellow-700 dark:text-yellow-300 bg-yellow-50 dark:bg-yellow-900/20 hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-all duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span>Skip Medical History</span>
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