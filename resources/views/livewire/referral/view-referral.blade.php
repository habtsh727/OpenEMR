{{-- resources/views/livewire/referral/view-referral.blade.php --}}
<div>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <!-- Page Title and Info -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Referral Details</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Complete referral information and tracking</p>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="flex flex-wrap items-center gap-3 mt-4">
                            @php
                                $statusConfig = [
                                    'created' => [
                                        'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                                        'text' => 'text-blue-800 dark:text-blue-300',
                                        'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
                                    ],
                                    'sent' => [
                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                        'text' => 'text-yellow-800 dark:text-yellow-300',
                                        'icon' => 'M12 19l9 2-9-18-9 18 9-2zm0 0v-8'
                                    ],
                                    'completed' => [
                                        'bg' => 'bg-green-100 dark:bg-green-900/30',
                                        'text' => 'text-green-800 dark:text-green-300',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                    'cancelled' => [
                                        'bg' => 'bg-red-100 dark:bg-red-900/30',
                                        'text' => 'text-red-800 dark:text-red-300',
                                        'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                ];
                                
                                $urgencyConfig = [
                                    'routine' => [
                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                        'text' => 'text-gray-800 dark:text-gray-300',
                                        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                    'urgent' => [
                                        'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                                        'text' => 'text-yellow-800 dark:text-yellow-300',
                                        'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z'
                                    ],
                                    'emergency' => [
                                        'bg' => 'bg-red-100 dark:bg-red-900/30',
                                        'text' => 'text-red-800 dark:text-red-300',
                                        'icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
                                    ],
                                ];
                                
                                $status = $statusConfig[$referral->status->value];
                                $urgency = $urgencyConfig[$referral->urgency->value];
                            @endphp
                            
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $status['bg'] }} {{ $status['text'] }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $status['icon'] }}" />
                                </svg>
                                {{ ucfirst($referral->status->value) }}
                            </span>
                            
                            <!-- Urgency Badge -->
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold {{ $urgency['bg'] }} {{ $urgency['text'] }}">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $urgency['icon'] }}" />
                                </svg>
                                {{ ucfirst($referral->urgency->value) }} Priority
                            </span>
                            
                            <!-- Date Info -->
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Referred: {{ $referral->referred_at->format('M d, Y H:i') }}
                            </div>
                            
                            <!-- ID Badge -->
                            <span class="inline-flex items-center px-3 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-mono">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                                REF-{{ str_pad($referral->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('referrals.queue') }}" 
                           class="inline-flex items-center px-5 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors duration-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Back to Queue
                        </a>
                        
                        @if($referral->status->value === 'sent')
                            <a href="{{ route('referrals.submit-result', $referral->id) }}" 
                               class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 dark:from-green-500 dark:to-emerald-500 dark:hover:from-green-600 dark:hover:to-emerald-600 text-white font-medium rounded-lg shadow hover:shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit Result
                            </a>
                        @endif
                        
                        <!-- More Actions Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" 
                                    class="inline-flex items-center px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                                </svg>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Print Referral</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Export to PDF</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Share via Email</a>
                                    @if($referral->status->value !== 'cancelled')
                                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                        <a href="#" class="block px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30">Cancel Referral</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Patient & Clinical Info -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Patient Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Patient Information</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Details of the referred patient</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Patient Name</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $referral->encounter->patient->first_name ?? 'N/A' }}</p>
                            </div>
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Encounter ID</p>
                                <p class="text-lg font-semibold text-gray-900 dark:text-white font-mono">ENC-{{ str_pad($referral->encounter_id, 6, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            @if($referral->encounter->patient)
                                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Age</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ \Carbon\Carbon::parse($referral->encounter->patient->date_of_birth ?? now())->age ?? 'N/A' }} years
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Gender</p>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $referral->encounter->patient->gender ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Facility Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                <svg class="h-5 w-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Destination Facility</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Receiving hospital or clinic information</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Facility Name</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $referral->facility_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Facility Type</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ ucfirst($referral->facility_type) }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Location</p>
                                <div class="flex items-start space-x-3">
                                    <svg class="h-5 w-5 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p class="text-gray-900 dark:text-white">{{ $referral->facility_location }}</p>
                                </div>
                            </div>
                            
                            <!-- Map/Directions Link -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="https://maps.google.com/?q={{ urlencode($referral->facility_location) }}" 
                                   target="_blank"
                                   class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    Get Directions
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Clinical Information Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="h-10 w-10 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                <svg class="h-5 w-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Clinical Information</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Medical details for referral</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Reason for Referral</p>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $referral->reason }}</p>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Clinical Summary</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ strlen($referral->clinical_summary) }} characters</span>
                                </div>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <p class="text-gray-900 dark:text-white whitespace-pre-line leading-relaxed">{{ $referral->clinical_summary }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Attachments Section -->
                    @if($referral->attachments->where('is_result', false)->isNotEmpty())
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="h-10 w-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Supporting Documents</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Referral attachments and files</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($referral->attachments->where('is_result', false) as $attachment)
                                    @php
                                        $extension = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                        $fileConfig = [
                                            'pdf' => ['color' => 'bg-red-100 dark:bg-red-900/30', 'icon' => 'text-red-600 dark:text-red-400'],
                                            'jpg' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'jpeg' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'png' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'gif' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'doc' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'docx' => ['color' => 'bg-blue-100 dark:bg-blue-900/30', 'icon' => 'text-blue-600 dark:text-blue-400'],
                                            'default' => ['color' => 'bg-gray-100 dark:bg-gray-700', 'icon' => 'text-gray-600 dark:text-gray-400'],
                                        ];
                                        $config = $fileConfig[$extension] ?? $fileConfig['default'];
                                    @endphp
                                    
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                        <div class="flex items-start space-x-3">
                                            <div class="h-12 w-12 {{ $config['color'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                                                @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                                                    <svg class="h-6 w-6 {{ $config['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="h-6 w-6 {{ $config['icon'] }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                    {{ $attachment->file_name }}
                                                </p>
                                                <div class="flex items-center justify-between mt-2">
                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ strtoupper($extension) }}
                                                        </span>
                                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($attachment->created_at)->format('M d') }}
                                                        </span>
                                                    </div>
                                                    <button wire:click="downloadAttachment({{ $attachment->id }})" 
                                                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                                        Download
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                                <span class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $attachment->uploadedBy->name ?? 'Unknown' }}
                                                </span>
                                                <span class="capitalize">{{ $attachment->attachment_type->value }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Timeline & Results -->
                <div class="space-y-6">
                    <!-- Status Timeline Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                <svg class="h-5 w-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Referral Timeline</h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Status history and updates</p>
                            </div>
                        </div>
                        
                        <div class="space-y-6">
                            <!-- Created Timeline Item -->
                            <div class="flex">
                                <div class="flex flex-col items-center mr-4">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div class="h-full w-0.5 bg-gray-200 dark:bg-gray-700 mt-2"></div>
                                </div>
                                <div class="pb-6 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Created</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $referral->created_at->format('M d, Y H:i') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Referral document created</p>
                                </div>
                            </div>

                            <!-- Sent Timeline Item -->
                            @if($referral->status->value === 'sent' || $referral->status->value === 'completed')
                                <div class="flex">
                                    <div class="flex flex-col items-center mr-4">
                                        <div class="h-8 w-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                            </svg>
                                        </div>
                                        <div class="h-full w-0.5 bg-gray-200 dark:bg-gray-700 mt-2"></div>
                                    </div>
                                    <div class="pb-6 flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Sent to Facility</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $referral->referred_at->format('M d, Y H:i') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Referral transmitted to destination</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Completed Timeline Item -->
                            @if($referral->status->value === 'completed')
                                @php
                                    $resultAttachment = $referral->attachments->where('is_result', true)->first();
                                @endphp
                                <div class="flex">
                                    <div class="flex flex-col items-center mr-4">
                                        <div class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                            <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">Completed</p>
                                        @if($resultAttachment)
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $resultAttachment->created_at->format('M d, Y H:i') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Results submitted by facility</p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Result Section (if completed) -->
                    @if($referral->status->value === 'completed')
                        @php
                            $resultAttachments = $referral->attachments->where('is_result', true);
                            $resultNotes = $resultAttachments->where('file_path', 'result_notes')->first();
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                            <div class="flex items-center space-x-3 mb-6">
                                <div class="h-10 w-10 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Results & Findings</h2>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">From receiving facility</p>
                                </div>
                            </div>
                            
                            @if($resultNotes)
                                <div class="mb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Result Notes</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ strlen($resultNotes->additional_notes ?? '') }} characters</span>
                                    </div>
                                    <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                        <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $resultNotes->additional_notes ?? 'No notes provided' }}</p>
                                    </div>
                                </div>
                            @endif

                            @if($resultAttachments->where('file_path', '!=', 'result_notes')->isNotEmpty())
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Result Attachments</p>
                                    <div class="space-y-3">
                                        @foreach($resultAttachments->where('file_path', '!=', 'result_notes') as $attachment)
                                            <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                                <div class="flex items-center space-x-3">
                                                    <div class="h-9 w-9 rounded bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                                        <svg class="h-4 w-4 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $attachment->file_name }}</p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            {{ \Carbon\Carbon::parse($attachment->created_at)->format('M d, Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <button wire:click="downloadAttachment({{ $attachment->id }})" 
                                                        class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">
                                                    Download
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Quick Actions Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <a href="{{ route('referrals.print', $referral->id) }}" class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Print Referral</span>
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            
                            <a href="#" class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Email Facility</span>
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            
                            <a href="#" class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <div class="flex items-center space-x-3">
                                    <div class="h-8 w-8 rounded bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                        <svg class="h-4 w-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Export as PDF</span>
                                </div>
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            // Initialize Alpine.js dropdowns
            Alpine.start();
            
            // Add smooth scrolling for page loads
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Add print functionality
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                    e.preventDefault();
                    // Trigger print dialog
                    window.print();
                }
            });
            
            // Add attachment download feedback
            Livewire.on('attachmentDownloaded', function() {
                // You could show a toast notification here
                console.log('Attachment download initiated');
            });
        });
    </script>
    @endpush
</div>