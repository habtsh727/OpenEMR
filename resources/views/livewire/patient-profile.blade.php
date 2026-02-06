<div class="min-h-screen bg-gray-50 py-5 rounded-2xl dark:bg-gray-900">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Header with Profile -->
        <div
            class="relative bg-gradient-to-r from-sky-500 to-sky-600 dark:from-sky-600 dark:to-sky-700 px-6 pt-8 pb-32 rounded-b-xl shadow-lg">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <!-- Patient Avatar -->
                    <div
                        class="w-20 h-20 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-sky-500 dark:text-sky-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                    </div>
                    <div class="text-white">
                        <h1 class="text-2xl font-bold">{{ $patient->first_name }} {{ $patient->middle_name }} {{
                            $patient->last_name }}</h1>
                        <p class="text-sky-100 dark:text-sky-200">Mother: {{ $patient->mother_name ?? 'N/A' }}</p>
                        <p class="text-sky-100 dark:text-sky-200">Card: {{ $patient->card_number ?? 'N/A' }}</p>
                    </div>
                </div>
                <!-- Status Badge -->
                <div
                    class="px-4 py-2 bg-white dark:bg-gray-800 text-sky-600 dark:text-sky-400 rounded-full text-sm font-semibold shadow-lg">
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
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">Age
                </div>
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
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">
                    Gender</div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ ucfirst($patient->gender) ?? 'N/A' }}
                </div>
            </div>
            <!-- Contact Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-amber-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">
                    Phone</div>
                <a href="tel:{{ $patient->phone_number1 }}"
                    class="text-lg font-bold text-sky-600 dark:text-sky-400 hover:text-sky-700">
                    {{ $patient->phone_number1 ?? 'N/A' }}
                </a>
            </div>
            <!-- Balance Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border-l-4 border-purple-500">
                <div class="text-xs text-slate-600 dark:text-slate-400 font-semibold uppercase tracking-wider mb-1">
                    Total Due</div>
                <div
                    class="text-2xl font-bold {{ $totalDue > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                    {{ number_format($totalDue, 2) }}
                </div>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="mx-6 mb-6">
            <div class="flex overflow-x-auto gap-1 border-b border-slate-200 dark:border-slate-800" role="tablist">
                <button wire:click="setActiveTab('overview')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'overview' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                        </svg>
                        Overview
                    </div>
                </button>
                <button wire:click="setActiveTab('vitals')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'vitals' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Vitals
                    </div>
                </button>
                <button wire:click="setActiveTab('history')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'history' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                        Medical History
                    </div>
                </button>
                <button wire:click="setActiveTab('services')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'services' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Services
                    </div>
                </button>
                <button wire:click="setActiveTab('visits')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'visits' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Dr Visits
                    </div>
                </button>
                <button wire:click="setActiveTab('financial')" class="px-6 py-3 text-sm font-medium border-b-2 transition whitespace-nowrap
                        {{ $activeTab === 'financial' 
                            ? 'text-slate-900 dark:text-white border-slate-900 dark:border-white' 
                            : 'text-slate-600 dark:text-slate-400 border-transparent hover:border-slate-300' }}">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                    <h3
                        class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-emerald-500 rounded"></div>
                        Identity & Contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Card Number</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->card_number ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Date of Birth</div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                @if($patient->date_of_birth)
                                {{ \Carbon\Carbon::parse($patient->date_of_birth)->format('d M Y') }}
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Phone 2</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->phone_number2 ?? 'N/A'
                                }}</div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div>
                    <h3
                        class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-amber-500 rounded"></div>
                        Emergency Contact
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Person</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->emergency_person ??
                                'N/A' }}</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Contact</div>
                            <a href="tel:{{ $patient->emergency_contact }}"
                                class="text-sky-600 dark:text-sky-400 hover:text-sky-700 font-medium">
                                {{ $patient->emergency_contact ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Relationship</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{
                                $patient->emergency_person_relationship ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Location Information -->
                <div>
                    <h3
                        class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-purple-500 rounded"></div>
                        Location Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Region</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Zone</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region_zone ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Woreda</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->region_woreda ?? 'N/A'
                                }}</div>
                        </div>
                    </div>
                </div>

                <!-- Last Visit Information -->
                <div>
                    <h3
                        class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                        <div class="w-1 h-5 bg-cyan-500 rounded"></div>
                        Visit Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Last Visit</div>
                            <div class="text-slate-900 dark:text-white font-medium">
                                @if($lastVisit)
                                {{ $lastVisit->format('d M Y, h:i A') }}
                                @else
                                No visits yet
                                @endif
                            </div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-4">
                            <div
                                class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-widest mb-2">
                                Created By</div>
                            <div class="text-slate-900 dark:text-white font-medium">{{ $patient->createdBy->name ??
                                'System' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

           <!-- Vitals Tab -->
@if($activeTab === 'vitals')
<div class="space-y-6">
    <!-- Header with Stats -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Vital Signs History
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Historical vital sign measurements</p>
        </div>

        <!-- Quick Stats -->
        <div class="flex items-center gap-3">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-semibold dark:text-white">{{ $this->vitalsHistory->count() }}</span>
                encounters
            </div>
            @php
                $totalVitals = 0;
                foreach($this->vitalsHistory as $encounter) {
                    $totalVitals += $encounter->vitals->count();
                }
            @endphp
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-semibold dark:text-white">{{ $totalVitals }}</span>
                vital readings
            </div>
        </div>
    </div>

    @if($this->vitalsHistory->isEmpty())
    <!-- Empty State -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No vital signs recorded</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-4">Vital signs will appear here after triage assessment</p>
    </div>
    @else
    <!-- Filter Options -->
    <div class="flex items-center justify-between bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Showing vitals from {{ $this->vitalsHistory->count() }} encounters
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 dark:text-gray-400">Group by:</label>
            <select wire:model="vitalGrouping" class="text-sm border border-gray-300 dark:border-gray-600 rounded px-2 py-1 bg-white dark:bg-gray-800">
                <option value="encounter">Encounter</option>
                <option value="vital_type">Vital Type</option>
            </select>
        </div>
    </div>

    @if($vitalGrouping === 'encounter')
    <!-- Encounter-based View -->
    @foreach($this->vitalsHistory as $encounter)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Encounter Header -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Encounter #{{ $encounter->id }}
                    </h3>
                    <div class="flex items-center gap-4 mt-1">
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $encounter->created_at->format('d M Y, h:i A') }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($encounter->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                            @elseif($encounter->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                            @elseif($encounter->priority === 'medium') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                            @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 @endif">
                            {{ ucfirst($encounter->priority) }} Priority
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($encounter->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                            @elseif($encounter->status === 'triaged') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                            @elseif($encounter->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">
                            {{ ucfirst($encounter->status) }}
                        </span>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $encounter->vitals->count() }} vitals recorded
                </div>
            </div>
        </div>

        <!-- Vital Signs Grid -->
        <div class="p-6">
            @if($encounter->vitals->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($encounter->vitals as $vital)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-white">
                                {{ $vital->vitalType->name }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $vital->created_at->format('h:i A') }}
                            </p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full 
                            @if($vital->vitalType->slug == 'bp_systolic' || $vital->vitalType->slug == 'bp_diastolic')
                                bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                            @elseif($vital->vitalType->slug == 'temperature')
                                bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                            @elseif($vital->vitalType->slug == 'pulse_rate')
                                bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                            @elseif($vital->vitalType->slug == 'spo2')
                                bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @else
                                bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                            @endif">
                            {{ $vital->vitalType->unit ?? 'N/A' }}
                        </span>
                    </div>
                    
                    <div class="mt-3">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">
                            {{ $vital->getFormattedValue() }}
                        </div>
                        
                        <!-- Value status indicator -->
                        @if($vital->vitalType->data_type === 'number')
                        <div class="mt-2">
                            @php
                                $value = floatval($vital->value);
                                $status = 'normal';
                                
                                // Basic range checking (you can customize these per vital type)
                                if($vital->vitalType->slug == 'bp_systolic') {
                                    if($value > 140) $status = 'high';
                                    elseif($value < 90) $status = 'low';
                                } elseif($vital->vitalType->slug == 'bp_diastolic') {
                                    if($value > 90) $status = 'high';
                                    elseif($value < 60) $status = 'low';
                                } elseif($vital->vitalType->slug == 'temperature') {
                                    if($value > 37.5) $status = 'high';
                                    elseif($value < 36.0) $status = 'low';
                                } elseif($vital->vitalType->slug == 'pulse_rate') {
                                    if($value > 100) $status = 'high';
                                    elseif($value < 60) $status = 'low';
                                } elseif($vital->vitalType->slug == 'spo2') {
                                    if($value < 92) $status = 'critical';
                                    elseif($value < 95) $status = 'low';
                                }
                            @endphp
                            
                            <span class="inline-flex items-center text-sm 
                                @if($status == 'critical') text-red-600 dark:text-red-400
                                @elseif($status == 'high' || $status == 'low') text-yellow-600 dark:text-yellow-400
                                @else text-green-600 dark:text-green-400 @endif">
                                @if($status == 'critical')
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                                Critical
                                @elseif($status == 'high')
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                                High
                                @elseif($status == 'low')
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Low
                                @else
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Normal
                                @endif
                            </span>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Recorded by -->
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                    {{ strtoupper(substr($vital->user->name ?? 'N', 0, 1)) }}
                                </span>
                            </div>
                            <div class="ml-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Recorded by {{ $vital->user->name ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <p class="text-gray-500 dark:text-gray-400">No vital signs recorded for this encounter</p>
            </div>
            @endif
        </div>
    </div>
    @endforeach

    @else
    <!-- Vital Type-based View -->
    @php
        // Group vitals by type
        $groupedVitals = [];
        foreach($this->vitalsHistory as $encounter) {
            foreach($encounter->vitals as $vital) {
                $typeId = $vital->vital_type_id;
                if(!isset($groupedVitals[$typeId])) {
                    $groupedVitals[$typeId] = [
                        'type' => $vital->vitalType,
                        'readings' => []
                    ];
                }
                $groupedVitals[$typeId]['readings'][] = [
                    'value' => $vital,
                    'encounter' => $encounter,
                    'timestamp' => $vital->created_at
                ];
            }
        }
    @endphp

    @foreach($groupedVitals as $groupId => $group)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        {{ $group['type']->name }}
                    </h3>
                    @if($group['type']->unit)
                    <span class="text-sm px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">
                        {{ $group['type']->unit }}
                    </span>
                    @endif
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ count($group['readings']) }} readings
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Latest Value -->
            @php
                $latestReading = collect($group['readings'])->sortByDesc('timestamp')->first();
            @endphp
            <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Latest Reading</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $latestReading['value']->getFormattedValue() }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $latestReading['timestamp']->format('d M Y, h:i A') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Encounter</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            #{{ $latestReading['encounter']->id }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- History Chart -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">History</h4>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date & Time</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Value</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Encounter</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Recorded By</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach(collect($group['readings'])->sortByDesc('timestamp')->take(10) as $reading)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        {{ $reading['timestamp']->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $reading['timestamp']->format('h:i A') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-lg font-medium text-gray-900 dark:text-white">
                                        {{ $reading['value']->getFormattedValue() }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                        Encounter #{{ $reading['encounter']->id }}
                                    </a>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ ucfirst($reading['encounter']->status) }}
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if($reading['encounter']->priority === 'critical') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                        @elseif($reading['encounter']->priority === 'high') bg-orange-100 dark:bg-orange-900/30 text-orange-800 dark:text-orange-300
                                        @elseif($reading['encounter']->priority === 'medium') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                        @else bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300 @endif">
                                        {{ ucfirst($reading['encounter']->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="h-6 w-6 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                {{ strtoupper(substr($reading['value']->user->name ?? 'N', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div class="ml-2">
                                            <p class="text-sm text-gray-900 dark:text-white">
                                                {{ $reading['value']->user->name ?? 'Unknown' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

    <!-- Pagination -->
    {{-- @if($this->vitalsHistory->hasPages())
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
        {{ $this->vitalsHistory->links() }}
    </div>
    @endif --}}
    @endif
</div>
@endif
            <!-- Medical History Tab -->
            
            <!-- Medical History Tab -->
          @if($activeTab === 'history')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Medical History
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Recorded medical conditions and history</p>
        </div>

        <!-- Stats -->
        <div class="flex items-center gap-3">
            <div class="text-sm text-gray-600 dark:text-gray-400">
                <span class="font-semibold dark:text-white">{{ $this->medicalHistoryStats['total_conditions'] }}</span> conditions recorded
            </div>
        </div>
    </div>

    @if($this->medicalHistoryByEncounter->isEmpty())
    <!-- Empty State -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
        <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No medical history recorded</h3>
        <p class="text-gray-500 dark:text-gray-400">Medical history will appear here after doctor consultations</p>
    </div>
    @else
    <!-- Medical History Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Conditions</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->medicalHistoryStats['total_conditions'] }}</div>
                </div>
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-500 dark:text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Conditions</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->medicalHistoryStats['active_conditions'] }}</div>
                </div>
                <div class="p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-red-500 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Updated</div>
                    <div class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                        @if($this->medicalHistoryStats['last_updated'])
                            {{ $this->medicalHistoryStats['last_updated']->format('d M Y') }}
                        @else
                            Never
                        @endif
                    </div>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="w-5 h-5 text-blue-500 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Medical History by Encounter -->
    <div class="space-y-6">
        @foreach($this->medicalHistoryByEncounter as $encounter)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Encounter Header -->
            <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-900 dark:text-white">Encounter #{{ $encounter->id }}</h3>
                        <div class="flex items-center space-x-4 mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <span>{{ $encounter->created_at->format('d M Y, h:i A') }}</span>
                            <span>•</span>
                            @if($encounter->doctor)
                                <span>Dr. {{ $encounter->doctor->name }}</span>
                            @endif
                            <span>•</span>
                            <span class="capitalize">{{ $encounter->status }}</span>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $encounter->medicalHistories->count() }} conditions
                    </div>
                </div>
            </div>

            <!-- Conditions List -->
            <div class="p-6">
                @if($encounter->medicalHistories->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 italic">No medical history recorded for this encounter</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($encounter->medicalHistories as $history)
                        <div class="border rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-start justify-between mb-2">
                                <span class="font-medium text-gray-900 dark:text-white">{{ $history->template->name ?? 'Unknown Condition' }}</span>
                                @if($history->value === 'yes')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">Yes</span>
                                @elseif($history->value === 'no')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">No</span>
                                @else
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $history->value ?? 'Not recorded' }}</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Updated: {{ $history->updated_at->format('d M Y') }}</div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Active Conditions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                Active Conditions
            </h3>
            @php
                $activeConditions = [];
                foreach($this->medicalHistoryByEncounter as $encounter) {
                    foreach($encounter->medicalHistories as $history) {
                        if($history->value === 'yes') {
                            $activeConditions[$history->template->name ?? 'Unknown'] = $history;
                        }
                    }
                }
            @endphp
            @if(empty($activeConditions))
                <p class="text-gray-500 dark:text-gray-400 text-sm italic">No active conditions</p>
            @else
                <div class="space-y-2">
                    @foreach($activeConditions as $condition => $history)
                        <div class="flex items-center justify-between p-2 bg-red-50 dark:bg-red-900/10 rounded">
                            <span class="text-sm text-gray-900 dark:text-white">{{ $condition }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Last: {{ $history->updated_at->format('d M') }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Recent Updates -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                Recent Updates
            </h3>
            @php
                $allHistory = [];
                foreach($this->medicalHistoryByEncounter as $encounter) {
                    foreach($encounter->medicalHistories as $history) {
                        $allHistory[] = $history;
                    }
                }
                usort($allHistory, function($a, $b) {
                    return $b->updated_at <=> $a->updated_at;
                });
                $recentHistory = array_slice($allHistory, 0, 3);
            @endphp
            @if(empty($recentHistory))
                <p class="text-gray-500 dark:text-gray-400 text-sm italic">No recent updates</p>
            @else
                <div class="space-y-3">
                    @foreach($recentHistory as $history)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $history->template->name ?? 'Unknown Condition' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Updated {{ $history->updated_at->diffForHumans() }}
                                    <span class="mx-1">•</span>
                                    Status: <span class="{{ $history->value === 'yes' ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                        {{ ucfirst($history->value) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endif
</div>
@endif
            <!-- Services Tab -->
            @if($activeTab === 'services')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    Services
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Services</h1>
            </div>
            @endif

            <!-- Dr Visits Tab -->
            {{-- @if($activeTab === 'visits')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Doctor Visits
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Doctor Visits</h1>
            </div>
            @endif --}}
            <!-- Dr Visits Tab -->
            @if($activeTab === 'visits')
            <div class="space-y-6">
                <!-- Header -->
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Doctor Consultations
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">All doctor consultations and
                            assessments</p>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-3">
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            Total: <span class="font-semibold dark:text-white">{{ $this->doctorVisits->count() }}</span>
                            consultations
                        </div>
                    </div>
                </div>

                @if($this->doctorVisits->isEmpty())
                <!-- Empty State -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mx-auto mb-4" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No doctor consultations yet</h3>
                    <p class="text-gray-500 dark:text-gray-400">Doctor consultations will appear here after assignment
                    </p>
                </div>
                @else
                <!-- Doctor Visits Table -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Assigned Doctor
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        Assessment Result
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($this->doctorVisits as $visit)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <!-- Assigned Doctor -->
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-100 to-purple-200 dark:from-purple-900/30 dark:to-purple-800/30 flex items-center justify-center shadow-sm">
                                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400"
                                                    fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    @if($visit->doctor)
                                                    Dr. {{ $visit->doctor->name }}
                                                    @else
                                                    <span class="text-gray-400 dark:text-gray-500 italic">Not
                                                        assigned</span>
                                                    @endif
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    Encounter #{{ $visit->id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $visit->created_at->format('d M Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $visit->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    <!-- Status -->
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    @if($visit->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                    @elseif($visit->status === 'doctor_assigned') bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300
                                    @elseif($visit->status === 'completed') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                    @elseif($visit->status === 'cancelled') bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                    @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @endif">

                                            @if($visit->status === 'doctor_assigned')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            @elseif($visit->status === 'completed')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            @endif

                                            {{ ucfirst($visit->status) }}
                                        </span>
                                    </td>

                                    <!-- Assessment Result -->
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $visit->assessment_result ?? 'No assessment recorded' }}
                                            </div>
                                            @if($visit->status === 'completed')
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Consultation completed
                                            </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Total Consultations</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $this->doctorVisits->count() }}
                                </div>
                            </div>
                            <div class="p-2 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-purple-500 dark:text-purple-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Completed</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $this->doctorVisits->where('status', 'completed')->count() }}
                                </div>
                            </div>
                            <div class="p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-green-500 dark:text-green-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div
                                    class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Pending</div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $this->doctorVisits->whereIn('status', ['pending', 'doctor_assigned'])->count()
                                    }}
                                </div>
                            </div>
                            <div class="p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                <svg class="w-5 h-5 text-yellow-500 dark:text-yellow-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            <!-- Financial Tab -->
            @if($activeTab === 'financial')
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Financial Information
                </h2>
                <h1 class="text-center text-gray-500 dark:text-gray-400 text-lg py-8">This is Financial Information</h1>
            </div>
            @endif
        </div>
    </div>
</div>