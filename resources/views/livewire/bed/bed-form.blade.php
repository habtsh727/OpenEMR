<div>
<div>
    <form wire:submit.prevent="save">
        <div class="space-y-4">
            <!-- Bed Number -->
            <div>
                <label for="bed_number" class="block text-sm font-medium text-gray-700">
                    Bed Number *
                </label>
                <input type="text" 
                       id="bed_number"
                       wire:model="bed.bed_number"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('bed.bed_number') border-red-300 @enderror"
                       placeholder="e.g., B-001">
                @error('bed.bed_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Ward Selection -->
            <div>
                <label for="selectedWard" class="block text-sm font-medium text-gray-700">
                    Ward *
                </label>
                <select id="selectedWard" 
                        wire:model.live="selectedWard"
                        wire:change="loadRooms($event.target.value)"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('selectedWard') border-red-300 @enderror">
                    <option value="">Select a Ward</option>
                    @foreach($wards as $ward)
                        <option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->code }})</option>
                    @endforeach
                </select>
                @error('selectedWard')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Room Selection -->
            <div>
                <label for="selectedRoom" class="block text-sm font-medium text-gray-700">
                    Room *
                </label>
                <select id="selectedRoom" 
                        wire:model="selectedRoom"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('selectedRoom') border-red-300 @enderror"
                        {{ !$selectedWard ? 'disabled' : '' }}>
                    <option value="">Select a Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}">
                            {{ $room->room_number }} 
                            ({{ $room->bedClass->name }})
                            @if($room->floor) - Floor {{ $room->floor }} @endif
                        </option>
                    @endforeach
                </select>
                @error('selectedRoom')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if(!$selectedWard)
                    <p class="mt-1 text-sm text-gray-500">Please select a ward first</p>
                @endif
            </div>

            <!-- Bed Type -->
            <div>
                <label for="bed_type_id" class="block text-sm font-medium text-gray-700">
                    Bed Type *
                </label>
                <select id="bed_type_id"
                        wire:model="bed.bed_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('bed.bed_type_id') border-red-300 @enderror">
                    <option value="">Select Bed Type</option>
                    @foreach($bedTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->code }})</option>
                    @endforeach
                </select>
                @error('bed.bed_type_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">
                    Status *
                </label>
                <select id="status"
                        wire:model="bed.status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    <option value="available">Available</option>
                    <option value="occupied">Occupied</option>
                    <option value="reserved">Reserved</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="mt-6 flex justify-end space-x-3">
            <button type="button" 
                    wire:click="$parent.showFormModal = false"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Cancel
            </button>
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                {{ $editingBed ? 'Update' : 'Create' }} Bed
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('bed-saved', () => {
                @this.set('showFormModal', false);
            });
        });
    </script>
</div>
</div>
