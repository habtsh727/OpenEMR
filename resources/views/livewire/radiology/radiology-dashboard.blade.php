<div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">Radiology Dashboard</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Process imaging orders and upload results</p>
                </div>
                
                {{-- Stats --}}
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ count($paidOrders) }}</div>
                        <div class="text-sm font-medium text-blue-500 dark:text-blue-300">Pending Orders</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $selectedOrder ? '1' : '0' }}
                        </div>
                        <div class="text-sm font-medium text-green-500 dark:text-green-300">Selected</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-100 dark:border-purple-800 rounded-xl px-4 py-3">
                        <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-sm font-medium text-purple-500 dark:text-purple-300">Radiologist</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Left Column: Orders List --}}
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-50 dark:bg-blue-900/20">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Paid Imaging Orders</h2>
                            </div>
                            <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">
                                {{ count($paidOrders) }} pending
                            </span>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        @if(count($paidOrders))
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Order Details
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Patient & Study
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($paidOrders as $order)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 
                                                  {{ $selectedOrder && $selectedOrder->id === $order->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg 
                                                        {{ $order->priority === 'urgent' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' }}">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            #{{ $order->id }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $order->created_at->format('M d, h:i A') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-2">
                                                    <div>
                                                        <div class="font-medium text-gray-900 dark:text-white">
                                                            {{ $order->encounter->patient->full_name ?? 'N/A' }}
                                                        </div>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                                            {{ $order->imagingType->name }}
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                                            {{ $order->priority === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                                            {{ ucfirst($order->priority) }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ $order->bodyPart->name }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button wire:click="selectOrder({{ $order->id }})" 
                                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                                    </svg>
                                                    Process
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="px-6 py-12 text-center">
                                <div class="mx-auto w-16 h-16 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">All caught up!</h3>
                                <p class="text-gray-500 dark:text-gray-400 mb-4">
                                    No pending imaging orders requiring results
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    New paid orders will appear here automatically
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Results Form --}}
            <div>
                @if($selectedOrder)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Upload Results</h2>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Order #{{ $selectedOrder->id }} • {{ $selectedOrder->encounter->patient->full_name ?? 'Patient' }}
                                        </p>
                                    </div>
                                </div>
                                <button wire:click="$set('selectedOrder', null)" 
                                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Order Details --}}
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $selectedOrder->imagingType->name }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-6">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $selectedOrder->bodyPart->name }}</span>
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-white">${{ number_format($selectedOrder->amount, 2) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2 ml-6">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                            {{ $selectedOrder->priority === 'urgent' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' }}">
                                            {{ ucfirst($selectedOrder->priority) }} Priority
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if($selectedOrder->clinical_notes)
                                <div class="mt-3 p-3 bg-blue-50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-800 rounded-lg">
                                    <div class="flex items-start space-x-2">
                                        <svg class="w-4 h-4 text-blue-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-1">Clinical Notes:</p>
                                            <p class="text-sm text-blue-700 dark:text-blue-400">{{ $selectedOrder->clinical_notes }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <form wire:submit.prevent="submitResult" class="p-6 space-y-6">
                            {{-- Images Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    <div class="flex items-center justify-between">
                                        <span>Upload Images</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ count($images) }} file(s) selected
                                        </span>
                                    </div>
                                </label>
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                            <label class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload imaging files</span>
                                                <input type="file" wire:model="images" multiple class="sr-only" accept="image/*">
                                            </label>
                                            <p class="pl-1">or drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            PNG, JPG, GIF up to 5MB each
                                        </p>
                                    </div>
                                </div>
                                @error('images.*') 
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                
                                {{-- Preview uploaded images --}}
                                @if(count($images))
                                    <div class="mt-4 grid grid-cols-3 gap-2">
                                        @foreach($images as $index => $image)
                                            <div class="relative">
                                                <img src="{{ $image->temporaryUrl() }}" 
                                                     class="w-full h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
                                                <button type="button" 
                                                        wire:click="removeImage({{ $index }})"
                                                        class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600 transition-colors duration-200">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- Report --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Radiologist Report <span class="text-red-500">*</span>
                                </label>
                                <textarea wire:model="report" 
                                          rows="8"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                          placeholder="Enter detailed radiology report including findings, impressions, and recommendations..."
                                          required></textarea>
                                <div class="mt-1 flex justify-between">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ strlen($report) }}/5000 characters
                                    </p>
                                    @error('report') 
                                        <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Measurements --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Measurements <small class="text-gray-500 dark:text-gray-400">(Optional)</small>
                                </label>
                                <textarea wire:model="measurements" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                                          placeholder="Enter any relevant measurements, dimensions, or quantitative findings..."></textarea>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Example: "Mass measures 2.3 x 1.8 cm in greatest dimensions"
                                </p>
                            </div>

                            {{-- Status --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                    Report Status
                                </label>
                                <div class="grid grid-cols-3 gap-3">
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="status" value="in_progress" class="sr-only peer">
                                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 peer-checked:ring-2 peer-checked:ring-yellow-200 dark:peer-checked:ring-yellow-800 transition-all duration-200">
                                            <div class="flex items-center justify-center">
                                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium text-gray-900 dark:text-white">In Progress</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Draft report</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="status" value="completed" class="sr-only peer" checked>
                                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 peer-checked:ring-2 peer-checked:ring-green-200 dark:peer-checked:ring-green-800 transition-all duration-200">
                                            <div class="flex items-center justify-center">
                                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-green-500 peer-checked:bg-green-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium text-gray-900 dark:text-white">Completed</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Final report</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative flex cursor-pointer">
                                        <input type="radio" wire:model="status" value="rejected" class="sr-only peer">
                                        <div class="w-full p-4 border border-gray-300 dark:border-gray-600 rounded-lg peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 peer-checked:ring-2 peer-checked:ring-red-200 dark:peer-checked:ring-red-800 transition-all duration-200">
                                            <div class="flex items-center justify-center">
                                                <div class="w-5 h-5 rounded-full border border-gray-300 dark:border-gray-600 peer-checked:border-red-500 peer-checked:bg-red-500 flex items-center justify-center mr-3">
                                                    <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-medium text-gray-900 dark:text-white">Rejected</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">Needs repeat</div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Completed reports are sent to the ordering doctor immediately
                                </p>
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span>Reporting as: {{ auth()->user()->name }}</span>
                                        </div>
                                    </div>
                                    <button type="submit" 
                                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center space-x-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Submit Radiology Report</span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden h-full">
                        <div class="h-full flex flex-col items-center justify-center px-6 py-12">
                            <div class="w-20 h-20 flex items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Select an Imaging Order</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-center mb-6">
                                Choose an order from the list to upload radiology results
                            </p>
                            <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                <p class="mb-1">• Upload imaging files and reports</p>
                                <p class="mb-1">• Provide detailed findings and measurements</p>
                                <p>• Reports are sent to ordering doctors</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>