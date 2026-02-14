<div>
    <div>
        <!-- Confirmation Modal -->
        <div x-data="{ show: false, title: '', message: '', confirmText: 'Confirm', cancelText: 'Cancel', id: null, index: null, action: null }"
            x-show="show"
            x-on:confirm-delete-modal.window="show = true; title = $event.detail.title; message = $event.detail.message; confirmText = $event.detail.confirmText; cancelText = $event.detail.cancelText; id = $event.detail.id; action = 'delete'"
            x-on:confirm-delete-item-modal.window="show = true; title = $event.detail.title; message = $event.detail.message; confirmText = $event.detail.confirmText; cancelText = $event.detail.cancelText; index = $event.detail.index; action = 'delete-item'"
            class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100"
                                    x-text="title"></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="message"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button"
                            @click="if (action === 'delete') { $wire.deletePackage(id); } else if (action === 'delete-item') { $wire.deleteItem(index); } show = false;"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 dark:bg-red-700 text-base font-medium text-white hover:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <span x-text="confirmText"></span>
                        </button>
                        <button type="button" @click="show = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            <span x-text="cancelText"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-green-800 dark:text-green-300">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
                <span class="text-red-800 dark:text-red-300">{{ session('error') }}</span>
            </div>
        </div>
        @endif

        <!-- Main Dashboard -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900">
            <!-- Header -->
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">
                            <i class="fas fa-cubes mr-2"></i> Rehab Packages
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Manage rehabilitation service packages
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search packages..."
                                class="pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400">
                            <div class="absolute left-3 top-2.5 text-gray-400 dark:text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <select wire:model.live="status"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="all">All</option>
                        </select>
                        <select wire:model.live="perPage"
                            class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                            <option value="10">10 per page</option>
                            <option value="15">15 per page</option>
                            <option value="25">25 per page</option>
                            <option value="50">50 per page</option>
                        </select>
                        <button wire:click="showCreateForm"
                            class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 dark:from-blue-600 dark:to-indigo-700 dark:hover:from-blue-500 dark:hover:to-indigo-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Create Package
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Packages</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['active']
                                    }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactive</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['inactive']
                                    }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Items</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{
                                    $stats['total_items'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Packages Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Package</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Items</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Discount</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Final Price</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Created By</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($packages as $package)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $package->name }}
                                </div>
                                @if($package->description)
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">
                                    {{ Str::limit($package->description, 50) }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($package->items->take(3) as $item)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-{{ $item->type_color }}-100 text-{{ $item->type_color }}-800 dark:bg-{{ $item->type_color }}-900 dark:text-{{ $item->type_color }}-200">
                                        {{ $item->item_name }}
                                    </span>
                                    @endforeach
                                    @if($package->items->count() > 3)
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        +{{ $package->items->count() - 3 }} more
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    ETB {{ number_format($package->base_price, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($package->discount_type)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $package->discount_display }}
                                </span>
                                @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                    ETB {{ number_format($package->final_price, 2) }}
                                </span>
                                @if($package->savings > 0)
                                <span class="text-xs text-green-600 dark:text-green-400 block">
                                    Save {{ $package->savings_percentage }}%
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $package->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $package->creator?->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="showEditForm({{ $package->id }})"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </button>

                                    <button wire:click="duplicatePackage({{ $package->id }})"
                                        class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                        Duplicate
                                    </button>

                                    <button wire:click="toggleStatus({{ $package->id }})"
                                        class="px-3 py-1.5 {{ $package->is_active ? 'bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-700 dark:hover:bg-yellow-800' : 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800' }} text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($package->is_active)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            @endif
                                        </svg>
                                        {{ $package->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>

                                    <button wire:click="confirmDelete({{ $package->id }})"
                                        class="px-3 py-1.5 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No packages found
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $search ? 'No packages match your search' : 'Get started by creating your first
                                    rehab package' }}
                                </p>
                                @if(!$search)
                                <button wire:click="showCreateForm"
                                    class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-lg transition-colors">
                                    Create Package
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($packages->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $packages->links() }}
            </div>
            @endif
        </div>

        <!-- Package Form Modal -->
        @if($showForm)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                    aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div
                    class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">

                    <!-- Modal Header -->
                    <div
                        class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 dark:from-blue-700 dark:to-indigo-800">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">{{ $formTitle }}</h3>
                                    <p class="text-sm text-white/90">Configure package details and items</p>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('showForm', false)"
                                class="p-2 rounded-lg hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Content -->
                    <div class="px-6 py-4 max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="savePackage" class="space-y-6">
                            <!-- Package Details -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Package
                                    Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Package Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model="name"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                            placeholder="e.g., Basic Rehab Package">
                                        @error('name') <p class="text-red-600 dark:text-red-400 text-xs mt-1">{{
                                            $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                        <div class="flex items-center space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model="is_active" :value="true"
                                                    class="form-radio text-blue-600 focus:ring-blue-500">
                                                <span
                                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" wire:model="is_active" :value="false"
                                                    class="form-radio text-blue-600 focus:ring-blue-500">
                                                <span
                                                    class="ml-2 text-sm text-gray-700 dark:text-gray-300">Inactive</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                        <textarea wire:model="description" rows="3"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                            placeholder="Package description..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Pricing -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pricing &
                                    Discounts</h4>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                            Base Price <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">ETB</span>
                                            <input type="number" step="0.01" min="0" wire:model.live="base_price"
                                                class="w-full pl-12 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                        </div>
                                        @error('base_price') <p class="text-red-600 dark:text-red-400 text-xs mt-1">{{
                                            $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount
                                            Type</label>
                                        <select wire:model.live="discount_type"
                                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                            <option value="">No Discount</option>
                                            <option value="fixed">Fixed Amount</option>
                                            <option value="percentage">Percentage</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Discount
                                            Value</label>
                                        <div class="relative">
                                            @if($discount_type === 'percentage')
                                            <span
                                                class="absolute right-3 top-2 text-gray-500 dark:text-gray-400">%</span>
                                            @elseif($discount_type === 'fixed')
                                            <span
                                                class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">ETB</span>
                                            @endif
                                            <input type="number" step="0.01" min="0" wire:model.live="discount_value"
                                                class="w-full {{ $discount_type === 'percentage' ? 'pr-8' : ($discount_type === 'fixed' ? 'pl-12' : '') }} border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                placeholder="0.00">
                                        </div>
                                        @error('discount_value') <p class="text-red-600 dark:text-red-400 text-xs mt-1">
                                            {{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Final
                                            Price</label>
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-2 text-gray-500 dark:text-gray-400">ETB</span>
                                            <input type="text" value="{{ number_format((float) $final_price, 2) }}"
                                                readonly
                                                class="w-full pl-12 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Package Items -->
                            <!-- Package Items Section -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Package Items</h4>
                                    <button type="button" wire:click="showItemFormModal"
                                        class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        </svg>
                                        Add Item
                                    </button>
                                </div>

                                <!-- Items List -->
                                @if(count($items) > 0)
                                <div class="space-y-2 mb-4">
                                    @foreach($items as $index => $item)
                                    <div
                                        class="bg-white dark:bg-gray-800 p-3 rounded-lg border dark:border-gray-700 flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <span
                                                class="w-2 h-2 rounded-full bg-{{ $item['type_color'] ?? 'gray' }}-500"></span>
                                            <div>
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{
                                                    $item['item_name'] }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{
                                                    $item['type_label'] ?? $item['item_type'] }}</span>
                                                @if(isset($item['dosage']) && $item['dosage'])
                                                <span class="text-xs text-gray-500 dark:text-gray-400 block">Dosage: {{
                                                    $item['dosage'] }}</span>
                                                @endif
                                                @if(isset($item['bed_duration_days']) && $item['bed_duration_days'])
                                                <span class="text-xs text-gray-500 dark:text-gray-400 block">Duration:
                                                    {{ $item['bed_duration_days'] }} days</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <button type="button" wire:click="showItemFormModal({{ $index }})"
                                                class="p-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button type="button" wire:click="confirmDeleteItem({{ $index }})"
                                                class="p-1 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div
                                    class="text-center py-8 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg mb-4">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No items added
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Click "Add Item" to add
                                        items to this package.</p>
                                </div>
                                @endif

                                <!-- Item Form (Inline) -->
                                @if($showItemForm)
                                <div
                                    class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-lg border-2 border-blue-200 dark:border-blue-800">
                                    <div class="flex justify-between items-center mb-4">
                                        <h5 class="text-md font-medium text-gray-900 dark:text-gray-100">{{
                                            $itemFormTitle }}</h5>
                                        <button type="button" wire:click="$set('showItemForm', false)"
                                            class="text-gray-500 hover:text-gray-700">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    <form wire:submit.prevent="saveItem">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Item Type -->
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Item Type <span class="text-red-500">*</span>
                                                </label>
                                                <select wire:model.live="item_type" required
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                    <option value="">Select Type</option>
                                                    @foreach($itemTypes as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('item_type') <p class="text-red-600 text-xs mt-1">{{ $message }}
                                                </p> @enderror
                                            </div>

                                            <!-- For Bed Type - Simple Input -->
                                            @if($item_type === 'bed')
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Bed Name <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" wire:model="item_name"
                                                    placeholder="e.g., Private Room, ICU Bed, General Ward"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                @error('item_name') <p class="text-red-600 text-xs mt-1">{{ $message }}
                                                </p> @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Duration (Days) <span class="text-red-500">*</span>
                                                </label>
                                                <input type="number" min="1" wire:model="bed_duration_days"
                                                    placeholder="e.g., 5"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                @error('bed_duration_days') <p class="text-red-600 text-xs mt-1">{{
                                                    $message }}</p> @enderror
                                            </div>
                                            @endif

                                            <!-- For Other Types - Search -->
                                            @if($item_type && $item_type !== 'bed')
                                            <div class="md:col-span-2">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Search {{ $itemTypes[$item_type] }} <span
                                                        class="text-red-500">*</span>
                                                </label>
                                                <div class="relative">
                                                    <input type="text" wire:model.live.debounce.300ms="searchTerm"
                                                        placeholder="Type at least 2 characters to search..."
                                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">

                                                    <!-- Loading indicator -->
                                                    <div wire:loading wire:target="searchTerm"
                                                        class="absolute right-3 top-2.5">
                                                        <svg class="animate-spin h-5 w-5 text-gray-400" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                                stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                            </path>
                                                        </svg>
                                                    </div>

                                                    <!-- Search Results Dropdown -->
                                                    @if($showSearchDropdown && count($searchResults) > 0)
                                                    <div
                                                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                                                        @foreach($searchResults as $index => $result)
                                                        <button type="button"
                                                            wire:click="selectSearchItem({{ $index }})"
                                                            class="w-full text-left px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 border-b last:border-b-0 border-gray-200 dark:border-gray-700 transition-colors">
                                                            <div
                                                                class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $result['name'] }}
                                                            </div>
                                                            @if(isset($result['code']))
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">Code:
                                                                {{ $result['code'] }}</div>
                                                            @endif
                                                            @if(isset($result['strength']))
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                                Strength: {{ $result['strength'] }}</div>
                                                            @endif
                                                            @if(isset($result['unit']))
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">Unit:
                                                                {{ $result['unit'] }}</div>
                                                            @endif
                                                        </button>
                                                        @endforeach
                                                    </div>
                                                    @elseif($showSearchDropdown && strlen($searchTerm) >= 2 &&
                                                    count($searchResults) === 0)
                                                    <div
                                                        class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-lg p-4 text-center text-gray-500">
                                                        No results found
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Selected Item Display -->
                                            @if($item_name && !$searchTerm)
                                            <div
                                                class="md:col-span-3 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <span
                                                            class="text-xs text-green-600 dark:text-green-400 uppercase font-semibold">Selected
                                                            Item</span>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $item_name }}</p>
                                                        @if($item_id)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{
                                                            $item_id }}</p>
                                                        @endif
                                                    </div>
                                                    <button type="button" wire:click="resetItemForm"
                                                        class="text-red-600 hover:text-red-800 p-1">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                            @endif

                                            <!-- Additional Fields for Standard/Custom Medications -->
                                            @if(in_array($item_type, ['standard_medication', 'custom_medication']) &&
                                            $item_name)
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosage</label>
                                                <input type="text" wire:model="dosage"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                    placeholder="e.g., 500mg" readonly>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequency</label>
                                                <select wire:model="frequency_id"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                                    <option value="">Select Frequency</option>
                                                    @foreach($frequencies as $freq)
                                                    <option value="{{ $freq->id }}">{{ $freq->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration</label>
                                                <input type="text" wire:model="duration"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                    placeholder="e.g., 7 days">
                                            </div>
                                            @endif

                                            <!-- For Consumables -->
                                            @if($item_type === 'consumable' && $item_name)
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Unit</label>
                                                <input type="text" wire:model="dosage"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                    placeholder="Unit" readonly>
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                                                <input type="number" min="1" wire:model="duration"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                    placeholder="e.g., 2">
                                            </div>
                                            @endif
                                            @endif

                                            <!-- Notes for all types -->
                                            @if($item_type && (($item_type !== 'bed' && $item_name) || $item_type ===
                                            'bed'))
                                            <div class="md:col-span-3">
                                                <label
                                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes
                                                    (Optional)</label>
                                                <textarea wire:model="notes" rows="2"
                                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100"
                                                    placeholder="Additional notes..."></textarea>
                                            </div>
                                            @endif
                                        </div>

                                        @if($item_type && (($item_type !== 'bed' && $item_name) || $item_type ===
                                        'bed'))
                                        <div class="flex justify-end space-x-3 mt-4 pt-4 border-t dark:border-gray-700">
                                            <button type="button" wire:click="$set('showItemForm', false)"
                                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                Cancel
                                            </button>
                                            <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white rounded-lg font-medium transition-all duration-200 flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                </svg>
                                                {{ $editingItemIndex !== null ? 'Update' : 'Add' }} Item
                                            </button>
                                        </div>
                                        
                                        @endif
                                        <!-- After the Package Items section, before closing the form -->
<div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
    <button type="button" wire:click="$set('showForm', false)"
        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
        Cancel
    </button>
    <button type="submit" 
        wire:loading.attr="disabled"
        class="px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-700 hover:from-green-700 hover:to-emerald-800 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 flex items-center">
        <span wire:loading.remove>
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ $editingId ? 'Update Package' : 'Create Package' }}
        </span>
        <span wire:loading>
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Saving...
        </span>
    </button>
</div>
                                    </form>
                                </div>
                                @endif
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Item Form Modal -->

    </div>
</div>