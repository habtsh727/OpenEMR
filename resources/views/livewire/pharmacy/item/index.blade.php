<div class="p-6 bg-gray-50 min-h-screen" x-data="{ open: false }">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <input
            type="text"
            wire:model.debounce.300ms="search"
            placeholder="Search medicine..."
            class="w-72 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
        />

        <button
            @click="open = true"
            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700"
        >
            + New Medicine
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="p-3">Name</th>
                    <th>Generic</th>
                    <th>Strength</th>
                    <th>Route</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-3 font-medium text-gray-800">
                            {{ $item->name }}
                        </td>
                        <td class="text-gray-600">
                            {{ $item->generic_name ?? '—' }}
                        </td>
                        <td>{{ $item->strength }}</td>
                        <td>{{ $item->route->short_name }}</td>
                        <td>
                            <span class="px-2 py-1 text-xs rounded-full
                                {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600' }}">
                                {{ $item->is_active ? 'Active' : 'Disabled' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">
                            No medicines found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    @include('livewire.pharmacy.item.create-modal')

</div>
