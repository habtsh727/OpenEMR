<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert') }" 
         x-show="show" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
            <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                    'bg-gradient-to-r from-blue-500 to-indigo-600') }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($alertType === 'error')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $alertMessage }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Sticky Patient Header Card -->
        <div class="sticky top-4 z-20 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-600 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ substr($patient->name ?? 'N/A', 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}</h1>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-sm text-gray-600 dark:text-gray-400">MRN: {{ $patient->medical_record_number ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $age }} years • {{ $gender }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Admitted: {{ $encounter->created_at->format('M d, Y') }}</span>
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
                                <svg wire:loading wire:target="startTreatment" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="startTreatment">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Start Treatment
                                </span>
                                <span wire:loading wire:target="startTreatment">Starting...</span>
                            </button>
                        @elseif($encounter->status === 'treatment_in_progress')
                            <button wire:click="completeTreatment" wire:loading.attr="disabled"
                                class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                                <svg wire:loading wire:target="completeTreatment" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="completeTreatment">
                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
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
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ $lengthOfStay }}</p>
                        @if($expectedEndDate)
                            <p class="text-xs text-gray-500 dark:text-gray-400">Expected: {{ $expectedEndDate->format('M d, Y') }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Diagnosis</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white truncate">{{ $diagnosis }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treatment Plan Summary Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Treatment Plan Summary</h2>
                </div>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column: Packages & Bed -->
                    <div class="lg:col-span-2 space-y-4">
                        @foreach($orderSummary['packages'] as $package)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $package['name'] }}</h3>
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($package['price'], 2) }}</span>
                                </div>
                                
                                @if(!empty($orderSummary['medications']))
                                    <div class="mt-3">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Medications</p>
                                        <div class="space-y-2">
                                            @foreach($orderSummary['medications'] as $med)
                                                <div class="text-sm p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $med['name'] }}</span>
                                                    @if($med['dosage']) <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">{{ $med['dosage'] }}</span> @endif
                                                    @if($med['frequency']) <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">{{ $med['frequency'] }}</span> @endif
                                                    @if($med['duration']) <span class="text-xs text-gray-600 dark:text-gray-400 ml-2">for {{ $med['duration'] }}</span> @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(!empty($orderSummary['services']))
                                    <div class="mt-3">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Services</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($orderSummary['services'] as $service)
                                                <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400 text-xs rounded-full">
                                                    {{ $service['name'] }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <!-- Right Column: Bed Summary -->
                    <div class="lg:col-span-1">
                        @if($orderSummary['bed'])
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="p-2 bg-purple-200 dark:bg-purple-800 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                    <h3 class="font-semibold text-purple-900 dark:text-purple-300">Bed Assignment</h3>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-purple-700 dark:text-purple-400">Class:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $orderSummary['bed']['class'] }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-purple-700 dark:text-purple-400">Duration:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $orderSummary['bed']['duration'] }} days</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-purple-700 dark:text-purple-400">Price/day:</span>
                                        <span class="font-medium text-gray-900 dark:text-white">ETB {{ number_format($orderSummary['bed']['price_per_day'], 2) }}</span>
                                    </div>
                                    <div class="pt-2 mt-2 border-t border-purple-200 dark:border-purple-800">
                                        <div class="flex justify-between font-bold">
                                            <span class="text-purple-800 dark:text-purple-300">Total Bed Cost:</span>
                                            <span class="text-indigo-600 dark:text-indigo-400">ETB {{ number_format($orderSummary['bed']['total'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Total Amount -->
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Paid Amount</span>
                                <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($orderSummary['total'] + ($orderSummary['bed']['total'] ?? 0), 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Treatment Timeline Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Treatment Timeline</h2>
                </div>
            </div>

            <div class="p-6">
                @if($progressEntries->isEmpty())
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No progress entries yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Start treatment and add progress entries to see timeline.</p>
                        </div>
                    </div>
                @else
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach($progressEntries as $index => $entry)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex items-start space-x-3">
                                            <div class="relative">
                                                <div class="h-10 w-10 rounded-full {{ $entry->category_color }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    @switch($entry->category)
                                                        @case('assessment')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            @break
                                                        @case('therapy')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                            </svg>
                                                            @break
                                                        @case('medication')
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path>
                                                            </svg>
                                                            @break
                                                        @default
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                            </svg>
                                                    @endswitch
                                                </div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div>
                                                    <div class="text-sm">
                                                        <span class="font-medium text-gray-900 dark:text-white">{{ $entry->user->name }}</span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{ $entry->user->roles->first()->name ?? 'Staff' }}</span>
                                                    </div>
                                                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $entry->created_at->format('M d, Y · g:i A') }}
                                                    </p>
                                                </div>
                                                <div class="mt-2">
                                                    <div class="flex flex-wrap gap-2 mb-2">
                                                        <span class="px-2 py-1 text-xs rounded-full {{ $entry->category_color }}">
                                                            {{ $entry->category_label }}
                                                        </span>
                                                        @if($entry->blood_pressure)
                                                            <span class="px-2 py-1 text-xs bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-full">BP: {{ $entry->blood_pressure }}</span>
                                                        @endif
                                                        @if($entry->pulse)
                                                            <span class="px-2 py-1 text-xs bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">Pulse: {{ $entry->pulse }}</span>
                                                        @endif
                                                        @if($entry->temperature)
                                                            <span class="px-2 py-1 text-xs bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full">Temp: {{ $entry->temperature }}°C</span>
                                                        @endif
                                                        @if($entry->mood_scale)
                                                            <span class="px-2 py-1 text-xs bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full">Mood: {{ $entry->mood_scale }}/10</span>
                                                        @endif
                                                    </div>
                                                    <div class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-100 dark:border-gray-700">
                                                        {{ $entry->note }}
                                                    </div>
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

        <!-- Add Progress Entry Form -->
        @if($encounter->status === 'treatment_in_progress')
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                    <div class="flex items-center gap-2">
                        <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Add Progress Entry</h2>
                    </div>
                </div>

                <div class="p-6">
                    <form wire:submit.prevent="addProgress" class="space-y-4">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select wire:model="category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                                <option value="">Select Category</option>
                                <option value="assessment">Assessment</option>
                                <option value="therapy">Therapy Session</option>
                                <option value="medication">Medication</option>
                                <option value="observation">Observation</option>
                                <option value="incident">Incident</option>
                                <option value="general">General Note</option>
                            </select>
                            @error('category') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Note -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Note</label>
                            <textarea wire:model="note" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" placeholder="Enter clinical notes..."></textarea>
                            @error('note') <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Vitals Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Blood Pressure (mmHg)</label>
                                <input type="text" wire:model="bloodPressure" placeholder="120/80" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pulse (bpm)</label>
                                <input type="number" wire:model="pulse" placeholder="72" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Temperature (°C)</label>
                                <input type="number" step="0.1" wire:model="temperature" placeholder="36.6" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mood Scale (1-10)</label>
                                <input type="number" min="1" max="10" wire:model="moodScale" placeholder="7" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end pt-4">
                            <button type="submit" wire:loading.attr="disabled" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                                <svg wire:loading wire:target="addProgress" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span wire:loading.remove wire:target="addProgress">Save Progress Entry</span>
                                <span wire:loading wire:target="addProgress">Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @elseif($encounter->status === 'completed')
            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800 text-center">
                <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-green-800 dark:text-green-300 mb-2">Treatment Completed</h3>
                <p class="text-green-700 dark:text-green-400">This treatment was completed on {{ $treatmentCompletedAt->format('F d, Y \a\t g:i A') }}</p>
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
        [x-cloak] { display: none !important; }
    </style>
</div>