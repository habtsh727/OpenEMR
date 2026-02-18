<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200" 
     x-data="{ 
        showAlert: @entangle('showAlert'),
        alertMessage: @entangle('alertMessage'),
        alertType: @entangle('alertType')
     }">
    
    <!-- Alert Notification with Dark/Light Theme -->
    <div x-show="showAlert" x-cloak
         x-init="setTimeout(() => showAlert = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4"
                 :class="{
                    'bg-gradient-to-r from-green-500 to-emerald-600 dark:from-green-600 dark:to-emerald-700': alertType === 'success',
                    'bg-gradient-to-r from-red-500 to-rose-600 dark:from-red-600 dark:to-rose-700': alertType === 'error',
                    'bg-gradient-to-r from-yellow-500 to-orange-600 dark:from-yellow-600 dark:to-orange-700': alertType === 'warning',
                    'bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700': alertType === 'info'
                 }">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <template x-if="alertType === 'success'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'error'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'warning'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="alertType === 'info'">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </template>
                    </div>
                    <div>
                        <p class="font-medium text-white" x-text="alertMessage"></p>
                    </div>
                </div>
                <button @click="showAlert = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Theme Toggle -->
        <div class="mb-8">
            <div class="bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-800 dark:via-purple-800 dark:to-pink-800 rounded-2xl shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                
                <div class="relative p-6 md:p-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="relative">
                                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-xl flex items-center justify-center border-2 border-white/30 shadow-xl">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-green-400 border-2 border-white dark:border-gray-800 animate-pulse"></div>
                            </div>
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-white">Payment Queue</h1>
                                <p class="text-indigo-100 dark:text-indigo-200 mt-1">Process rehabilitation payments</p>
                            </div>
                        </div>
                        
                        <!-- Theme Toggle Button -->
                        <button onclick="toggleTheme()" class="p-3 bg-white/20 backdrop-blur-xl rounded-xl hover:bg-white/30 transition-all duration-200 group">
                            <svg class="w-6 h-6 text-white dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                            <svg class="w-6 h-6 text-white hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Stats Cards -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Total Orders</p>
                            <p class="text-white text-2xl font-bold">{{ App\Models\RehabOrder::count() }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Pending</p>
                            <p class="text-yellow-300 text-2xl font-bold">{{ App\Models\RehabOrder::where('status', 'sent_to_cashier')->count() }}</p>
                        </div>
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                            <p class="text-white/60 text-sm">Processed</p>
                            <p class="text-green-300 text-2xl font-bold">{{ App\Models\RehabOrder::where('status', 'paid')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
            <div class="flex gap-2">
                <button wire:click="$set('tab', 'pending')" 
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                        {{ $tab === 'pending' 
                            ? 'bg-indigo-600 text-white shadow-md' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pending Payments
                    </span>
                </button>
                <button wire:click="$set('tab', 'processed')" 
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200
                        {{ $tab === 'processed' 
                            ? 'bg-indigo-600 text-white shadow-md' 
                            : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                    <span class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Processed Payments
                    </span>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="mb-6">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" 
                    placeholder="Search by patient name..." 
                    class="w-full pl-12 pr-4 py-4 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white transition-all duration-200">
            </div>
        </div>

        <!-- Orders Grid/Cards for Mobile -->
        <div class="lg:hidden space-y-4">
            @forelse($orders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Order #{{ $order->id }}</span>
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $order->encounter->encounter->patient->name }}</h3>
                    </div>
                    @if($order->status === 'sent_to_cashier')
                        <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                    @else
                        <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">Paid</span>
                    @endif
                </div>
                
                <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Doctor:</span>
                        <span class="ml-1 text-gray-900 dark:text-white">{{ $order->encounter->encounter->doctor->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Amount:</span>
                        <span class="ml-1 font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Packages:</span>
                        <span class="ml-1 text-gray-900 dark:text-white">{{ $order->packages->count() }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Date:</span>
                        <span class="ml-1 text-gray-900 dark:text-white">{{ $order->created_at->format('M d') }}</span>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button wire:click="viewOrder({{ $order->id }})" 
                        class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                        View
                    </button>
                    @if($order->status === 'sent_to_cashier')
                        <button wire:click="openPaymentModal({{ $order->id }})" 
                            class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 transition-colors">
                            Pay
                        </button>
                    @else
                        <button wire:click="recheckPayment({{ $order->id }})" 
                            class="flex-1 px-3 py-2 bg-purple-600 text-white rounded-lg text-sm hover:bg-purple-700 transition-colors">
                            Recheck
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No orders found</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Order</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Patient</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Doctor</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Packages</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900 dark:text-white">#{{ $order->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $order->encounter->encounter->patient->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $order->encounter->encounter->patient->id }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">{{ $order->encounter->encounter->doctor->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300 text-xs rounded-full">
                                {{ $order->packages->count() }} package(s)
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status === 'sent_to_cashier')
                                <span class="px-3 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300 text-xs rounded-full">Pending</span>
                            @else
                                <span class="px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 text-xs rounded-full">Paid</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">{{ $order->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <button wire:click="viewOrder({{ $order->id }})" 
                                    class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                    title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </button>

                                @if($order->status === 'sent_to_cashier')
                                    <button wire:click="openPaymentModal({{ $order->id }})" 
                                        class="p-2 text-green-600 dark:text-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 rounded-lg transition-colors"
                                        title="Process Payment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </button>
                                @else
                                    <button wire:click="recheckPayment({{ $order->id }})" 
                                        class="p-2 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-colors"
                                        title="Recheck Payment">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                            No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->links() }}
        </div>

        <!-- View Details Modal -->
        @if($showDetailsModal && $selectedOrder)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Order Details #{{ $selectedOrder->id }}</h2>
                    <button wire:click="closeModal" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-6">
                    <!-- Patient Info -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Patient Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Name</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->encounter->encounter->patient->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Doctor</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->encounter->encounter->doctor->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Order Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                <p class="font-medium">
                                    @if($selectedOrder->status === 'sent_to_cashier')
                                        <span class="text-yellow-600 dark:text-yellow-400">Pending Payment</span>
                                    @else
                                        <span class="text-green-600 dark:text-green-400">Paid</span>
                                    @endif
                                </p>
                            </div>
                            @if($selectedOrder->paid_at)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Paid At</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->paid_at->format('M d, Y H:i') }}</p>
                            </div>
                            @endif
                            @if($selectedOrder->payment_method)
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Payment Method</p>
                                <p class="font-medium text-gray-900 dark:text-white capitalize">{{ $selectedOrder->payment_method }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Packages -->
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Ordered Packages</h3>
                        @foreach($selectedOrder->packages as $package)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-medium text-gray-900 dark:text-white">{{ $package->package_name }}</h4>
                                <span class="font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($package->final_price, 2) }}</span>
                            </div>
                            @if($package->items->isNotEmpty())
                            <div class="mt-3 space-y-2">
                                @foreach($package->items as $item)
                                <div class="text-sm p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <div class="flex justify-between">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $item->item_name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ str_replace('_', ' ', $item->item_type) }}</span>
                                    </div>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-1 text-xs text-gray-600 dark:text-gray-400">
                                        @if($item->dosage)<div>Dosage: {{ $item->dosage }}</div>@endif
                                        @if($item->frequency)<div>Freq: {{ $item->frequency }}</div>@endif
                                        @if($item->duration)<div>Duration: {{ $item->duration }}</div>@endif
                                        @if($item->bed_duration_days)<div>Bed: {{ $item->bed_duration_days }} days</div>@endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900 dark:text-white">Total Amount</span>
                        <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">ETB {{ number_format($selectedOrder->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <div class="sticky bottom-0 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closeModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Close
                    </button>
                    @if($selectedOrder->status === 'sent_to_cashier')
                    <button wire:click="openPaymentModal({{ $selectedOrder->id }})" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        Process Payment
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Payment Modal -->
        @if($showPaymentModal && $paymentOrder)
        <div class="fixed inset-0 bg-black/50 dark:bg-black/70 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full">
                <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Process Payment</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Order #{{ $paymentOrder->id }} - {{ $paymentOrder->encounter->encounter->patient->name }}</p>
                </div>
                
                <div class="p-6 space-y-4">
                    <!-- Amount Display -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg text-center">
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Amount</p>
                        <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($paymentOrder->total_amount, 2) }}</p>
                    </div>
                    
                    <!-- Payment Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Method</label>
                        <div class="grid grid-cols-3 gap-2">
                            <button type="button" wire:click="$set('paymentMethod', 'cash')"
                                class="p-3 border-2 rounded-lg text-center transition-all duration-200
                                    {{ $paymentMethod === 'cash' 
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' 
                                        : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300' }}">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">Cash</span>
                            </button>
                            <button type="button" wire:click="$set('paymentMethod', 'card')"
                                class="p-3 border-2 rounded-lg text-center transition-all duration-200
                                    {{ $paymentMethod === 'card' 
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' 
                                        : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300' }}">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">Card</span>
                            </button>
                            <button type="button" wire:click="$set('paymentMethod', 'insurance')"
                                class="p-3 border-2 rounded-lg text-center transition-all duration-200
                                    {{ $paymentMethod === 'insurance' 
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' 
                                        : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300' }}">
                                <span class="block text-sm font-medium text-gray-900 dark:text-white">Insurance</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Amount Received -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Amount Received</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500 dark:text-gray-400">ETB</span>
                            <input type="number" step="0.01" wire:model.live="paymentAmount" 
                                class="w-full pl-8 pr-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white transition-all duration-200"
                                placeholder="0.00">
                        </div>
                    </div>
                    
                    <!-- Change Due -->
                    @if($changeAmount > 0)
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                        <div class="flex justify-between items-center">
                            <span class="font-medium text-green-800 dark:text-green-400">Change Due:</span>
                            <span class="text-xl font-bold text-green-600 dark:text-green-400">ETB{{ number_format($changeAmount, 2) }}</span>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Validation Message -->
                    @if($paymentAmount < $paymentOrder->total_amount && $paymentAmount > 0)
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 text-sm rounded-lg">
                        Amount received is less than total amount
                    </div>
                    @endif
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4 flex justify-end gap-3">
                    <button wire:click="closePaymentModal" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </button>
                    <button wire:click="processPayment" 
                        @if($paymentAmount < $paymentOrder->total_amount) disabled @endif
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                        Confirm Payment
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Theme Toggle Script -->
    <script>
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Check for saved theme preference
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>