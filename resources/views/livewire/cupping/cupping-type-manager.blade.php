<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- Header --}}
        <div class="mb-8">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center space-x-4">
                        <div class="h-14 w-14 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-2 border-white/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Types Management</h1>
                            <p class="text-purple-100 mt-1">Manage cupping therapy types</p>
                        </div>
                    </div>

                    <button wire:click="create"
                        class="px-6 py-2.5 bg-white/20 hover:bg-white/30 text-white rounded-xl transition-all duration-200 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add New Type
                    </button>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        @if($showAlert)
        <div class="mb-4 p-4 rounded-xl flex items-center justify-between shadow-lg
            {{ $alertType === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
               ($alertType === 'error' ? 'bg-red-100 border border-red-400 text-red-700' : 'bg-yellow-100 border border-yellow-400 text-yellow-700') }}">
            <span>{{ $alertMessage }}</span>
            <button wire:click="closeAlert" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        @endif

        {{-- Form Modal --}}
        @if($showForm)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
             x-data="{ open: true }"
             x-show="open"
             x-on:keydown.escape.window="open = false; $wire.resetForm()">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ $editingId ? 'Edit Cupping Type' : 'Add New Cupping Type' }}
                    </h3>
                    <button wire:click="resetForm" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type Name <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="name" placeholder="e.g., Dry Cupping, Wet Cupping, Flash Cupping"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600">
                        @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea wire:model="description" rows="3" placeholder="Describe this cupping type..."
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:border-gray-600"></textarea>
                    </div>

                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model="status" class="w-4 h-4 text-purple-600 rounded">
                            <span class="text-sm text-gray-700 dark:text-gray-300">Active</span>
                        </label>
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="resetForm" class="px-4 py-2 border rounded-lg hover:bg-gray-50">Cancel</button>
                    <button wire:click="save" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium">Save</button>
                </div>
            </div>
        </div>
        @endif

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text" wire:model.live="search" placeholder="Search by name or description..."
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Per Page</label>
                    <select wire:model.live="perPage" class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Types Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($types as $type)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="px-5 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white">{{ $type->name }}</h3>
                            @if($type->description)
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $type->description }}</p>
                            @endif
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $type->status ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $type->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="p-4 flex justify-end gap-2">
                        <button wire:click="toggleStatus({{ $type->id }})"
                            class="px-3 py-1.5 text-sm rounded-lg {{ $type->status ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                            {{ $type->status ? 'Deactivate' : 'Activate' }}
                        </button>
                        <button wire:click="edit({{ $type->id }})"
                            class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                            Edit
                        </button>
                        <button wire:click="delete({{ $type->id }})"
                            onclick="return confirm('Delete this cupping type?')"
                            class="px-3 py-1.5 text-sm bg-red-100 text-red-700 rounded-lg hover:bg-red-200">
                            Delete
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No cupping types found</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Click "Add New Type" to create one</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $types->links() }}
        </div>
    </div>
</div>
