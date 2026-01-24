<div>
    <div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{-- Header with Stats --}}
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Examination
                            Templates</h1>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Manage examination systems and findings used
                            during patient consultations</p>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="flex items-center space-x-4">
                        <div
                            class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl px-4 py-3">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $templates->total() }}
                            </div>
                            <div class="text-sm font-medium text-blue-500 dark:text-blue-300">Total Templates</div>
                        </div>
                        @php
                        $activeCount = $templates->where('active', true)->count();
                        $systemCount = $templates->unique('system')->count();
                        @endphp
                        <div
                            class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl px-4 py-3">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $activeCount }}</div>
                            <div class="text-sm font-medium text-green-500 dark:text-green-300">Active</div>
                        </div>
                        <div
                            class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-xl px-4 py-3">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $systemCount }}
                            </div>
                            <div class="text-sm font-medium text-purple-500 dark:text-purple-300">Systems</div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons and Filters --}}
                <div class="mb-6">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        {{-- Left Side: Search and System Filter --}}
        <div class="flex-1">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- Search --}}
                <div class="relative flex-1 sm:flex-initial">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" wire:model.live.debounce.300ms="search" 
                           placeholder="Search templates..." 
                           class="pl-10 pr-4 py-2.5 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                </div>

                {{-- System Filter --}}
                
            </div>
        </div>

        {{-- Right Side: Filters and Add Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                {{-- Field Type Filter --}}
               
                {{-- Status Filter --}}
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Status:</span>
                    <div class="flex bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
                        <button wire:click="$set('activeFilter', null)" 
                                class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ !$activeFilter ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                            All
                        </button>
                        <button wire:click="$set('activeFilter', true)" 
                                class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ $activeFilter === true ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                            Active
                        </button>
                        <button wire:click="$set('activeFilter', false)" 
                                class="px-3 py-1.5 text-sm rounded-md transition-all duration-200 {{ $activeFilter === false ? 'bg-white dark:bg-gray-600 shadow-sm' : 'text-gray-600 dark:text-gray-400' }}">
                            Inactive
                        </button>
                    </div>
                </div>
            </div>

            {{-- Add Template Button --}}
            <div class="sm:self-center">
                <button wire:click="$toggle('showForm')" 
                        class="w-full sm:w-auto flex items-center justify-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Template</span>
                </button>
            </div>
        </div>
    </div>
</div>
            </div>

            {{-- Success/Error Messages --}}
            @if (session()->has('success'))
            <div class="mb-6 animate-fade-in">
                <div
                    class="flex items-center p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-800 rounded-xl shadow-sm">
                    <svg class="w-5 h-5 text-green-500 dark:text-green-400 mr-3" fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-800 dark:text-green-300 font-medium">{{ session('success') }}</span>
                </div>
            </div>
            @endif

            {{-- Create/Edit Form Card --}}
            @if($showForm || $isEditing)
            <div class="mb-8 animate-slide-down">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                                    {{ $isEditing ? '✏️ Edit Examination Template' : '➕ Create New Template' }}
                                </h2>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $isEditing ? 'Update the examination template' : 'Add a new examination finding
                                    for doctors' }}
                                </p>
                            </div>
                            <button wire:click="resetForm"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form wire:submit.prevent="{{ $isEditing ? 'update' : 'create' }}" class="space-y-6">
                            {{-- System Field --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    System <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(e.g., General,
                                        Respiratory, Abdomen)</span>
                                </label>
                                <input type="text" wire:model="system"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="e.g., General, Vitals, Respiratory, Neurology" autofocus>
                                @error('system')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Name Field --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Finding Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" wire:model="name"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="e.g., Temperature, Blood Pressure, Wheezing, Level of consciousness">
                                @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Field Type Selection --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Input Type <span class="text-red-500">*</span>
                                </label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="field_type" value="text" class="sr-only peer">
                                        <div
                                            class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 peer-checked:ring-2 peer-checked:ring-blue-200 dark:peer-checked:ring-blue-800 transition-all duration-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">Text</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Text input
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="field_type" value="number" class="sr-only peer">
                                        <div
                                            class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:ring-2 peer-checked:ring-green-200 dark:peer-checked:ring-green-800 transition-all duration-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">Number</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Numeric value
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="field_type" value="yes_no" class="sr-only peer">
                                        <div
                                            class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 peer-checked:ring-2 peer-checked:ring-yellow-200 dark:peer-checked:ring-yellow-800 transition-all duration-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">Yes/No</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Boolean choice
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="field_type" value="select" class="sr-only peer">
                                        <div
                                            class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-purple-500 peer-checked:bg-purple-50 dark:peer-checked:bg-purple-900/20 peer-checked:ring-2 peer-checked:ring-purple-200 dark:peer-checked:ring-purple-800 transition-all duration-200">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-purple-500 peer-checked:bg-purple-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">Select</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Dropdown
                                                        options</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('field_type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Options Field (only for select type) --}}
                            @if($field_type === 'select')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Options <span class="text-red-500">*</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">(comma separated
                                        values)</span>
                                </label>
                                <input type="text" wire:model="options"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                    placeholder="e.g., Alert, Drowsy, Unconscious or Normal, Mild, Moderate, Severe">
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Enter comma separated values for the dropdown options
                                </p>
                                @error('options')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif

                            {{-- Status Toggle --}}
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-900 dark:text-white">Template Status</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Active templates are visible to doctors during examination
                                    </p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" wire:model="active" class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                    <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $active ? 'Active' : 'Inactive' }}
                                    </span>
                                </label>
                            </div>

                            {{-- Form Actions --}}
                            <div
                                class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" wire:click="resetForm"
                                    class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="px-4 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span>{{ $isEditing ? 'Update Template' : 'Create Template' }}</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            {{-- Templates Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{-- Table Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Examination Template List</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ $templates->total() }} template(s) found across {{ $templates->unique('system')->count() }}
                        systems
                    </p>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    System
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Finding Name
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Input Type
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Created
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($templates as $template)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <div
                                                class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors duration-200">
                                                {{ $template->system }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                System
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $template->name }}
                                    </div>
                                    {{-- @if($template->field_type === 'select' && $template->options)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Options: {{ implode(', ', $template->options) }}
                                    </div>
                                    @endif --}}
                                    {{-- Replace lines 380-384 with: --}}
                                    @if($template->field_type === 'select' && $template->options)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Options:
                                        @php
                                        // Safely handle options - could be array or JSON string
                                        $options = $template->options;
                                        if (is_string($options)) {
                                        $options = json_decode($options, true);
                                        }
                                        if (is_array($options) && !empty($options)) {
                                        echo implode(', ', $options);
                                        } elseif (is_string($options)) {
                                        echo $options;
                                        } else {
                                        echo 'No options';
                                        }
                                        @endphp
                                    </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 flex items-center justify-center rounded-full mr-2
                                            {{ $template->field_type === 'text' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' : 
                                               ($template->field_type === 'number' ? 'bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400' : 
                                               ($template->field_type === 'yes_no' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : 
                                               'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400')) }}">
                                            @if($template->field_type === 'text')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            @elseif($template->field_type === 'number')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            @elseif($template->field_type === 'yes_no')
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                            </svg>
                                            @endif
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ ucfirst($template->field_type) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button wire:click="toggleStatus({{ $template->id }})"
                                        class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 
                                                {{ $template->active 
                                                    ? 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/30 dark:text-green-300 dark:hover:bg-green-900/50' 
                                                    : 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/30 dark:text-red-300 dark:hover:bg-red-900/50' }}">
                                        <span
                                            class="w-2 h-2 rounded-full mr-1.5 {{ $template->active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                        {{ $template->active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $template->created_at ? $template->created_at->format('M d, Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <button wire:click="edit({{ $template->id }})"
                                            class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 group/edit">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-500 group-hover/edit:text-blue-500"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button
                                            onclick="if(confirm('Are you sure you want to delete this examination template?')) { @this.delete({{ $template->id }}) }"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors duration-200 group/delete">
                                            <svg class="w-4 h-4 mr-1.5 text-red-500 group-hover/delete:text-red-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12">
                                    <div class="text-center">
                                        <div
                                            class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No
                                            examination templates found</h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">
                                            {{ $search || $activeFilter !== null || $fieldTypeFilter || $systemFilter ?
                                            'Try adjusting your search or filter' : 'Get started by creating your first
                                            examination template' }}
                                        </p>
                                        @if($search || $activeFilter !== null || $fieldTypeFilter || $systemFilter)
                                        <button
                                            wire:click="$set(['search' => '', 'activeFilter' => null, 'fieldTypeFilter' => null, 'systemFilter' => null])"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                            Clear filters
                                        </button>
                                        @else
                                        <button wire:click="$set('showForm', true)"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Create First Template
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($templates->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Showing <span class="font-medium">{{ $templates->firstItem() }}</span> to
                            <span class="font-medium">{{ $templates->lastItem() }}</span> of
                            <span class="font-medium">{{ $templates->total() }}</span> results
                        </div>
                        <div>
                            {{ $templates->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Add custom animations --}}
        <style>
            .animate-fade-in {
                animation: fadeIn 0.3s ease-in-out;
            }

            .animate-slide-down {
                animation: slideDown 0.3s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    </div>
</div>