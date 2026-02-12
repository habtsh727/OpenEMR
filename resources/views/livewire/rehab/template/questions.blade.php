<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('rehab.templates.index') }}" wire:navigate 
                    class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $template->title }}
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage questions for this assessment template
                    </p>
                </div>
            </div>
            
            <button
                wire:click="createQuestion"
                class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Question
            </button>
        </div>
    </div>

    <!-- Question Form -->
    @if($showForm)
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-teal-200 dark:border-teal-800 p-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                    <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $editingQuestionId ? 'Edit Question' : 'Add New Question' }}
                </h2>
            </div>

            <form wire:submit.prevent="saveQuestion" class="space-y-6">
                <!-- Question Text -->
                <div>
                    <label for="question" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Question Text <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="question"
                        wire:model="question"
                        rows="2"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Enter your question..."
                    ></textarea>
                    @error('question')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Question Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Question Type <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="type"
                            wire:model.live="type"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                        >
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Order -->
                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Display Order
                        </label>
                        <input
                            type="number"
                            id="order"
                            wire:model="order"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white"
                            placeholder="0"
                            min="0"
                        >
                        @error('order')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Options (for select/checkbox) -->
                @if(in_array($type, ['select', 'checkbox']))
                    <div>
                        <label for="options" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Options <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="options"
                            wire:model="options"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 dark:bg-gray-700 dark:text-white font-mono text-sm"
                            placeholder="Enter one option per line&#10;e.g.:&#10;Option 1&#10;Option 2&#10;Option 3"
                        ></textarea>
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Each line will become an option in the {{ $type === 'select' ? 'dropdown' : 'checkbox list' }}.
                        </p>
                        @error('options')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                @endif

                <!-- Required Toggle -->
                <div class="flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            wire:model="is_required"
                            class="sr-only peer"
                        >
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600"></div>
                        <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Required Field
                        </span>
                    </label>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button
                        type="button"
                        wire:click="cancelForm"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                    >
                        <svg wire:loading wire:target="saveQuestion" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span wire:loading.remove wire:target="saveQuestion">
                            {{ $editingQuestionId ? 'Update Question' : 'Add Question' }}
                        </span>
                        <span wire:loading wire:target="saveQuestion">
                            {{ $editingQuestionId ? 'Updating...' : 'Adding...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    @endif

    <!-- Questions List -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-teal-600 dark:text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Questions ({{ count($questions) }})
            </h3>
        </div>

        <div class="p-6">
            @forelse($questions as $index => $question)
                <div wire:key="question-{{ $question['id'] }}" 
                    class="flex items-start gap-4 p-4 {{ !$loop->last ? 'border-b border-gray-100 dark:border-gray-700' : '' }}">
                    
                    <!-- Order Number -->
                    <div class="flex flex-col items-center gap-1">
                        <span class="flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-semibold text-sm">
                            {{ $question['order'] ?? $loop->iteration }}
                        </span>
                        
                        <!-- Move Up/Down -->
                        <div class="flex flex-col mt-1">
                            @if(!$loop->first)
                                <button
                                    wire:click="moveUp({{ $question['id'] }})"
                                    class="p-1 text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                    title="Move Up"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            @endif
                            @if(!$loop->last)
                                <button
                                    wire:click="moveDown({{ $question['id'] }})"
                                    class="p-1 text-gray-500 hover:text-teal-600 dark:text-gray-400 dark:hover:text-teal-500 hover:bg-gray-100 dark:hover:bg-gray-700 rounded"
                                    title="Move Down"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Question Content -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $typeOptions[$question['type']] ?? ucfirst($question['type']) }}
                                    </span>
                                    
                                    @if($question['is_required'])
                                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                            Required
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-base font-medium text-gray-900 dark:text-white">
                                    {{ $question['question'] }}
                                </p>
                                
                                @if(!empty($question['options']))
                                    @php
                                        $options = is_array($question['options']) 
                                            ? $question['options'] 
                                            : json_decode($question['options'], true);
                                    @endphp
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach($options as $option)
                                            <span class="px-2 py-1 text-xs bg-gray-50 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400 rounded border border-gray-200 dark:border-gray-600">
                                                {{ $option }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-1 ml-4">
                                <button
                                    wire:click="editQuestion({{ $question['id'] }})"
                                    class="p-2 text-blue-600 hover:text-blue-900 dark:text-blue-500 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                    title="Edit Question"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </button>
                                <button
                                    wire:click="deleteQuestion({{ $question['id'] }})"
                                    wire:confirm="Are you sure you want to delete this question?"
                                    class="p-2 text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                    title="Delete Question"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        No questions yet
                    </h3>
                    <p class="text-gray-500 dark:text-gray-400">
                        Click "Add Question" to start building your assessment template.
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Back Button -->
    <div class="flex justify-start">
        <a href="{{ route('rehab.templates.index') }}" wire:navigate
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Templates
        </a>
    </div>
</div>