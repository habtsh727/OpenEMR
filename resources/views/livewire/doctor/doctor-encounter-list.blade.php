<div>
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">My Encounters</h2>
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
                        <option value="pending">Pending</option>
                        <option value="triaged">Triaged</option>
                        <option value="doctor_assigned">Doctor Assigned</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">MRN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($encounters as $encounter)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $encounter->patient->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $encounter->patient->age }} years</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $encounter->patient->medical_record_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <x-status-badge :status="$encounter->status" />
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $encounter->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a 
                                        href="{{ route('doctor.encounter.show', $encounter) }}"
                                        class="text-blue-600 hover:text-blue-900"
                                    >
                                        View
                                    </a>
                                    
                                    @if($encounter->status === 'completed' && !$encounter->rehabEncounter)
                                        <button 
                                            wire:click="orderRehab({{ $encounter->id }})"
                                            wire:confirm="Are you sure you want to order rehabilitation for this patient?"
                                            wire:loading.attr="disabled"
                                            class="text-green-600 hover:text-green-900 disabled:opacity-50"
                                        >
                                            <span wire:loading.remove wire:target="orderRehab({{ $encounter->id }})">Order Rehab</span>
                                            <span wire:loading wire:target="orderRehab({{ $encounter->id }})">Ordering...</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No encounters found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    @if($search || $status)
                                        Try adjusting your search or filter
                                    @else
                                        You don't have any encounters yet
                                    @endif
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($encounters->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $encounters->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>