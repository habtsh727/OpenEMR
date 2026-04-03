<div>
    <div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Header with Stats --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Doctor Consultation
                            Queue</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage patients assigned to you for medical
                            consultation</p>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl px-4 py-3 shadow-sm">
                            <div class="text-2xl font-bold">{{ $stats['waiting'] }}</div>
                            <div class="text-sm font-medium text-blue-100">Waiting Patients</div>
                        </div>
                        <div
                            class="bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl px-4 py-3 shadow-sm">
                            <div class="text-2xl font-bold">{{ $stats['in_consultation'] }}</div>
                            <div class="text-sm font-medium text-purple-100">In Consultation</div>
                        </div>
                        <div
                            class="bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl px-4 py-3 shadow-sm">
                            <div class="text-2xl font-bold">{{ $stats['completed_today'] }}</div>
                            <div class="text-sm font-medium text-green-100">Completed Today</div>
                        </div>
                    </div>
                </div>

                {{-- Search and Filter Bar --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search by name, card number, phone..."
                                class="pl-10 pr-4 py-2.5 w-72 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 shadow-sm">
                        </div>

                        {{-- Status Filter --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400">Filter:</span>
                            <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1 shadow-inner">
                                <button wire:click="$set('statusFilter', null)"
                                    class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ !$statusFilter ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                                    All
                                </button>
                                <button wire:click="$set('statusFilter', 'triaged')"
                                    class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ $statusFilter === 'triaged' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                                    Waiting
                                </button>
                                <button wire:click="$set('statusFilter', 'in_progress')"
                                    class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ $statusFilter === 'in_progress' ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                                    In Progress
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="flex items-center space-x-3">
                        <button wire:click="$refresh"
                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Success/Error Messages --}}
            @if (session()->has('success'))
            <div class="mb-6 animate-fade-in">
                <div
                    class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            @if (session()->has('error'))
            <div class="mb-6 animate-fade-in">
                <div
                    class="flex items-center p-4 bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border border-red-200 dark:border-red-800 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-red-800 dark:text-red-300 font-medium">{{ session('error') }}</span>
                </div>
            </div>
            @endif

            {{-- Queue Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Patient Queue</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $encounters->total() }} patient(s) found
                        @if($search)
                        • Searching: "{{ $search }}"
                        @endif
                    </p>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Patient Details
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Priority
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                {{-- <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Wait Time
                                </th> --}}
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($encounters as $encounter)
                            @php
                            $patient = $encounter->patient;
                            $waitingTime = now()->diffInMinutes($encounter->updated_at);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-12 w-12 flex items-center justify-center rounded-xl 
                                            {{ $patient->gender === 'Male' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 'bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400' }}">
                                            @if($patient->gender === 'Male')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                                {{ $patient->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                <span class="font-medium">{{ $patient->card_number }}</span> • {{
                                                $patient->age }}y • {{ $patient->gender }}
                                            </div>
                                            <div
                                                class="text-sm text-gray-500 dark:text-gray-400 flex items-center mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                                    </path>
                                                </svg>
                                                {{ $patient->phone_number1 }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($encounter->priority)
                                    <div class="flex flex-col items-start">
                                        <span
                                            class="px-3 py-1.5 rounded-full text-xs font-medium shadow-sm
                                                {{ $encounter->priority === 'high' 
                                                    ? 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 dark:from-red-900/30 dark:to-red-800/30 dark:text-red-300 border border-red-200 dark:border-red-800' 
                                                    : ($encounter->priority === 'medium' 
                                                        ? 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 dark:from-yellow-900/30 dark:to-yellow-800/30 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-800' 
                                                        : 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 dark:from-green-900/30 dark:to-green-800/30 dark:text-green-300 border border-green-200 dark:border-green-800') }}">
                                            {{ ucfirst($encounter->priority) }} Priority
                                        </span>

                                    </div>
                                    @else
                                    <span class="text-gray-400 text-sm">No priority set</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start">
                                        <span
                                            class="px-3 py-1.5 rounded-full text-xs font-medium shadow-sm
                                            {{ $encounter->status === 'in_progress' 
                                                ? 'bg-gradient-to-r from-purple-100 to-purple-200 text-purple-800 dark:from-purple-900/30 dark:to-purple-800/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800' 
                                                : 'bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 dark:from-blue-900/30 dark:to-blue-800/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800' }}">
                                            <span
                                                class="w-2 h-2 rounded-full inline-block mr-2 {{ $encounter->status === 'in_progress' ? 'bg-purple-500' : 'bg-blue-500' }}"></span>
                                            {{ $encounter->status === 'in_progress' ? 'In Consultation' : 'Waiting' }}
                                        </span>

                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-2">
                                        {{-- View Patient Profile --}}
                                        <a href="{{ route('patients.profile', $patient) }}" wire:navigate
                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200 group/profile"
                                            title="View Patient Profile">
                                            <svg class="w-5 h-5 text-gray-500 group-hover/profile:text-blue-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>

                                        {{-- Action Buttons --}}
                                        @if($encounter->status === 'triaged')
                                        <button wire:click="takePatient({{ $encounter->id }})"
                                            wire:loading.attr="disabled" wire:target="takePatient"
                                            class="px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                                wire:loading.remove wire:target="takePatient">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                                </path>
                                            </svg>
                                            <svg class="w-4 h-4 animate-spin hidden" wire:loading
                                                wire:target="takePatient" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                                </path>
                                            </svg>
                                            <span wire:loading.remove wire:target="takePatient">Take Patient</span>
                                            <span wire:loading wire:target="takePatient">Processing...</span>
                                        </button>
                                        @elseif($encounter->status === 'in_progress')
<div class="flex items-center space-x-2">
    <a href="{{ route('consultation.medical-history', ['encounter' => $encounter->id]) }}"
        wire:navigate
        class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
        </svg>
        <span>Continue</span>
    </a>
    
    {{-- Complete Button with Alpine.js confirmation --}}
    <button 
        x-data="{}"
        x-on:click="if(confirm('Complete this consultation? This will mark the patient as done.')) $wire.completeConsultation({{ $encounter->id }})"
        class="px-3 py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span>Complete</span>
    </button>
</div>
@endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12">
                                    <div class="text-center">
                                        <div
                                            class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                vdociewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No patients
                                            in queue</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                                            {{ $search || $statusFilter ? 'Try adjusting your search or filter' :
                                            'Patients will appear here after nurse triage' }}
                                        </p>
                                        @if($search || $statusFilter)
                                        <button wire:click="$set(['search' => '', 'statusFilter' => null])"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            Clear filters
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($encounters->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium">{{ $encounters->firstItem() }}</span> to
                            <span class="font-medium">{{ $encounters->lastItem() }}</span> of
                            <span class="font-medium">{{ $encounters->total() }}</span> patients
                        </div>
                        <div>
                            {{ $encounters->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Add custom animations --}}
        <style>
            .animate-fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>
    </div>
</div>