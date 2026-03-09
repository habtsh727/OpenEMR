<x-layouts.app.sidebar>
    <flux:main>
        {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script> --}}
        <!-- Add before </head> -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>
<!-- Remove this line if it exists -->
<!-- <script src="//unpkg.com/alpinejs" defer></script> -->

<!-- Add this CDN instead -->
{{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
{{-- <script src="//unpkg.com/alpinejs" defer></script> --}}