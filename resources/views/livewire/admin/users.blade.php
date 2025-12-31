<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-8xl mx-auto p-6 bg-white dark:bg-slate-900 rounded-lg shadow-lg">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">User Management</h1>
            <p class="text-slate-600 dark:text-slate-400">Create, edit, and manage system users and their roles</p>
        </div>

        {{-- Success Message --}}
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <p class="text-green-800 dark:text-green-200 text-sm font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Form Section --}}
            <div class="lg:col-span-1 ">
                <div>
                    <flux:heading size="lg" level="2" class="mb-6">
                        {{ $editMode ? 'Edit User' : 'Add New User' }}
                    </flux:heading>

                    <div class="space-y-5">
                        {{-- Name Field --}}
                        <div>
                            <flux:input 
                                label="Full Name" 
                                wire:model.defer="name"
                                placeholder="Enter full name"
                                class="w-full"
                            />
                        </div>

                        {{-- Email Field --}}
                        <div>
                            <flux:input 
                                label="Email Address" 
                                wire:model.defer="email"
                                type="email"
                                placeholder="user@example.com"
                                class="w-full"
                            />
                        </div>

                        {{-- Password Field --}}
                        <div>
                            <flux:input 
                                label="Password" 
                                wire:model.defer="password"
                                type="password"
                                placeholder="{{ $editMode ? 'Leave blank to keep current' : 'Enter password' }}"
                                class="w-full"
                                description="{{ $editMode ? 'Leave blank to keep the current password' : '' }}"
                            />
                        </div>

                        {{-- Active Status Toggle --}}
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                            <flux:checkbox 
                                label="Active User"
                                wire:model="is_active"
                                description="Allow this user to access the system"
                            />
                        </div>

                        {{-- Roles Section --}}
                        <div class="pt-3 border-t border-slate-200 dark:border-slate-700">
                            <label class="block text-sm font-semibold text-slate-900 dark:text-white mb-3">
                                Assign Roles
                            </label>
                            <div class="space-y-3 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-lg">
                                @forelse($roles as $role)
                                    <flux:checkbox 
                                        label="{{ $role->name }}"
                                        wire:model="selectedRoles"
                                        value="{{ $role->name }}"
                                    />
                                @empty
                                    <p class="text-sm text-slate-500 dark:text-slate-400">No roles available</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex gap-3 pt-6 border-t border-slate-200 dark:border-slate-700">
                            <flux:button 
                                wire:click="{{ $editMode ? 'updateUser' : 'createUser' }}"
                                variant="primary"
                                class="flex-1"
                            >
                                {{ $editMode ? 'Update User' : 'Create User' }}
                            </flux:button>

                            @if($editMode)
                                <flux:button 
                                    wire:click="resetForm"
                                    variant="ghost"
                                    class="flex-1"
                                >
                                    Cancel
                                </flux:button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Table Section --}}
            <div class="lg:col-span-2">
                <div class="shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <flux:heading size="lg" level="2">
                            Users
                        </flux:heading>
                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 text-sm font-semibold rounded-full">
                            {{ count($users) }} Users
                        </span>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-slate-700">
                                    <th class="text-left py-4 px-4 font-semibold text-slate-600 dark:text-slate-400">Name</th>
                                    <th class="text-left py-4 px-4 font-semibold text-slate-600 dark:text-slate-400">Email</th>
                                    <th class="text-left py-4 px-4 font-semibold text-slate-600 dark:text-slate-400">Roles</th>
                                    <th class="text-left py-4 px-4 font-semibold text-slate-600 dark:text-slate-400">Status</th>
                                    <th class="text-right py-4 px-4 font-semibold text-slate-600 dark:text-slate-400">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($users as $user)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-150">
                                        {{-- Name --}}
                                        <td class="py-4 px-4">
                                            <p class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</p>
                                        </td>

                                        {{-- Email --}}
                                        <td class="py-4 px-4">
                                            <p class="text-slate-600 dark:text-slate-400 text-xs">{{ $user->email }}</p>
                                        </td>

                                        {{-- Roles --}}
                                        <td class="py-4 px-4">
                                            <div class="flex flex-wrap gap-2">
                                                @forelse($user->roles as $role)
                                                    <flux:badge size="sm" color="slate">
                                                        {{ $role->name }}
                                                    </flux:badge>
                                                @empty
                                                    <span class="text-xs text-slate-500 dark:text-slate-400">No roles</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-4 px-4">
                                            @if($user->is_active)
                                                <flux:badge color="green" size="sm">Active</flux:badge>
                                            @else
                                                <flux:badge color="red" size="sm">Inactive</flux:badge>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <flux:button 
                                                    wire:click="editUser({{ $user->id }})"
                                                    size="sm"
                                                    variant="ghost"
                                                >
                                                    Edit
                                                </flux:button>

                                                <flux:button 
                                                    wire:click="toggleActive({{ $user->id }})"
                                                    size="sm"
                                                    variant="primary"
                                                >
                                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                                </flux:button>

                                                <flux:button 
                                                    wire:click="deleteUser({{ $user->id }})"
                                                    size="sm"
                                                    variant="danger"
                                                    onclick="confirm('Are you sure you want to delete this user? This action cannot be undone.') || event.stopImmediatePropagation()"
                                                >
                                                    Delete
                                                </flux:button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-12 px-4 text-center">
                                            <p class="text-slate-500 dark:text-slate-400 font-medium">No users found</p>
                                            <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Create your first user using the form on the left</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
