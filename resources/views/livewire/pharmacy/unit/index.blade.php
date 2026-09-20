<div>
    <x-shared-form :editing-id="$editingId">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
            <input wire:model="name" placeholder="Unit name"
                class="w-full px-3.5 py-2.5 text-sm border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-200 placeholder-gray-400" />

        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Short name</label>
            <input wire:model="short_name" placeholder="Short name"
                class="w-full px-3.5 py-2.5 text-sm border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-200 placeholder-gray-400" />

        </div>
    </x-shared-form>


    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @include('livewire.pharmacy.shared.table', [
            'columns' => ['name', 'short_name'],
            'rows' => $units,
        ])
    </div>
</div>
