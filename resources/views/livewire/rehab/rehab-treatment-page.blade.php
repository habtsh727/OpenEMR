<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">

    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert') }" x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 translate-x-2" class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
        <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between p-4 {{
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' :
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' :
                    'bg-gradient-to-r from-yellow-500 to-orange-600') }}">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        @if($alertType === 'success')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        @elseif($alertType === 'error')
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="font-medium text-white">{{ $alertMessage }}</p>
                    </div>
                </div>
                <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        @endif
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        <!-- Back to Queue Link -->
        <div class="flex items-center gap-2">
            <a href="{{ route('rehab.treatment.queue') }}" wire:navigate
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white flex items-center gap-1 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Treatment Queue
            </a>
        </div>

        <!-- Sticky Patient Header Card -->
        <div
            class="sticky top-4 z-20 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden backdrop-blur-sm bg-white/95 dark:bg-gray-800/95">
            <div class="p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div
                            class="h-16 w-16 rounded-full bg-gradient-to-br from-emerald-600 to-teal-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                            {{ substr($patient->name ?? 'N/A', 0, 1) }}
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $patient->name ?? 'N/A' }}
                            </h1>
                            <div class="flex flex-wrap items-center gap-3 mt-1">
                                <span class="text-sm text-gray-600 dark:text-gray-400">MRN: {{
                                    $patient->medical_record_number ?? 'N/A' }}</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ $age }} years</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Admitted: {{
                                    $encounter->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Status Badge -->
                        <span class="px-4 py-2 rounded-full text-sm font-medium {{ $statusColor }}">
                            {{ $encounter->status_label }}
                        </span>

                        <!-- Action Buttons -->
                        @if($encounter->status === 'sent_to_rehab')
                        <button wire:click="startTreatment" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="startTreatment" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="startTreatment">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Start Treatment
                            </span>
                            <span wire:loading wire:target="startTreatment">Starting...</span>
                        </button>
                        @elseif($encounter->status === 'treatment_in_progress')
                        <button wire:click="completeTreatment" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="completeTreatment" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="completeTreatment">
                                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Complete Treatment
                            </span>
                            <span wire:loading wire:target="completeTreatment">Completing...</span>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Patient Info Grid -->
                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Attending Doctor</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">Dr. {{ $doctor->name ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bed Assignment</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            {{ $bedInfo['ward'] }} → {{ $bedInfo['room'] }} → {{ $bedInfo['bed'] }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $bedInfo['class'] }}</p>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Length of Stay</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">
                            @if($daysLeft)
                            @if($daysLeft['type'] === 'overdue')
                            <span class="text-orange-600 dark:text-orange-400">{{ $daysLeft['text'] }}</span>
                            @else
                            {{ $daysLeft['text'] }}
                            @endif
                            @else
                            Not started
                            @endif
                        </p>
                        @if($expectedEndDate && !$treatmentCompletedAt)
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Expected: {{ $expectedEndDate->format('M d, Y') }}
                        </p>
                        @endif
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-3">
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rehab Staff</p>
                        <p class="mt-1 font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Order Package Details Card -->
<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ordered Treatment Packages</h2>
            </div>
            <button wire:click="togglePackageDetails"
                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-700 flex items-center gap-1">
                <span>{{ $showPackageDetails ? 'Hide' : 'Show' }} Details</span>
                <svg class="w-4 h-4 transition-transform {{ $showPackageDetails ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    </div>

    @if($showPackageDetails)
    <div class="p-6">
        @if(empty($packageDetails))
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <p class="text-gray-500 dark:text-gray-400">No packages ordered for this patient.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($packageDetails as $index => $package)
                <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                    <!-- Package Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 px-5 py-3 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">{{ $package['name'] }}</h3>
                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ count($package['items']) }} items</span>
                                    @if($package['discount_value'] > 0)
                                        <span class="text-xs text-green-600 dark:text-green-400">
                                            Discount: {{ $package['discount_type'] === 'percentage' ? $package['discount_value'] . '%' : 'ETB ' . number_format($package['discount_value'], 2) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-through">ETB {{ number_format($package['base_price'], 2) }}</p>
                                <p class="text-lg font-bold text-blue-600 dark:text-blue-400">ETB {{ number_format($package['final_price'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Package Items -->
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Item</th>
                                        <th class="text-left py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Details</th>
                                        <th class="text-right py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Price</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                    @foreach($package['items'] as $item)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                        <td class="py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="p-1.5 rounded-lg
                                                    @if($item['type'] === 'standard_medication') bg-blue-100 dark:bg-blue-900/30
                                                    @elseif($item['type'] === 'custom_medication') bg-purple-100 dark:bg-purple-900/30
                                                    @elseif($item['type'] === 'service') bg-green-100 dark:bg-green-900/30
                                                    @else bg-orange-100 dark:bg-orange-900/30 @endif">
                                                    @if($item['type'] === 'standard_medication')
                                                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                                        </svg>
                                                    @elseif($item['type'] === 'custom_medication')
                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                        </svg>
                                                    @elseif($item['type'] === 'service')
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ str_replace('_', ' ', $item['type']) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="space-y-1 text-sm">
                                                @if($item['dosage'])
                                                    <div class="text-gray-600 dark:text-gray-400">Dosage: {{ $item['dosage'] }}</div>
                                                @endif
                                                @if($item['frequency'])
                                                    <div class="text-gray-600 dark:text-gray-400">Frequency: {{ $item['frequency'] }}</div>
                                                @endif
                                                @if($item['duration'])
                                                    <div class="text-gray-600 dark:text-gray-400">Duration: {{ $item['duration'] }}</div>
                                                @endif
                                                @if($item['quantity'])
                                                    <div class="text-gray-600 dark:text-gray-400">Quantity: {{ $item['quantity'] }}</div>
                                                @endif
                                                @if($item['bed_duration_days'])
                                                    <div class="text-gray-600 dark:text-gray-400">Bed Duration: {{ $item['bed_duration_days'] }} days</div>
                                                @endif
                                                @if($item['notes'])
                                                    <div class="text-xs text-gray-500 dark:text-gray-500 italic">{{ $item['notes'] }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 text-right">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">ETB {{ number_format($item['unit_price'], 2) }}</div>
                                            @if($item['quantity'] && $item['quantity'] > 1)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">x{{ $item['quantity'] }}</div>
                                                <div class="text-xs font-medium text-gray-700 dark:text-gray-300">ETB {{ number_format($item['total_price'], 2) }}</div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="border-t border-gray-200 dark:border-gray-700">
                                        <td colspan="2" class="pt-3 text-right font-medium text-gray-900 dark:text-white">Total:</td>
                                        <td class="pt-3 text-right font-bold text-blue-600 dark:text-blue-400">ETB {{ number_format($package['final_price'], 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        @if($package['notes'])
                        <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                            <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                <span class="font-medium">Note:</span> {{ $package['notes'] }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach

                <!-- Total Summary -->
                @php
                    $totalPackagePrice = array_sum(array_column($packageDetails, 'final_price'));
                    $bedPrice = $bedInfo['bed'] !== 'Not assigned' ? ($encounter->bedSelections->first()->total_price ?? 0) : 0;
                    $grandTotal = $totalPackagePrice + $bedPrice;
                @endphp
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Package Cost</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($totalPackagePrice, 2) }}</p>
                        </div>
                        @if($bedPrice > 0)
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Bed Charge</p>
                            <p class="text-xl font-bold text-gray-900 dark:text-white">ETB {{ number_format($bedPrice, 2) }}</p>
                        </div>
                        @endif
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Grand Total</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">ETB {{ number_format($grandTotal, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>

        <!-- Dynamic Treatment Entry Form -->
        @if($encounter->status === 'treatment_in_progress')
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Add Treatment Entry</h2>
                </div>
            </div>

            <div class="p-6">
                <!-- Treatment Type Selection -->
                @if(!$selectedType)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($treatmentTypes as $type)
                    <button wire:click="selectType({{ $type->id }})"
                        class="p-6 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-lg transition-all text-left group">
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg group-hover:bg-emerald-500 transition-colors">
                                <div class="w-5 h-5 text-emerald-600 dark:text-emerald-400 group-hover:text-white">
                                    {!! $type->icon_html !!}
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-emerald-600">{{
                                $type->name }}</h3>
                        </div>
                        @if($type->description)
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $type->description }}</p>
                        @endif
                    </button>
                    @endforeach
                </div>
                @else
                <!-- Dynamic Form -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ $currentType->name }}</h3>
                        <button wire:click="$set('selectedType', null)"
                            class="text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Change Type
                        </button>
                    </div>

                    @foreach($formFields as $field)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            {{ $field['label'] }}
                            @if($field['required'] ?? false)
                            <span class="text-red-500">*</span>
                            @endif
                        </label>

                        @switch($field['type'])
                        @case('text')
                        <input type="text" wire:model="answers.{{ $field['name'] }}"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('textarea')
                        <textarea wire:model="answers.{{ $field['name'] }}" rows="3"
                            placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        @break

                        @case('number')
                        <input type="number" wire:model="answers.{{ $field['name'] }}"
                            step="{{ $field['step'] ?? '1' }}" min="{{ $field['min'] ?? '' }}"
                            max="{{ $field['max'] ?? '' }}" placeholder="{{ $field['placeholder'] ?? '' }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('select')
                        <select wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Select {{ $field['label'] }}</option>
                            @foreach($field['options'] ?? [] as $option)
                            <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                        @break

                        @case('radio')
                        <div class="space-y-2">
                            @foreach($field['options'] ?? [] as $option)
                            <label class="flex items-center gap-2">
                                <input type="radio" wire:model="answers.{{ $field['name'] }}"
                                    value="{{ $option['value'] }}" class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @break

                        @case('checkbox')
                        <div class="space-y-2">
                            @foreach($field['options'] ?? [] as $option)
                            <label class="flex items-center gap-2">
                                <input type="checkbox" wire:model="answers.{{ $field['name'] }}"
                                    value="{{ $option['value'] }}"
                                    class="rounded text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $option['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        @break

                        @case('date')
                        <input type="date" wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('time')
                        <input type="time" wire:model="answers.{{ $field['name'] }}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break

                        @case('blood_pressure')
                        <input type="text" wire:model="answers.{{ $field['name'] }}" placeholder="120/80"
                            pattern="\d{2,3}/\d{2,3}"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        @break
                        @endswitch

                        @if(!empty($field['help']))
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $field['help'] }}</p>
                        @endif
                    </div>
                    @endforeach

                    <!-- Additional Notes -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional
                            Notes</label>
                        <textarea wire:model="notes" rows="3"
                            placeholder="Add any additional notes about this treatment session..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-4">
                        <button wire:click="saveEntry" wire:loading.attr="disabled"
                            class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg wire:loading wire:target="saveEntry" class="w-5 h-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span wire:loading.remove wire:target="saveEntry">Save Treatment Entry</span>
                            <span wire:loading wire:target="saveEntry">Saving...</span>
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Treatment Timeline -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div
                class="border-b border-gray-200 dark:border-gray-700 px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Treatment Timeline</h2>
                </div>
            </div>

            <div class="p-6">
                @if($treatmentEntries->isEmpty())
                <div class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No treatment entries yet</h3>
                        <p class="text-gray-500 dark:text-gray-400">Start treatment and add entries to see timeline.</p>
                    </div>
                </div>
                @else
                <div class="flow-root">
                    <ul class="-mb-8">
                        @foreach($treatmentEntries as $index => $entry)
                        <li>
                            <div class="relative pb-8">
                                @if(!$loop->last)
                                <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700"
                                    aria-hidden="true"></span>
                                @endif
                                <div class="relative flex items-start space-x-3">
                                    <div class="relative">
                                        <div
                                            class="h-10 w-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                            <div class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                                                {!! $entry->treatmentType->icon_html !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-900 dark:text-white">{{
                                                    $entry->user->name }}</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400 ml-2">{{
                                                    $entry->treatmentType->name }}</span>
                                            </div>
                                            <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                                {{ $entry->created_at->format('M d, Y · g:i A') }}
                                            </p>
                                        </div>
                                        <div class="mt-2">
                                            <!-- Display dynamic answers -->
                                            <div class="grid grid-cols-2 gap-2 mb-2">
                                                @foreach($entry->formatted_answers as $key => $value)
                                                <div class="text-sm">
                                                    <span class="font-medium text-gray-600 dark:text-gray-400">{{
                                                        ucfirst($key) }}:</span>
                                                    <span class="ml-1 text-gray-900 dark:text-white">{{ $value }}</span>
                                                </div>
                                                @endforeach
                                            </div>

                                            @if($entry->notes)
                                            <div
                                                class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-3 border border-gray-100 dark:border-gray-700 mt-2">
                                                {{ $entry->notes }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>

        <!-- Completion Message -->
        @if($encounter->status === 'completed')
        <div
            class="bg-green-50 dark:bg-green-900/20 rounded-xl p-6 border border-green-200 dark:border-green-800 text-center">
            <svg class="w-16 h-16 text-green-600 dark:text-green-400 mx-auto mb-4" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <h3 class="text-lg font-medium text-green-800 dark:text-green-300 mb-2">Treatment Completed</h3>
            <p class="text-green-700 dark:text-green-400">This treatment was completed on {{
                $treatmentCompletedAt->format('F d, Y \a\t g:i A') }}</p>
        </div>
        @endif
    </div>

    <!-- Notification Script -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert-shown', () => {
                setTimeout(() => {
                    @this.dispatch('closeAlert');
                }, 5000);
            });
        });
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</div>
