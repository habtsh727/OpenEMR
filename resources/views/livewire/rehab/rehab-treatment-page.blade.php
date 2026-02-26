<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">

    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-2" class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                    'bg-gradient-to-r from-yellow-500 to-orange-600') }}">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        @if($alertType === 'success')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @elseif($alertType === 'error')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Back to Queue Link -->
        <div class="flex items-center gap-2">
            <a href="{{ route('rehab.treatment.queue') }}" wire:navigate
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Treatment Queue
            </a>
        </div>

        <!-- Sticky Patient Header Card -->
        <div
            class="sticky top-4 z-20 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 rounded-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ substr($patient->name ?? 'N/A', 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-sm text-gray-600 dark:text-gray-400">MRN: {{
                                    $patient->medical_record_number ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $age }} years</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Admitted: {{
                                    $encounter->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Status Badge -->
                        <span class="px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }}">
                            {{ $encounter->status_label }}
                        </span>

                        <!-- Action Buttons -->
                        @if($encounter->status === 'sent_to_rehab')
                        <button wire:click="startTreatment" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="startTreatment" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="startTreatment">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Start Treatment
                            </span>
                            <span wire:loading wire:target="startTreatment">Starting...</span>
                        </button>
                        @elseif($encounter->status === 'treatment_in_progress')
                        <button wire:click="completeTreatment" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="completeTreatment" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="completeTreatment">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Complete Treatment
                            </span>
                            <span wire:loading wire:target="completeTreatment">Completing...</span>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Patient Info Grid -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Attending Doctor</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">Dr. {{ $doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bed Assignment</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ $bedInfo['ward'] }} → {{ $bedInfo['room'] }} → {{ $bedInfo['bed'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bedInfo['class'] }}</p>
                    </div>
                   <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Length of Stay</p>
    <p class="mt-1 font-semibold text-gray-900 dark:text-white">
        @if($daysLeft)
            @if($daysLeft['type'] === 'overdue')
                <span class="text-orange-600 dark:text-orange-400">{{ $daysLeft['text'] }}</span>
            @else
                {{ $daysLeft['text'] }}
            @endif
        @else
            Not started
        @endif
    </p>
    @if($expectedEndDate && !$treatmentCompletedAt)
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Expected: {{ $expectedEndDate->format('M d, Y') }}
        </p>
    @endif
</div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rehab Staff</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Treatment Entry Form -->
        @if($encounter->status === 'treatment_in_progress')
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Add Treatment Entry</h2>
                </div>
            </div>

            <div class="p-6">
                <!-- Treatment Type Selection -->
                @if(!$selectedType)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($treatmentTypes as $type)
                    <button wire:click="selectType({{ $type->id }})"
                        class="p-6 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-lg transition-all text-left group">
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <div class="w-5 h-5 text-emerald-600 dark:text-emerald-400 group-hover:text-white">
                                    {!! $type->icon_html !!}
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600">{{
                                $type->name }}</h3>
                        </div>
                        @if($type->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $type->description }}</p>
                        @endif
                    </button>
                    @endforeach
                </div>
                @else
                <!-- Dynamic Form -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $currentType->name }}</h3>
                        <button wire:click="$set('selectedType', null)"
                            class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Change Type
                        </button>
                    </div>

                    @foreach($formFields as $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $field['label'] }}
                            @if($field['required'] ?? false)
                            <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @switch($field['type'])
                        @case('text')
                        <input type="text" wire:model="answers.{{ $field['name'] }}"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('textarea')
                        <textarea wire:model="answers.{{ $field['name'] }}" rows="3"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        @break

                        @case('number')
                        <input type="number" wire:model="answers.{{ $field['name'] }}"
                            step="{{ $field['step'] ?? '1' }}" min="{{ $field['min'] ?? '' }}"
                            max="{{ $field['max'] ?? '' }}" placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('select')
                        <select wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select {{ $field['label'] }}</option>
                            @foreach($field['options'] ?? [] as $option)
                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @break

                        @case('radio')
                        <div class="space-y-2">
                            @foreach($field['options'] ?? [] as $option)
                            <label class="flex items-center gap-2">
                                <input type="radio" wire:model="answers.{{ $field['name'] }}"
                                    value="{{ $option['value'] }}" class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @break

                        @case('checkbox')
                        <div class="space-y-2">
                            @foreach($field['options'] ?? [] as $option)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="answers.{{ $field['name'] }}"
                                    value="{{ $option['value'] }}"
                                    class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @break

                        @case('date')
                        <input type="date" wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('time')
                        <input type="time" wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('blood_pressure')
                        <input type="text" wire:model="answers.{{ $field['name'] }}" placeholder="120/80"
                            pattern="\d{2,3}/\d{2,3}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break
                        @endswitch

                        @if(!empty($field['help']))
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $field['help'] }}</p>
                        @endif
                    </div>
                    @endforeach

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional
                            Notes</label>
                        <textarea wire:model="notes" rows="3"
                            placeholder="Add any additional notes about this treatment session..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button wire:click="saveEntry" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="saveEntry" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="saveEntry">Save Treatment Entry</span>
                            <span wire:loading wire:target="saveEntry">Saving...</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Treatment Timeline -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Treatment Timeline</h2>
                </div>
            </div>

            <div class="p-6">
                @if($treatmentEntries->isEmpty())
                <div class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No treatment entries yet</h3>
                        <p class="text-gray-500 dark:text-gray-400">Start treatment and add entries to see timeline.</p>
                    </div>
                </div>
                @else
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($treatmentEntries as $index => $entry)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                    aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div
                                            class="h-10 w-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            <div class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                                                {!! $entry->treatmentType->icon_html !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-900 dark:text-white">{{
                                                    $entry->user->name }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{
                                                    $entry->treatmentType->name }}</span>
                                            </div>
                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $entry->created_at->format('M d, Y · g:i A') }}
                                            </p>
                                        </div>
                                        <div class="mt-2">
                                            <!-- Display dynamic answers -->
                                            <div class="grid grid-cols-2 gap-2 mb-2">
                                                @foreach($entry->formatted_answers as $key => $value)
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{
                                                        ucfirst($key) }}:</span>
                                                    <span class="ml-1 text-gray-900 dark:text-white">{{ $value }}</span>
                                                </div>
                                                @endforeach
                                            </div>

                                            @if($entry->notes)
                                            <div
                                                class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-100 dark:border-gray-700 mt-2">
                                                {{ $entry->notes }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <!-- Completion Message -->
        @if($encounter->status === 'completed')
        <div
            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800 text-center">
            <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-green-800 dark:text-green-300 mb-2">Treatment Completed</h3>
            <p class="text-green-700 dark:text-green-400">This treatment was completed on {{
                $treatmentCompletedAt->format('F d, Y \a\t g:i A') }}</p>
        </div>
        @endif
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert-shown', () => {
                setTimeout(() => {
                    @this.dispatch('closeAlert');
                }, 5000);
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>