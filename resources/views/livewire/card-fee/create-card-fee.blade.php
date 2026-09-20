
<flux:modal name="create-card-fee" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Create Card Fee</flux:heading>
            <flux:text class="mt-2">Add a new card fee record</flux:text>
        </div>

        <flux:input label="Date" type="date" wire:model="date" />
        <flux:input label="Amount" type="number" placeholder="Card fee amount" wire:model="amount" />
        <flux:checkbox label="Is Active" value="1" checked wire:model="is_active" />
        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary"  wire:click="save">Create</flux:button>
        </div>
    </div>
</flux:modal>