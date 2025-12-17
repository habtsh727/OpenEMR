<div class="p-6 bg-gray-50 min-h-screen">

    {{-- Header --}}
    @include('livewire.pharmacy.shared.header')

    {{-- Tabs --}}
    <div class="flex gap-2 border-b mb-4">
        @foreach(['categories','units','routes','frequencies'] as $t)
            <button
                wire:click="$set('tab','{{ $t }}')"
                class="px-4 py-2 text-sm border-b-2
                    {{ $tab === $t ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500' }}">
                {{ ucfirst($t) }}
            </button>
        @endforeach
    </div>

    {{-- Active Tab --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        @switch($tab)
            @case('categories') <livewire:pharmacy.category.index /> @break
            @case('units') <livewire:pharmacy.unit.index /> @break
            @case('routes') <livewire:pharmacy.route.index /> @break
            @case('frequencies') <livewire:pharmacy.frequency.index /> @break
        @endswitch
    </div>

</div>
