<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header with Navigation -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('appointments.index') }}" 
                   class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View and manage appointment information</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                @if($appointment->isCheckInAvailable())
                    <button wire:click="openCheckInModal"
                            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Check In
                    </button>
                @endif

                @if($appointment->status === 'scheduled')
                    <button wire:click="openRescheduleModal"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Reschedule
                    </button>
                @endif

                @if(in_array($appointment->status, ['scheduled', 'rescheduled']))
                    <button wire:click="openCancelModal"
                            class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Cancel
                    </button>
                @endif

                @if($appointment->status === 'scheduled' && $appointment->appointment_time->isPast() && !$appointment->checked_in_at)
                    <button wire:click="markMissed"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Mark Missed
                    </button>
                @endif
            </div>
        </div>

        <!-- Patient Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-800 dark:to-indigo-800 px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                        <span class="text-2xl font-bold text-white">
                            {{ substr($appointment->patient->first_name ?? 'N', 0, 1) }}{{ substr($appointment->patient->last_name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">
                            {{ $appointment->patient->first_name }} {{ $appointment->patient->middle_name }} {{ $appointment->patient->last_name }}
                        </h2>
                        <div class="flex items-center gap-4 mt-1 text-blue-100">
                            <span>Card: {{ $appointment->patient->card_number }}</span>
                            <span>•</span>
                            <span>{{ ucfirst($appointment->patient->gender) }}, {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} yrs</span>
                            <span>•</span>
                            <span>{{ $appointment->patient->phone_number1 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 divide-x divide-gray-200 dark:divide-gray-700">
                <div class="p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Appointment ID</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">#{{ $appointment->id }}</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
                <div class="p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Time Until</p>
                   <p class="text-lg font-semibold {{ $timeUntil === 'Past' ? 'text-red-600' : 'text-green-600' }}">
    {{ $timeUntil }}
</p>
                </div>
                <div class="p-4 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Created By</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $appointment->creator->name ?? 'System' }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8">
                <button wire:click="setActiveTab('overview')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors
                            {{ $activeTab === 'overview' 
                                ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Overview
                    </div>
                </button>
                <button wire:click="setActiveTab('history')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors
                            {{ $activeTab === 'history' 
                                ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        History
                    </div>
                </button>
                <button wire:click="setActiveTab('documents')"
                        class="py-4 px-1 border-b-2 font-medium text-sm transition-colors
                            {{ $activeTab === 'documents' 
                                ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                                : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Documents
                    </div>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="mt-6">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Appointment Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Appointment Info Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Appointment Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Date</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                            {{ $appointment->appointment_date->format('l, F j, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Time</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                            {{ $appointment->appointment_time->format('h:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Doctor</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                            Dr. {{ $appointment->doctor->name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->doctor->specialization ?? 'General' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Visit Type</p>
                                        <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Time Slot</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                            {{ $appointment->time_slot ?? 'Not specified' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Checked In</p>
                                        <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                            @if($appointment->checked_in_at)
                                                {{ $appointment->checked_in_at->format('h:i A') }}
                                            @else
                                                <span class="text-gray-400">Not checked in</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                @if($appointment->additional_notes)
                                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Additional Notes</p>
                                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                            <p class="text-gray-700 dark:text-gray-300">{{ $appointment->additional_notes }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Encounter Info (if checked in) -->
                        @if($appointment->encounter)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-green-50 dark:bg-green-900/20 border-b border-green-200 dark:border-green-800">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="text-lg font-semibold text-green-800 dark:text-green-300">Linked Encounter</h3>
                                    </div>
                                </div>
                                <div class="p-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Encounter #{{ $appointment->encounter->id }}</p>
                                            <p class="text-base font-medium text-gray-900 dark:text-white mt-1">
                                                {{ $appointment->encounter->created_at->format('M d, Y h:i A') }}
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Status: 
                                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                                    {{ ucfirst($appointment->encounter->status) }}
                                                </span>
                                            </p>
                                        </div>
                                        <a href="" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors">
                                            View Encounter
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Patient Info & Payment -->
                    <div class="space-y-6">
                        <!-- Patient Details Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Patient Details</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Full Name</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Card Number</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->card_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->phone_number1 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Date of Birth</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Gender</span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($appointment->patient->gender) }}</span>
                                </div>
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <a href="" 
                                       class="text-blue-600 hover:text-blue-800 dark:text-blue-400 text-sm font-medium flex items-center gap-1">
                                        View Full Profile
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Information</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status_color }}">
                                        {{ ucfirst($appointment->payment_status) }}
                                    </span>
                                </div>
                                @if($appointment->payment_amount)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Amount</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-white">
                                            ETB {{ number_format($appointment->payment_amount, 2) }}
                                        </span>
                                    </div>
                                @endif
                                @if($appointment->related_order_type !== 'none')
                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Related Order</p>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($appointment->related_order_type) }}
                                        </span>
                                        @if($appointment->related_order_id)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Order #{{ $appointment->related_order_id }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- History Tab -->
            @if($activeTab === 'history')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Appointment History</h3>
                    </div>
                    <div class="p-6">
                        @if($histories->isEmpty())
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">No history records found</p>
                            </div>
                        @else
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($histories as $index => $history)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div class="relative">
                                                        <div class="h-10 w-10 rounded-full bg-{{ $history->action === 'created' ? 'green' : ($history->action === 'cancelled' ? 'red' : 'blue') }}-100 dark:bg-{{ $history->action === 'created' ? 'green' : ($history->action === 'cancelled' ? 'red' : 'blue') }}-900/30 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                            @if($history->action === 'created')
                                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                </svg>
                                                            @elseif($history->action === 'cancelled')
                                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @elseif($history->action === 'rescheduled')
                                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            @elseif($history->action === 'checked_in')
                                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div>
                                                            <div class="text-sm">
                                                                <span class="font-medium text-gray-900 dark:text-white">{{ $history->user->name }}</span>
                                                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($history->action) }}</span>
                                                            </div>
                                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                                {{ $history->created_at->format('M d, Y · h:i A') }}
                                                            </p>
                                                        </div>
                                                        @if($history->reason)
                                                            <div class="mt-2 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                                                <span class="font-medium">Reason:</span> {{ $history->reason }}
                                                            </div>
                                                        @endif
                                                        @if($history->old_values || $history->new_values)
                                                            <div class="mt-2 text-xs">
                                                                @if($history->old_values)
                                                                    <span class="text-gray-500 dark:text-gray-400">Old: {{ json_encode($history->old_values) }}</span>
                                                                @endif
                                                                @if($history->new_values)
                                                                    <span class="text-gray-500 dark:text-gray-400 ml-2">New: {{ json_encode($history->new_values) }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
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
            @endif

            <!-- Documents Tab -->
            @if($activeTab === 'documents')
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Documents & Attachments</h3>
                    </div>
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Documents</h3>
                        <p class="text-gray-500 dark:text-gray-400">No documents attached to this appointment.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Check In Modal -->
        @if($showCheckInModal)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Check In Patient</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </p>
                            </div>
                        </div>

                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            This will create a new encounter and mark the appointment as completed. Continue?
                        </p>

                        <div class="flex justify-end gap-3">
                            <button wire:click="$set('showCheckInModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="confirmCheckIn"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                Confirm Check In
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reschedule Modal -->
        @if($showRescheduleModal)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reschedule Appointment</h2>
                        
                        <div class="space-y-4">
                            <!-- Current Appointment Info -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <p class="text-sm text-blue-600 dark:text-blue-400">Current Appointment</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} with Dr. {{ $appointment->doctor->name }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('h:i A') }}
                                </p>
                            </div>

                            <!-- New Doctor -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Doctor *</label>
                                <select wire:model.live="newDoctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="">Select Doctor</option>
                                    @foreach(\App\Models\User::whereHas('roles', fn($q) => $q->where('name', 'doctor'))->get() as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- New Date -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Date *</label>
                                <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            <!-- Available Time Slots -->
                            @if($availableSlots)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Available Time Slots *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($availableSlots as $slot)
                                            @if($slot['available'])
                                                <button type="button" wire:click="selectSlot('{{ $slot['time'] }}')"
                                                        class="px-3 py-2 text-sm rounded-lg border transition-all
                                                            {{ $newTime === $slot['time'] 
                                                                ? 'bg-blue-600 text-white border-blue-600' 
                                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                    {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Reason -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rescheduling *</label>
                                <textarea wire:model="rescheduleReason" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                          placeholder="Please provide a reason..."></textarea>
                                @error('rescheduleReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showRescheduleModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="reschedule"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Reschedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cancel Modal -->
        @if($showCancelModal)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cancel Appointment</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Cancellation *</label>
                                <textarea wire:model="cancellationReason" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                          placeholder="Please provide a reason..."></textarea>
                                @error('cancellationReason') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showCancelModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="cancel"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                Confirm Cancellation
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ' + 
                    (event[0].type === 'success' ? 'bg-green-500' : 'bg-red-500') + ' text-white';
                notification.textContent = event[0].message;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>