<div class="p-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <flux:heading size="xl" level="1" class="mb-1">Service Categories</flux:heading>
            <flux:subheading size="md" class="text-gray-500 dark:text-gray-400">
                Manage your service categories efficiently
            </flux:subheading>
        </div>

        <div class="mt-4 md:mt-0">
            <flux:modal.trigger name="create-service-category">
                <flux:button size="sm" variant="primary" class="flex items-center gap-2">
                    <span>+ Add Category</span>
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Success Message --}}
    @session('success')
    <div 
        class="fixed top-5 right-5 bg-green-600 text-white text-sm p-3 rounded-lg shadow-lg z-50"
        x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
        role="alert"
    >
        {{ $value }}
    </div>
    @endsession

    {{-- Modals --}}
    <livewire:create-service-category />
    <livewire:edit-service-category />
    <livewire:detail-service-category />

    {{-- Table Card --}}
    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                <thead class="bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-medium text-left">Name</th>
                        <th class="px-4 py-3 font-medium text-left">Description</th>
                        <th class="px-4 py-3 font-medium text-left">Code</th>
                        <th class="px-4 py-3 font-medium text-left">Status</th>
                        <th class="px-4 py-3 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach ($categories as $category)
                    <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                        <td class="px-4 py-2 font-medium">{{ $category->name }}</td>
                        <td class="px-4 py-2 text-gray-500 dark:text-gray-300 truncate max-w-xs">{{ $category->description }}</td>
                        <td class="px-4 py-2">{{ $category->code }}</td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $category->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        
                        <td class="px-4 py-2 flex justify-center items-center gap-2">
                        {{-- detail --}}
                            <flux:button size="sm" variant="ghost" wire:click="viewDetails({{ $category->id }})" class="p-1 hover:bg-gray-100 dark:hover:bg-slate-700/30 rounded">
                                    <flux:icon.eye class="text-yellow-500" /> 
                            </flux:button>
                            {{-- Edit --}}
                            <flux:button size="sm" variant="ghost" wire:click="edit({{ $category->id }})" class="p-1 hover:bg-sky-100 dark:hover:bg-sky-700/30 rounded">
                                <flux:icon.pencil-square class="text-sky-500" />
                            </flux:button>

                            {{-- Delete --}}
                            <flux:modal.trigger name="delete-category">
                                <flux:button size="sm" variant="ghost" wire:click="confirmDelete({{ $category->id }})" class="p-1 hover:bg-red-100 dark:hover:bg-red-700/30 rounded">
                                    <flux:icon.trash class="text-red-500" />
                                </flux:button>
                            </flux:modal.trigger>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 flex justify-end">
            {{ $categories->links() }}
        </div>
    </div>

    {{-- Delete Modal --}}
    <flux:modal name="delete-category" class="min-w-[20rem] md:min-w-[24rem]">
        <div class="space-y-4">
            <div>
                <flux:heading size="lg" class="text-red-600">Delete Service Category?</flux:heading>
                <flux:text class="text-gray-600 dark:text-gray-300 mt-1">
                    You're about to delete this service category.<br>
                    This action <strong>cannot</strong> be reversed.
                </flux:text>
            </div>
            <div class="flex justify-end gap-2">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button wire:click="delete" variant="danger">
                    Delete
                </flux:button>
            </div>
        </div>
    </flux:modal>

    


</div>
