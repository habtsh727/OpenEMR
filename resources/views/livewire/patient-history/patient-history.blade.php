<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900">
    <!-- Header Section -->
    <div class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Patient Profile</h1>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Comprehensive patient management system</p>
                </div>
               
            </div>

            <!-- Tab Navigation -->
            <div class="flex overflow-x-auto gap-1 border-b border-slate-200 dark:border-slate-800" role="tablist">
                <button
                    class="tab-btn active px-6 py-3 text-sm font-medium text-slate-900 dark:text-white border-b-2 border-slate-900 dark:border-white transition"
                    data-tab="overview">
                    Overview
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition"
                    data-tab="vitals">
                    Vitals
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition"
                    data-tab="history">
                    Medical History
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition"
                    data-tab="services">
                    Services
                </button>
                <button
                    class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition"
                    data-tab="visits">
                    Dr Visits
                </button>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- OVERVIEW TAB -->
        <div id="overview" class="tab-content space-y-8">
            <!-- Patient Information Card -->
            <div
                class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">

                {{-- Header --}}
                <div class="pb-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Patient Information</h2>

                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        Last updated: {{ $patient->updated_at }}
                    </span>
                </div>

                {{-- INFO GRID --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

                    {{-- FIRST NAME --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">First Name</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->first_name }}
                        </p>
                    </div>

                    {{-- Father Name --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Father Name</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->middle_name }}
                        </p>
                    </div>

                    {{-- Last Name --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Grandfather Name
                        </p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->last_name }}
                        </p>
                    </div>

                    {{-- Card Number --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Card Number</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->card_number }}
                        </p>
                    </div>

                    {{-- PHONE --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Phone Number</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->phone_number1 }}
                            @if ($patient->phone_number2)
                                <span class="text-slate-500 text-sm"> / {{ $patient->phone_number2 }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- GENDER --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Gender</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ ucfirst($patient->gender) }}
                        </p>
                    </div>

                    {{-- DOB --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Date of Birth</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->date_of_birth }}
                        </p>
                    </div>

                    {{-- LAST VISIT --}}
                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Last Visit</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->last_visit_at ? $patient->last_visit_at : '—' }}
                        </p>
                    </div>

                </div>

                {{-- LOCATION --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Region</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">{{ $patient->region }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Zone</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">{{ $patient->region_zone }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Woreda</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">{{ $patient->region_woreda }}</p>
                    </div>

                </div>

                {{-- EMERGENCY CONTACT --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Emergency Person
                        </p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">{{ $patient->emergency_person }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Relationship</p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">
                            {{ $patient->emergency_person_relationship }}</p>
                    </div>

                    <div>
                        <p class="text-xs uppercase text-slate-500 dark:text-slate-400 font-semibold">Emergency Contact
                        </p>
                        <p class="mt-1 text-slate-900 dark:text-white font-medium">{{ $patient->emergency_contact }}
                        </p>
                    </div>

                </div>

            </div>


            <!-- Quick Stats -->
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Total Due -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border p-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase mb-2">Total Due</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ number_format($patient->cardPayments->where('is_paid', 0)->sum('amount'), 2) }} ETB
                    </p>
                    <p class="text-xs text-slate-500 mt-2">
                        {{ $patient->cardPayments->where('is_paid', 0)->count() }} pending invoices
                    </p>
                </div>

                <!-- Last Visit -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border p-6">
                    <p class="text-xs font-semibold text-slate-600 uppercase mb-2">Last Visit</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ optional($patient->vital->last())->created_at?->format('M d, Y') ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-slate-500 mt-2">Recorded by nurse</p>
                </div>

                <!-- Total Vital Records -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border p-6">
                    <p class="text-xs font-semibold text-slate-600 uppercase mb-2">Vital Records</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ $patient->vital->count() }}
                    </p>
                    <p class="text-xs text-slate-500 mt-2">
                        Latest included
                    </p>
                </div>

                <!-- Priority -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border p-6">
                    <p class="text-xs font-semibold text-slate-600 uppercase mb-2">Current Priority</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">
                        {{ strtoupper(optional($patient->vital->last())->priority ?? 'N/A') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-2">
                        Based on last triage
                    </p>
                </div>
            </div>

        </div>

        <!-- VITALS TAB -->
        <div id="vitals" class="tab-content hidden space-y-8">

            <!-- Latest Vitals Section -->


            <!-- Vital Cards -->
            @php
                $latest = $patient->vital->last();
            @endphp

            <div class="bg-white dark:bg-slate-900 rounded-2xl border shadow-lg p-8 mt-8">
                <div class="pb-6 border-b">
                    <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Latest Vital Signs</h2>

                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        Recorded: {{ $latest?->created_at?->format('M d, Y h:i A') ?? 'No records yet' }}
                    </p>
                </div>

                @if ($latest)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">

                        <!-- Blood Pressure -->
                        <div class="p-6 rounded-xl border shadow-md bg-gradient-to-br from-green-50 to-white">
                            <p class="text-xs font-semibold text-green-700 uppercase">Blood Pressure</p>
                            <p class="text-4xl font-extrabold mt-3">
                                {{ $latest->bp_systolic }}/{{ $latest->bp_diastolic }}
                            </p>
                            <p class="text-xs text-green-700 mt-2">Measured</p>
                        </div>

                        <!-- Heart Rate -->
                        <div class="p-6 rounded-xl border shadow-md bg-gradient-to-br from-blue-50 to-white">
                            <p class="text-xs font-semibold text-blue-700 uppercase">Heart Rate</p>
                            <p class="text-4xl font-extrabold mt-3">{{ $latest->pulse }}</p>
                            <p class="text-xs text-blue-700 mt-2">bpm</p>
                        </div>

                        <!-- Temperature -->
                        <div class="p-6 rounded-xl border shadow-md bg-gradient-to-br from-orange-50 to-white">
                            <p class="text-xs font-semibold text-orange-700 uppercase">Temperature</p>
                            <p class="text-4xl font-extrabold mt-3">{{ $latest->temperature }}°C</p>
                            <p class="text-xs text-orange-700 mt-2">Measured</p>
                        </div>

                        <!-- Oxygen -->
                        <div class="p-6 rounded-xl border shadow-md bg-gradient-to-br from-purple-50 to-white">
                            <p class="text-xs font-semibold text-purple-700 uppercase">Oxygen Level</p>
                            <p class="text-4xl font-extrabold mt-3">{{ $latest->spo2 }}%</p>
                            <p class="text-xs text-purple-700 mt-2">SpO₂</p>
                        </div>

                    </div>
                @else
                    <div class="text-center py-10 text-slate-500">
                        No vital signs recorded for this patient.
                    </div>
                @endif

                <!-- Vital History Table -->
                <div class="mt-12">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Vital History</h3>

                    <div class="overflow-x-auto border rounded-xl">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                <tr>
                                    <th class="px-4 py-3">Date</th>
                                    <th class="px-4 py-3">BP</th>
                                    <th class="px-4 py-3">Pulse</th>
                                    <th class="px-4 py-3">Temp</th>
                                    <th class="px-4 py-3">SpO₂</th>
                                    <th class="px-4 py-3">Priority</th>
                                    <th class="px-4 py-3">Processed By</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($patient->vital as $v)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $v->created_at->format('M d, Y h:i A') }}</td>
                                        <td class="px-4 py-3">{{ $v->bp_systolic }}/{{ $v->bp_diastolic }}</td>
                                        <td class="px-4 py-3">{{ $v->pulse }}</td>
                                        <td class="px-4 py-3">{{ $v->temperature }}°C</td>
                                        <td class="px-4 py-3">{{ $v->spo2 }}%</td>
                                        <td class="px-4 py-3">{{ strtoupper($v->priority) }}</td>
                                        <td class="px-4 py-3">{{ $v->processed_by }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <!-- MEDICAL HISTORY TAB -->
        <div id="history" class="tab-content hidden space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Medical History</h2>
                </div>

                <div class="space-y-4 mt-6">
                    <div
                        class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Hypertension</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Diagnosed: January 2023 •
                                Status: Active</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Type 2 Diabetes</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Diagnosed: June 2020 •
                                Status:
                                Managed</p>
                        </div>
                    </div>

                    <div
                        class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Allergies: Penicillin</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Severity: Moderate •
                                Reaction:
                                Rash</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- SERVICES TAB -->
        <div id="services" class="tab-content hidden space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Services Assigned</h2>
                </div>

                <div class="overflow-x-auto mt-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">
                                    Service Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">
                                    Assigned Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">
                                    Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">
                                    Cost</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">General Checkup</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 1, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$100</td>
                                <td class="px-4 py-3 text-sm">
                                    <button
                                        class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">Blood Test</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 5, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded text-xs font-medium">Pending</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$75</td>
                                <td class="px-4 py-3 text-sm">
                                    <button
                                        class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">Dental Cleaning</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 10, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span
                                        class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded text-xs font-medium">Scheduled</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$60</td>
                                <td class="px-4 py-3 text-sm">
                                    <button
                                        class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <button
                        class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                        Assign New Service
                    </button>
                </div>
            </div>
        </div>

        <!-- PAYMENTS TAB -->


        <!-- DR VISITS TAB -->
        <div id="visits" class="tab-content hidden space-y-8">
            <div
                class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Doctor Visits</h2>
                </div>

                <div class="space-y-4 mt-6">
                    <div
                        class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Sarah Johnson</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">December 1, 2024 • 10:30 AM
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Routine checkup, blood pressure
                            slightly elevated. Prescribed new medication.</p>
                        <div class="mt-3 flex gap-2">
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View
                                Notes</button>
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>

                    <div
                        class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Michael Chen</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">November 15, 2024 • 2:00 PM
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Follow-up on diabetes
                            management.
                            Blood sugar levels stable.</p>
                        <div class="mt-3 flex gap-2">
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View
                                Notes</button>
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>

                    <div
                        class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Emily Davis</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">October 20, 2024 • 3:15 PM
                                </p>
                            </div>
                            <span
                                class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Specialist consultation for
                            hypertension. Adjusted medication dosage.</p>
                        <div class="mt-3 flex gap-2">
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View
                                Notes</button>
                            <button
                                class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <button
                        class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                        Schedule New Visit
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Tab switching functionality
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const tabName = btn.getAttribute('data-tab');

                // Remove active state from all buttons
                document.querySelectorAll('.tab-btn').forEach(b => {
                    b.classList.remove('active', 'text-slate-900', 'dark:text-white',
                        'border-slate-900', 'dark:border-white');
                    b.classList.add('text-slate-600', 'dark:text-slate-400', 'border-transparent');
                });

                // Add active state to clicked button
                btn.classList.add('active', 'text-slate-900', 'dark:text-white', 'border-slate-900',
                    'dark:border-white');
                btn.classList.remove('text-slate-600', 'dark:text-slate-400', 'border-transparent');

                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });

                // Show selected tab content
                document.getElementById(tabName).classList.remove('hidden');
            });
        });
    </script>
