<div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100 dark:from-slate-950 dark:to-slate-900">
    <!-- Header Section -->
    <div class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Patient Profile</h1>
                    <p class="text-slate-600 dark:text-slate-400 mt-1">Comprehensive patient management system</p>
                </div>
                <button class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                    Save Changes
                </button>
            </div>

            <!-- Tab Navigation -->
            <div class="flex overflow-x-auto gap-1 border-b border-slate-200 dark:border-slate-800" role="tablist">
                <button class="tab-btn active px-6 py-3 text-sm font-medium text-slate-900 dark:text-white border-b-2 border-slate-900 dark:border-white transition" data-tab="overview">
                    Overview
                </button>
                <button class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition" data-tab="vitals">
                    Vitals
                </button>
                <button class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition" data-tab="history">
                    Medical History
                </button>
                <button class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition" data-tab="services">
                    Services
                </button>
                <button class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition" data-tab="payments">
                    Payments
                </button>
                <button class="tab-btn px-6 py-3 text-sm font-medium text-slate-600 dark:text-slate-400 border-b-2 border-transparent hover:border-slate-300 transition" data-tab="visits">
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
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Patient Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">First Name</label>
                        <input type="text" placeholder="John" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Last Name</label>
                        <input type="text" placeholder="Doe" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Date of Birth</label>
                        <input type="date" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Phone Number</label>
                        <input type="tel" placeholder="+1 (555) 000-0000" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Email Address</label>
                        <input type="email" placeholder="john@example.com" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Patient ID</label>
                        <input type="text" placeholder="PT-001234" disabled class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800 text-slate-500 dark:text-slate-400 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Blood Type</label>
                        <select class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                            <option value="">Select Blood Type</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Gender</label>
                        <select class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Insurance ID</label>
                        <input type="text" placeholder="INS-789456" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 p-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Total Due</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">$1,250</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">3 pending invoices</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 p-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Last Visit</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">Dec 1</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">With Dr. Johnson</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 p-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Active Services</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">5</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">3 completed</p>
                </div>
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 p-6">
                    <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide mb-2">Allergies</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">1</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Penicillin</p>
                </div>
            </div>
        </div>

        <!-- VITALS TAB -->
        <div id="vitals" class="tab-content hidden space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-2">Latest Vital Signs</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Recorded: Today at 2:30 PM</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
                    <!-- Blood Pressure -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Blood Pressure</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">120/80</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">mmHg • Normal</p>
                    </div>

                    <!-- Heart Rate -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Heart Rate</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">72</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">bpm • Normal</p>
                    </div>

                    <!-- Temperature -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Temperature</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">98.6</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">°F • Normal</p>
                    </div>

                    <!-- Oxygen Level -->
                    <div class="p-6 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Oxygen Level</p>
                        <p class="text-3xl font-bold text-slate-900 dark:text-white mt-2">98%</p>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2">SpO2 • Normal</p>
                    </div>
                </div>

                <!-- Vital Signs Input Form -->
                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Add New Vital Signs</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Systolic BP</label>
                            <input type="number" placeholder="120" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Diastolic BP</label>
                            <input type="number" placeholder="80" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Heart Rate</label>
                            <input type="number" placeholder="72" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Temperature</label>
                            <input type="number" placeholder="98.6" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                        </div>
                    </div>
                    <button class="mt-6 px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                        Record Vitals
                    </button>
                </div>
            </div>
        </div>

        <!-- MEDICAL HISTORY TAB -->
        <div id="history" class="tab-content hidden space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Medical History</h2>
                </div>

                <div class="space-y-4 mt-6">
                    <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Hypertension</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Diagnosed: January 2023 • Status: Active</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Type 2 Diabetes</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Diagnosed: June 2020 • Status: Managed</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                        <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-slate-400"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-slate-900 dark:text-white">Allergies: Penicillin</p>
                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Severity: Moderate • Reaction: Rash</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6">Add Medical History</h3>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Condition</label>
                                <input type="text" placeholder="e.g., Hypertension" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Diagnosis Date</label>
                                <input type="date" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-900 dark:text-white mb-2">Notes</label>
                            <textarea placeholder="Additional notes..." rows="4" class="w-full px-4 py-2 border border-slate-300 dark:border-slate-700 rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-900"></textarea>
                        </div>
                        <button class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                            Add to History
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- SERVICES TAB -->
        <div id="services" class="tab-content hidden space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Services Assigned</h2>
                </div>

                <div class="overflow-x-auto mt-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-slate-700">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Service Name</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Assigned Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Status</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Cost</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">General Checkup</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 1, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$100</td>
                                <td class="px-4 py-3 text-sm">
                                    <button class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">Blood Test</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 5, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded text-xs font-medium">Pending</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$75</td>
                                <td class="px-4 py-3 text-sm">
                                    <button class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">Dental Cleaning</td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">Dec 10, 2024</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="px-2 py-1 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded text-xs font-medium">Scheduled</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">$60</td>
                                <td class="px-4 py-3 text-sm">
                                    <button class="text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 text-xs font-medium transition">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <button class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                        Assign New Service
                    </button>
                </div>
            </div>
        </div>

        <!-- PAYMENTS TAB -->
        <div id="payments" class="tab-content hidden space-y-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Card Payments -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                    <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-1">Card Payments</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Manage payment methods</p>
                    </div>

                    <div class="space-y-4 mt-6">
                        <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-8 bg-slate-900 dark:bg-slate-700 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">VISA</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Visa • •••• 4242</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">Exp: 12/26</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Default</span>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                                <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Remove</button>
                            </div>
                        </div>

                        <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-8 bg-slate-700 dark:bg-slate-600 rounded flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">MC</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 dark:text-white text-sm">Mastercard • •••• 5555</p>
                                        <p class="text-xs text-slate-600 dark:text-slate-400">Exp: 08/25</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                                <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Remove</button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button class="w-full px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                            Add New Card
                        </button>
                    </div>
                </div>

                <!-- Service Payments -->
                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                    <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                        <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-1">Service Payments</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Outstanding balance</p>
                    </div>

                    <div class="space-y-4 mt-6">
                        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Total Due</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">$1,250</p>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400">3 pending services</p>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Paid Amount</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">$850</p>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400">From 5 services</p>
                        </div>

                        <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-sm font-medium text-slate-900 dark:text-white">Balance</p>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">$400</p>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-slate-400">Remaining balance</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                        <button class="w-full px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                            Pay Outstanding Balance
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- DR VISITS TAB -->
        <div id="visits" class="tab-content hidden space-y-8">
            <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="pb-6 border-b border-slate-200 dark:border-slate-700">
                    <h2 class="text-xl font-semibold text-slate-900 dark:text-white mb-4">Doctor Visits</h2>
                </div>

                <div class="space-y-4 mt-6">
                    <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Sarah Johnson</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">December 1, 2024 • 10:30 AM</p>
                            </div>
                            <span class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Routine checkup, blood pressure slightly elevated. Prescribed new medication.</p>
                        <div class="mt-3 flex gap-2">
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View Notes</button>
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>

                    <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Michael Chen</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">November 15, 2024 • 2:00 PM</p>
                            </div>
                            <span class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Follow-up on diabetes management. Blood sugar levels stable.</p>
                        <div class="mt-3 flex gap-2">
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View Notes</button>
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>

                    <div class="p-4 border border-slate-200 dark:border-slate-700 rounded-lg hover:shadow-md transition">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">Dr. Emily Davis</p>
                                <p class="text-sm text-slate-600 dark:text-slate-400">October 20, 2024 • 3:15 PM</p>
                            </div>
                            <span class="px-2 py-1 bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200 rounded text-xs font-medium">Completed</span>
                        </div>
                        <p class="text-sm text-slate-700 dark:text-slate-300 mt-2">Specialist consultation for hypertension. Adjusted medication dosage.</p>
                        <div class="mt-3 flex gap-2">
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">View Notes</button>
                            <button class="text-xs font-medium text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 transition">Edit</button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-slate-200 dark:border-slate-700">
                    <button class="px-6 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 transition font-medium">
                        Schedule New Visit
                    </button>
                </div>
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
                b.classList.remove('active', 'text-slate-900', 'dark:text-white', 'border-slate-900', 'dark:border-white');
                b.classList.add('text-slate-600', 'dark:text-slate-400', 'border-transparent');
            });
            
            // Add active state to clicked button
            btn.classList.add('active', 'text-slate-900', 'dark:text-white', 'border-slate-900', 'dark:border-white');
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
