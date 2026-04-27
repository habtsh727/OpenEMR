<x-layouts.app>
   <!-- resources/views/livewire/hospital-dashboard.blade.php -->

<div class="min-h-screen bg-gradient-to-br from-white to-slate-50 text-slate-900 p-6">
    <!-- Header Section -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-slate-900">Hospital Management System</h1>
            <p class="text-slate-600 mt-2">Real-time operational dashboard & patient care monitoring</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-slate-600">Last Updated: {{ now()->format('H:i:s') }}</p>
        </div>
    </div>

    <!-- Critical Alerts Section -->
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-red-500 p-2 rounded-full animate-pulse">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-red-900">Critical Alert</h3>
                    <p class="text-sm text-red-700">3 patients requiring immediate attention in Emergency Ward</p>
                </div>
            </div>
            <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold transition">View Details</button>
        </div>
    </div>

    <!-- KPI Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total Patients -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Total Patients</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">342</p>
                    <p class="text-green-600 text-xs font-semibold mt-1">↑ 12 from yesterday</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bed Occupancy -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Bed Occupancy</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">87%</p>
                    <p class="text-yellow-600 text-xs font-semibold mt-1">165/190 beds</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.5 1.5H9.5V0h1v1.5zm-8 8h1.5v1H2.5v-1zm15 0h1.5v1H17.5v-1zM5.5 5.5L4.793 4.793l1.06-1.061.707.707-1.06 1.061zm9 9l-.707-.707 1.061-1.06.707.707-1.061 1.06zM5.5 14.5l-.707.707-1.061-1.06.707-.707 1.061 1.06zm9-9l.707-.707 1.06 1.061-.707.707-1.06-1.061z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Admissions -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Pending Admissions</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">18</p>
                    <p class="text-orange-600 text-xs font-semibold mt-1">Avg wait: 2.5 hrs</p>
                </div>
                <div class="bg-orange-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2 4 4 0 00-4 4v9a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-4-4 1 1 0 000 2 2 2 0 012 2v9a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Doctors On Duty -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Doctors On Duty</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">24</p>
                    <p class="text-green-600 text-xs font-semibold mt-1">✓ All staffed</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v-1h8v1z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Surgeries Today -->
        <div class="bg-white border border-slate-200 rounded-lg p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-600 text-sm font-medium">Surgeries Today</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">12</p>
                    <p class="text-blue-600 text-xs font-semibold mt-1">4 in progress</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 17v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Status & Resource Management -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Department Status -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Department Status</h2>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-slate-700">Emergency Ward</span>
                        <span class="text-sm font-semibold text-red-600">Critical</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-slate-700">ICU</span>
                        <span class="text-sm font-semibold text-orange-600">High</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-orange-500 h-2 rounded-full" style="width: 82%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-slate-700">General Ward</span>
                        <span class="text-sm font-semibold text-yellow-600">Moderate</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 68%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm text-slate-700">Pediatrics</span>
                        <span class="text-sm font-semibold text-green-600">Normal</span>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Medical Inventory -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Medical Inventory Alert</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Oxygen Cylinders</p>
                        <p class="text-xs text-slate-600">Low Stock</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold bg-orange-100 text-orange-700 rounded">8 units</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Surgical Gloves</p>
                        <p class="text-xs text-slate-600">Adequate</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">285 boxes</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Blood Type O-</p>
                        <p class="text-xs text-slate-600">Critical</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-700 rounded">2 units</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Queue & Emergency Cases -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Emergency Cases Queue -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4">Emergency Queue (Triage)</h2>
            <div class="space-y-2">
                <div class="flex items-center justify-between p-3 bg-red-50 border-l-4 border-red-500 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Patient ID: EMG-2847</p>
                        <p class="text-xs text-slate-600">Trauma / Chest Pain - Priority 1</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-red-600 text-white rounded">URGENT</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-orange-50 border-l-4 border-orange-500 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Patient ID: EMG-2845</p>
                        <p class="text-xs text-slate-600">Head Injury / Bleeding - Priority 2</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-orange-600 text-white rounded">HIGH</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Patient ID: EMG-2843</p>
                        <p class="text-xs text-slate-600">Fracture / Burn - Priority 3</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-yellow-600 text-white rounded">MEDIUM</span>
                </div>
            </div>
        </div>

        <!-- Doctor On-Call Schedule -->
        <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 mb-4">On-Call Specialists</h2>
            <div class="space-y-2">
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Dr. Sarah Johnson</p>
                        <p class="text-xs text-slate-600">Cardiology • Available</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">ONLINE</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Dr. Michael Chen</p>
                        <p class="text-xs text-slate-600">Neurology • In Surgery</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded">BUSY</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Dr. Emily Rodriguez</p>
                        <p class="text-xs text-slate-600">Emergency Med • Available</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">ONLINE</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Patient Admissions & Discharge Log -->
    <div class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-4">Patient Management Log</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Patient ID</th>
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Name</th>
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Status</th>
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Department</th>
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Assigned Doctor</th>
                        <th class="text-left py-3 px-4 text-slate-600 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-900">PAT-0001</td>
                        <td class="py-3 px-4 text-slate-900">James Anderson</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded">In Treatment</span></td>
                        <td class="py-3 px-4 text-slate-700">ICU</td>
                        <td class="py-3 px-4 text-slate-700">Dr. Sarah Johnson</td>
                        <td class="py-3 px-4"><button class="text-blue-600 hover:text-blue-700 font-semibold text-xs">View</button></td>
                    </tr>
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-900">PAT-0045</td>
                        <td class="py-3 px-4 text-slate-900">Lisa Martinez</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">Ready for Discharge</span></td>
                        <td class="py-3 px-4 text-slate-700">General Ward</td>
                        <td class="py-3 px-4 text-slate-700">Dr. Robert Kumar</td>
                        <td class="py-3 px-4"><button class="text-green-600 hover:text-green-700 font-semibold text-xs">Discharge</button></td>
                    </tr>
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-900">PAT-0089</td>
                        <td class="py-3 px-4 text-slate-900">David Thompson</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded">Under Observation</span></td>
                        <td class="py-3 px-4 text-slate-700">Emergency</td>
                        <td class="py-3 px-4 text-slate-700">Dr. Emily Rodriguez</td>
                        <td class="py-3 px-4"><button class="text-blue-600 hover:text-blue-700 font-semibold text-xs">View</button></td>
                    </tr>
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 text-slate-900">PAT-0102</td>
                        <td class="py-3 px-4 text-slate-900">Maria Garcia</td>
                        <td class="py-3 px-4"><span class="px-2 py-1 text-xs font-bold bg-purple-100 text-purple-700 rounded">Scheduled Surgery</span></td>
                        <td class="py-3 px-4 text-slate-700">OR - Room 3</td>
                        <td class="py-3 px-4 text-slate-700">Dr. Michael Chen</td>
                        <td class="py-3 px-4"><button class="text-blue-600 hover:text-blue-700 font-semibold text-xs">View</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer Stats -->
    <div class="mt-8 pt-6 border-t border-slate-200 flex justify-between items-center text-xs text-slate-600">
        <p>Last sync: {{ now()->format('d M Y, H:i') }}</p>
        <p>System Status: <span class="text-green-600 font-semibold">All Systems Operational</span></p>
    </div>
</div>
</x-layouts.app>
