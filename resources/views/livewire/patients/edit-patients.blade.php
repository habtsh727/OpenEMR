<flux:modal name="edit-patients" class="max-w-[65rem] mx-auto">
    <div class="space-y-8">
        <div class="border-b border-gray-200 pb-4">
            <div class="flex items-center gap-3">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 shadow-lg shadow-sky-500/30">
                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="flex-1">
                    <flux:heading size="lg" class="text-gray-900">Update Patient</flux:heading>
                    <flux:text class="mt-0.5 text-sm text-gray-500">
                        Fill in the details below to update the patient information.
                    </flux:text>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-1 h-6 bg-sky-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Personal Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pl-3">
                <flux:input label="First Name" placeholder="First name" wire:model="first_name" />
                <flux:input label="Father Name" placeholder="Father name" wire:model="middle_name" />
                <flux:input label="Grandfather Name" placeholder="Grandfather name" wire:model="last_name" />
                <flux:input label="Mother Name" placeholder="Mother name" wire:model="mother_name" />
            </div>
        </div>

        <!-- Identity & Contact Section -->
        <div class="space-y-4 border-t border-slate-200 pt-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-1 h-6 bg-emerald-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Identity & Contact</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pl-3">
                <flux:input label="Date of Birth" placeholder="YYYY-MM-DD" wire:model="date_of_birth" type="date" />
                <flux:select label="Gender" wire:model="gender">
                    <flux:select.option value="">--Select--</flux:select.option>
                    <flux:select.option value="Male">Male</flux:select.option>
                    <flux:select.option value="Female">Female</flux:select.option>
                </flux:select>
                <flux:input label="Phone Number 1" placeholder="+251 (911) 111-457" wire:model="phone_number1"
                    type="tel" />
                <flux:input label="Phone Number 2" placeholder="+251 (911) 111-457" wire:model="phone_number2"
                    type="tel" />
            </div>
        </div>

        <!-- Emergency Contact Section -->
        <div class="space-y-4 border-t border-slate-200 pt-4">
            <div class="flex items-center gap-2 mb-4">
                <div class="w-1 h-6 bg-amber-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Emergency Contact</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 pl-3">
                <flux:input label="Contact Person" placeholder="Full name" wire:model="emergency_person" />
                <flux:input label="Contact Number" placeholder="+251 (911) 111-457" wire:model="emergency_contact"
                    type="tel" />

                <flux:select label="Relationship" wire:model="emergency_person_relationship" class="md:col-span-2">
                    <flux:select.option value="">--Select--</flux:select.option>
                    <flux:select.option value="Spouse">Spouse</flux:select.option>
                    <flux:select.option value="Parent">Parent</flux:select.option>
                    <flux:select.option value="Sibling">Sibling</flux:select.option>
                    <flux:select.option value="Friend">Friend</flux:select.option>
                    <flux:select.option value="Other">Other</flux:select.option>
                </flux:select>
            </div>
        </div>

        <!-- Location Information Section -->
        <div class="space-y-4 border-t border-slate-200 pt-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-1 h-6 bg-purple-500 rounded-full"></div>
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Location Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-3">
                <flux:input label="Region" placeholder="Patient region" wire:model="region" />
                <flux:input label="Zone" placeholder="Patient zone" wire:model="zone" />
                <flux:input label="Woreda" placeholder="Patient woreda" wire:model="woreda" />
            </div>
        </div>

        <!-- Action Buttons with improved layout -->
        <div class="flex gap-3 justify-end pt-6 border-t border-slate-200">
            <flux:button wire:click="$dispatch('closeModal')" variant="ghost">Cancel</flux:button>
            <flux:button type="submit" variant="primary" wire:click="update">Update</flux:button>
        </div>
    </div>
</flux:modal>
