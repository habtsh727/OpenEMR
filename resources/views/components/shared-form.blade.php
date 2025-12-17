<form wire:submit.prevent="save" class="w-full">
    <div class="flex flex-col gap-4 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col gap-4">
            {{ $slot }}
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
            <flux:button 
                type="button" 
                variant="ghost"
                wire:click="$dispatch('close-modal')"
            >
                Cancel
            </flux:button>
            
            <flux:button 
                type="submit" 
                variant="primary"
                class="min-w-[100px]"
            >
                <span wire:loading.remove wire:target="save">
                    {{ $editingId ? 'Update' : 'Save' }}
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-2">
                    <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                </span>
            </flux:button>
        </div>
    </div>
</form>