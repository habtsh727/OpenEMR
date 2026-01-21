<div class="p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Employee Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your hospital staff and their roles</p>
        </div>
        <button wire:click="create"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2 dark:bg-blue-500 dark:hover:bg-blue-600">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            Add Employee
        </button>
    </div>

    <!-- Success Message -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-green-700 dark:text-green-300">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search and Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input wire:model.live.debounce.300ms="search" 
                       type="text" 
                       placeholder="Search by name, email, code, or role..."
                       class="pl-10 w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white dark:bg-gray-800 text-gray-900 dark:text-white dark:placeholder-gray-400">
            </div>
        </div>
        <div class="flex items-center justify-end">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                Total: <span class="font-semibold dark:text-white">{{ $employees->total() }}</span> employees
            </div>
        </div>
    </div>

    <!-- Professional Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Employee
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Role
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Details
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <!-- Employee Info -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/30 dark:to-blue-800/30 flex items-center justify-center shadow-sm">
                                        <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
                                            {{ strtoupper(substr($employee->user->name ?? '?', 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $employee->user->name ?? 'N/A' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $employee->user->email ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Role -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col gap-1">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                        @if($employee->role === 'admin') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                        @elseif($employee->role === 'doctor') bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
                                        @elseif($employee->role === 'nurse') bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-300
                                        @elseif($employee->role === 'cashier') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                        @else bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 @endif">
                                        {{ ucfirst($employee->role) }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Employee ID: {{ $employee->id }}</span>
                                </div>
                            </td>

                            <!-- Details -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded text-xs text-gray-800 dark:text-gray-200">{{ $employee->employee_code }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Added: {{ $employee->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-2 w-2 rounded-full mr-2 
                                        @if($employee->status === 'active') bg-green-500
                                        @else bg-red-500 @endif"></div>
                                    <span class="text-sm font-medium {{ $employee->status === 'active' ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                        {{ ucfirst($employee->status) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <!-- View Button -->
                                    <button onclick="alert('View feature coming soon!')"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors duration-150"
                                            title="View Profile">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    <!-- Edit Button -->
                                    <button wire:click="edit({{ $employee->id }})"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors duration-150"
                                            title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <!-- Delete Button -->
                                    <button wire:click="delete({{ $employee->id }})"
                                            wire:confirm="Are you sure you want to delete this employee?"
                                            class="p-2 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors duration-150"
                                            title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <!-- More Actions Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open"
                                                class="p-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150"
                                                title="More options">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open" 
                                             @click.away="open = false"
                                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10"
                                             style="display: none;">
                                            <div class="py-1">
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    View Profile
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Send Message
                                                </a>
                                                <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                    Change Password
                                                </a>
                                                <div class="border-t border-gray-100 dark:border-gray-700"></div>
                                                <button wire:click="delete({{ $employee->id }})"
                                                        wire:confirm="Delete this employee?"
                                                        class="block w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30">
                                                    Delete Employee
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-6A8.5 8.5 0 0012 3.5 8.5 8.5 0 003.5 12 8.5 8.5 0 0012 20.5a8.5 8.5 0 008.5-8.5z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No employees found</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by adding your first employee</p>
                                    <button wire:click="create"
                                            class="px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600">
                                        Add Employee
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($employees->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    <!-- Modal - Positioned on Current Page -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto p-4 bg-black/25 dark:bg-black/50 backdrop-blur-sm">
            <div class="relative my-8 w-full max-w-md">
                <!-- Modal Container -->
                <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700">
                    <!-- Header -->
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white dark:from-gray-900/50 dark:to-gray-800">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $employeeId ? 'Edit Employee' : 'Add New Employee' }}
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Fill in the employee details below</p>
                        </div>
                        <button wire:click="closeModal" 
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-150">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form wire:submit.prevent="save" class="px-6 py-5 space-y-5">
                        <!-- User Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>User</span>
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select wire:model="user_id" 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('user_id') border-red-500 dark:border-red-500 @enderror">
                                <option value="">Select a user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected($user->id == $user_id)>
                                        {{ $user->name }} • {{ $user->email }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Role</span>
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select wire:model="role" 
                                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white @error('role') border-red-500 dark:border-red-500 @enderror">
                                <option value="">Select a role</option>
                                @foreach($roles as $roleItem)
                                    <option value="{{ $roleItem->name }}" @selected($roleItem->name == $role)>
                                        {{ ucfirst($roleItem->name) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Employee Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <span>Employee Code</span>
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <div class="relative">
                                <input wire:model="employee_code" 
                                       type="text"
                                       placeholder="e.g., EMP-001"
                                       class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-800 text-gray-900 dark:text-white dark:placeholder-gray-400 @error('employee_code') border-red-500 dark:border-red-500 @enderror">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            @error('employee_code')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" wire:model="status" value="active" 
                                           class="h-4 w-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Active</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" wire:model="status" value="inactive" 
                                           class="h-4 w-4 text-blue-600 dark:text-blue-500 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">Inactive</span>
                                </label>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                            <button type="button" 
                                    wire:click="closeModal"
                                    class="px-4 py-2.5 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2.5 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                {{ $employeeId ? 'Update Employee' : 'Create Employee' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Add Alpine.js for dropdown -->
<script src="//unpkg.com/alpinejs" defer></script>