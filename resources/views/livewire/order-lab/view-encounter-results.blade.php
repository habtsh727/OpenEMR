<div>
    <div class="max-h-[85vh] overflow-y-auto">
    <!-- Header -->
    <div class="sticky top-0 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 z-20 pb-4 border-b border-gray-200 dark:border-gray-700 px-6 pt-4">
        <div class="flex justify-between items-start">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                            Laboratory Results Report
                        </h3>
                        <div class="flex flex-wrap items-center gap-2 mt-1 text-sm">
                            <span class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Encounter:</span> #{{ $encounter->id }}
                            </span>
                            <span class="text-gray-400 dark:text-gray-500">•</span>
                            <span class="text-gray-600 dark:text-gray-400">
                                <span class="font-medium">Patient:</span> 
                                {{ $encounter->patient->first_name }} {{ $encounter->patient->last_name }}
                                @if($encounter->patient->medical_record_number)
                                    ({{ $encounter->patient->medical_record_number }})
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Print Button -->
                {{-- <button onclick="window.print()" 
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button> --}}
                
                <!-- Close Button -->
                <button @click="$dispatch('close-modal')" 
                        class="p-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="px-6 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <!-- Total Tests -->
            <div class="bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/20 dark:to-gray-800 p-4 rounded-xl border border-blue-100 dark:border-blue-800/50 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Tests</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $labOrders->count() }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Reported -->
            <div class="bg-gradient-to-br from-green-50 to-white dark:from-green-900/20 dark:to-gray-800 p-4 rounded-xl border border-green-100 dark:border-green-800/50 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reported</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                            {{ $labOrders->where('status', 'reported')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Verified -->
            <div class="bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/20 dark:to-gray-800 p-4 rounded-xl border border-purple-100 dark:border-purple-800/50 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $labOrders->where('status', 'verified')->count() }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Action Required -->
            <div class="bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/20 dark:to-gray-800 p-4 rounded-xl border border-amber-100 dark:border-amber-800/50 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 bg-amber-100 dark:bg-amber-900 rounded-lg flex items-center justify-center">
                        <svg class="h-6 w-6 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Verification</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400">
                            {{ $labOrders->where('status', 'reported')->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Banner (if any tests need verification) -->
    @if($labOrders->where('status', 'reported')->count() > 0)
    <div class="mx-6 mb-6">
        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                        <svg class="h-5 w-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">
                            {{ $labOrders->where('status', 'reported')->count() }} test(s) require verification
                        </h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Please review and verify the test results below.
                        </p>
                    </div>
                </div>
                <button wire:click="verifyAll"
                        class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-white font-medium rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Verify All
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Lab Tests Results -->
    <div class="px-6 pb-6 space-y-6">
        @foreach($labOrders as $labOrder)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-200">
            <!-- Test Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 bg-indigo-100 dark:bg-indigo-900 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg">
                                    {{ $labOrder->labTest->name }}
                                    <span class="text-sm font-normal text-indigo-600 dark:text-indigo-400 ml-2">
                                        ({{ $labOrder->labTest->code }})
                                    </span>
                                </h4>
                                <div class="flex flex-wrap items-center gap-3 mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                        </svg>
                                        {{ $labOrder->labTest->sample_type }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Ordered: {{ $labOrder->created_at->format('M d, Y h:i A') }}
                                    </span>
                                    @if($labOrder->verified_at)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Verified: {{ $labOrder->verified_at->format('M d, Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Status Badge -->
                        @php
                            $statusConfig = [
                                'reported' => [
                                    'color' => 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30',
                                    'text' => 'text-green-800 dark:text-green-300',
                                    'border' => 'border border-green-200 dark:border-green-800',
                                    'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                ],
                                'verified' => [
                                    'color' => 'bg-gradient-to-r from-purple-100 to-violet-100 dark:from-purple-900/30 dark:to-violet-900/30',
                                    'text' => 'text-purple-800 dark:text-purple-300',
                                    'border' => 'border border-purple-200 dark:border-purple-800',
                                    'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
                                ],
                            ];
                            $config = $statusConfig[$labOrder->status] ?? [
                                'color' => 'bg-gray-100 dark:bg-gray-700',
                                'text' => 'text-gray-800 dark:text-gray-300',
                                'border' => 'border border-gray-200 dark:border-gray-600',
                                'icon' => ''
                            ];
                        @endphp
                        <span class="px-4 py-2 rounded-lg font-semibold text-sm flex items-center gap-2 {{ $config['color'] }} {{ $config['text'] }} {{ $config['border'] }}">
                            @if($config['icon'])
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $config['icon'] }}" />
                            </svg>
                            @endif
                            {{ ucfirst($labOrder->status) }}
                        </span>
                        
                        <!-- Verification Button (only for reported tests) -->
                        @if($labOrder->status === 'reported')
                        <button wire:click="verifyTest({{ $labOrder->id }})"
                                wire:loading.attr="disabled"
                                wire:loading.class="opacity-50 cursor-not-allowed"
                                class="px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-medium rounded-lg transition-all duration-200 flex items-center gap-2 shadow-sm hover:shadow">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            <span wire:loading.remove wire:target="verifyTest({{ $labOrder->id }})">Verify</span>
                            <span wire:loading wire:target="verifyTest({{ $labOrder->id }})">Verifying...</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Test Results -->
            @if($labOrder->labResults->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Parameter
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Result
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Unit
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Reference Range
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($labOrder->labResults as $result)
                        @php
                            $flagConfig = [
                                'high' => [
                                    'color' => 'text-red-700 dark:text-red-400',
                                    'bg' => 'bg-red-50 dark:bg-red-900/20',
                                    'border' => 'border border-red-100 dark:border-red-800/50',
                                    'icon' => 'M5 10l7-7m0 0l7 7m-7-7v18'
                                ],
                                'low' => [
                                    'color' => 'text-yellow-700 dark:text-yellow-400',
                                    'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
                                    'border' => 'border border-yellow-100 dark:border-yellow-800/50',
                                    'icon' => 'M19 14l-7 7m0 0l-7-7m7 7V3'
                                ],
                                'normal' => [
                                    'color' => 'text-green-700 dark:text-green-400',
                                    'bg' => 'bg-green-50 dark:bg-green-900/20',
                                    'border' => 'border border-green-100 dark:border-green-800/50',
                                    'icon' => 'M5 13l4 4L19 7'
                                ],
                            ];
                            $flag = $flagConfig[$result->flag] ?? [
                                'color' => 'text-gray-700 dark:text-gray-400',
                                'bg' => 'bg-gray-50 dark:bg-gray-900/20',
                                'border' => 'border border-gray-100 dark:border-gray-800/50',
                                'icon' => ''
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $result->parameter }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold {{ $flag['color'] }}">
                                    {{ $result->value }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $result->unit }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $result->reference_range }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($result->flag)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold {{ $flag['bg'] }} {{ $flag['border'] }} {{ $flag['color'] }}">
                                    @if($flag['icon'])
                                    <svg class="h-3 w-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $flag['icon'] }}" />
                                    </svg>
                                    @endif
                                    {{ ucfirst($result->flag) }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    Not Set
                                </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <svg class="h-12 w-12 text-gray-300 dark:text-gray-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h4 class="mt-4 text-sm font-medium text-gray-900 dark:text-white">No Results Available</h4>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Test results have not been entered yet.
                </p>
            </div>
            @endif
            
            <!-- Footer with Notes -->
            @if($labOrder->notes || $labOrder->verification_notes)
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <div class="space-y-3">
                    @if($labOrder->notes)
                    <div>
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                            </svg>
                            Notes
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $labOrder->notes }}</p>
                    </div>
                    @endif
                    
                    @if($labOrder->verification_notes)
                    <div>
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Verification Notes
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $labOrder->verification_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Verification Panel -->
    @if($labOrders->where('status', 'reported')->count() > 0)
    <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 bg-amber-100 dark:bg-amber-900 rounded-full flex items-center justify-center">
                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 dark:text-white">Verification Required</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $labOrders->where('status', 'reported')->count() }} of {{ $labOrders->count() }} tests are pending verification
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="verifyAll"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-50 cursor-not-allowed"
                        class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span wire:loading.remove wire:target="verifyAll">Verify All Tests</span>
                    <span wire:loading wire:target="verifyAll">Verifying All...</span>
                </button>
                
                {{-- <button onclick="window.print()" 
                        class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors flex items-center justify-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print Report
                </button> --}}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Print Styles -->
<style>
@media print {
    @page {
        margin: 1cm;
        size: A4 portrait;
    }
    
    body * {
        visibility: hidden;
    }
    
    .max-h-\\[85vh\\], .max-h-\\[80vh\\] {
        max-height: none !important;
        overflow: visible !important;
    }
    
    .max-h-\\[85vh\\] *, .max-h-\\[80vh\\] * {
        visibility: visible;
    }
    
    .max-h-\\[85vh\\], .max-h-\\[80vh\\] {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        color: black;
    }
    
    button, .sticky, .shadow, .hover\\:shadow, .bg-gradient-to-r {
        display: none !important;
    }
    
    .border, .rounded-lg, .rounded-xl {
        border: 1px solid #ddd !important;
        border-radius: 4px !important;
    }
    
    .bg-white, .bg-gray-50, .bg-blue-50, .bg-green-50, .bg-purple-50, .bg-amber-50,
    .dark\\:bg-gray-800, .dark\\:bg-gray-900, .dark\\:from-gray-800, .dark\\:to-gray-900 {
        background: white !important;
        color: black !important;
    }
    
    .text-gray-900, .text-white, .dark\\:text-white {
        color: black !important;
    }
    
    .text-gray-600, .text-gray-400, .text-gray-500, .dark\\:text-gray-400 {
        color: #666 !important;
    }
    
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    
    th {
        background-color: #f8f8f8;
        font-weight: bold;
    }
}
</style>
</div>