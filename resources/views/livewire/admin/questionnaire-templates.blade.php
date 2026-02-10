<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Questionnaire Templates</h2>
                <p class="mt-1 text-sm text-gray-600">Manage rehabilitation questionnaire templates</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button 
                    wire:click="create"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Template
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
                    placeholder="Search templates..."
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
    
    <!-- Templates Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($templates->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($templates as $template)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $template->title }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $template->questions_count }} questions
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $template->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <a 
                                            href="{{ route('admin.templates.questions', $template) }}"
                                            class="text-blue-600 hover:text-blue-900"
                                        >
                                            Questions
                                        </a>
                                        <button 
                                            wire:click="edit({{ $template->id }})"
                                            class="text-indigo-600 hover:text-indigo-900"
                                        >
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="delete({{ $template->id }})"
                                            wire:confirm="Are you sure you want to delete this template? All associated questions will also be deleted."
                                            wire:loading.attr="disabled"
                                            class="text-red-600 hover:text-red-900 disabled:opacity-50"
                                        >
                                            <span wire:loading.remove wire:target="delete({{ $template->id }})">Delete</span>
                                            <span wire:loading wire:target="delete({{ $template->id }})">Deleting...</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if($templates->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $templates->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No templates found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($search)
                        Try adjusting your search
                    @else
                        Get started by creating a new questionnaire template
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
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900">
                                {{ $editingId ? 'Edit Template' : 'Create Template' }}
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
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                    <input 
                                        type="text" 
                                        wire:model="form.title"
                                        id="title"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 focus:outline-none"
                                        placeholder="Enter template title"
                                        autofocus
                                    >
                                    @error('form.title') 
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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