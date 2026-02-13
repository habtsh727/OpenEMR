<div>
    <!-- Confirmation Modal -->
    <div x-data="{ show: false, title: '', message: '', confirmText: 'Confirm', cancelText: 'Cancel', id: null, action: null }"
        x-show="show"
        x-on:confirm-delete-modal.window="show = true; title = $event.detail.title; message = $event.detail.message; confirmText = $event.detail.confirmText; cancelText = $event.detail.cancelText; id = $event.detail.id; action = 'delete'"
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
                        @click="if (action === 'delete') { $wire.deleteMedication(id); } show = false;"
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
                        <i class="fas fa-flask mr-2"></i> Custom Medications
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Manage custom compounded medications
                    </p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search medications..."
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
                        <option value="20">20 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                    <button wire:click="showAddForm"
                        class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700 dark:hover:from-green-500 dark:hover:to-emerald-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Custom Medication
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <!-- Total Medications -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Medications</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['total'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Active -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['active'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Inactive -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-yellow-100 dark:bg-yellow-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Inactive</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['inactive'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Today's Added -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Today's Added</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['recent'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Low Stock -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Stock</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['low_stock'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Out of Stock -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border dark:border-gray-700 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-red-100 dark:bg-red-900 flex items-center justify-center">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Out of Stock</p>
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $stats['out_of_stock'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medications Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Medication</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ingredients</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Dosage & Frequency</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Current Stock</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock Actions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($medications as $medication)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $medication->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                Created: {{ $medication->created_at->format('M d, Y') }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-gray-100 max-w-xs truncate">{{ Str::limit($medication->ingredients, 50) }}</div>
                            @if($medication->preparation_instructions)
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Prep: {{ Str::limit($medication->preparation_instructions, 30) }}
                            </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $medication->dosage }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $medication->frequency->name ?? 'N/A' }} • {{ $medication->duration }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">ETB {{ number_format($medication->base_price, 2) }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $medication->total_orders ?? 0 }} orders
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-medium rounded-full 
                                {{ $medication->is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                {{ $medication->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        
                        <!-- CURRENT STOCK COLUMN -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($medication->stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $medication->stock->quantity <= 0 ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                       ($this->isLowStock($medication->stock) ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                       'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200') }}">
                                    {{ number_format($medication->stock->quantity, 2) }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    0.00
                                </span>
                            @endif
                        </td>

                        <!-- STOCK STATUS BADGE -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if(!$medication->stock)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                    No Stock
                                </span>
                            @elseif($medication->stock->quantity <= 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Out of Stock
                                </span>
                            @elseif($this->isLowStock($medication->stock))
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Low Stock
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    In Stock
                                </span>
                            @endif
                        </td>

                        <!-- STOCK ACTION BUTTONS -->
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-1">
                                <button wire:click="openStockModal({{ $medication->id }}, 'stock_in')"
                                    class="p-1.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg hover:bg-green-200 dark:hover:bg-green-900 transition-colors"
                                    title="Add Stock"
                                    wire:loading.attr="disabled">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                </button>
                                
                                <button wire:click="openStockModal({{ $medication->id }}, 'stock_out')"
                                    class="p-1.5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400 rounded-lg hover:bg-yellow-200 dark:hover:bg-yellow-900 transition-colors"
                                    title="Reduce Stock"
                                    wire:loading.attr="disabled"
                                    @if(!$medication->stock || $medication->stock->quantity <= 0) disabled @endif>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                    </svg>
                                </button>
                                
                                <button wire:click="openStockModal({{ $medication->id }}, 'adjustment')"
                                    class="p-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors"
                                    title="Adjust Stock"
                                    wire:loading.attr="disabled">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    </svg>
                                </button>
                                
                                <button wire:click="showMovementHistory({{ $medication->id }})"
                                    class="p-1.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900 transition-colors"
                                    title="View Movement History"
                                    wire:loading.attr="disabled">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </button>
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $medication->creator->name ?? 'System' }}
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button wire:click="showEditForm({{ $medication->id }})"
                                    class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </button>

                                <button wire:click="duplicateMedication({{ $medication->id }})"
                                    class="px-3 py-1.5 bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    Duplicate
                                </button>

                                <button wire:click="toggleStatus({{ $medication->id }})"
                                    class="px-3 py-1.5 {{ $medication->is_active ? 'bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-700 dark:hover:bg-yellow-800' : 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800' }} text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($medication->is_active)
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        @endif
                                    </svg>
                                    {{ $medication->is_active ? 'Deactivate' : 'Activate' }}
                                </button>

                                <button wire:click="confirmDelete({{ $medication->id }})"
                                    class="px-3 py-1.5 bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white text-xs font-medium rounded-lg transition-colors flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No custom medications found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                {{ $search ? 'No medications match your search' : 'Get started by creating your first custom medication' }}
                            </p>
                            @if(!$search)
                            <button wire:click="showAddForm"
                                class="mt-4 px-4 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800 text-white rounded-lg transition-colors">
                                Create Custom Medication
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($medications->hasPages())
        <div class="px-6 py-4 border-t dark:border-gray-700">
            {{ $medications->links() }}
        </div>
        @endif
    </div>

    <!-- Add/Edit Form Modal -->
    @if($showForm)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-6 pb-4 border-b dark:border-gray-700">
                        <div class="flex items-center">
                            <div
                                class="p-2 rounded-lg bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 mr-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="modal-title">
                                    {{ $formTitle }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $editingId ? 'Update custom medication details' : 'Create a new custom medication' }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="$set('showForm', false)"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveMedication" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <!-- Name -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medication Name *</label>
                                    <input type="text" wire:model="name"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="e.g., Pain Relief Compound">
                                    @error('name') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Dosage -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dosage *</label>
                                    <input type="text" wire:model="dosage"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="e.g., 5mg, 10ml">
                                    @error('dosage') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Frequency -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Frequency *</label>
                                    <select wire:model="frequency_id"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                                        <option value="">Select Frequency</option>
                                        @foreach($frequencies as $frequency)
                                        <option value="{{ $frequency->id }}">{{ $frequency->name }} ({{ $frequency->short_code }})</option>
                                        @endforeach
                                    </select>
                                    @error('frequency_id') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration *</label>
                                    <input type="text" wire:model="duration"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="e.g., 5 days, 1 week, 2 months">
                                    @error('duration') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Base Price -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Base Price (ETB) *</label>
                                    <input type="number" wire:model="base_price" step="0.01" min="0"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="0.00">
                                    @error('base_price') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="is_active"
                                            class="rounded border-gray-300 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-600">
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Inactive medications won't appear in search results
                                    </p>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <!-- Ingredients -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ingredients *</label>
                                    <textarea wire:model="ingredients" rows="4"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="List all ingredients with quantities..."></textarea>
                                    @error('ingredients') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        List each ingredient on a new line for better readability
                                    </p>
                                </div>

                                <!-- Preparation Instructions -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preparation Instructions</label>
                                    <textarea wire:model="preparation_instructions" rows="3"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Step-by-step preparation instructions..."></textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Optional: Instructions for pharmacy staff
                                    </p>
                                </div>

                                <!-- Patient Instructions -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Patient Instructions</label>
                                    <textarea wire:model="instructions" rows="3"
                                        class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400"
                                        placeholder="Instructions for the patient..."></textarea>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Optional: How and when to take the medication
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                            <button type="button" wire:click="$set('showForm', false)"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" wire:loading.attr="disabled"
                                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 dark:from-blue-600 dark:to-indigo-700 dark:hover:from-blue-500 dark:hover:to-indigo-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center">
                                <span wire:loading.remove>{{ $editingId ? 'Update' : 'Create' }} Medication</span>
                                <span wire:loading>Saving...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- STOCK MANAGEMENT MODAL -->
    @if($showStockModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="stock-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4 pb-4 border-b dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-br 
                                {{ $stockActionType === 'stock_in' ? 'from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30' : 
                                   ($stockActionType === 'stock_out' ? 'from-yellow-100 to-orange-100 dark:from-yellow-900/30 dark:to-orange-900/30' : 
                                   'from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30') }} mr-3">
                                <svg class="w-6 h-6 
                                    {{ $stockActionType === 'stock_in' ? 'text-green-600 dark:text-green-400' : 
                                       ($stockActionType === 'stock_out' ? 'text-yellow-600 dark:text-yellow-400' : 
                                       'text-blue-600 dark:text-blue-400') }}" 
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($stockActionType === 'stock_in')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    @elseif($stockActionType === 'stock_out')
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6" />
                                    @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                    @endif
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="stock-modal-title">
                                    @switch($stockActionType)
                                        @case('stock_in')
                                            Add Stock
                                            @break
                                        @case('stock_out')
                                            Reduce Stock
                                            @break
                                        @case('adjustment')
                                            Adjust Stock
                                            @break
                                    @endswitch
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $stockMedicationName }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeStockModal"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="adjustStock" x-on:submit="console.log('Form submitted')">
                        <!-- Current Stock Display -->
                        <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4 border dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Current Stock:</span>
                                <span class="text-2xl font-bold {{ $currentStock <= 0 ? 'text-red-600 dark:text-red-400' : ($currentStock <= $lowStockAlert ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }}">
                                    {{ number_format($currentStock, 2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Quantity Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                @if($stockActionType === 'adjustment')
                                    New Quantity
                                @else
                                    Quantity to {{ $stockActionType === 'stock_in' ? 'Add' : 'Reduce' }}
                                @endif
                                <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   step="0.01" 
                                   min="{{ $stockActionType === 'stock_out' ? '0.01' : '0' }}"
                                   class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 @error('stockQuantity') border-red-500 dark:border-red-400 @enderror" 
                                   wire:model="stockQuantity"
                                   wire:keydown.enter.prevent
                                   placeholder="0.00">
                            @error('stockQuantity')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Note Input -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Note (Optional)
                            </label>
                            <textarea class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-blue-500 dark:focus:border-blue-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 @error('stockNote') border-red-500 dark:border-red-400 @enderror" 
                                      wire:model="stockNote"
                                      rows="2"
                                      placeholder="Enter reason or reference..."></textarea>
                            @error('stockNote')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Warning for Stock Out -->
                        @if($stockActionType === 'stock_out' && $currentStock < ($stockQuantity ?? 0))
                        <div class="bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h4 class="text-sm font-medium text-red-800 dark:text-red-300">Insufficient Stock</h4>
                                    <p class="text-sm text-red-700 dark:text-red-400 mt-1">
                                        Available stock: {{ number_format($currentStock, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3 pt-4 border-t dark:border-gray-700">
                            <button type="button" wire:click="closeStockModal"
                                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" 
                                class="px-4 py-2 bg-gradient-to-r 
                                    {{ $stockActionType === 'stock_in' ? 'from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 dark:from-green-600 dark:to-emerald-700' : 
                                       ($stockActionType === 'stock_out' ? 'from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 dark:from-yellow-600 dark:to-orange-700' : 
                                       'from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 dark:from-blue-600 dark:to-indigo-700') }} 
                                    text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 
                                    {{ $stockActionType === 'stock_in' ? 'focus:ring-green-500' : ($stockActionType === 'stock_out' ? 'focus:ring-yellow-500' : 'focus:ring-blue-500') }}
                                    transition-all duration-200 flex items-center"
                                wire:loading.attr="disabled"
                                wire:target="{{ $stockActionType === 'stock_in' ? 'addStock' : ($stockActionType === 'stock_out' ? 'reduceStock' : 'adjustStock') }}">
                                <span wire:loading.remove wire:target="{{ $stockActionType === 'stock_in' ? 'addStock' : ($stockActionType === 'stock_out' ? 'reduceStock' : 'adjustStock') }}">
                                    @switch($stockActionType)
                                        @case('stock_in')
                                            Add Stock
                                            @break
                                        @case('stock_out')
                                            Reduce Stock
                                            @break
                                        @case('adjustment')
                                            Adjust Stock
                                            @break
                                    @endswitch
                                </span>
                                <span wire:loading wire:target="{{ $stockActionType === 'stock_in' ? 'addStock' : ($stockActionType === 'stock_out' ? 'reduceStock' : 'adjustStock') }}">
                                    Processing...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- MOVEMENT HISTORY MODAL -->
    @if($showMovementModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="movement-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl dark:shadow-gray-900 transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <!-- Modal Header -->
                    <div class="flex justify-between items-center mb-4 pb-4 border-b dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="p-2 rounded-lg bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 mr-3">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100" id="movement-modal-title">
                                    Stock Movement History
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $movementMedicationName }}
                                </p>
                            </div>
                        </div>
                        <button type="button" wire:click="closeMovementModal"
                            class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Movements Table -->
                    <div class="overflow-x-auto">
                        @if(count($movements) > 0)
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date/Time</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantity</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Note</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Performed By</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($movements as $movement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400" title="{{ $movement['created_at'] }}">
                                            {{ $movement['created_at_diff'] }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @switch($movement['type'])
                                                @case('stock_in')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                        Stock In
                                                    </span>
                                                    @break
                                                @case('stock_out')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                                        Stock Out
                                                    </span>
                                                    @break
                                                @case('adjustment')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                        Adjustment
                                                    </span>
                                                    @break
                                                @case('dispensed')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                        Dispensed
                                                    </span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium 
                                            {{ $movement['quantity'] > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                            {{ $movement['quantity_formatted'] }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate">
                                            {{ $movement['note'] ?: '—' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $movement['performed_by'] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center py-8">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No Movement History</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    No stock movements recorded for this medication yet.
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end pt-4 mt-4 border-t dark:border-gray-700">
                        <button type="button" wire:click="closeMovementModal"
                            class="px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 dark:from-gray-600 dark:to-gray-700 dark:hover:from-gray-500 dark:hover:to-gray-600 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @script
    <script>
        document.addEventListener('livewire:init', () => {
            // Modal handling is done via Alpine.js in the template
        });
    </script>
    @endscript
</div>