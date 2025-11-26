<flux:modal name="edit-service-category" class="md:w-96">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Update Service Category</flux:heading>
            <flux:text class="mt-2">This will be used during service creation</flux:text>
        </div>

        <flux:input label="Name" placeholder="Service category name" wire:model="name" />
        <flux:input label="Code" placeholder="Service category Code" wire:model="code" />
        <flux:textarea label="Description" placeholder="Service category description" wire:model="description" />
        <flux:checkbox label="Is Active" value="1" checked />
        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary" wire:click="update">Update changes</flux:button>
        </div>
    </div>
</flux:modal>
