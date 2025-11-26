<div class="p-6 max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
        <div>
            <flux:heading size="xl" level="1" class="mb-1">Card Fee</flux:heading>
            <flux:subheading size="md" class="text-gray-500 dark:text-gray-400">
                Manage Card Fee
            </flux:subheading>
        </div>

        <div class="mt-4 md:mt-0">
            <flux:modal.trigger name="create-card-fee">
                <flux:button size="sm" variant="primary" class="flex items-center gap-2">
                    <span>+ Add Card Fee</span>
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
    <livewire:card-fee.create-card-fee />
    <livewire:card-fee.edit-card-fee />
    <livewire:card-fee.card-fee-detail />

    {{-- Table Card --}}
    <div class="bg-white dark:bg-slate-800 shadow-lg rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-slate-700 text-sm">
                <thead class="bg-gray-50 dark:bg-slate-700 text-gray-600 dark:text-gray-200">
                    <tr>
                        <th class="px-4 py-3 font-medium text-left">Date</th>
                        <th class="px-4 py-3 font-medium text-left">Amount</th>
                        <th class="px-4 py-3 font-medium text-left">Status</th>
                        <th class="px-4 py-3 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-200 dark:divide-slate-700">
                    @foreach ($cardFees as $cardFee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-slate-700/30 transition">
                            <td class="px-4 py-2 font-medium">{{ $cardFee->date }}</td>
                            <td class="px-4 py-2 text-gray-500 dark:text-gray-300 truncate max-w-xs">
                                {{ $cardFee->amount }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 text-xs font-semibold rounded-md
                                {{ $cardFee->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $cardFee->is_active ? 'Active' : 'Inactive' }}
                                </span>

                                <flux:button size="sm" variant="ghost"
                                    wire:click="changeStatus({{ $cardFee->id }})"
                                    class="p-1 hover:bg-sky-100 dark:hover:bg-slate-700/30 rounded">
                                    <flux:icon.arrow-path class="text-sky-500" />
                                </flux:button>
                            </td>

                            <td class="px-4 py-2 flex justify-center items-center gap-2">
                                {{-- Detail --}}
                                
                                {{-- Detail --}}
                                <flux:button size="sm" variant="ghost"
                                    wire:click="edit({{ $cardFee->id }})"
                                    class="p-1 hover:bg-sky-100 dark:hover:bg-slate-700/30 rounded">
                                    <flux:icon.pencil-square class="text-blue-500" />
                                </flux:button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 flex justify-end">
            {{ $cardFees->links() }}
        </div>
    </div>



</div>
