<div class="p-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <flux:heading size="xl" level="1" class="mb-1">Services</flux:heading>
            <flux:subheading size="md" class="text-gray-500 dark:text-gray-400">
                Manage your services
            </flux:subheading>
        </div>

        <div class="mt-4 md:mt-0">
            <flux:modal.trigger name="create-service">
                <flux:button size="sm" variant="primary" class="flex items-center gap-2">
                    <span>+ Add Service</span>
                </flux:button>
            </flux:modal.trigger>
        </div>
    </div>

    {{-- Success Message --}}
    @session('success')
        <div class="fixed top-5 right-5 bg-green-600 text-white text-sm p-3 rounded-lg shadow-lg z-50"
            x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" role="alert">
            {{ $value }}
        </div>
    @endsession

    {{-- Modals --}}
    <livewire:services.create-service />
    <livewire:services.edit-service />
    <livewire:services.service-detail />

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
                        <th class="px-4 py-3 font-medium text-left">Price</th>
                        <th class="px-4 py-3 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-2 font-medium">{{ $service->name }}</td>
                            <td class="px-4 py-2 text-gray-500 dark:text-gray-300 truncate max-w-xs">
                                {{ $service->description }}</td>
                            <td class="px-4 py-2">{{ $service->code }}</td>
                            <td class="px-4 py-2">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $service->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $service->price }}</td>
                            <td class="px-4 py-2 flex justify-center items-center gap-2">
                            {{-- Detail --}}
                            <flux:button size="sm" variant="ghost" wire:click="viewDetails({{ $service->id }})" class="p-1 hover:bg-gray-100 dark:hover:bg-slate-700/30 rounded">
                                    <flux:icon.eye class="text-yellow-500" /> 
                            </flux:button>
                                {{-- Edit --}}
                                <flux:button size="sm" variant="ghost" wire:click="edit({{ $service->id }})"
                                    class="p-1 hover:bg-sky-100 dark:hover:bg-sky-700/30 rounded">
                                    <flux:icon.pencil-square class="text-sky-500" />
                                </flux:button>

                                {{-- Delete --}}
                                <flux:modal.trigger name="delete-service">
                                    <flux:button size="sm" variant="ghost"
                                        wire:click="confirmDelete({{ $service->id }})"
                                        class="p-1 hover:bg-red-100 dark:hover:bg-red-700/30 rounded">
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
            {{ $services->links() }}
        </div>
    </div>



</div>
