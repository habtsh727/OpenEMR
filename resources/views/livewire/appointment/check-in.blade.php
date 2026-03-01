<div>
    <!-- Check-In Modal -->
    @if($showModal && $appointment)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transition-all duration-300 transform">
                
                <!-- Success State -->
                @if($showSuccess && $encounter)
                    <div class="p-6 text-center">
                        <div class="flex justify-center mb-4">
                            <div class="h-20 w-20 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center animate-bounce">
                                <svg class="w-10 h-10 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Check-In Successful!</h3>
                        
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 mb-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">Encounter Created</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">#{{ $encounter->id }}</p>
                        </div>
                        
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('encounters.show', $encounter->id) }}" 
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                                View Encounter
                            </a>
                            <button wire:click="closeModal" 
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Close
                            </button>
                        </div>
                    </div>
                    
                <!-- Check-In Form -->
                @else
                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-full">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Check In Patient</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Verify patient information</p>
                            </div>
                            <button wire:click="closeModal" class="ml-auto text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Patient Info Card -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Card: {{ $appointment->patient->card_number }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-3 mt-3 text-sm">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Appointment Time</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->appointment_time->format('h:i A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Doctor</p>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        Dr. {{ $appointment->doctor->name }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Visit Type</p>
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Payment</p>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status_color }}">
                                        {{ ucfirst($appointment->payment_status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Check-In Info -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 mb-4">
                            <div class="flex items-start gap-2">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-700 dark:text-blue-300">
                                        Checking in will:
                                    </p>
                                    <ul class="text-xs text-blue-600 dark:text-blue-400 list-disc list-inside mt-1">
                                        <li>Create a new encounter for this patient</li>
                                        <li>Mark appointment as completed</li>
                                        <li>Record check-in time</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-3">
                            <button wire:click="closeModal"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button wire:click="processCheckIn" 
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2 min-w-[120px] justify-center">
                                <span wire:loading.remove wire:target="processCheckIn">Confirm Check In</span>
                                <span wire:loading wire:target="processCheckIn" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>