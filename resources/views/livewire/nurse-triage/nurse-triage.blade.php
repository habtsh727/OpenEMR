<div class="p-6 max-w-5xl mx-auto space-y-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">

    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-1 h-8 bg-slate-800 dark:bg-white rounded-sm"></div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Triage Assessment</h1>
        </div>
        <hr class="border-t border-dashed border-sky-400 my-6" />

        <h2 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Vital Signs Collection
        </h2>

        <hr class="border-t border-dashed border-sky-400 my-6" />
    </div>


    @if (session()->has('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="saveTriage" class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <flux:input label="BP Systolic (mmHg)" type="number" wire:model.defer="bp_systolic" required />
            </div>
            <div>
                <flux:input label="BP Diastolic (mmHg)" type="number" wire:model.defer="bp_diastolic" required />
            </div>
            <div>
                <flux:input label="Temperature (°C)" type="number" step="0.1" wire:model.defer="temperature"
                    required />
            </div>
            <div>
                <flux:input label="Pulse (bpm)" type="number" wire:model.defer="pulse" required />
            </div>
            <div>
                <flux:input label="SPO2 (%)" type="number" wire:model.defer="spo2" required />
            </div>
        </div>

        <div>
            <flux:select label="Priority" wire:model.defer="priority" required>
                <option value="normal">Normal</option>
                <option value="high">High (Emergency)</option>
            </flux:select>
        </div>

        <div class="flex justify-end mt-4">
            <flux:button wire:click="save" variant="primary">
                Save Triage
            </flux:button>
        </div>
    </form>
</div>
