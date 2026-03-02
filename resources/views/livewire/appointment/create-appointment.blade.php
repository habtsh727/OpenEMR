{{-- resources/views/livewire/appointment/create-appointment.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 transition-colors duration-200">
    
    <!-- Alert Notification -->
    @if($showAlert)
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 5000)"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-2"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-2"
             class="fixed top-4 right-4 z-50 max-w-md w-full">
            <div class="rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    'bg-gradient-to-r from-red-500 to-rose-600' }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
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
        </div>
    @endif

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header with Animated Gradient -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 dark:from-blue-800 dark:via-indigo-800 dark:to-purple-800 shadow-2xl mb-8">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:40px_40px]"></div>
            <div class="absolute top-0 right-0 -mt-32 -mr-32 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-32 -ml-32 h-64 w-64 rounded-full bg-purple-500/10 blur-3xl"></div>
            
            <div class="relative px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-2xl">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">Schedule Appointment</h1>
                        <p class="text-white/80 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ Carbon\Carbon::now('Africa/Addis_Ababa')->format('l, F j, Y') }}
                        </p>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="flex items-center gap-2 mt-6">
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $formStep >= 1 ? 'bg-white text-blue-600' : 'bg-white/30 text-white' }} flex items-center justify-center text-sm font-bold transition-all duration-300">1</div>
                        <span class="ml-2 text-sm {{ $formStep >= 1 ? 'text-white' : 'text-white/60' }}">Patient</span>
                    </div>
                    <div class="w-12 h-0.5 {{ $formStep >= 2 ? 'bg-white' : 'bg-white/30' }} transition-all duration-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $formStep >= 2 ? 'bg-white text-blue-600' : 'bg-white/30 text-white' }} flex items-center justify-center text-sm font-bold transition-all duration-300">2</div>
                        <span class="ml-2 text-sm {{ $formStep >= 2 ? 'text-white' : 'text-white/60' }}">Details</span>
                    </div>
                    <div class="w-12 h-0.5 {{ $formStep >= 3 ? 'bg-white' : 'bg-white/30' }} transition-all duration-300"></div>
                    <div class="flex items-center">
                        <div class="w-8 h-8 rounded-full {{ $formStep >= 3 ? 'bg-white text-blue-600' : 'bg-white/30 text-white' }} flex items-center justify-center text-sm font-bold transition-all duration-300">3</div>
                        <span class="ml-2 text-sm {{ $formStep >= 3 ? 'text-white' : 'text-white/60' }}">Confirm</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <form wire:submit.prevent="save" class="divide-y divide-gray-200 dark:divide-gray-700">
                
                <!-- Step 1: Patient Selection -->
                <div class="p-8 {{ $formStep != 1 ? 'opacity-50 pointer-events-none' : '' }} transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">1</div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Select Patient</h2>
                        @if($selectedPatient)
                            <span class="ml-auto text-sm text-green-600 dark:text-green-400 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Patient Selected
                            </span>
                        @endif
                    </div>

                    @if(!$selectedPatient)
                        <div class="relative">
                            <div class="relative">
                                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="patientSearch" 
                                       placeholder="Search by name, card number, or phone..."
                                       class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-lg">
                                @if($searchLoading)
                                    <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            @if($showPatientSearch && count($searchResults) > 0)
                                <div class="absolute z-20 mt-2 w-full bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl max-h-80 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($searchResults as $result)
                                        <div wire:click="selectPatient({{ $result['id'] }})" 
                                             class="p-4 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 dark:hover:from-gray-700 dark:hover:to-gray-700 cursor-pointer transition-all group">
                                            <div class="flex items-center gap-4">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-md">
                                                    {{ $result['avatar'] }}
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400">{{ $result['name'] }}</p>
                                                    <div class="flex items-center gap-4 mt-1 text-sm">
                                                        <span class="text-gray-500 dark:text-gray-400">Card: {{ $result['card_number'] }}</span>
                                                        <span class="text-gray-500 dark:text-gray-400">•</span>
                                                        <span class="text-gray-500 dark:text-gray-400">{{ $result['gender'] }}, {{ $result['age'] }}y</span>
                                                    </div>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="h-16 w-16 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                        {{ substr($selectedPatient->first_name, 0, 1) }}{{ substr($selectedPatient->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $selectedPatient->first_name }} {{ $selectedPatient->middle_name }} {{ $selectedPatient->last_name }}
                                        </h3>
                                        <div class="grid grid-cols-2 gap-x-6 gap-y-2 mt-2 text-sm">
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                                <span>Card: {{ $selectedPatient->card_number }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span>{{ $selectedPatient->phone_number1 }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                                <span>{{ ucfirst($selectedPatient->gender) }}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{{ Carbon\Carbon::parse($selectedPatient->date_of_birth)->age }} years</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" wire:click="clearPatient" 
                                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endif

                    @error('patient_id')
                        <p class="mt-3 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror

                    @if(!$selectedPatient)
                        <div class="mt-6 flex justify-end">
                            <button type="button" wire:click="$set('formStep', 2)" 
                                    class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                                Skip to Details
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Step 2: Appointment Details -->
                <div class="p-8 {{ $formStep != 2 ? 'opacity-50 pointer-events-none' : '' }} transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">2</div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Appointment Details</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Visit Type and Date -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Visit Type *</label>
                                <select wire:model="visit_type" 
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    @foreach($visitTypes as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date *</label>
                                <input type="date" wire:model.live="appointment_date" min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                @error('appointment_date') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Time Slots -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Available Time Slots *
                                <span class="text-xs text-gray-500 ml-2">({{ $availableSlots ? collect($availableSlots)->where('available', true)->count() : 0 }} slots available)</span>
                            </label>

                            @if($loadingSlots)
                                <div class="text-center py-8 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                                    <svg class="animate-spin h-8 w-8 mx-auto text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="mt-3 text-gray-600 dark:text-gray-400">Loading available time slots...</p>
                                </div>
                            @elseif($availableSlots && collect($availableSlots)->where('available', true)->count() > 0)
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                    @foreach($availableSlots as $slot)
                                        @if($slot['available'])
                                            <button type="button" 
                                                    wire:click="selectSlot('{{ $slot['time'] }}')"
                                                    class="relative px-4 py-3 text-sm font-medium rounded-xl border-2 transition-all duration-200
                                                        {{ $selectedSlot === $slot['time'] 
                                                            ? 'bg-blue-600 text-white border-blue-600 shadow-lg scale-105' 
                                                            : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-blue-400 hover:shadow-md hover:scale-105' }}">
                                                {{ Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                                @if($selectedSlot === $slot['time'])
                                                    <span class="absolute -top-2 -right-2 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </span>
                                                @endif
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            @elseif(!$loadingSlots)
                                <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/50 rounded-xl border-2 border-gray-200 dark:border-gray-700">
                                    <div class="inline-flex p-4 bg-yellow-100 dark:bg-yellow-900/30 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="text-gray-900 dark:text-white font-medium text-lg mb-2">No Available Slots</p>
                                    <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                                        All time slots for this date are either booked or have passed. Please select a different date.
                                    </p>
                                </div>
                            @endif

                            @error('appointment_time') 
                                <p class="mt-3 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Step 3: Additional Information -->
                <div class="p-8 {{ $formStep != 3 ? 'opacity-50 pointer-events-none' : '' }} transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold">3</div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Additional Information</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Doctor's Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor's Notes (Optional)</label>
                            <textarea wire:model="doctor_notes" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                      placeholder="Add any clinical notes or observations..."></textarea>
                        </div>

                        <!-- Payment Information (Optional) -->
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-xl p-6 border-2 border-gray-200 dark:border-gray-700">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Payment Information (Optional)
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Payment Status</label>
                                    <select wire:model="payment_status" 
                                            class="w-full px-4 py-2 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @foreach($paymentStatuses as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Amount (ETB)</label>
                                    <input type="number" step="0.01" wire:model="payment_amount"
                                           class="w-full px-4 py-2 border-2 border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Notes (Optional)</label>
                            <textarea wire:model="additional_notes" rows="2" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                      placeholder="Any special instructions or notes for reception..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 dark:bg-gray-700/50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        @if($formStep > 1)
                            <button type="button" wire:click="$set('formStep', {{ $formStep - 1 }})" 
                                    class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all font-medium flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Previous
                            </button>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('doctor.appointments.today') }}" 
                           class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all font-medium">
                            Cancel
                        </a>

                        @if($formStep < 3)
                            <button type="button" wire:click="$set('formStep', {{ $formStep + 1 }})" 
                                    class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                                Continue
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <button type="submit"
                                    class="px-8 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Schedule Appointment
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Tips Card -->
        <div class="mt-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4">
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-200 dark:bg-blue-800 rounded-lg">
                    <svg class="w-5 h-5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-300 mb-1">Quick Tips</h4>
                    <p class="text-sm text-blue-700 dark:text-blue-400">
                        <span class="font-medium">Time slots:</span> Available slots are shown in green. Select a time to continue.<br>
                        <span class="font-medium">Payment info:</span> Optional - can be updated later by cashier.<br>
                        <span class="font-medium">Doctor's notes:</span> Add clinical notes for your reference.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading wire:target="save" 
         class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[100]">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4">
            <svg class="animate-spin h-12 w-12 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <p class="text-gray-900 dark:text-white font-medium">Creating your appointment...</p>
        </div>
    </div>
</div>