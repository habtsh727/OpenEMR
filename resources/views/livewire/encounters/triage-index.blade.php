<div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Triage Queue</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pending patient encounters requiring assessment
                </p>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                {{ $encounters->total() }} total
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
       <table class="w-full">
    <thead>
        <tr class="bg-gray-50 dark:bg-gray-800 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            <th class="px-6 py-3 font-semibold">Encounter</th>
            <th class="px-6 py-3 font-semibold">Patient Details</th>
            <th class="px-6 py-3 font-semibold">Card No.</th>
            <th class="px-6 py-3 font-semibold">Gender</th>
            <th class="px-6 py-3 font-semibold">Status</th>
            <th class="px-6 py-3 font-semibold">Arrival Time</th>
            <th class="px-6 py-3 font-semibold text-right">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
        @foreach($encounters as $encounter)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-150">
            <td class="px-6 py-4">
                <div class="font-mono text-sm font-medium text-gray-900 dark:text-gray-100">
                    ENC-{{ str_pad($encounter->id, 5, '0', STR_PAD_LEFT) }}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="font-medium text-gray-900 dark:text-gray-100">
                    {{ $encounter->patient->name ?? 'N/A' }}
                </div>
                @if($encounter->patient)
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Patient ID: {{ $encounter->patient->id }}
                </div>
                @endif
            </td>
            <td class="px-6 py-4">
                @if($encounter->patient && $encounter->patient->card_number)
                    <div class="font-mono text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $encounter->patient->card_number }}
                    </div>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">No card</span>
                @endif
            </td>
            <td class="px-6 py-4">
                @if($encounter->patient && $encounter->patient->gender)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium 
                        @if(strtolower($encounter->patient->gender) === 'male')
                            bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                        @elseif(strtolower($encounter->patient->gender) === 'female')
                            bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-300
                        @else
                            bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                        @endif">
                        @if(strtolower($encounter->patient->gender) === 'male')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        @elseif(strtolower($encounter->patient->gender) === 'female')
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ ucfirst($encounter->patient->gender) }}
                    </span>
                @else
                    <span class="text-xs text-gray-400 dark:text-gray-500 italic">Unknown</span>
                @endif
            </td>
            <td class="px-6 py-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                    {{ ucfirst($encounter->status) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm text-gray-900 dark:text-gray-100">
                    {{ $encounter->created_at->format('d M Y') }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $encounter->created_at->format('H:i') }}
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex justify-end space-x-2">
                    <!-- View Button -->
                    <button class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 text-xs font-medium rounded text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-900 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        View
                    </button>

                    <!-- Process Button -->
                    <button wire:click="openProcessModal({{ $encounter->id }})"
                        class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-900 transition-colors">
                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5l7 7-7 7" />
                        </svg>
                        Process
                    </button>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
    </div>

    <!-- Empty State -->
    @if($encounters->isEmpty())
    <div class="px-6 py-12 text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">No pending encounters</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">All patient encounters have been processed.</p>
    </div>
    @endif

    <!-- Pagination -->
    @if($encounters->hasPages())
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
        {{ $encounters->links() }}
    </div>
    @endif

    <!-- Triage Processing Modal (Side Modal) -->
   @if($showProcessModal)
<div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 bg-black/25 dark:bg-black/50 backdrop-blur-sm">
    <div class="relative my-8 w-full max-w-4xl">
        <!-- Modal Container -->
        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white dark:from-gray-900/50 dark:to-gray-800">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Process Triage - ENC-{{ $selectedEncounterId ? str_pad($selectedEncounterId, 5, '0', STR_PAD_LEFT) : '' }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Record vital signs and assign priority</p>
                </div>
                <button wire:click="closeModal" 
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="processTriage" class="px-6 py-5 space-y-6">
                <!-- Patient Information -->
                @if($this->selectedEncounter && $this->selectedEncounter->patient)
                <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-100 dark:border-blue-800/30">
                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-2">Patient Information</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Patient Name</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $this->selectedEncounter->patient->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Patient ID</p>
                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $this->selectedEncounter->patient->id }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Vital Signs Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Vital Signs</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Blood Pressure -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Blood Pressure (mm Hg)</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <input type="number" 
                                           wire:model="vitals.bp_systolic"
                                           placeholder="Systolic"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                           min="50"
                                           max="250">
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Systolic</p>
                                </div>
                                <div>
                                    <input type="number" 
                                           wire:model="vitals.bp_diastolic"
                                           placeholder="Diastolic"
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                           min="30"
                                           max="150">
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Diastolic</p>
                                </div>
                            </div>
                        </div>

                        <!-- Temperature -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Temperature (°C)</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       wire:model="vitals.temperature"
                                       placeholder="36.5"
                                       step="0.1"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                       min="30"
                                       max="45">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.917.522l-4.897 9.723a1 1 0 00.914 1.406h9.8a1 1 0 00.914-1.406L9.917 2.522A1 1 0 009 2z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Pulse Rate -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Pulse Rate (bpm)</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       wire:model="vitals.pulse"
                                       placeholder="72"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                       min="30"
                                       max="200">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Oxygen Saturation -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>SpO₂ (%)</span>
                            </label>
                            <div class="relative">
                                <input type="number" 
                                       wire:model="vitals.spo2"
                                       placeholder="98"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                                       min="70"
                                       max="100">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Triage Assessment Section -->
                <div>
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Triage Assessment</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Priority Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Priority Level</span>
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select wire:model="vitals.priority"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Select Priority</option>
                                <option value="low">Low (Green)</option>
                                <option value="medium">Medium (Yellow)</option>
                                <option value="high">High (Red)</option>
                                <option value="critical">Critical</option>
                            </select>
                            @error('vitals.priority')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assign Doctor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Assign to Doctor</span>
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select wire:model="vitals.doctor_id"
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="">Select Doctor</option>
                                @foreach($this->doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        Dr. {{ $doctor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vitals.doctor_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea wire:model="vitals.notes"
                              rows="3"
                              placeholder="Any additional observations or notes..."
                              class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white"></textarea>
                </div>

                <!-- Form Actions -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                    <button type="button" 
                            wire:click="closeModal"
                            class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2.5 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200 flex items-center gap-2"
                            wire:loading.attr="disabled">
                        <span wire:loading.remove>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Complete Triage
                        </span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Processing...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

    <!-- Success Message -->
    @if(session()->has('message'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="fixed top-4 right-4 z-50">
        <div
            class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg shadow-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-500 dark:text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    </div>
    @endif
</div>