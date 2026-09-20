<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen" x-data="{ open: false }">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div class="relative">
            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Search medicine..."
                class="w-72 pl-10 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:outline-none bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
            />
        </div>

        <button
            @click="open = true"
            class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-500 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200 flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Medicine
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">
                    <tr>
                        <th class="p-3 font-medium text-xs uppercase tracking-wider">Name</th>
                        <th class="p-3 font-medium text-xs uppercase tracking-wider">Generic</th>
                        <th class="p-3 font-medium text-xs uppercase tracking-wider">Strength</th>
                        <th class="p-3 font-medium text-xs uppercase tracking-wider">Route</th>
                        <th class="p-3 font-medium text-xs uppercase tracking-wider">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="p-3 font-medium text-gray-800 dark:text-gray-200">
                                {{ $item->name }}
                            </td>
                            <td class="p-3 text-gray-600 dark:text-gray-300">
                                {{ $item->generic_name ?? '—' }}
                            </td>
                            <td class="p-3 text-gray-800 dark:text-gray-200">
                                {{ $item->strength }}
                            </td>
                            <td class="p-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                    {{ $item->route->short_name }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold
                                    {{ $item->is_active 
                                        ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800' 
                                        : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border border-gray-300 dark:border-gray-600' }}">
                                    @if($item->is_active)
                                        <span class="w-1.5 h-1.5 bg-green-500 dark:bg-green-400 rounded-full animate-pulse"></span>
                                    @else
                                        <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full"></span>
                                    @endif
                                    {{ $item->is_active ? 'Active' : 'Disabled' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                    <div>
                                        <p class="text-gray-600 dark:text-gray-400 font-medium">No medicines found</p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Try adding a new medicine or adjust your search</p>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- @if($items->hasPages())
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $items->links() }}
            </div>
        @endif --}}
    </div>

    <!-- Create Modal -->
    @include('livewire.pharmacy.item.create-modal')

</div>