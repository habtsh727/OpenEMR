<div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen">

    {{-- Header --}}
    @include('livewire.pharmacy.shared.header')

    {{-- Tabs --}}
    <div class="flex gap-2 border-b mb-4 border-gray-200 dark:border-gray-700">
        @foreach(['categories','units','routes','frequencies'] as $t)
            <button
                wire:click="$set('tab','{{ $t }}')"
                class="px-4 py-2 text-sm border-b-2 transition-colors duration-200
                    {{ $tab === $t 
                        ? 'border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 font-medium' 
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                {{ ucfirst($t) }}
            </button>
        @endforeach
    </div>

    {{-- Active Tab --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-4 border border-gray-100 dark:border-gray-700">
        @switch($tab)
            @case('categories') <livewire:pharmacy.category.index /> @break
            @case('units') <livewire:pharmacy.unit.index /> @break
            @case('routes') <livewire:pharmacy.route.index /> @break
            @case('frequencies') <livewire:pharmacy.frequency.index /> @break
        @endswitch
    </div>

</div>