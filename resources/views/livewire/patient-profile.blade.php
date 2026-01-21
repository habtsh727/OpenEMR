<div class="min-h-screen bg-gray-50 py-5 rounded-2xl dark:bg-gray-900">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Header with Profile -->
        <div class="relative bg-gradient-to-r from-sky-500 to-sky-600 dark:from-sky-600 dark:to-sky-700 px-6 pt-8 pb-32 rounded-b-xl shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <!-- Patient Avatar -->
                    <div class="w-20 h-20 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-sky-500 dark:text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">{{ $patient->first_name }} {{ $patient->middle_name }} {{ $patient->last_name }}</h1>
                        <p class="text-sky-100 dark:text-sky-200">Mother: {{ $patient->mother_name ?? 'N/A' }}</p>
                        <p class="text-sky-100 dark:text-sky-200">Card: {{ $patient->card_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <!-- Status Badge -->
                <div class="px-4 py-2 bg-white dark:bg-gray-800 text-sky-600 dark:text-sky-400 rounded-full text-sm font-semibold shadow-lg">
                    @if($currentPriority)
                        {{ ucfirst($currentPriority) }} Priority
                    @else
                        Active
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="relative -mt-24 mx-6 grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            <!-- Age Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-sky-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">Age</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">
                    @if($patient->date_of_birth)
                        {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }}
                    @else
                        N/A
                    @endif
                </div>
            </div>
            <!-- Gender Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-emerald-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">Gender</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ ucfirst($patient->gender) ?? 'N/A' }}</div>
            </div>
            <!-- Contact Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-amber-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">Phone</div>
                <a href="tel:{{ $patient->phone_number1 }}" class="text-lg font-bold text-sky-600 dark:text-sky-400 hover:text-sky-700">
                    {{ $patient->phone_number1 ?? 'N/A' }}
                </a>
            </div>
            <!-- Balance Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">Total Due</div>
                <div class="text-2xl font-bold {{ $totalDue > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                    {{ number_format($totalDue, 2) }}
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mx-6 mb-6">
            <div class="flex overflow-x-auto gap-1 border-b border-slate-200 dark:border-slate-800" role="tablist">
                <button
                    wire:click="setActiveTab('overview')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'overview' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Overview
                    </div>
                </button>
                <button
                    wire:click="setActiveTab('vitals')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'vitals' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Vitals
                    </div>
                </button>
                <button
                    wire:click="setActiveTab('history')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'history' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Medical History
                    </div>
                </button>
                <button
                    wire:click="setActiveTab('services')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'services' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Services
                    </div>
                </button>
                <button
                    wire:click="setActiveTab('visits')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'visits' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Dr Visits
                    </div>
                </button>
                <button
                    wire:click="setActiveTab('financial')"
                    class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'financial' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Financial
                    </div>
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="mx-6">
            <!-- Overview Tab -->
            @if($activeTab === 'overview')
            <div class="space-y-6">
                <!-- Identity & Contact -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-emerald-500 rounded"></div>
                        Identity & Contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Card Number</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->card_number ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Date of Birth</div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                @if($patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Phone 2</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->phone_number2 ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-amber-500 rounded"></div>
                        Emergency Contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Person</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->emergency_person ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Contact</div>
                            <a href="tel:{{ $patient->emergency_contact }}" class="text-sky-600 dark:text-sky-400 hover:text-sky-700 font-medium">
                                {{ $patient->emergency_contact ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Relationship</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->emergency_person_relationship ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-purple-500 rounded"></div>
                        Location Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Region</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Zone</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region_zone ?? 'N/A' }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Woreda</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region_woreda ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Last Visit Information -->
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-500 rounded"></div>
                        Visit Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Last Visit</div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                @if($lastVisit)
                                    {{ $lastVisit->format('d M Y, h:i A') }}
                                @else
                                    No visits yet
                                @endif
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">Created By</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->createdBy->name ?? 'System' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Vitals Tab -->
            @if($activeTab === 'vitals')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Vital Signs
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Vital Records</h1>
            </div>
            @endif

            <!-- Medical History Tab -->
            @if($activeTab === 'history')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    Medical History
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Medical History</h1>
            </div>
            @endif

            <!-- Services Tab -->
            @if($activeTab === 'services')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Services
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Services</h1>
            </div>
            @endif

            <!-- Dr Visits Tab -->
            @if($activeTab === 'visits')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Doctor Visits
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Doctor Visits</h1>
            </div>
            @endif

            <!-- Financial Tab -->
            @if($activeTab === 'financial')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Financial Information
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Financial Information</h1>
            </div>
            @endif
        </div>
    </div>
</div>