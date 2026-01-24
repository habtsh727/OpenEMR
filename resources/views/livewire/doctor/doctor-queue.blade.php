<div class="max-w-[95rem] mx-auto">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Doctor Queue</h1>
            <p class="text-gray-600 dark:text-gray-400">Patients assigned to you for consultation</p>
        </div>
        
        {{-- Stats Cards --}}
        <div class="flex gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 px-4 py-3 rounded-lg border border-blue-100 dark:border-blue-800">
                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['waiting'] }}</div>
                <div class="text-sm text-blue-500 dark:text-blue-300">Waiting</div>
            </div>
            <div class="bg-yellow-50 dark:bg-yellow-900/20 px-4 py-3 rounded-lg border border-yellow-100 dark:border-yellow-800">
                <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ $stats['in_consultation'] }}</div>
                <div class="text-sm text-yellow-500 dark:text-yellow-300">In Consultation</div>
            </div>
            <div class="bg-green-50 dark:bg-green-900/20 px-4 py-3 rounded-lg border border-green-100 dark:border-green-800">
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['completed_today'] }}</div>
                <div class="text-sm text-green-500 dark:text-green-300">Completed Today</div>
            </div>
        </div>
    </div>

    {{-- Messages --}}
    @session('success')
        <div class="fixed top-5 right-5 bg-gradient-to-r from-green-500 to-green-600 text-white text-sm p-4 rounded-lg shadow-lg z-50 flex items-center gap-3"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" role="alert">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ $value }}
        </div>
    @endsession

    @session('error')
        <div class="fixed top-5 right-5 bg-gradient-to-r from-red-500 to-red-600 text-white text-sm p-4 rounded-lg shadow-lg z-50 flex items-center gap-3"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" role="alert">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"></path>
            </svg>
            {{ $value }}
        </div>
    @endsession

    {{-- Search Bar --}}
    <div class="mb-6">
        <div class="relative">
            <svg class="absolute left-4 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input type="text" wire:model.live="search" placeholder="Search patients..."
                class="w-full pl-12 pr-4 py-3 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white transition-all duration-200">
        </div>
    </div>

    {{-- Queue Table --}}
    <div class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden border border-gray-100 dark:border-slate-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700">
                <thead class="bg-gradient-to-r from-slate-50 to-gray-50 dark:from-slate-700 dark:to-slate-600 border-b border-gray-200 dark:border-slate-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Patient Info</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Vitals</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-100 dark:divide-slate-700">
                    @forelse ($encounters as $encounter)
                        @php
                            $patient = $encounter->patient;
                            $waitingTime = now()->diffInMinutes($encounter->updated_at);
                        @endphp
                        <tr class="hover:bg-blue-50 dark:hover:bg-slate-700/50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $patient->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $patient->card_number }}
                                            • {{ $patient->age }} years • {{ $patient->gender }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            📞 {{ $patient->phone_number1 }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">BP:</span>
                                        <span class="text-sm font-medium">
                                            {{ $encounter->bp_systolic }}/{{ $encounter->bp_diastolic }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">Temp:</span>
                                        <span class="text-sm font-medium">{{ $encounter->temperature }}°C</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-500">Pulse:</span>
                                        <span class="text-sm font-medium">{{ $encounter->pulse }} bpm</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($encounter->priority)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                        {{ $encounter->priority === 'high' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 
                                           ($encounter->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 
                                           'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300') }}">
                                        {{ ucfirst($encounter->priority) }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $encounter->status === 'in_progress' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : 
                                       'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' }}">
                                    {{ $encounter->status === 'in_progress' ? 'In Consultation' : 'Waiting' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex flex-col">
                                    <span>Triaged: {{ $encounter->updated_at->format('h:i A') }}</span>
                                    <span class="text-xs text-gray-500">
                                        {{ $waitingTime }} min ago
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- View Patient Profile --}}
                                    <a href="{{ route('patients.profile', $patient) }}" class="p-1 hover:bg-gray-100 dark:hover:bg-slate-700/30 rounded">
                                        <flux:icon.eye class="w-5 h-5 text-yellow-500" />
                                    </a>
                                    
                                   {{-- Action Button --}}
@if($encounter->status === 'triaged')
    <button wire:click="takePatient({{ $encounter->id }})" 
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        Take Patient
    </button>
@elseif($encounter->status === 'in_progress')
    {{-- Updated Continue button to go to medical history page --}}
    <a href="{{ route('consultation.medical-history', ['encounter' => $encounter->id]) }}" 
       class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
        Continue
    </a>
    
    <button wire:click="completeConsultation({{ $encounter->id }})" 
            onclick="return confirm('Complete this consultation?')"
            class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-lg transition-colors duration-200">
        Complete
    </button>
@endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-600 dark:text-gray-400 text-lg font-medium">No patients in your queue</p>
                                    <p class="text-gray-500 dark:text-gray-500 text-sm">Patients will appear here after nurse assigns them to you</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/30 border-t border-gray-200 dark:border-slate-700 flex justify-end">
            {{ $encounters->links() }}
        </div>
    </div>
</div>