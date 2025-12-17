<div
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
>

    <div
        @click.away="open = false"
        class="w-full max-w-xl bg-white rounded-xl shadow-lg p-6"
    >

        <!-- Title -->
        <h2 class="text-lg font-semibold mb-4">
            New Medicine
        </h2>

        <!-- Form -->
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block text-sm mb-1">Medicine Name</label>
                <input wire:model="name"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-indigo-500 focus:outline-none"
                />
            </div>

            <div>
                <label class="block text-sm mb-1">Generic Name</label>
                <input wire:model="generic_name"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>

            <div>
                <label class="block text-sm mb-1">Strength</label>
                <input wire:model="strength"
                    placeholder="500 mg"
                    class="w-full px-3 py-2 border rounded-lg"
                />
            </div>

            <div>
                <label class="block text-sm mb-1">Category</label>
                <select wire:model="category_id"
                    class="w-full px-3 py-2 border rounded-lg"
                >
                    <option value="">Select</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Unit</label>
                <select wire:model="unit_id"
                    class="w-full px-3 py-2 border rounded-lg"
                >
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1">Route</label>
                <select wire:model="route_id"
                    class="w-full px-3 py-2 border rounded-lg"
                >
                    @foreach($routes as $r)
                        <option value="{{ $r->id }}">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 flex items-center space-x-2">
                <input type="checkbox" wire:model="is_prescription_required">
                <span class="text-sm">Prescription Required</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-6 flex justify-end space-x-2">
            <button
                @click="open = false"
                class="px-4 py-2 text-sm border rounded-lg"
            >
                Cancel
            </button>

           

             <flux:button variant="primary" wire:click="save" @click="open = false">
                  Save
            </flux:button>


        </div>
    </div>
</div>
