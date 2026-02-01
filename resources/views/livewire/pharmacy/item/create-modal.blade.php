<div
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 dark:bg-black/70 backdrop-blur-sm transition-opacity"
>

    <div
        @click.away="open = false"
        class="w-full max-w-xl bg-white dark:bg-gray-800 rounded-xl shadow-lg dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700"
    >

        <!-- Title -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                New Medicine
            </h2>
            <button @click="open = false" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Medicine Name</label>
                <input wire:model="name"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="Enter medicine name"
                />
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Generic Name</label>
                <input wire:model="generic_name"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                    placeholder="Enter generic name"
                />
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Strength</label>
                <input wire:model="strength"
                    placeholder="500 mg"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                />
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Category</label>
                <select wire:model="category_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 appearance-none"
                >
                    <option value="" class="text-gray-400 dark:text-gray-500">Select Category</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" class="text-gray-900 dark:text-gray-100">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Unit</label>
                <select wire:model="unit_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 appearance-none"
                >
                    <option value="" class="text-gray-400 dark:text-gray-500">Select Unit</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}" class="text-gray-900 dark:text-gray-100">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm mb-1 font-medium text-gray-700 dark:text-gray-300">Route</label>
                <select wire:model="route_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 appearance-none"
                >
                    <option value="" class="text-gray-400 dark:text-gray-500">Select Route</option>
                    @foreach($routes as $r)
                        <option value="{{ $r->id }}" class="text-gray-900 dark:text-gray-100">{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-span-2 flex items-center space-x-3 p-2 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                <input type="checkbox" 
                       wire:model="is_prescription_required"
                       class="w-4 h-4 text-indigo-600 dark:text-indigo-500 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400 bg-white dark:bg-gray-800">
                <span class="text-sm text-gray-700 dark:text-gray-300 font-medium">Prescription Required</span>
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
            <button
                @click="open = false"
                class="px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200"
            >
                Cancel
            </button>

            <button 
                wire:click="save" 
                @click="open = false"
                class="px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-500 rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors duration-200 flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Save Medicine
            </button>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>