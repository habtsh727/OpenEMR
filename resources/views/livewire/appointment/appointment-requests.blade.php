<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Header with Stats -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-800 dark:to-indigo-800 rounded-2xl shadow-xl p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">Appointment Requests</h1>
                        <p class="text-purple-100 dark:text-purple-200 mt-1">Manage and process patient appointment requests</p>
                    </div>
                </div>
                
                <!-- Quick Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 text-center">
                        <p class="text-purple-100 text-xs">Total</p>
                        <p class="text-xl font-bold text-white">{{ $stats['total'] }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 text-center">
                        <p class="text-purple-100 text-xs">Today</p>
                        <p class="text-xl font-bold text-white">{{ $stats['today'] }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 text-center">
                        <p class="text-purple-100 text-xs">This Week</p>
                        <p class="text-xl font-bold text-white">{{ $stats['this_week'] }}</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 text-center">
                        <p class="text-purple-100 text-xs">This Month</p>
                        <p class="text-xl font-bold text-white">{{ $stats['this_month'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" wire:model.live.debounce="search" 
                           placeholder="Search patient or doctor..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                </div>

                <!-- Doctor Filter -->
                <div>
                    <select wire:model.live="doctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                        <option value="">All Doctors</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Visit Type Filter -->
                <div>
                    <select wire:model.live="visitType" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                        <option value="">All Types</option>
                        @foreach($visitTypes as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <input type="date" wire:model.live="dateFrom" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Date To -->
                <div>
                    <input type="date" wire:model.live="dateTo" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                </div>
            </div>

            <!-- Active Filters -->
            <div class="flex flex-wrap items-center gap-2 mt-3">
                @if($search)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                        Search: {{ $search }}
                        <button wire:click="$set('search', '')" class="ml-2 hover:text-purple-900 dark:hover:text-purple-300">×</button>
                    </span>
                @endif
                @if($doctorId)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                        Doctor: {{ $doctors->firstWhere('id', $doctorId)?->name }}
                        <button wire:click="$set('doctorId', '')" class="ml-2 hover:text-purple-900 dark:hover:text-purple-300">×</button>
                    </span>
                @endif
                @if($visitType)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                        Type: {{ $visitTypes[$visitType] ?? $visitType }}
                        <button wire:click="$set('visitType', '')" class="ml-2 hover:text-purple-900 dark:hover:text-purple-300">×</button>
                    </span>
                @endif
                @if($dateFrom !== now()->format('Y-m-d') || $dateTo !== now()->addMonth()->format('Y-m-d'))
                    <button wire:click="resetFilters" class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400">
                        Reset All Filters
                    </button>
                @endif
            </div>
        </div>

        <!-- Requests Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Request Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Requested For</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($requests as $request)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $request->created_at->format('h:i A') }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-medium shadow-sm">
                                            {{ substr($request->patient->first_name ?? 'N', 0, 1) }}{{ substr($request->patient->last_name ?? 'A', 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $request->patient->first_name ?? '' }} {{ $request->patient->last_name ?? '' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Card: {{ $request->patient->card_number ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        Dr. {{ $request->doctor->name ?? 'Not Specified' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $request->doctor->specialization ?? 'General' }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $request->appointment_date->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $request->appointment_time->format('h:i A') }}
                                    </div>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                        {{ ucfirst(str_replace('-', ' ', $request->visit_type)) }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                        Requested
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="viewDetails({{ $request->id }})"
                                                class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                                title="View Details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="openApproveModal({{ $request->id }})"
                                                class="p-2 text-green-600 hover:bg-green-50 dark:text-green-400 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                                title="Approve">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="openRejectModal({{ $request->id }})"
                                                class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                                title="Reject">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <button wire:click="openRescheduleModal({{ $request->id }})"
                                                class="p-2 text-amber-600 hover:bg-amber-50 dark:text-amber-400 dark:hover:bg-amber-900/20 rounded-lg transition-colors"
                                                title="Reschedule">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No appointment requests</h3>
                                        <p class="text-gray-500 dark:text-gray-400">There are no pending appointment requests.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($requests->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>

        <!-- Doctor Request Summary (if any) -->
        @if($stats['by_doctor'] && $stats['by_doctor']->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Requests by Doctor</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    @foreach($stats['by_doctor'] as $doctorStat)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                            <p class="font-medium text-gray-900 dark:text-white">{{ $doctorStat['doctor_name'] }}</p>
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-2">{{ $doctorStat['count'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">requests pending</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Approve Modal -->
        @if($showApproveModal && $selectedAppointment)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Approve Appointment Request</h2>
                        
                        <!-- Patient Info -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4 mb-4">
                            <p class="text-sm text-purple-600 dark:text-purple-400">Patient</p>
                            <p class="font-medium text-gray-900 dark:text-white">
                                {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Requested: {{ $selectedAppointment->created_at->format('M d, Y h:i A') }}
                            </p>
                        </div>

                        <!-- Approve Form -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor *</label>
                                <select wire:model.live="approveDoctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                                    <option value="">Select Doctor</option>
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date *</label>
                                <input type="date" wire:model.live="approveDate" min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                            </div>

                            @if($availableSlots)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Available Time Slots *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($availableSlots as $slot)
                                            @if($slot['available'])
                                                <button type="button" wire:click="selectSlot('{{ $slot['time'] }}')"
                                                        class="px-3 py-2 text-sm rounded-lg border transition-all
                                                            {{ $approveTime === $slot['time'] 
                                                                ? 'bg-purple-600 text-white border-purple-600' 
                                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                    {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($selectedAppointment->additional_notes)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Patient Notes</label>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3">
                                        {{ $selectedAppointment->additional_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Modal Actions -->
                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showApproveModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="approve"
                                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg">
                                Approve & Schedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reject Modal -->
        @if($showRejectModal && $selectedAppointment)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reject Request</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Rejection *</label>
                                <textarea wire:model="rejectionReason" rows="4"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500"
                                          placeholder="Please provide a reason..."></textarea>
                                @error('rejectionReason') 
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showRejectModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="reject"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                Reject Request
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reschedule Modal -->
        @if($showRescheduleModal && $selectedAppointment)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Reschedule & Approve</h2>
                        
                        <div class="space-y-4">
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-4 mb-4">
                                <p class="text-sm text-amber-600 dark:text-amber-400">Patient</p>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Doctor *</label>
                                <select wire:model.live="approveDoctorId" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                                    @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date *</label>
                                <input type="date" wire:model.live="approveDate" min="{{ now()->format('Y-m-d') }}"
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500">
                            </div>

                            @if($availableSlots)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Available Time Slots *</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($availableSlots as $slot)
                                            @if($slot['available'])
                                                <button type="button" wire:click="selectSlot('{{ $slot['time'] }}')"
                                                        class="px-3 py-2 text-sm rounded-lg border transition-all
                                                            {{ $approveTime === $slot['time'] 
                                                                ? 'bg-amber-600 text-white border-amber-600' 
                                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                                                    {{ \Carbon\Carbon::parse($slot['time'])->format('h:i A') }}
                                                </button>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reason for Reschedule *</label>
                                <textarea wire:model="rejectionReason" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-amber-500"></textarea>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="$set('showRescheduleModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Cancel
                            </button>
                            <button wire:click="reschedule"
                                    class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg">
                                Reschedule & Approve
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Details Modal -->
        @if($showDetailsModal && $selectedAppointment)
            <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Appointment Request Details</h2>
                            <button wire:click="$set('showDetailsModal', false)" class="text-gray-400 hover:text-gray-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="space-y-4">
                            <!-- Patient Info -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Patient Information</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Name</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $selectedAppointment->patient->first_name }} {{ $selectedAppointment->patient->last_name }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Card Number</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedAppointment->patient->card_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Phone</p>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $selectedAppointment->patient->phone_number1 }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Gender/Age</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst($selectedAppointment->patient->gender) }}, 
                                            {{ \Carbon\Carbon::parse($selectedAppointment->patient->date_of_birth)->age }} yrs
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Details -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Request Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Requested Date</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $selectedAppointment->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Requested By</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $selectedAppointment->creator->name ?? 'Patient' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Preferred Doctor</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            Dr. {{ $selectedAppointment->doctor->name ?? 'Any' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Visit Type</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ ucfirst(str_replace('-', ' ', $selectedAppointment->visit_type)) }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Preferred Date</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $selectedAppointment->appointment_date->format('M d, Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Preferred Time</p>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $selectedAppointment->appointment_time->format('h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                @if($selectedAppointment->additional_notes)
                                    <div class="mt-3">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Additional Notes</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-1 bg-white dark:bg-gray-800 p-2 rounded">
                                            {{ $selectedAppointment->additional_notes }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="openApproveModal({{ $selectedAppointment->id }})"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                                Approve
                            </button>
                            <button wire:click="openRejectModal({{ $selectedAppointment->id }})"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                                Reject
                            </button>
                            <button wire:click="$set('showDetailsModal', false)"
                                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>