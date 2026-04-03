<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 transition-colors duration-200">
    
    <!-- Alert Notification -->
    <div x-data="{ show: @entangle('showAlert'), autoHide: null }" 
         x-show="show" 
         x-init="
            Livewire.on('auto-hide-alert', () => {
                clearTimeout(autoHide);
                autoHide = setTimeout(() => { show = false; }, 3000);
            });
         "
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-x-2"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-2"
         class="fixed top-4 right-4 z-50 max-w-md w-full">
        @if($showAlert)
            <div class="rounded-xl shadow-2xl overflow-hidden backdrop-blur-sm">
                <div class="flex items-center justify-between p-4 {{ 
                    $alertType === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                    ($alertType === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                    'bg-gradient-to-r from-blue-500 to-indigo-600') }}">
                    <div class="flex items-center space-x-3">
                        <div class="h-8 w-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                            @if($alertType === 'success')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            @elseif($alertType === 'error')
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                        <div>
                            <p class="font-medium text-white">{{ $alertMessage }}</p>
                        </div>
                    </div>
                    <button @click="show = false" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(!$showReportForm && !$showSessionDetail)
            <!-- Header -->
            <div class="mb-8">
                <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 dark:from-gray-950 dark:via-gray-900 dark:to-gray-950 rounded-2xl shadow-2xl overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-32 translate-x-32 blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-black/10 rounded-full translate-y-24 -translate-x-24 blur-2xl"></div>
                    
                    <div class="relative p-6 md:p-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <div class="h-16 w-16 rounded-full bg-white/10 backdrop-blur-xl flex items-center justify-center border-2 border-white/20 shadow-xl">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 h-6 w-6 rounded-full bg-purple-400 border-2 border-white dark:border-gray-900 animate-pulse"></div>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-bold text-white">Cupping Treatment Queue</h1>
                                    <p class="text-purple-200 mt-1">Manage patient treatments and therapy sessions</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="bg-white/10 backdrop-blur-xl rounded-lg px-4 py-2">
                                    <span class="text-sm text-purple-200">Cupping Department</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Waiting Queue</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ count($queue) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">In Progress</p>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ count($inProgress) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Completed Today</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                                @php
                                    $completedToday = App\Models\CuppingSession::where('treatment_status', 'completed')
                                        ->whereDate('treatment_completed_at', today())
                                        ->count();
                                @endphp
                                {{ $completedToday }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Sessions</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-1">{{ count($queue) + count($inProgress) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- In Progress Sessions Section -->
            @if(count($inProgress) > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="h-8 w-1 bg-blue-500 rounded-full"></div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Currently In Progress</h2>
                        <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-xs">{{ count($inProgress) }}</span>
                    </div>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($inProgress as $session)
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center">
                                            <span class="text-sm font-bold text-blue-600 dark:text-blue-300">
                                                {{ strtoupper(substr($session->cuppingTherapy->encounter->patient->name ?? 'N/A', 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Session {{ $session->session_number }}/{{ $session->cuppingTherapy->total_sessions }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-300 rounded-full text-xs">
                                            <span class="relative flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                            </span>
                                            In Progress
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Started: {{ \Carbon\Carbon::parse($session->treatment_started_at)->format('h:i A') }}
                                    </div>
                                    <button wire:click="continueTreatment({{ $session->id }})"
                                        class="px-4 py-1.5 bg-blue-500 hover:bg-blue-600 text-white text-sm rounded-lg transition-colors">
                                        Continue Treatment
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Waiting Queue Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-1 bg-purple-500 rounded-full"></div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Waiting Queue</h2>
                        <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full text-xs">{{ count($queue) }}</span>
                    </div>
                </div>

                <div class="p-0">
                    @if(count($queue) > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Session</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Doctor's Order</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Items</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($queue as $item)
                                        @php $session = $item->cuppingSession; @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-full text-sm font-bold">
                                                    {{ $item->position }}
                                                </span>
                                              </td>
                                            <td class="px-6 py-4">
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">ID: #{{ $session->cuppingTherapy->encounter->patient->id ?? 'N/A' }}</p>
                                                </div>
                                              </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded text-xs font-medium">
                                                    {{ $session->session_number }}/{{ $session->cuppingTherapy->total_sessions }}
                                                </span>
                                                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($session->session_date)->format('M d, Y') }}</p>
                                              </td>
                                            <td class="px-6 py-4">
                                                <button wire:click="viewSessionDetail({{ $session->id }})"
                                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    View Order
                                                </button>
                                              </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1 max-w-md">
                                                    @foreach($session->items as $itemDetail)
                                                        <span class="inline-block text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                                            {{ $itemDetail->cuppingType->name }} ({{ $itemDetail->cuppingLocation->name }})
                                                            <span class="text-gray-500">x{{ $itemDetail->qty }}</span>
                                                        </span>
                                                    @endforeach
                                                </div>
                                              </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button wire:click="startTreatment({{ $session->id }})"
                                                    class="px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow">
                                                    Start Treatment
                                                </button>
                                              </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-16">
                            <div class="flex flex-col items-center">
                                <div class="h-24 w-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No pending treatments</h3>
                                <p class="text-gray-500 dark:text-gray-400">All caught up! Paid sessions will appear here automatically.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Session Detail Modal -->
        @if($showSessionDetail && $selectedDetailSession)
            <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                <div class="max-w-3xl w-full bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 dark:from-blue-700 dark:to-indigo-700 px-6 py-4 flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-white">Doctor's Order Details</h2>
                            <p class="text-blue-100 text-sm">Session {{ $selectedDetailSession->session_number }}/{{ $selectedDetailSession->cuppingTherapy->total_sessions }}</p>
                        </div>
                        <button wire:click="closeSessionDetail" class="text-white/80 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="p-6">
                        <!-- Patient Info -->
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Patient Name</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedDetailSession->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Doctor</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $selectedDetailSession->cuppingTherapy->doctor->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Treatment Date</p>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedDetailSession->session_date)->format('F d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Session Amount</p>
                                    <p class="font-semibold text-green-600">{{ number_format($selectedDetailSession->session_amount, 2) }} ETB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Doctor's Notes -->
                        @if($selectedDetailSession->cuppingTherapy->notes)
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Doctor's Notes</h3>
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                                    <p class="text-gray-700 dark:text-gray-300">{{ $selectedDetailSession->cuppingTherapy->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Prescribed Items -->
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Prescribed Items</h3>
                            <div class="space-y-2">
                                @foreach($selectedDetailSession->items as $item)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->cuppingType->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Location: {{ $item->cuppingLocation->name }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">Quantity: {{ $item->qty }}</p>
                                            <p class="text-sm font-medium text-green-600">{{ number_format($item->price, 2) }} ETB each</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button wire:click="closeSessionDetail" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Report Form Modal -->
        @if($showReportForm && $selectedSession)
            <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
                <div class="max-w-4xl w-full">
                    @livewire('cupping-department.report-form', ['session' => $selectedSession], key($selectedSession->id))
                    <div class="mt-4 flex justify-center gap-3">
                        <button wire:click="backToQueueWithoutSubmit" 
                            class="px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors shadow-lg">
                            ← Back to Queue (Save Progress)
                        </button>
                        <button wire:click="closeReportForm" 
                            class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors shadow-lg">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert-shown', () => {
                setTimeout(() => {
                    @this.dispatch('closeAlert');
                }, 5000);
            });
            
            Livewire.on('close-modal', () => {
                @this.dispatch('closeReportForm');
            });
        });
    </script>

    <style>
        [x-cloak] { display: none !important; }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.3s ease-out;
        }
    </style>
</div>