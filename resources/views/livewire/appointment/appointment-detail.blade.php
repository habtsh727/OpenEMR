{{-- resources/views/livewire/appointment/appointment-detail.blade.php --}}
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 transition-colors duration-200">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Header with Navigation -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ url()->previous() }}"
                    class="p-3 hover:bg-gray-200 dark:hover:bg-gray-700 rounded-xl transition-all group">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Appointment Details</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">View and manage appointment #{{ $appointment->id }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                @if($appointment->isCheckInAvailable())
                <button wire:click="openCheckInModal"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-xl shadow-lg shadow-green-500/20 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Check In
                </button>
                @endif

                @if($appointment->status === 'scheduled' && $appointment->checked_in_at)
                <button wire:click="openCompleteModal"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Complete
                </button>
                @endif

                @if(in_array($appointment->status, ['scheduled', 'rescheduled']))
                <button wire:click="openRescheduleModal"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white text-sm font-medium rounded-xl shadow-lg shadow-yellow-500/20 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Reschedule
                </button>
                @endif

                @if(in_array($appointment->status, ['scheduled', 'rescheduled']))
                <button wire:click="openCancelModal"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-sm font-medium rounded-xl shadow-lg shadow-red-500/20 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Cancel
                </button>
                @endif

                @if($appointment->status === 'scheduled' && $appointment->appointment_time->isPast() && !$appointment->checked_in_at)
                <button wire:click="markMissed"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white text-sm font-medium rounded-xl shadow-lg shadow-gray-500/20 hover:shadow-xl transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Mark Missed
                </button>
                @endif
            </div>
        </div>

        <!-- Patient Header Card with Gradient -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-600 dark:from-blue-800 dark:via-indigo-800 dark:to-purple-800 shadow-2xl">
            <!-- Animated background pattern -->
            <div class="absolute inset-0 bg-grid-white/[0.05] bg-[size:40px_40px]"></div>
            <div class="absolute top-0 right-0 -mt-32 -mr-32 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-32 -ml-32 h-64 w-64 rounded-full bg-purple-500/10 blur-3xl"></div>
            
            <div class="relative px-8 py-6">
                <div class="flex items-center gap-6">
                    <div class="h-20 w-20 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-2xl">
                        <span class="text-3xl font-bold text-white">
                            {{ substr($appointment->patient->first_name ?? 'N', 0, 1) }}{{ substr($appointment->patient->last_name ?? 'A', 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-white">
                            {{ $appointment->patient->first_name }} {{ $appointment->patient->middle_name }} {{ $appointment->patient->last_name }}
                        </h2>
                        <div class="flex flex-wrap items-center gap-4 mt-2 text-white/80">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                Card: {{ $appointment->patient->card_number }}
                            </span>
                            <span>•</span>
                            <span>{{ ucfirst($appointment->patient->gender) }}, {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->age }} yrs</span>
                            <span>•</span>
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                {{ $appointment->patient->phone_number1 }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all">
                <p class="text-sm text-gray-500 dark:text-gray-400">Appointment ID</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">#{{ $appointment->id }}</p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all">
                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                <div class="mt-1">
                    <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium {{ $appointment->status_color }}">
                        {{ ucfirst($appointment->status) }}
                    </span>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all">
                <p class="text-sm text-gray-500 dark:text-gray-400">Time Until</p>
                <p class="text-2xl font-bold {{ $timeUntil === 'Past' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }} mt-1">
                    {{ $timeUntil }}
                </p>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-5 hover:shadow-xl transition-all">
                <p class="text-sm text-gray-500 dark:text-gray-400">Created By</p>
                <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">{{ $appointment->creator->name ?? 'System' }}</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex space-x-8">
                <button wire:click="setActiveTab('overview')"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-all relative group
                        {{ $activeTab === 'overview' 
                            ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Overview
                    </div>
                    @if($activeTab === 'overview')
                        <span class="absolute bottom-0 left-0 right-0 h-0.5 bg-blue-600 dark:bg-blue-400"></span>
                    @endif
                </button>
                <button wire:click="setActiveTab('history')"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-all relative group
                        {{ $activeTab === 'history' 
                            ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        History
                        <span class="ml-2 px-2 py-0.5 text-xs bg-gray-200 dark:bg-gray-700 rounded-full">
                            {{ $histories->count() }}
                        </span>
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
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-600">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Appointment Information
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->appointment_date->format('l, F j, Y') }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Time</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $appointment->appointment_time->format('h:i A') }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doctor</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        Dr. {{ $appointment->doctor->name }}
                                    </p>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Visit Type</p>
                                    <span class="inline-flex px-3 py-1.5 text-xs rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 font-medium">
                                        {{ ucfirst(str_replace('-', ' ', $appointment->visit_type)) }}
                                    </span>
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Checked In</p>
                                    @if($appointment->checked_in_at)
                                        <p class="text-base font-semibold text-green-600 dark:text-green-400">
                                            {{ $appointment->checked_in_at->format('h:i A') }}
                                        </p>
                                    @else
                                        <p class="text-base text-gray-400 dark:text-gray-500 italic">Not checked in</p>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider">Payment</p>
                                    <div class="flex items-center gap-2">
                                        <span class="px-3 py-1.5 text-xs rounded-full {{ $appointment->payment_status_color }} font-medium">
                                            {{ ucfirst($appointment->payment_status) }}
                                        </span>
                                        @if($appointment->payment_amount)
                                            <span class="text-base font-semibold text-gray-900 dark:text-white">
                                                ETB {{ number_format($appointment->payment_amount, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Doctor Notes Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Doctor's Notes
                            </h3>
                            @if(!$showEditNotes && $appointment->status !== 'completed')
                            <button wire:click="toggleEditNotes" 
                                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 flex items-center gap-1 px-3 py-1.5 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Edit Notes
                            </button>
                            @endif
                        </div>
                        <div class="p-6">
                            @if($showEditNotes)
                            <textarea wire:model="doctorNotes" rows="6"
                                class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                placeholder="Enter your clinical notes..."></textarea>
                            <div class="flex justify-end gap-3 mt-4">
                                <button wire:click="toggleEditNotes" 
                                        class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                                    Cancel
                                </button>
                                <button wire:click="saveNotes"
                                        class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-lg shadow-lg shadow-blue-500/20 hover:shadow-xl transition-all font-medium">
                                    Save Notes
                                </button>
                            </div>
                            @else
                                @if($appointment->doctor_notes)
                                    <div class="prose prose-sm max-w-none">
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
                                            {{ $appointment->doctor_notes }}
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </div>
                                        <p class="text-gray-500 dark:text-gray-400">No notes added yet.</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($appointment->additional_notes)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Additional Notes</h3>
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
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Patient Details
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Full Name</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Card Number</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->card_number }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Phone</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $appointment->patient->phone_number1 }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Date of Birth</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($appointment->patient->date_of_birth)->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Gender</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($appointment->patient->gender) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Payment Information
                            </h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                <span class="px-3 py-1.5 text-xs rounded-full {{ $appointment->payment_status_color }} font-medium">
                                    {{ ucfirst($appointment->payment_status) }}
                                </span>
                            </div>
                            @if($appointment->payment_amount)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Amount</span>
                                <span class="text-lg font-bold text-gray-900 dark:text-white">
                                    ETB {{ number_format($appointment->payment_amount, 2) }}
                                </span>
                            </div>
                            @endif
                            @if($appointment->related_order_type)
                            <div class="pt-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Related Order</p>
                                <span class="inline-flex px-3 py-1.5 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 font-medium">
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
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Appointment History
                    </h3>
                </div>
                <div class="p-6">
                    @if($histories->isEmpty())
                    <div class="text-center py-12">
                        <div class="inline-flex p-4 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
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
                                            <div class="h-10 w-10 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                                @if($history->action === 'created') bg-green-100 dark:bg-green-900/30
                                                @elseif($history->action === 'cancelled') bg-red-100 dark:bg-red-900/30
                                                @elseif($history->action === 'rescheduled') bg-yellow-100 dark:bg-yellow-900/30
                                                @elseif($history->action === 'checked_in') bg-green-100 dark:bg-green-900/30
                                                @elseif($history->action === 'completed') bg-emerald-100 dark:bg-emerald-900/30
                                                @elseif($history->action === 'missed') bg-gray-100 dark:bg-gray-700
                                                @else bg-blue-100 dark:bg-blue-900/30 @endif">
                                                @if($history->action === 'created')
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                @elseif($history->action === 'cancelled')
                                                <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                                @elseif($history->action === 'rescheduled')
                                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                                @elseif($history->action === 'checked_in')
                                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                @elseif($history->action === 'completed')
                                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @elseif($history->action === 'missed')
                                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @else
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div>
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-900 dark:text-white">{{ $history->user->name }}</span>
                                                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full 
                                                        @if($history->action === 'created') bg-green-100 text-green-800
                                                        @elseif($history->action === 'cancelled') bg-red-100 text-red-800
                                                        @elseif($history->action === 'rescheduled') bg-yellow-100 text-yellow-800
                                                        @elseif($history->action === 'checked_in') bg-green-100 text-green-800
                                                        @elseif($history->action === 'completed') bg-emerald-100 text-emerald-800
                                                        @elseif($history->action === 'missed') bg-gray-100 text-gray-800
                                                        @else bg-blue-100 text-blue-800 @endif">
                                                        {{ ucfirst(str_replace('_', ' ', $history->action)) }}
                                                    </span>
                                                </div>
                                                <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $history->created_at->format('M d, Y · h:i A') }}
                                                </p>
                                            </div>
                                            @if($history->reason)
                                            <div class="mt-2 text-sm bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-200 dark:border-gray-600">
                                                <span class="font-medium text-gray-700 dark:text-gray-300">Reason:</span>
                                                <span class="text-gray-600 dark:text-gray-400 ml-2">{{ $history->reason }}</span>
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
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-full">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Check In Patient</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Appointment Time</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $appointment->appointment_time->format('h:i A') }}</span>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        This will mark the patient as checked in. You can add notes and complete the appointment later.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCheckInModal', false)" 
                                class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                            Cancel
                        </button>
                        <button wire:click="confirmCheckIn" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white rounded-lg shadow-lg shadow-green-500/20 hover:shadow-xl transition-all font-medium flex items-center gap-2">
                            <span wire:loading.remove wire:target="confirmCheckIn">Confirm Check In</span>
                            <span wire:loading wire:target="confirmCheckIn" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Complete Modal -->
        @if($showCompleteModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-full">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Complete Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Mark this appointment as completed? The patient will be marked as finished.
                    </p>

                    <div class="flex justify-end gap-3">
                        <button wire:click="$set('showCompleteModal', false)" 
                                class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                            Cancel
                        </button>
                        <button wire:click="confirmComplete" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white rounded-lg shadow-lg shadow-emerald-500/20 hover:shadow-xl transition-all font-medium">
                            Complete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Reschedule Modal -->
        @if($showRescheduleModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reschedule Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <!-- Current Appointment -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-200 dark:border-gray-600">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Current Appointment</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $appointment->appointment_date->format('M d, Y') }} at {{ $appointment->appointment_time->format('h:i A') }}
                            </p>
                        </div>

                        <!-- New Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Date</label>
                            <input type="date" wire:model.live="newDate" min="{{ now()->format('Y-m-d') }}"
                                   class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all">
                        </div>

                        <!-- Available Slots -->
                        @if($loadingSlots)
                            <div class="text-center py-4">
                                <svg class="animate-spin h-5 w-5 mx-auto text-yellow-600" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <p class="text-gray-500 mt-2">Loading available slots...</p>
                            </div>
                        @elseif($availableSlots)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Available Times</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($availableSlots as $slot)
                                        @if($slot['available'] && !$slot['is_past'])
                                            <button wire:click="selectSlot('{{ $slot['time'] }}')"
                                                    class="px-3 py-2 text-sm rounded-xl border-2 transition-all
                                                        {{ $selectedSlot === $slot['time'] 
                                                            ? 'bg-yellow-600 text-white border-yellow-600 shadow-lg scale-105' 
                                                            : 'border-gray-200 dark:border-gray-700 hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 text-gray-700 dark:text-gray-300' }}">
                                                {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                            </button>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Reason -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason</label>
                            <textarea wire:model="rescheduleReason" rows="3" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-yellow-500 focus:border-transparent transition-all"
                                      placeholder="Please provide a reason for rescheduling..."></textarea>
                            @error('rescheduleReason') 
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="$set('showRescheduleModal', false)" 
                                class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                            Cancel
                        </button>
                        <button wire:click="reschedule" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white rounded-lg shadow-lg shadow-yellow-500/20 hover:shadow-xl transition-all font-medium flex items-center gap-2">
                            <span wire:loading.remove wire:target="reschedule">Reschedule</span>
                            <span wire:loading wire:target="reschedule">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Cancel Modal -->
        @if($showCancelModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Cancel Appointment</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</p>
                        </div>
                    </div>

                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Are you sure you want to cancel this appointment? This action cannot be undone.
                    </p>

                    <div class="space-y-4">
                        <textarea wire:model="cancellationReason" rows="3" 
                                  class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all"
                                  placeholder="Reason for cancellation..."></textarea>
                        @error('cancellationReason') 
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button wire:click="$set('showCancelModal', false)" 
                                class="px-4 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors font-medium">
                            Keep Appointment
                        </button>
                        <button wire:click="cancel" 
                                wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-lg shadow-lg shadow-red-500/20 hover:shadow-xl transition-all font-medium flex items-center gap-2">
                            <span wire:loading.remove wire:target="cancel">Cancel Appointment</span>
                            <span wire:loading wire:target="cancel">Processing...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Loading Overlay -->
        <div wire:loading wire:target="confirmCheckIn,confirmComplete,reschedule,cancel" 
             class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[100]">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 flex flex-col items-center gap-4">
                <svg class="animate-spin h-12 w-12 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-gray-900 dark:text-white font-medium">Processing your request...</p>
            </div>
        </div>
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('notify', (event) => {
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 px-6 py-3 rounded-xl shadow-2xl z-[100] transform transition-all duration-300 ' + 
                    (event[0].type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 'bg-gradient-to-r from-red-500 to-rose-600');
                notification.innerHTML = `
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            ${event[0].type === 'success' 
                                ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />' 
                                : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />'}
                        </svg>
                        <span class="text-white font-medium">${event[0].message}</span>
                    </div>
                `;
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            });
        });
    </script>
</div>