<div>
        <x-shared-form :editing-id="$editingId">
            <div class="space-y-4">
                <!-- Category Name Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Category Name</label>
                    <input 
                        wire:model="name" 
                        placeholder="Enter category name" 
                        class="w-full px-3.5 py-2.5 text-sm border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                    />
                </div>

                <!-- Code Input -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Code</label>
                    <input 
                        wire:model="code" 
                        placeholder="Enter code" 
                        class="w-full px-3.5 py-2.5 text-sm border border-sky-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition-all duration-200 placeholder-gray-400"
                    />
                </div>
            </div>
        </x-shared-form>
    

    <!-- Data Table -->
    <div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @include('livewire.pharmacy.shared.table',[
            'columns'=>['name','code'],
            'rows'=>$categories
        ])
    </div>
</div>
