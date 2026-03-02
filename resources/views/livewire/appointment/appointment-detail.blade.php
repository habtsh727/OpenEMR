{{-- resources/views/livewire/appointment/appointment-detail.blade.php --}}
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header with Navigation -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}" 
                   class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors group">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View and manage appointment #{{ $appointment->id }}</p>
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

                @if($appointment->status === 'scheduled' && $appointment->checked_in_at)
                    <button wire:click="openCompleteModal"
                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Complete
                    </button>
                @endif

                @if(in_array($appointment->status, ['scheduled', 'rescheduled']))
                    <button wire:click="openRescheduleModal"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
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
                            class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all">
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
                    <p class="text-lg font-semibold {{ $this->timeUntil === 'Past' ? 'text-red-600' : 'text-green-600' }}">
                        {{ $this->timeUntil }}
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
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Visit Type</p>
                                        <span class="inline-flex mt-1 px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                            {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                        </span>
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
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Payment</p>
                                        <div class="mt-1">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status_color }}">
                                                {{ ucfirst($appointment->payment_status) }}
                                            </span>
                                            @if($appointment->payment_amount)
                                                <span class="ml-2 text-sm font-medium">ETB {{ number_format($appointment->payment_amount, 2) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor Notes Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Doctor's Notes</h3>
                                @if(!$showEditNotes)
                                    <button wire:click="toggleEditNotes" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit Notes
                                    </button>
                                @endif
                            </div>
                            <div class="p-6">
                                @if($showEditNotes)
                                    <textarea wire:model="doctorNotes" rows="5" 
                                              class="w-full border rounded-lg p-3 mb-3 dark:bg-gray-700"
                                              placeholder="Enter your clinical notes..."></textarea>
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="toggleEditNotes" class="px-4 py-2 border rounded-lg text-sm">
                                            Cancel
                                        </button>
                                        <button wire:click="saveNotes" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                                            Save Notes
                                        </button>
                                    </div>
                                @else
                                    @if($appointment->doctor_notes)
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $appointment->doctor_notes }}</p>
                                    @else
                                        <p class="text-gray-400 dark:text-gray-500 italic">No notes added yet.</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if($appointment->additional_notes)
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                                    <h3 class="text-lg font-semibold">Additional Notes</h3>
                                </div>
                                <div class="p-6">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $appointment->additional_notes }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Patient Info & Payment -->
                    <div class="space-y-6">
                        <!-- Patient Details Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                                <h3 class="text-lg font-semibold">Patient Details</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Full Name</span>
                                    <span class="text-sm font-medium">
                                        {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Card Number</span>
                                    <span class="text-sm font-medium">{{ $appointment->patient->card_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Phone</span>
                                    <span class="text-sm font-medium">{{ $appointment->patient->phone_number1 }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Date of Birth</span>
                                    <span class="text-sm font-medium">
                                        {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Gender</span>
                                    <span class="text-sm font-medium">{{ ucfirst($appointment->patient->gender) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                                <h3 class="text-lg font-semibold">Payment Information</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Status</span>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $appointment->payment_status_color }}">
                                        {{ ucfirst($appointment->payment_status) }}
                                    </span>
                                </div>
                                @if($appointment->payment_amount)
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Amount</span>
                                        <span class="text-lg font-bold">ETB {{ number_format($appointment->payment_amount, 2) }}</span>
                                    </div>
                                @endif
                                @if($appointment->related_order_type)
                                    <div class="pt-4 border-t">
                                        <p class="text-sm text-gray-500 mb-2">Related Order</p>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                            {{ ucfirst($appointment->related_order_type) }}
                                        </span>
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
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b">
                        <h3 class="text-lg font-semibold">Appointment History</h3>
                    </div>
                    <div class="p-6">
                        @if($histories->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500">No history records found</p>
                            </div>
                        @else
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    @foreach($histories as $history)
                                        <li>
                                            <div class="relative pb-8">
                                                @if(!$loop->last)
                                                    <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                @endif
                                                <div class="relative flex items-start space-x-3">
                                                    <div class="relative">
                                                        <div class="h-10 w-10 rounded-full bg-{{ $history->action === 'created' ? 'green' : ($history->action === 'cancelled' ? 'red' : 'blue') }}-100 flex items-center justify-center ring-8 ring-white">
                                                            @if($history->action === 'created')
                                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                                </svg>
                                                            @elseif($history->action === 'cancelled')
                                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            @elseif($history->action === 'rescheduled')
                                                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                </svg>
                                                            @elseif($history->action === 'checked_in')
                                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                                </svg>
                                                            @elseif($history->action === 'completed')
                                                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div>
                                                            <div class="text-sm">
                                                                <span class="font-medium">{{ $history->user->name }}</span>
                                                                <span class="ml-2 text-xs text-gray-500">{{ ucfirst($history->action) }}</span>
                                                            </div>
                                                            <p class="mt-0.5 text-xs text-gray-500">
                                                                {{ $history->created_at->format('M d, Y · h:i A') }}
                                                            </p>
                                                        </div>
                                                        @if($history->reason)
                                                            <div class="mt-2 text-sm bg-gray-50 rounded-lg p-3">
                                                                <span class="font-medium">Reason:</span> {{ $history->reason }}
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
        </div>

        <!-- Check In Modal -->
        @if($showCheckInModal)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold mb-4">Check In Patient</h3>
                    <p class="mb-4">Check in <span class="font-semibold">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</span>?</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCheckInModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button wire:click="confirmCheckIn" class="px-4 py-2 bg-green-600 text-white rounded-lg">Check In</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Complete Modal -->
        @if($showCompleteModal)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold mb-4">Complete Appointment</h3>
                    <p class="mb-4">Mark this appointment as completed?</p>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCompleteModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button wire:click="confirmComplete" class="px-4 py-2 bg-emerald-600 text-white rounded-lg">Complete</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reschedule Modal -->
        @if($showRescheduleModal)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6">
                    <h3 class="text-lg font-semibold mb-4">Reschedule Appointment</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">New Date</label>
                            <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        @if($availableSlots)
                            <div>
                                <label class="block text-sm font-medium mb-2">Available Times</label>
                                <div class="grid grid-cols-3 gap-2">
                                    @foreach($availableSlots as $slot)
                                        @if($slot['available'] && !$slot['is_past'])
                                            <button wire:click="selectSlot('{{ $slot['time'] }}')"
                                                    class="px-3 py-2 text-sm border rounded-lg {{ $newTime === $slot['time'] ? 'bg-yellow-600 text-white' : 'hover:bg-gray-100' }}">
                                                {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium mb-1">Reason</label>
                            <textarea wire:model="rescheduleReason" rows="2" 
                                      class="w-full border rounded-lg px-3 py-2"
                                      placeholder="Reason for rescheduling..."></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button wire:click="$set('showRescheduleModal', false)" class="px-4 py-2 border rounded-lg">Cancel</button>
                        <button wire:click="reschedule" class="px-4 py-2 bg-yellow-600 text-white rounded-lg">Reschedule</button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Cancel Modal -->
        @if($showCancelModal)
            <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6">
                    <h3 class="text-lg font-semibold mb-4">Cancel Appointment</h3>
                    <p class="mb-4">Are you sure you want to cancel this appointment?</p>
                    <textarea wire:model="rescheduleReason" rows="2" 
                              class="w-full border rounded-lg p-3 mb-4"
                              placeholder="Reason for cancellation..."></textarea>
                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCancelModal', false)" class="px-4 py-2 border rounded-lg">No</button>
                        <button wire:click="cancel" class="px-4 py-2 bg-red-600 text-white rounded-lg">Yes, Cancel</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>