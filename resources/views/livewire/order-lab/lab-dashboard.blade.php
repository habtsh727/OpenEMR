<div>
    <!-- Debug Panel (remove in production) -->
    <div x-data="{ debug: false }" class="fixed bottom-4 right-4 z-50">
        <button @click="debug = !debug" 
                class="p-2 bg-red-500 text-white rounded-full text-xs">
            Debug
        </button>
        <div x-show="debug" x-cloak 
             class="mt-2 p-4 bg-black text-white text-xs rounded max-w-xs">
            <p>Selected ID: {{ $selectedLabOrder }}</p>
            <p>Collect Modal: {{ $showCollectModal ? 'OPEN' : 'CLOSED' }}</p>
            <p>Result Modal: {{ $showResultModal ? 'OPEN' : 'CLOSED' }}</p>
        </div>
    </div>

    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-colors duration-200">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Laboratory Dashboard</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage lab orders and sample processing</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3">
                @if($search || $filterStatus || $filterPriority)
                <button wire:click="clearFilters"
                    class="px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Clear Filters
                </button>
                @endif
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/30 dark:to-amber-900/30 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $pendingCount }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/30 dark:to-violet-900/30 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Processing</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $processingCount }}</p>
                    </div>
                </div>
            </div>

            <div
                class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 p-4 rounded-lg border border-green-100 dark:border-green-800">
                <div class="flex items-center">
                    <div
                        class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reported</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $reportedCount }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session()->has('success'))
        <div
            class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-300 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <!-- Search and Filters -->
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Search Orders
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by test, patient, or MRN..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 transition-colors">
                    </div>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select wire:model.live="filterStatus"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="sample_collected">Sample Collected</option>
                        <option value="processing">Processing</option>
                        <option value="reported">Reported</option>
                    </select>
                </div>

                <div class="w-full lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Priority
                    </label>
                    <select wire:model.live="filterPriority"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-600 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors">
                        <option value="">All Priorities</option>
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div
            class="overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Patient Information
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Test Details
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Sample Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Priority
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Timeline
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($labOrders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <!-- Patient Information -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $order->order->encounter->patient->first_name }} {{
                                            $order->order->encounter->patient->last_name }}
                                        </div>
                                        <div class="flex flex-wrap gap-2 mt-1">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                Order: #{{ $order->order_id }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Test Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-start">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{
                                            $order->labTest->name }}</div>
                                        <div class="flex items-center mt-1">
                                            <span
                                                class="text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 mr-2">
                                                {{ $order->labTest->code }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{
                                                $order->labTest->sample_type }}</span>
                                        </div>
                                        @if($order->labResults->count() > 0)
                                        <div class="text-xs text-green-600 dark:text-green-400 mt-1">
                                            {{ $order->labResults->count() }} result(s) added
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Sample Status -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $sample = $order->labSamples->first();
                                $sampleConfig = [
                                'pending' => [
                                'color' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                                'label' => 'Pending'
                                ],
                                'collected' => [
                                'color' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14
                                0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
                                'label' => 'Collected'
                                ],
                                'accepted' => [
                                'color' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'label' => 'Accepted'
                                ],
                                'rejected' => [
                                'color' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                                'label' => 'Rejected'
                                ],
                                ];
                                $config = $sampleConfig[$sample->status ?? 'pending'];
                                @endphp
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-8 w-8 {{ str_replace('text-', 'bg-', $config['color']) }} rounded-lg flex items-center justify-center mr-2">
                                        <svg class="h-4 w-4 {{ str_replace('bg-', 'text-', explode(' ', $config['color'])[1]) }}"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $config['icon'] }}" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full {{ $config['color'] }}">
                                            {{ $config['label'] }}
                                        </span>
                                        @if($sample && $sample->collected_at)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $sample->collected_at->format('M d, H:i') }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <!-- Priority -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                $priorityColors = [
                                'routine' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                'urgent' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                                'stat' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $priorityColors[$order->priority] }}">
                                    {{ ucfirst($order->priority) }}
                                </span>
                            </td>

                            <!-- Timeline -->
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        Ordered: {{ $order->created_at->format('M d') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('h:i A') }}
                                    </div>
                                    <div
                                        class="text-xs {{ $order->created_at->diffInHours(now()) < 2 ? 'text-red-600 dark:text-red-400 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $order->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    @if(in_array($order->status, ['pending', 'sample_collected']))
                                    <button wire:click="openCollectSample({{ $order->id }})"
                                        class="px-3 py-1 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-600 dark:hover:to-indigo-700 text-white rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        Collect Sample
                                    </button>
                                    @endif

                                    @if(in_array($order->status, ['processing', 'sample_collected']))
                                    <button wire:click="openAddResult({{ $order->id }})"
                                        class="px-3 py-1 bg-gradient-to-r from-purple-600 to-violet-600 hover:from-purple-700 hover:to-violet-700 dark:from-purple-700 dark:to-violet-800 dark:hover:from-purple-600 dark:hover:to-violet-700 text-white rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Add Result
                                    </button>
                                    @endif

                                    @if($order->status === 'processing' && $order->labResults->count() > 0)
                                    <button wire:click="markCompleted({{ $order->id }})"
                                        wire:confirm="Mark this lab order as completed?"
                                        class="px-3 py-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 dark:from-green-700 dark:to-emerald-800 dark:hover:from-green-600 dark:hover:to-emerald-700 text-white rounded-md text-sm font-medium transition-colors flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Mark Complete
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No lab orders
                                        found</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        @if($search || $filterStatus || $filterPriority)
                                        No orders match your search criteria.
                                        @else
                                        All lab orders have been processed or no orders are pending.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($labOrders->hasPages())
        <div
            class="mt-6 px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 rounded-b-lg">
            {{ $labOrders->links() }}
        </div>
        @endif
    </div>

    <!-- Collect Sample Modal -->
    @if($showCollectModal && $selectedLabOrder)
    <div x-data="{ open: true }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-on:keydown.escape.window="open = false; $wire.closeAllModals()">
        
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <!-- Modal Container -->
        <div class="flex items-center justify-center min-h-screen p-4">
            <!-- Modal Panel -->
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false; $wire.closeAllModals()">
                
                <!-- Close Button -->
                <button @click="open = false; $wire.closeAllModals()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <!-- Modal Content -->
                <div class="p-6">
                    @livewire('order-lab.lab-sample-collect', ['labOrder' => \App\Models\LabOrder::find($selectedLabOrder)], 
                             key('collect-sample-' . $selectedLabOrder))
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Result Modal -->
    @if($showResultModal && $selectedLabOrder)
    <div x-data="{ open: true }" x-show="open" x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         x-on:keydown.escape.window="open = false; $wire.closeAllModals()">
        
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-2xl w-full"
                 x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 @click.away="open = false; $wire.closeAllModals()">
                
                <button @click="open = false; $wire.closeAllModals()"
                        class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                
                <div class="p-6">
                    @livewire('order-lab.lab-result-create', ['labOrder' => \App\Models\LabOrder::find($selectedLabOrder)], 
                             key('add-result-' . $selectedLabOrder))
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- JavaScript for better debugging -->
    <script>
        document.addEventListener('livewire:init', () => {
            console.log('Livewire initialized for lab dashboard');
            
            // Listen for Livewire requests
            Livewire.hook('request', ({ uri, options, payload, respond }) => {
                console.log('Livewire request:', payload.method);
            });
            
            // Listen for Livewire responses
            Livewire.hook('response', ({ status, component }) => {
                console.log('Livewire response:', status, 'for component:', component);
            });
        });
        
        // Check if Alpine is loaded
        document.addEventListener('alpine:init', () => {
            console.log('Alpine.js initialized');
        });
    </script>
</div>