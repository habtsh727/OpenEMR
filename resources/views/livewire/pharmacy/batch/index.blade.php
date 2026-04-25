<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto p-6 lg:p-8">

        {{-- Alert Messages --}}
        @if (session()->has('message'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-400 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('message') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 dark:text-green-400 hover:text-green-900">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-2">Medicine Batches</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage your pharmacy inventory and batch records</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Batches</p>
                        <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_batches'] }}</p>
                    </div>
                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Total Stock</p>
                        <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ number_format($stats['total_stock']) }}</p>
                    </div>
                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Expiring Soon</p>
                        <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($stats['expiring_soon']) }}</p>
                    </div>
                    <div class="px-4 py-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <p class="text-xs text-gray-500 dark:text-gray-400">Expired Stock</p>
                        <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ number_format($stats['expired_stock']) }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filter Bar --}}
        <div class="mb-6 space-y-3">
            <div class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by medicine name, generic name, batch number, code, or strength..."
                        class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    />
                    @if($search)
                        <button wire:click="clearSearch" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>

                <div class="flex gap-2">
                    <button wire:click="toggleExpiringSoon"
                        class="px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium
                        {{ $filterExpiringSoon ? 'bg-yellow-500 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        Expiring Soon
                    </button>
                    <button wire:click="toggleExpired"
                        class="px-4 py-2 rounded-lg transition-all duration-200 text-sm font-medium
                        {{ $filterExpired ? 'bg-red-500 text-white shadow-md' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                        Expired
                    </button>
                    <button wire:click="$set('editingId', null)"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-600 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg shadow-blue-500/30 transition-all duration-200 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        New Batch
                    </button>
                </div>
            </div>

            {{-- Search hint --}}
            @if($search)
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    Showing results for: <span class="font-semibold text-blue-600 dark:text-blue-400">"{{ $search }}"</span>
                    <button wire:click="clearSearch" class="ml-2 text-blue-600 hover:text-blue-800 underline">Clear</button>
                </div>
            @endif
        </div>

        {{-- Form Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-gray-900/50 p-6 mb-8 border border-gray-100 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                {{ $editingId ? 'Edit Batch Information' : 'Add New Batch' }}
            </h2>

            <form wire:submit.prevent="save" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                {{-- Medicine Select --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medicine Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <select wire:model="medicine_id" class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all appearance-none text-gray-900 dark:text-gray-100">
                            <option value="" class="text-gray-400 dark:text-gray-500">Select Medicine</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" class="text-gray-900 dark:text-gray-100">
                                    {{ $medicine->name }} @if($medicine->generic_name) ({{ $medicine->generic_name }}) @endif - {{ $medicine->strength }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('medicine_id') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Batch Number --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Batch Number <span class="text-red-500">*</span></label>
                    <input
                        type="text"
                        wire:model="batch_number"
                        placeholder="e.g., BATCH-2024-001"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    />
                    @error('batch_number') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Expiry Date --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expiry Date <span class="text-red-500">*</span></label>
                    <input
                        type="date"
                        wire:model="expiry_date"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100"
                    />
                    @error('expiry_date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Quantity --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Quantity <span class="text-red-500">*</span></label>
                    <input
                        type="number"
                        wire:model="quantity"
                        placeholder="0"
                        min="0"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    />
                    @error('quantity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Purchase Price --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Purchase Price (ETB)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">ETB</span>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="purchase_price"
                            placeholder="0.00"
                            min="0"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                        />
                    </div>
                    @error('purchase_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Selling Price --}}
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Selling Price (ETB)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">ETB</span>
                        <input
                            type="number"
                            step="0.01"
                            wire:model="selling_price"
                            placeholder="0.00"
                            min="0"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                        />
                    </div>
                    @error('selling_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Submit Button --}}
                <div class="md:col-span-2 lg:col-span-3 flex justify-end gap-3 mt-2">
                    @if($editingId)
                        <button
                            type="button"
                            wire:click="$set('editingId', null)"
                            class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-xl transition-all duration-200 flex items-center gap-2 font-medium">
                            Cancel
                        </button>
                    @endif
                    <button
                        type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 dark:from-green-500 dark:to-green-600 text-white rounded-xl hover:from-green-700 hover:to-green-800 shadow-lg shadow-green-500/30 transition-all duration-200 flex items-center gap-2 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        {{ $editingId ? 'Update Batch' : 'Save Batch' }}
                    </button>
                </div>
            </form>
        </div>

        {{-- Table Card --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl dark:shadow-gray-900/50 overflow-hidden border border-gray-100 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Medicine</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Batch Number</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Expiry Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Purchase(ETB)</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Selling(ETB)</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($batches as $batch)
                            @php
                                $isExpired = $batch->expiry_date < now();
                                $isExpiringSoon = !$isExpired && $batch->expiry_date <= now()->addDays(30);
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150
                                {{ $isExpired ? 'bg-red-50/30 dark:bg-red-900/10' : '' }}
                                {{ $isExpiringSoon ? 'bg-yellow-50/30 dark:bg-yellow-900/10' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-gray-100">{{ $batch->medicine->name }}</div>
                                            @if($batch->medicine->generic_name)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $batch->medicine->generic_name }} - {{ $batch->medicine->strength }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-mono text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-900 px-3 py-1 rounded-lg">{{ $batch->batch_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $batch->expiry_date->format('M d, Y') }}</span>
                                        @if($isExpired)
                                            <span class="text-xs text-red-600 dark:text-red-400 font-medium">Expired</span>
                                        @elseif($isExpiringSoon)
                                            <span class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Expires soon</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1 font-semibold text-gray-800 dark:text-gray-200">
                                        {{ number_format($batch->quantity) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-gray-700 dark:text-gray-300">{{ number_format($batch->purchase_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-green-700 dark:text-green-400 font-semibold">{{ number_format($batch->selling_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($batch->is_active && !$isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></span>
                                            Active
                                        </span>
                                    @elseif($isExpired)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Expired
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">
                                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span>
                                            Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button
                                            wire:click="edit({{ $batch->id }})"
                                            class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button
                                            wire:click="delete({{ $batch->id }})"
                                            onclick="return confirm('Are you sure you want to delete this batch?')"
                                            class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors duration-150"
                                            title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">
                                            @if($search)
                                                No batches found matching "{{ $search }}"
                                            @else
                                                No batches found
                                            @endif
                                        </p>
                                        <p class="text-gray-400 dark:text-gray-500 text-sm">Add a new batch to get started</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($batches->hasPages())
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    {{ $batches->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
