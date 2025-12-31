<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-50 dark:from-slate-950 dark:via-slate-900/50 dark:to-slate-950">
    {{-- Header Section --}}
    <div class="border-b border-slate-200/50 dark:border-slate-800/50 bg-white/90 dark:bg-slate-900/90 backdrop-blur-xl sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-2">
                        {{-- Enhanced icon with better gradient and shadow --}}
                        <div class="p-3 bg-gradient-to-br from-blue-600 via-blue-500 to-cyan-500 rounded-xl shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">Role Management</h1>
                        </div>
                    </div>
                    {{-- Updated description styling --}}
                    <p class="text-slate-600 dark:text-slate-400 text-sm ml-16 font-medium">Manage roles and permissions for your application</p>
                </div>
                <div class="flex items-center gap-3">
                    <flux:badge color="blue" inset="top bottom" class="text-base shadow-lg shadow-blue-500/20">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                        {{ count($roles) }} {{ count($roles) === 1 ? 'Role' : 'Roles' }}
                    </flux:badge>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Alerts --}}
        <div class="space-y-4 mb-8">
            @if (session()->has('success'))
                {{-- Enhanced success alert with better styling --}}
                <div class="flex items-center gap-4 px-5 py-4 bg-gradient-to-r from-green-50/80 via-emerald-50/80 to-teal-50/80 dark:from-green-950/40 dark:via-emerald-950/40 dark:to-teal-950/40 border border-green-200/60 dark:border-green-800/40 rounded-lg backdrop-blur-xl shadow-sm">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-green-800 dark:text-green-200 font-semibold">{{ session('success') }}</span>
                </div>
            @endif
            @if (session()->has('error'))
                {{-- Enhanced error alert with better styling --}}
                <div class="flex items-center gap-4 px-5 py-4 bg-gradient-to-r from-red-50/80 via-rose-50/80 to-pink-50/80 dark:from-red-950/40 dark:via-rose-950/40 dark:to-pink-950/40 border border-red-200/60 dark:border-red-800/40 rounded-lg backdrop-blur-xl shadow-sm">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-red-800 dark:text-red-200 font-semibold">{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Form Section --}}
            <div class="lg:col-span-1">
                <div class="sticky top-28">
                    {{-- Enhanced form card with stronger shadow and gradient border --}}
                    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20 hover:shadow-xl hover:shadow-blue-500/15 transition-all duration-300 p-6">
                        {{-- Enhanced form header with better styling --}}
                        <div class="mb-6 pb-4 border-b border-slate-200 dark:border-slate-800">
                            <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                                {{-- Improved icon styling --}}
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white font-semibold shadow-lg shadow-blue-500/30">
                                    {{ $editMode ? '✎' : '✚' }}
                                </span>
                                {{ $editMode ? 'Edit Role' : 'Create New Role' }}
                            </h2>
                        </div>

                        <div class="space-y-5">
                            {{-- Role Name Input --}}
                            <div>
                                <flux:input 
                                    label="Role Name" 
                                    wire:model.defer="name"
                                    placeholder="e.g., Administrator"
                                    description="Choose a unique role name"
                                />
                            </div>

                            {{-- Improved permissions section with better styling --}}
                            <div>
                                <div class="mb-3">
                                    {{-- Enhanced label with better counter styling --}}
                                    <label class="block text-sm font-bold text-slate-900 dark:text-white">
                                        Permissions
                                        <span class="ml-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-gradient-to-br from-blue-600 to-cyan-500 text-white text-xs font-bold shadow-lg shadow-blue-500/30">
                                            {{ count($selectedPermissions) }}
                                        </span>
                                    </label>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">Select permissions to assign to this role</p>
                                </div>
                                {{-- Enhanced checkbox container styling --}}
                                <div class="space-y-2 max-h-72 overflow-y-auto pr-2 bg-slate-50/50 dark:bg-slate-800/30 rounded-lg p-3">
                                    @forelse($permissions as $permission)
                                        <div class="flex items-center px-3 py-2.5 rounded-lg hover:bg-white dark:hover:bg-slate-800 transition-all duration-200 cursor-pointer group">
                                            <flux:checkbox 
                                                wire:model="selectedPermissions"
                                                value="{{ $permission->name }}"
                                                label="{{ $permission->name }}"
                                                id="permission-{{ $permission->id }}"
                                            />
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500 dark:text-slate-400 italic py-4 text-center">No permissions available</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Enhanced action buttons with better spacing and styling --}}
                            <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex gap-3">
                                <flux:button 
                                    wire:click="{{ $editMode ? 'updateRole' : 'createRole' }}"
                                    class="flex-1 font-semibold shadow-lg shadow-blue-500/30"
                                    variant="primary"
                                >
                                    {{ $editMode ? 'Update Role' : 'Create Role' }}
                                </flux:button>

                                @if($editMode)
                                    <flux:button 
                                        wire:click="resetForm"
                                        variant="primary"
                                        class="flex-1 font-semibold"
                                    >
                                        Cancel
                                    </flux:button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Roles Table Section --}}
            <div class="lg:col-span-2">
                {{-- Enhanced table card with stronger styling --}}
                <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/60 dark:border-slate-800/60 shadow-lg shadow-blue-500/10 dark:shadow-blue-900/20 overflow-hidden transition-all duration-300">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-800 bg-gradient-to-r from-blue-50/50 to-cyan-50/50 dark:from-slate-800/30 dark:to-slate-800/20">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 12a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                                </svg>
                            </div>
                            All Roles
                        </h2>
                    </div>

                    @if($roles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/60">
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Role Name</th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Permissions</th>
                                        <th class="px-6 py-4 text-right text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                                    @foreach($roles as $role)
                                        {{-- Enhanced row hover with gradient --}}
                                        <tr class="hover:bg-gradient-to-r hover:from-blue-50/70 hover:to-cyan-50/70 dark:hover:from-blue-900/20 dark:hover:to-cyan-900/20 transition-all duration-200">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-2.5 h-2.5 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 shadow-lg shadow-blue-500/50"></div>
                                                    <span class="font-semibold text-slate-900 dark:text-white">{{ $role->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @forelse($role->permissions as $permission)
                                                        {{-- Enhanced badge styling --}}
                                                        <flux:badge color="slate" size="sm" class="bg-gradient-to-r from-slate-100 to-slate-100 dark:from-slate-800 dark:to-slate-800 font-medium shadow-sm">
                                                            {{ $permission->name }}
                                                        </flux:badge>
                                                    @empty
                                                        <span class="text-xs text-slate-500 dark:text-slate-400 italic font-medium">No permissions</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center justify-end gap-2">
                                                    <flux:button 
                                                        wire:click="editRole({{ $role->id }})"
                                                        size="sm"
                                                        variant="primary"
                                                        icon="pencil"
                                                        class="font-semibold"
                                                    >
                                                        Edit
                                                    </flux:button>
                                                    <flux:button 
                                                        wire:click="deleteRole({{ $role->id }})"
                                                        size="sm"
                                                        variant="danger"
                                                        icon="trash"
                                                        class="font-semibold"
                                                        onclick="confirm('Are you sure you want to delete this role?') || event.stopImmediatePropagation()"
                                                    >
                                                        Delete
                                                    </flux:button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Enhanced empty state with better visual hierarchy --}}
                        <div class="text-center py-20 px-6">
                            {{-- Improved empty state icon and styling --}}
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/40 dark:to-cyan-900/40 mb-5 shadow-lg shadow-blue-500/20">
                                <svg class="w-10 h-10 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <p class="text-slate-900 dark:text-white text-xl font-bold">No roles created yet</p>
                            <p class="text-slate-600 dark:text-slate-400 text-sm mt-2 font-medium">Create your first role using the form on the left to get started</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
