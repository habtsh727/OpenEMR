<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Patient and Header --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Imaging Results
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ $patient->first_name ?? 'Patient' }} • Encounter #{{ $encounter->id }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Summary Stats --}}
                <div class="flex items-center space-x-4">
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $summaryStats['total'] }}
                        </div>
                        <div class="text-sm font-medium text-green-500 dark:text-green-300">Total Reports</div>
                    </div>
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $summaryStats['today'] }}
                        </div>
                        <div class="text-sm font-medium text-blue-500 dark:text-blue-300">Today</div>
                    </div>
                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{
                            $summaryStats['last_week'] }}</div>
                        <div class="text-sm font-medium text-purple-500 dark:text-purple-300">Last 7 Days</div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div class="mb-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                    {{-- Search --}}
                    <div class="flex-1">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="Search imaging reports by type or body part..."
                                class="pl-10 pr-4 py-2.5 w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        {{-- Imaging Type Filter --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Type:</span>
                            <select wire:model.live="selectedType"
                                class="border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white px-3 py-2 text-sm transition-all duration-200">
                                <option value="">All Types</option>
                                @foreach($imagingTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date Range Filter --}}
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">Date:</span>
                            <select wire:model.live="dateRange"
                                class="border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white px-3 py-2 text-sm transition-all duration-200">
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">Last Week</option>
                                <option value="month">Last Month</option>
                                <option value="year">Last Year</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Reports Table --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            {{-- Table Header --}}
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Completed Imaging Reports</h2>
                    </div>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $orders->count() }} report(s) found
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                @if($orders->count())
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Order Details
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Imaging Study
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Report Details
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            Order #{{ $order->id }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Ordered: {{ $order->created_at->format('M d, Y') }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ $order->imagingType->name }}
                                        </span>
                                        <span class="text-gray-900 dark:text-white font-medium">
                                            {{ number_format($order->amount, 2) }}Birr
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            {{ $order->bodyPart->name }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            Reported: {{ $order->imagingResult->reported_at->format('M d, Y h:i A') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            By: {{ $order->imagingResult->radiologist->name ?? 'N/A' }}
                                        </span>
                                    </div>
                                    @if($order->imagingResult->images && count($order->imagingResult->images) > 0)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ count($order->imagingResult->images) }} image(s)
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex flex-col space-y-2">
                                    <button wire:click="viewResult({{ $order->id }})"
                                        class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                        View Report
                                    </button>
                                    <div class="flex space-x-2">
{{-- 
                                            <button wire:click="printReport({{ $order->imagingResult->id }})"
                                                class="flex-1 inline-flex items-center justify-center px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200 text-xs">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                                    </path>
                                                </svg>
                                                Print
                                            </button> --}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="px-6 py-12 text-center">
                    <div
                        class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No imaging reports available</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">
                        {{ $search || $selectedType || $dateRange != 'all' ? 'No reports match your filters' :
                        'Completed imaging reports will appear here' }}
                    </p>
                    @if($search || $selectedType || $dateRange != 'all')
                    <button wire:click="$set(['search' => '', 'selectedType' => null, 'dateRange' => 'all'])"
                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium">
                        Clear filters
                    </button>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
    <button type="button" wire:click="BackToImaging"
        class="px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 flex items-center space-x-2 hover:shadow-md">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        <span>Back to Imaging</span>
    </button>
    {{-- Report Modal --}}
    @if($showModal && $selectedResult)
    <div x-data="{ activeTab: 'report', zoomLevel: 1 }" x-show="true" class="fixed inset-0 z-50 overflow-y-auto"
        style="background-color: rgba(0, 0, 0, 0.5);" @keydown.escape.window="$wire.set('showModal', false)">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                {{-- Modal Header --}}
                <div
                    class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Imaging Report
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    @if($selectedResult && $selectedResult->order_details)
                                    {{ $selectedResult->order_details->imagingType->name ?? 'No Imaging Type' }} •
                                    {{ $selectedResult->order_details->bodyPart->name ?? 'No Body Part' }}
                                    @else
                                    No order details available
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button @click="zoomLevel = Math.min(2, zoomLevel + 0.1)"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7">
                                    </path>
                                </svg>
                            </button>
                            <button @click="zoomLevel = Math.max(0.5, zoomLevel - 0.1)"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                                </svg>
                            </button>
                            <button wire:click="$set('showModal', false)"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Tabs --}}
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex -mb-px">
                        <button @click="activeTab = 'report'"
                            :class="activeTab === 'report' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Report</span>
                            </div>
                        </button>
                        @if($selectedResult->images && count($selectedResult->images) > 0)
                        <button @click="activeTab = 'images'"
                            :class="activeTab === 'images' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Images ({{ count($selectedResult->images) }})</span>
                            </div>
                        </button>
                        @endif
                        <button @click="activeTab = 'details'"
                            :class="activeTab === 'details' ? 'border-green-500 text-green-600 dark:text-green-400' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400'"
                            class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-200">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Details</span>
                            </div>
                        </button>
                    </nav>
                </div>

                {{-- Tab Content --}}
                <div class="p-6 max-h-[60vh] overflow-y-auto">
                    {{-- Report Tab --}}
                    <div x-show="activeTab === 'report'" class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Radiologist's Report
                            </h4>
                            <div class="prose prose-green dark:prose-invert max-w-none"
                                :style="`font-size: ${zoomLevel}em`">
                                {!! nl2br(e($selectedResult->report)) !!}
                            </div>
                        </div>
                    </div>

                    {{-- Images Tab --}}
                    <div x-show="activeTab === 'images'" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($selectedResult->images as $index => $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image) }}"
                                    :style="`transform: scale(${zoomLevel}); transform-origin: center;`"
                                    class="w-full h-64 object-cover rounded-lg border border-gray-200 dark:border-gray-700 transition-transform duration-200"
                                    alt="Imaging Image {{ $index + 1 }}">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <a href="{{ Storage::url($image) }}" target="_blank"
                                        class="p-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Details Tab --}}
                    <div x-show="activeTab === 'details'" class="space-y-6">
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6">
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Report Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-4">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            Order Information
                                        </h5>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Order ID:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    #{{ optional($selectedResult->order_details)->id ?? 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Accession Number:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ optional($selectedResult->order_details)->accession_number ??
                                                    'N/A' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Order Date:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">
                                                    {{ optional($selectedResult->order_details)->created_at?->format('M
                                                    d, Y') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Reporting
                                            Details</h5>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Radiologist:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">{{
                                                    $selectedResult->radiologist->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Reported On:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">{{
                                                    $selectedResult->reported_at->format('M d, Y h:i A') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Images:</span>
                                                <span class="font-medium text-gray-900 dark:text-white">{{
                                                    count($selectedResult->images ?? []) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Report Status:</span>
                                                <span
                                                    class="font-medium text-green-600 dark:text-green-400 capitalize">{{
                                                    str_replace('_', ' ', $selectedResult->status) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div
                    class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 flex justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <span x-text="`Zoom: ${Math.round(zoomLevel * 100)}%`"></span>
                    </div>
                    <div class="flex space-x-3">
                        <button wire:click="printReport({{ $selectedResult->id }})" wire:loading.attr="disabled"
                            wire:target="printReport"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg wire:loading wire:target="printReport"
                                class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                                fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <svg wire:loading.remove wire:target="printReport" class="w-4 h-4 mr-2" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="printReport">Print/Download Report</span>
                            <span wire:loading wire:target="printReport">Generating PDF...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>