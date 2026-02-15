<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    {{-- Alert Notification --}}
    @if($showAlert)
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" class="fixed top-6 right-6 z-50 max-w-sm w-full">
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4 
                @if($alertType === 'success') bg-gradient-to-r from-green-500 via-green-600 to-emerald-600
                @elseif($alertType === 'error') bg-gradient-to-r from-red-500 via-red-600 to-rose-600
                @else bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600
                @endif">
                <div class="flex items-center space-x-3">
                    @if($alertType === 'success')
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    @elseif($alertType === 'error')
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    @endif
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header with Theme Toggle --}}
        <div class="mb-8">
            <div class="bg-gradient-to-br from-purple-600 via-purple-500 to-indigo-600 dark:from-purple-800 dark:via-purple-700 dark:to-indigo-800 rounded-2xl shadow-2xl overflow-hidden relative">
                {{-- Decorative Elements --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                
                <div class="relative p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 border-2 border-white dark:border-gray-800 animate-pulse"></div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Rehabilitation Package</h1>
                                <p class="text-purple-100 dark:text-purple-200 mt-1 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                    </svg>
                                    {{ $packageId ? 'Edit package details' : 'Create new rehabilitation package' }}
                                </p>
                            </div>
                        </div>
                        
                        {{-- Theme Toggle Button --}}
                        <button onclick="toggleTheme()" class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group">
                            <svg class="w-6 h-6 text-white dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg class="w-6 h-6 text-white hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    
                    {{-- Progress Indicator --}}
                    <div class="mt-6 flex items-center space-x-2">
                        <div class="flex-1 h-2 bg-white/20 rounded-full overflow-hidden">
                            <div class="h-full bg-white rounded-full" style="width: 60%"></div>
                        </div>
                        <span class="text-sm text-white font-medium">Package Details</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Form with Enhanced Styling --}}
        <div class="space-y-6">
            {{-- Basic Information Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Basic Information</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Package Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="name"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200"
                                placeholder="e.g., Basic Rehabilitation Package">
                            @error('name') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Base Price <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 dark:text-gray-400">$</span>
                                <input type="number" step="0.01" wire:model.live="base_price"
                                    class="w-full pl-8 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200"
                                    placeholder="0.00">
                            </div>
                            @error('base_price') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <textarea wire:model="description" rows="3"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200"
                                placeholder="Package description..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Discount Settings Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Discount Settings</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Discount Type
                            </label>
                            <select wire:model.live="discount_type"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200">
                                <option value="">No Discount</option>
                                <option value="fixed">Fixed Amount ($)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        
                        @if($discount_type)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Discount Value
                            </label>
                            <div class="relative">
                                @if($discount_type === 'fixed')
                                <span class="absolute left-4 top-3 text-gray-500 dark:text-gray-400">$</span>
                                @endif
                                <input type="number" step="0.01" wire:model.live="discount_value"
                                    class="w-full {{ $discount_type === 'fixed' ? 'pl-8' : 'px-4' }} pr-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200"
                                    placeholder="{{ $discount_type === 'percentage' ? '0' : '0.00' }}">
                                @if($discount_type === 'percentage')
                                <span class="absolute right-4 top-3 text-gray-500 dark:text-gray-400">%</span>
                                @endif
                            </div>
                            @error('discount_value') <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Final Price
                            </label>
                            <div class="w-full px-4 py-3 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-gray-700 dark:to-gray-600 rounded-xl border-2 border-purple-200 dark:border-purple-800">
                                <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($finalPrice, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Add Item Button with Animation --}}
            @if(!$showItemForm)
            <div class="flex justify-center transform hover:scale-105 transition-transform duration-300">
                <button type="button" wire:click="showAddItemForm"
                    class="group px-8 py-4 bg-gradient-to-r from-purple-600 via-purple-500 to-indigo-600 hover:from-purple-700 hover:via-purple-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transition-all duration-200 flex items-center space-x-3">
                    <svg class="w-6 h-6 group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add New Item</span>
                </button>
            </div>
            @endif

            {{-- Item Form with Enhanced Styling --}}
            @if($showItemForm)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border-2 border-purple-200 dark:border-purple-800 overflow-hidden transform transition-all duration-300">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 via-purple-100 to-indigo-50 dark:from-purple-900/30 dark:via-purple-900/20 dark:to-indigo-900/30 border-b border-purple-200 dark:border-purple-800">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-purple-900 dark:text-purple-300">
                                @if($editingIndex !== null)
                                    Edit Item
                                @else
                                    Add New Item
                                @endif
                            </h3>
                        </div>
                        <button type="button" wire:click="cancelItemForm" class="p-2 hover:bg-white/50 dark:hover:bg-gray-700 rounded-lg transition-colors">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    @if(!$selectedItemType)
                        {{-- Item Type Selection with Enhanced Cards --}}
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">
                                Select Item Type <span class="text-red-500">*</span>
                            </label>
                            
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <button type="button" wire:click="$set('selectedItemType', 'standard_medication')"
                                    class="group p-4 border-2 rounded-xl hover:border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200 {{ $selectedItemType === 'standard_medication' ? 'border-purple-500 bg-purple-50 dark:bg-purple-900/30 shadow-lg' : 'border-gray-200 dark:border-gray-700' }}">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-purple-600 dark:text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Standard Medication</span>
                                </button>
                                
                                <button type="button" wire:click="$set('selectedItemType', 'custom_medication')"
                                    class="group p-4 border-2 rounded-xl hover:border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 {{ $selectedItemType === 'custom_medication' ? 'border-green-500 bg-green-50 dark:bg-green-900/30 shadow-lg' : 'border-gray-200 dark:border-gray-700' }}">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-green-600 dark:text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Custom Medication</span>
                                </button>
                                
                                <button type="button" wire:click="$set('selectedItemType', 'service')"
                                    class="group p-4 border-2 rounded-xl hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200 {{ $selectedItemType === 'service' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/30 shadow-lg' : 'border-gray-200 dark:border-gray-700' }}">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Service</span>
                                </button>
                                
                                @if(!$bed)
                                <button type="button" wire:click="$set('selectedItemType', 'bed')"
                                    class="group p-4 border-2 rounded-xl hover:border-yellow-500 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-all duration-200 {{ $selectedItemType === 'bed' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/30 shadow-lg' : 'border-gray-200 dark:border-gray-700' }}">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-yellow-600 dark:text-yellow-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bed</span>
                                </button>
                                @endif
                            </div>
                            
                            @if($selectedItemType)
                            <div class="flex justify-end mt-6">
                                <button type="button" wire:click="selectItemType"
                                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                    <span>Continue</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                            @endif
                        </div>
                    @else
                        {{-- Item Details Form with Enhanced Fields --}}
                        <div class="space-y-4">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center space-x-3">
                                    <span class="px-4 py-2 rounded-xl text-sm font-semibold
                                        @if($selectedItemType === 'standard_medication') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                        @elseif($selectedItemType === 'custom_medication') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                        @elseif($selectedItemType === 'service') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                        @endif">
                                        {{ str_replace('_', ' ', ucwords($selectedItemType)) }}
                                    </span>
                                    <button type="button" wire:click="$set('selectedItemType', '')" 
                                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span>Change</span>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                {{-- Common for all types --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Name/Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" wire:model="currentItem.item_name"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white transition-all duration-200"
                                        placeholder="Enter name">
                                    @error('currentItem.item_name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                </div>
                                
                                {{-- Medication fields with icons --}}
                                @if(in_array($selectedItemType, ['standard_medication', 'custom_medication']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                </svg>
                                                <span>Dosage <span class="text-red-500">*</span></span>
                                            </span>
                                        </label>
                                        <input type="text" wire:model="currentItem.dosage"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                            placeholder="e.g., 500mg, 1 tablet">
                                        @error('currentItem.dosage') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>Frequency <span class="text-red-500">*</span></span>
                                            </span>
                                        </label>
                                        <input type="text" wire:model="currentItem.frequency"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                            placeholder="e.g., Twice daily, Every 8 hours">
                                        @error('currentItem.frequency') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>Duration <span class="text-red-500">*</span></span>
                                            </span>
                                        </label>
                                        <input type="text" wire:model="currentItem.duration"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                            placeholder="e.g., 7 days, 2 weeks">
                                        @error('currentItem.duration') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16 4 16M9 12h6"></path>
                                                </svg>
                                                <span>Quantity <span class="text-red-500">*</span></span>
                                            </span>
                                        </label>
                                        <input type="text" wire:model="currentItem.quantity"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                            placeholder="e.g., 30 tablets, 1 box">
                                        @error('currentItem.quantity') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                                
                                {{-- Bed field --}}
                                @if($selectedItemType === 'bed')
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            <span class="flex items-center space-x-2">
                                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                                </svg>
                                                <span>Duration (Days) <span class="text-red-500">*</span></span>
                                            </span>
                                        </label>
                                        <input type="number" wire:model="currentItem.bed_duration_days" min="1"
                                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                            placeholder="Enter number of days">
                                        @error('currentItem.bed_duration_days') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                                
                                {{-- Instructions for all types --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span>Special Instructions</span>
                                        </span>
                                    </label>
                                    <textarea wire:model="currentItem.instructions" rows="2"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                        placeholder="Any special instructions for administration..."></textarea>
                                </div>
                                
                                {{-- Additional Notes --}}
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                            </svg>
                                            <span>Additional Notes</span>
                                        </span>
                                    </label>
                                    <textarea wire:model="currentItem.notes" rows="2"
                                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:text-white"
                                        placeholder="Any additional notes..."></textarea>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-8">
                                <button type="button" wire:click="cancelItemForm"
                                    class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                                    Cancel
                                </button>
                                <button type="button" wire:click="{{ $editingIndex !== null ? 'updateItem' : 'saveItem' }}"
                                    class="px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 flex items-center space-x-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $editingIndex !== null ? 'Update' : 'Add' }} Item</span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Items Lists with Enhanced Cards --}}
            @if(count($standardMedications) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-900/20 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-purple-200 dark:bg-purple-800 rounded-lg">
                                <svg class="w-5 h-5 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h2 class="text-lg font-semibold text-purple-900 dark:text-purple-300">Standard Medications</h2>
                        </div>
                        <span class="px-3 py-1 bg-purple-200 dark:bg-purple-800 text-purple-800 dark:text-purple-300 rounded-full text-sm font-medium">{{ count($standardMedications) }} items</span>
                    </div>
                </div>
                <div class="p-6 space-y-4">
                    @foreach($standardMedications as $index => $med)
                    <div class="group border border-gray-200 dark:border-gray-700 rounded-xl p-5 hover:shadow-xl hover:border-purple-200 dark:hover:border-purple-700 transition-all duration-200">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-3">
                                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded-full">Standard</span>
                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $med['item_name'] }}</h4>
                                </div>
                                
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Dosage</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $med['dosage'] }}</span>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Frequency</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $med['frequency'] }}</span>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Duration</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $med['duration'] }}</span>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg">
                                        <span class="text-gray-500 dark:text-gray-400 block text-xs">Quantity</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $med['quantity'] }}</span>
                                    </div>
                                </div>
                                
                                @if($med['instructions'])
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                    <span class="text-gray-600 dark:text-gray-400 text-sm flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>{{ $med['instructions'] }}</span>
                                    </span>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex space-x-2 ml-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" wire:click="editItem('standard_medication', {{ $index }})" 
                                    class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button" wire:click="removeItem('standard_medication', {{ $index }})" 
                                    onclick="return confirm('Remove this medication?')"
                                    class="p-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-800 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Similar updates for Custom Medications, Services, and Bed sections with dark mode classes --}}
            {{-- ... (apply same pattern to other sections) ... --}}

            {{-- Status Card --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-white dark:from-gray-800 dark:to-gray-800/50">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Package Status</h2>
                    </div>
                </div>
                
                <div class="p-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model="is_active"
                            class="w-5 h-5 text-purple-600 focus:ring-purple-500 border-gray-300 rounded transition-all duration-200">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active (available for use)</span>
                    </label>
                </div>
            </div>

            {{-- Form Actions with Sticky Effect --}}
            <div class="sticky bottom-6 z-10">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-700 p-6 backdrop-blur-xl bg-white/80 dark:bg-gray-800/80">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('rehab.packages.index') }}" wire:navigate
                                class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 hover:shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Cancel</span>
                            </a>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" wire:click="resetForm"
                                class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 hover:shadow-md"
                                onclick="return confirm('Clear all form data?')">
                                Clear Form
                            </button>
                            
                            <button type="button" wire:click="save" wire:loading.attr="disabled"
                                class="group px-8 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transition-all duration-200 flex items-center space-x-3">
                                <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                </svg>
                                <span>{{ $packageId ? 'Update' : 'Save' }} Package</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Theme Toggle Script --}}
    <script>
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Check for saved theme preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @script
    <script>
        $wire.on('redirectAfterDelay', (data) => {
            setTimeout(() => {
                window.location.href = data.url;
            }, 1500);
        });
        
        $wire.on('scrollToForm', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        $wire.on('closeAlertAfterDelay', () => {
            setTimeout(() => {
                $wire.closeAlert();
            }, 3000);
        });
    </script>
    @endscript
</div>