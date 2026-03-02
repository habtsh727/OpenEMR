{{-- resources/views/livewire/appointment/create-appointment.blade.php --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">

    <!-- Alert -->
    @if($showAlert)
    <div class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="rounded-xl shadow-2xl overflow-hidden">
            <div
                class="flex items-center justify-between p-4 {{ $alertType === 'success' ? 'bg-green-500' : 'bg-red-500' }}">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 flex items-center justify-center">
                        @if($alertType === 'success')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button wire:click="$set('showAlert', false)" class="text-white/80 hover:text-white">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="flex items-center gap-4">
                    <div
                        class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Schedule New Appointment</h1>
                        <p class="text-blue-100 mt-1">Create a follow-up or new appointment for your patient</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form wire:submit.prevent="save" class="p-6 space-y-6">

                <!-- Patient Selection -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Patient Information</h2>

                    @if(!$selectedPatient)
                    <div class="relative">
                        <div class="flex gap-2">
                            <div class="flex-1 relative">
                                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" wire:model.live.debounce="patientSearch"
                                    placeholder="Search by name, card number, or phone..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                        </div>

                        @if($showPatientSearch && count($searchResults) > 0)
                        <div
                            class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl max-h-60 overflow-y-auto">
                            @foreach($searchResults as $result)
                            <div wire:click="selectPatient({{ $result['id'] }})"
                                class="p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $result['name'] }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Card: {{
                                            $result['card_number'] }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $result['gender'] }}, {{
                                            $result['age'] }}y</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $result['phone'] }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($selectedPatient)
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                                    {{ substr($selectedPatient->first_name, 0, 1) }}{{
                                    substr($selectedPatient->last_name, 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedPatient->first_name }} {{ $selectedPatient->middle_name }} {{
                                        $selectedPatient->last_name }}
                                    </h3>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Card: {{ $selectedPatient->card_number }} •
                                        {{ ucfirst($selectedPatient->gender) }} •
                                        {{ \Carbon\Carbon::parse($selectedPatient->date_of_birth)->age }} years
                                    </div>
                                </div>
                            </div>
                            <button type="button" wire:click="clearPatient" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif

                    @error('patient_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Appointment Details -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Appointment Details</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Visit Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Visit Type
                                *</label>
                            <select wire:model="visit_type"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @foreach($visitTypes as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Appointment Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date
                                *</label>
                            <input type="date" wire:model.live="appointment_date" min="{{ now()->format('Y-m-d') }}"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            @error('appointment_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Time Slots Section -->
                   <!-- Time Slots Section -->
@if($availableSlots)
    <div class="mt-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
            Available Time Slots *
            <span class="text-xs text-gray-500 ml-2">(Select a time for the appointment)</span>
        </label>
        
        @if($loadingSlots)
            <div class="text-center py-4">
                <svg class="animate-spin h-5 w-5 mx-auto text-blue-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-500 mt-2">Loading available slots...</p>
            </div>
        @else
            @php
                $availableCount = collect($availableSlots)->where('available', true)->count();
            @endphp
            
            @if($availableCount > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @foreach($availableSlots as $slot)
                        @if($slot['available'])
                            <button type="button" 
                                    wire:click="selectSlot('{{ $slot['time'] }}')"
                                    class="px-3 py-2 text-sm rounded-lg border transition-all duration-200
                                        {{ $selectedSlot === $slot['time'] 
                                            ? 'bg-blue-600 text-white border-blue-600 shadow-md' 
                                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 hover:border-blue-400' }}">
                                {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                            </button>
                        @endif
                    @endforeach
                </div>
                
                <p class="text-xs text-gray-500 mt-2">
                    {{ $availableCount }} of {{ count($availableSlots) }} slots available
                </p>
            @else
                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 font-medium">No available slots</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">All time slots are either booked or in the past.</p>
                    <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">Try selecting a different date.</p>
                </div>
            @endif
        @endif
        
        @error('appointment_time') 
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
@elseif(!$loadingSlots)
    <div class="mt-4 text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-700">
        <p class="text-gray-500 dark:text-gray-400">No time slots available for this date</p>
    </div>
@endif
                </div>

                <!-- Payment Information (Optional) -->
                <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Payment Information (Optional)
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Payment
                                Status</label>
                            <select wire:model="payment_status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                @foreach($paymentStatuses as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount
                                (ETB)</label>
                            <input type="number" step="0.01" wire:model="payment_amount"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Additional
                        Notes</label>
                    <textarea wire:model="additional_notes" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Any special instructions or notes..."></textarea>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('doctor.appointments.today') }}"
                        class="px-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>