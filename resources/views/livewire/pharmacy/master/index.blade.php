<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100">
    <div class="max-w-7xl mx-auto p-6">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">
                        Pharmacy Masters
                    </h1>
                    <p class="text-slate-600 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Configure medicines and prescription settings
                    </p>
                </div>
                
                <!-- Optional: Quick Stats -->
                <div class="hidden md:flex gap-4">
                    <div class="bg-white rounded-lg shadow-sm px-4 py-3 border border-slate-200">
                        <div class="text-xs text-slate-500 uppercase tracking-wide">Active Items</div>
                        <div class="text-2xl font-bold text-blue-600">{{ $stats['items'] ?? 0 }}</div>
                    </div>
                    <div class="bg-white rounded-lg shadow-sm px-4 py-3 border border-slate-200">
                        <div class="text-xs text-slate-500 uppercase tracking-wide">Categories</div>
                        <div class="text-2xl font-bold text-emerald-600">{{ $stats['categories'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modern Tab Navigation -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-6 overflow-hidden">
            <div class="flex overflow-x-auto scrollbar-hide">
                @php
                    $tabs = [
                        'items' => ['label' => 'Medicines', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />'],
                        'categories' => ['label' => 'Categories', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />'],
                        'units' => ['label' => 'Units', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />'],
                        'routes' => ['label' => 'Routes', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />'],
                        'frequencies' => ['label' => 'Frequencies', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />'],
                    ];
                @endphp

                @foreach ($tabs as $key => $data)
                    <button wire:click="setTab('{{ $key }}')"
                        class="flex-1 min-w-max px-6 py-4 text-sm font-medium transition-all duration-200 relative group
                            {{ $tab === $key
                                ? 'text-blue-600 bg-blue-50'
                                : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                        
                        <div class="flex items-center justify-center gap-2">
                            <!-- Icon -->
                            <svg class="w-5 h-5 {{ $tab === $key ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }}" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $data['icon'] !!}
                            </svg>
                            
                            <!-- Label -->
                            <span>{{ $data['label'] }}</span>
                        </div>

                        <!-- Active Indicator -->
                        @if ($tab === $key)
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-600 rounded-t-full"></div>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Tab Content Area -->
        <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6 min-h-[500px]">
            <div class="animate-fadeIn">
                @switch($tab)
                    @case('items')
                        <livewire:pharmacy.item.index />
                    @break

                    @case('categories')
                        <livewire:pharmacy.category.index />
                    @break

                    @case('units')
                        <livewire:pharmacy.unit.index />
                    @break

                    @case('routes')
                        <livewire:pharmacy.route.index />
                    @break

                    @case('frequencies')
                        <livewire:pharmacy.frequency.index />
                    @break
                @endswitch
            </div>
        </div>

    </div>
<style>
    /* Smooth fade-in animation for tab content */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    /* Hide scrollbar for tab navigation */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
</div>

