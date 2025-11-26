<flux:modal name="edit-service" class="md:w-126">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Edit Service</flux:heading>
            <flux:text class="mt-2">This will be used during service editing</flux:text>
        </div>

        <flux:input label="Name" placeholder="Service name" wire:model="name" />
        <flux:input label="Code" placeholder="Service Code" wire:model="code" />
        <flux:textarea label="Description" placeholder="Service description" wire:model="description" />

        <flux:select wire:model="category_id" placeholder="Choose Category...">
            @foreach ($categories as $category)
                <flux:select.option value="{{ $category->id }}">{{ $category->name }}</flux:select.option>
            @endforeach
        </flux:select>
    

        <flux:input label="Price" placeholder="Service price" wire:model="price" />
        <flux:checkbox label="Is Active" value="1" checked />
        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary" wire:click="update">Update Service</flux:button>
        </div>
    </div>
</flux:modal>
