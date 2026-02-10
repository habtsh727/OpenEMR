<div>
    <!-- Header -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.templates') }}" class="text-blue-600 hover:text-blue-900">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $template->title }}</h2>
                        <p class="mt-1 text-sm text-gray-600">Manage questionnaire questions</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:mt-0">
                <button 
                    wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Question
                </button>
            </div>
        </div>
    </div>
    
    <!-- Search -->
    <div class="mb-6">
        <div class="max-w-md">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search questions..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none"
                >
                <div class="absolute left-3 top-2.5">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Questions List -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($questions->count() > 0)
            <div 
                x-data="{
                    dragging: false,
                    draggedItem: null,
                    
                    handleDragStart(event, questionId) {
                        this.dragging = true;
                        this.draggedItem = questionId;
                        event.dataTransfer.effectAllowed = 'move';
                        event.dataTransfer.setData('text/plain', questionId);
                    },
                    
                    handleDragOver(event) {
                        if (this.dragging) {
                            event.preventDefault();
                        }
                    },
                    
                    handleDrop(event, targetQuestionId) {
                        event.preventDefault();
                        if (this.draggedItem && this.draggedItem !== targetQuestionId) {
                            $wire.reorder([{
                                value: this.draggedItem,
                                order: targetQuestionId
                            }]);
                        }
                        this.dragging = false;
                        this.draggedItem = null;
                    },
                    
                    handleDragEnd() {
                        this.dragging = false;
                        this.draggedItem = null;
                    }
                }"
                class="divide-y divide-gray-200"
            >
                @foreach($questions as $question)
                    <div 
                        draggable="true"
                        @dragstart="handleDragStart($event, {{ $question->id }})"
                        @dragover="handleDragOver($event)"
                        @drop="handleDrop($event, {{ $question->id }})"
                        @dragend="handleDragEnd()"
                        :class="{ 'opacity-50': dragging && draggedItem === {{ $question->id }} }"
                        class="p-6 hover:bg-gray-50 cursor-move"
                    >
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-start space-x-3">
                                    <div class="text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <h3 class="text-sm font-medium text-gray-900">{{ $question->question }}</h3>
                                            @if($question->is_required)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Required
                                                </span>
                                            @endif
                                        </div>
                                        <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="capitalize">{{ $question->type }}</span>
                                            <span>•</span>
                                            <span>Order: {{ $question->order }}</span>
                                            @if(in_array($question->type, ['checkbox', 'select']) && $question->options)
                                                <span>•</span>
                                                <span>{{ count($question->options) }} options</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="ml-4 flex space-x-3">
                                <button 
                                    wire:click="edit({{ $question->id }})"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    Edit
                                </button>
                                <button 
                                    wire:click="delete({{ $question->id }})"
                                    wire:confirm="Are you sure you want to delete this question?"
                                    wire:loading.attr="disabled"
                                    class="text-red-600 hover:text-red-900 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="delete({{ $question->id }})">Delete</span>
                                    <span wire:loading wire:target="delete({{ $question->id }})">Deleting...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if($questions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $questions->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No questions found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search)
                        Try adjusting your search
                    @else
                        Get started by adding questions to this template
                    @endif
                </p>
            </div>
        @endif
    </div>
    
    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                     wire:click="$set('showModal', false)"></div>
                
                <!-- Modal panel -->
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl sm:p-6">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                {{ $editingId ? 'Edit Question' : 'Add Question' }}
                            </h3>
                            <button type="button" 
                                    wire:click="$set('showModal', false)"
                                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <form wire:submit="save">
                            <div class="space-y-6">
                                <!-- Question Text -->
                                <div>
                                    <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                                    <input 
                                        type="text" 
                                        wire:model="form.question"
                                        id="question"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                        placeholder="Enter your question"
                                        autofocus
                                    >
                                    @error('form.question') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Question Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700">Question Type</label>
                                    <select 
                                        wire:model.live="form.type"
                                        id="type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                    >
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="number">Number</option>
                                        <option value="boolean">Yes/No</option>
                                        <option value="select">Dropdown</option>
                                        <option value="checkbox">Checkbox (Multiple)</option>
                                        <option value="datetime">Date & Time</option>
                                    </select>
                                    @error('form.type') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Options for select and checkbox -->
                                @if(in_array($form->type, ['select', 'checkbox']))
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Options</label>
                                        <div class="space-y-2">
                                            @foreach($form->options as $index => $option)
                                                <div class="flex items-center space-x-2">
                                                    <input 
                                                        type="text" 
                                                        wire:model="form.options.{{ $index }}"
                                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                                        placeholder="Option {{ $index + 1 }}"
                                                    >
                                                    <button 
                                                        type="button"
                                                        wire:click="removeOption({{ $index }})"
                                                        class="text-red-600 hover:text-red-900"
                                                    >
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-2">
                                            <button 
                                                type="button"
                                                wire:click="addOption"
                                                class="inline-flex items-center text-sm text-blue-600 hover:text-blue-900"
                                            >
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add Option
                                            </button>
                                        </div>
                                        @error('form.options') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endif
                                
                                <!-- Additional Settings -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                                        <input 
                                            type="number" 
                                            wire:model="form.order"
                                            id="order"
                                            min="0"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                        >
                                        @error('form.order') 
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            wire:model="form.is_required"
                                            id="is_required"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                        >
                                        <label for="is_required" class="ml-2 block text-sm text-gray-900">
                                            Required Question
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6 flex justify-end space-x-3">
                                <button 
                                    type="button"
                                    wire:click="$set('showModal', false)"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    Cancel
                                </button>
                                <button 
                                    type="submit"
                                    wire:loading.attr="disabled"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
                                >
                                    <span wire:loading.remove wire:target="save">
                                        {{ $editingId ? 'Update' : 'Create' }}
                                    </span>
                                    <span wire:loading wire:target="save">Saving...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>