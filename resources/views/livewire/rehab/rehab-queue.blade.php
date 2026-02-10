<div>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Rehabilitation Queue</h2>
                <div class="flex space-x-4">
                    <div class="relative">
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Search patients..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                        >
                        <div class="absolute left-3 top-2.5">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    
                    <select 
                        wire:model.live="status"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none bg-white"
                    >
                        <option value="">All Status</option>
                        <option value="pending_questionnaire">Pending</option>
                        <option value="questionnaire_in_progress">In Progress</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($rehabEncounters as $rehabEncounter)
                <div class="p-6 hover:bg-gray-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $rehabEncounter->encounter->patient->name }}
                            </h3>
                            <div class="mt-1 flex items-center space-x-4 text-sm text-gray-600">
                                <span>MRN: {{ $rehabEncounter->encounter->patient->medical_record_number }}</span>
                                <span>•</span>
                                <span>Doctor: {{ $rehabEncounter->encounter->doctor->name ?? 'N/A' }}</span>
                                <span>•</span>
                                <span>Created: {{ $rehabEncounter->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <x-status-badge :status="$rehabEncounter->status" />
                            
                            @if($rehabEncounter->status === 'pending_questionnaire')
                                <button 
                                    wire:click="startQuestionnaire({{ $rehabEncounter->id }})"
                                    wire:confirm="Start questionnaire for {{ $rehabEncounter->encounter->patient->name }}?"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="startQuestionnaire({{ $rehabEncounter->id }})">Start</span>
                                    <span wire:loading wire:target="startQuestionnaire({{ $rehabEncounter->id }})">Starting...</span>
                                </button>
                            @elseif($rehabEncounter->status === 'questionnaire_in_progress' && $rehabEncounter->questionnaire_filled_by === auth()->id())
                                <a 
                                    href="{{ route('rehab.questionnaire', $rehabEncounter) }}"
                                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                >
                                    Continue
                                </a>
                            @elseif($rehabEncounter->status === 'questionnaire_in_progress')
                                <span class="px-3 py-1 text-sm bg-yellow-100 text-yellow-800 rounded-full">
                                    In Progress
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No rehabilitation cases in queue</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($search || $status)
                            Try adjusting your search or filter
                        @else
                            All caught up! No pending rehabilitation questionnaires.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>
        
        @if($rehabEncounters->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $rehabEncounters->links('vendor.pagination.tailwind') }}
            </div>
        @endif
        
        <!-- Auto-refresh queue every 30 seconds -->
        <div wire:poll.30s></div>
    </div>
</div>