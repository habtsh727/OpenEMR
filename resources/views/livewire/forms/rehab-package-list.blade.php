{{-- resources/views/livewire/rehab-package-list.blade.php --}}
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
                @elseif($alertType === 'warning') bg-gradient-to-r from-amber-500 via-amber-600 to-orange-600
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
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                    </svg>
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 border-2 border-white dark:border-gray-800 animate-pulse"></div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Rehabilitation Packages</h1>
                                <p class="text-purple-100 dark:text-purple-200 mt-1">Manage rehabilitation packages and pricing</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <button onclick="toggleTheme()" class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group">
                                <svg class="w-5 h-5 text-white dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                </svg>
                                <svg class="w-5 h-5 text-white hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            
                            <button wire:click="toggleTrashed"
                                class="px-4 py-2 bg-white/20 backdrop-blur-xl hover:bg-white/30 text-white rounded-xl transition-all duration-200 flex items-center space-x-2 border border-white/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                                <span>{{ $showTrashed ? 'Hide Trashed' : 'Show Trashed' }}</span>
                            </button>
                            
                            <a href="{{ route('rehab.packages.create') }}" wire:navigate
                                class="px-4 py-2 bg-white text-purple-600 rounded-xl hover:bg-gray-100 transition-all duration-200 flex items-center space-x-2 font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span>Create New Package</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Stats Summary --}}
                    <div class="mt-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="text-white/60 text-xs">Total Packages</div>
                            <div class="text-white text-xl font-bold">{{ $packages->total() }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="text-white/60 text-xs">Active</div>
                            <div class="text-green-300 text-xl font-bold">{{ $packages->where('is_active', true)->count() }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="text-white/60 text-xs">Inactive</div>
                            <div class="text-yellow-300 text-xl font-bold">{{ $packages->where('is_active', false)->count() }}</div>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                            <div class="text-white/60 text-xs">Trashed</div>
                           <div class="text-red-300 text-xl font-bold">{{ $packages->filter(function($p) { return $p->trashed(); })->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search with Filters --}}
        <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1 relative">
                    <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search"
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Search packages by name or description...">
                </div>
                
                {{-- Quick Filter Buttons --}}
                <div class="flex space-x-2">
                    <button wire:click="$set('showTrashed', false)" 
                        class="px-4 py-2 rounded-lg {{ !$showTrashed ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition-colors">
                        Active
                    </button>
                    <button wire:click="$set('showTrashed', true)" 
                        class="px-4 py-2 rounded-lg {{ $showTrashed ? 'bg-purple-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }} transition-colors">
                        Trashed
                    </button>
                </div>
            </div>
        </div>

        {{-- Packages Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($packages as $package)
            <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 {{ $package->trashed() ? 'opacity-75 bg-red-50 dark:bg-red-900/10 border-red-200 dark:border-red-800' : '' }}">
                {{-- Card Header with Status Bar --}}
                <div class="h-2 w-full {{ $package->trashed() ? 'bg-red-500' : ($package->is_active ? 'bg-green-500' : 'bg-yellow-500') }}"></div>
                
                <div class="p-6">
                    {{-- Header with Status --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center space-x-2 flex-wrap gap-2">
                            @if($package->trashed())
                            <span class="px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300 text-xs font-medium rounded-full flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span>Deleted</span>
                            </span>
                            @else
                            <span class="px-3 py-1 {{ $package->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300' }} text-xs font-medium rounded-full flex items-center space-x-1">
                                <span class="w-2 h-2 rounded-full {{ $package->is_active ? 'bg-green-500 animate-pulse' : 'bg-gray-500' }}"></span>
                                <span>{{ $package->is_active ? 'Active' : 'Inactive' }}</span>
                            </span>
                            @endif
                            
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs font-medium rounded-full flex items-center space-x-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <span>{{ $package->items->count() }} items</span>
                            </span>
                        </div>
                        
                        @unless($package->trashed())
                        <button wire:click="toggleActive({{ $package->id }})"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all"
                            title="{{ $package->is_active ? 'Deactivate' : 'Activate' }}">
                            @if($package->is_active)
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                            </svg>
                            @endif
                        </button>
                        @endunless
                    </div>

                    {{-- Content --}}
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                        {{ $package->name }}
                    </h3>
                    
                    @if($package->description)
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">{{ $package->description }}</p>
                    @endif

                    {{-- Price Information Card --}}
                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 mb-4">
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Base Price:</span>
                                <span class="font-medium text-gray-900 dark:text-white">${{ number_format($package->base_price, 2) }}</span>
                            </div>
                            
                            @if($package->discount_type)
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 dark:text-gray-400">Discount:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">
                                    @if($package->discount_type === 'fixed')
                                        -${{ number_format($package->discount_value, 2) }}
                                    @else
                                        -{{ number_format($package->discount_value, 1) }}%
                                    @endif
                                </span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-center text-base font-bold pt-2 border-t border-gray-200 dark:border-gray-600">
                                <span class="text-gray-700 dark:text-gray-300">Final Price:</span>
                                <span class="text-2xl text-purple-600 dark:text-purple-400">${{ number_format($package->final_price, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Items Summary with Icons --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        @php
                            $medCount = $package->items->whereIn('item_type', ['standard_medication', 'custom_medication'])->count();
                            $serviceCount = $package->items->where('item_type', 'service')->count();
                            $hasBed = $package->items->where('item_type', 'bed')->isNotEmpty();
                        @endphp
                        
                        @if($medCount > 0)
                        <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $medCount }} Medication{{ $medCount > 1 ? 's' : '' }}
                        </span>
                        @endif
                        
                        @if($serviceCount > 0)
                        <span class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $serviceCount }} Service{{ $serviceCount > 1 ? 's' : '' }}
                        </span>
                        @endif
                        
                        @if($hasBed)
                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 text-xs rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            Bed Included
                        </span>
                        @endif
                    </div>

                    {{-- Footer with Creator and Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                            <div class="w-6 h-6 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <span class="text-purple-600 dark:text-purple-400 font-bold text-sm">
                                    {{ substr($package->creator?->name ?? 'N/A', 0, 1) }}
                                </span>
                            </div>
                            <div>
                                <div class="font-medium">{{ $package->creator?->name ?? 'N/A' }}</div>
                                <div>{{ $package->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-1">
                            {{-- View Button --}}
                            <button wire:click="viewPackage({{ $package->id }})" 
                                class="p-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                title="View Details">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            @if($package->trashed())
                                <button wire:click="confirmRestore({{ $package->id }})"
                                    class="p-2 text-green-600 hover:text-green-700 dark:text-green-400 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                    title="Restore">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                </button>
                                <button wire:click="confirmForceDelete({{ $package->id }})"
                                    class="p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete Permanently">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @else
                                <a href="{{ route('rehab.packages.edit', $package->id) }}" wire:navigate
                                    class="p-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button wire:click="confirmDelete({{ $package->id }})"
                                    class="p-2 text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                    <div class="w-24 h-24 mx-auto bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No packages found</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md mx-auto">
                        {{ $search ? 'No packages match your search criteria. Try different keywords.' : 'Get started by creating your first rehabilitation package.' }}
                    </p>
                    @if($search)
                        <button wire:click="$set('search', '')"
                            class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors shadow-lg hover:shadow-xl">
                            Clear Search
                        </button>
                    @else
                        <a href="{{ route('rehab.packages.create') }}" wire:navigate
                            class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Package
                        </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($packages->hasPages())
        <div class="mt-8">
            {{ $packages->links() }}
        </div>
        @endif

        {{-- View Package Details Modal --}}
        @if($viewingPackage)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeViewModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white">Package Details</h3>
                            <button wire:click="closeViewModal" class="text-white/80 hover:text-white">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    @if($selectedPackage)
                    <div class="p-6 max-h-[70vh] overflow-y-auto">
                        {{-- Basic Info --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Basic Information</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Package Name</span>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $selectedPackage['name'] }}</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Status</span>
                                    <span class="inline-flex items-center px-2 py-1 mt-1 {{ $selectedPackage['is_active'] ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }} text-xs font-medium rounded-full">
                                        {{ $selectedPackage['is_active'] ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                @if($selectedPackage['description'])
                                <div class="col-span-2 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Description</span>
                                    <span class="text-gray-900 dark:text-white">{{ $selectedPackage['description'] }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Pricing --}}
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pricing Details</h4>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Base Price</span>
                                    <span class="text-xl font-bold text-gray-900 dark:text-white">${{ number_format($selectedPackage['base_price'], 2) }}</span>
                                </div>
                                @if($selectedPackage['discount_type'])
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                    <span class="text-sm text-gray-500 dark:text-gray-400 block">Discount</span>
                                    <span class="text-xl font-bold text-green-600 dark:text-green-400">
                                        @if($selectedPackage['discount_type'] === 'fixed')
                                            -${{ number_format($selectedPackage['discount_value'], 2) }}
                                        @else
                                            -{{ number_format($selectedPackage['discount_value'], 1) }}%
                                        @endif
                                    </span>
                                </div>
                                @endif
                                <div class="bg-purple-50 dark:bg-purple-900/30 p-3 rounded-lg">
                                    <span class="text-sm text-purple-600 dark:text-purple-400 block">Final Price</span>
                                    <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">${{ number_format($selectedPackage['final_price'], 2) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Items by Category --}}
                        <div class="space-y-6">
                            {{-- Standard Medications --}}
                            @if(count($selectedPackage['standard_medications']) > 0)
                            <div>
                                <h5 class="font-medium text-purple-600 dark:text-purple-400 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Standard Medications ({{ count($selectedPackage['standard_medications']) }})
                                </h5>
                                <div class="space-y-3">
                                    @foreach($selectedPackage['standard_medications'] as $med)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h6 class="font-medium text-gray-900 dark:text-white">{{ $med['item_name'] }}</h6>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2 text-sm">
                                                    <div><span class="text-gray-500">Dosage:</span> {{ $med['dosage'] }}</div>
                                                    <div><span class="text-gray-500">Frequency:</span> {{ $med['frequency'] }}</div>
                                                    <div><span class="text-gray-500">Duration:</span> {{ $med['duration'] }}</div>
                                                    <div><span class="text-gray-500">Quantity:</span> {{ $med['quantity'] }}</div>
                                                </div>
                                                @if($med['instructions'])
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="text-gray-500">Instructions:</span> {{ $med['instructions'] }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Custom Medications --}}
                            @if(count($selectedPackage['custom_medications']) > 0)
                            <div>
                                <h5 class="font-medium text-green-600 dark:text-green-400 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Custom Medications ({{ count($selectedPackage['custom_medications']) }})
                                </h5>
                                <div class="space-y-3">
                                    @foreach($selectedPackage['custom_medications'] as $med)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h6 class="font-medium text-gray-900 dark:text-white">{{ $med['item_name'] }}</h6>
                                                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-2 text-sm">
                                                    <div><span class="text-gray-500">Dosage:</span> {{ $med['dosage'] }}</div>
                                                    <div><span class="text-gray-500">Frequency:</span> {{ $med['frequency'] }}</div>
                                                    <div><span class="text-gray-500">Duration:</span> {{ $med['duration'] }}</div>
                                                    <div><span class="text-gray-500">Quantity:</span> {{ $med['quantity'] }}</div>
                                                </div>
                                                @if($med['instructions'])
                                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="text-gray-500">Instructions:</span> {{ $med['instructions'] }}
                                                </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Services --}}
                            @if(count($selectedPackage['services']) > 0)
                            <div>
                                <h5 class="font-medium text-blue-600 dark:text-blue-400 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    Services ({{ count($selectedPackage['services']) }})
                                </h5>
                                <div class="space-y-3">
                                    @foreach($selectedPackage['services'] as $service)
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                        <h6 class="font-medium text-gray-900 dark:text-white">{{ $service['item_name'] }}</h6>
                                        @if($service['instructions'])
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $service['instructions'] }}</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- Bed --}}
                            @if($selectedPackage['bed'])
                            <div>
                                <h5 class="font-medium text-yellow-600 dark:text-yellow-400 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Bed Accommodation
                                </h5>
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                                    <div class="grid grid-cols-1 gap-2">
                                        <div><span class="text-gray-500">Duration:</span> {{ $selectedPackage['bed']['bed_duration_days'] }} days</div>
                                        @if($selectedPackage['bed']['instructions'])
                                        <div><span class="text-gray-500">Instructions:</span> {{ $selectedPackage['bed']['instructions'] }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>

                        {{-- Metadata --}}
                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <div>Created by: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $selectedPackage['creator'] }}</span></div>
                                <div>Created at: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $selectedPackage['created_at'] }}</span></div>
                                @if($selectedPackage['updated_at'])
                                <div>Last updated: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $selectedPackage['updated_at'] }}</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex justify-end">
                        <button wire:click="closeViewModal"
                            class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Delete Confirmation Modal --}}
        @if($confirmingDelete)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Delete Package
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to delete this package? This action can be undone later from the trash.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="delete"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button type="button" wire:click="cancelAction"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Restore Confirmation Modal --}}
        @if($confirmingRestore)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Restore Package
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to restore this package? It will become active again.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="restore"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Restore
                        </button>
                        <button type="button" wire:click="cancelAction"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Force Delete Confirmation Modal --}}
        @if($confirmingForceDelete)
        <div class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Permanently Delete Package
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Are you sure you want to permanently delete this package? This action cannot be undone.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" wire:click="forceDelete"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Permanently Delete
                        </button>
                        <button type="button" wire:click="cancelAction"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif
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
        $wire.on('closeAlertAfterDelay', () => {
            setTimeout(() => {
                $wire.closeAlert();
            }, 3000);
        });
    </script>
    @endscript
</div>