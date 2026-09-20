<form wire:submit.prevent="save" class="flex gap-2 mb-4">
    {{ $slot }}
    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
        {{ $editingId ? 'Update' : 'Save' }}
    </button>
</form>
