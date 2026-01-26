<div class="p-6 bg-white dark:bg-gray-800 rounded-lg transition-colors duration-200" id="printable-lab-result">
    <!-- Print-only header (hidden on screen) -->
    <div class="hidden print:block mb-8 border-b-2 border-gray-300 pb-4">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">LABORATORY TEST REPORT</h1>
                <p class="text-gray-600">Official Medical Laboratory Results</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-600">Report ID: LAB-{{ str_pad($labOrder->id, 6, '0', STR_PAD_LEFT) }}
                </div>
                <div class="text-sm text-gray-600">Generated: {{ now()->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
    </div>

    <!-- Screen header (hidden when printing) -->
    <div
        class="print:hidden flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
        <div>
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Laboratory Test Results</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Comprehensive test report for clinical review</p>
        </div>
        <div class="flex space-x-3 mt-3 md:mt-0">
            <button type="button" onclick="window.print()"
                class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-700 dark:to-indigo-800 dark:hover:from-blue-600 dark:hover:to-indigo-700 text-white rounded-lg font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center shadow-sm hover:shadow">
                Print Results
            </button>
        </div>
    </div>

    <!-- Patient Information -->
    <div
        class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-900 dark:to-gray-800 rounded-lg border border-blue-100 dark:border-gray-700">
        <div>
            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Patient Information
            </h4>
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Name:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{
                        $labOrder->order->encounter->patient->first_name }} {{
                        $labOrder->order->encounter->patient->last_name }}</span>
                </div>
                {{-- <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">MRN:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{
                        $labOrder->order->encounter->patient->medical_record_number ?? 'N/A' }}</span>
                </div> --}}
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Age/Sex:</span>
                    <span class="text-sm text-gray-900 dark:text-white">
                        @php
                        $dob = $labOrder->order->encounter->patient->date_of_birth ?? null;
                        $age = $dob ? \Carbon\Carbon::parse($dob)->age : 'N/A';
                        @endphp
                        {{ $age }} / {{ $labOrder->order->encounter->patient->gender ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>
        <div>
            <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                Test Information
            </h4>
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Test:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $labOrder->labTest->name }} ({{
                        $labOrder->labTest->code }})</span>
                </div>
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Ordered:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $labOrder->created_at->format('M d, Y H:i')
                        }}</span>
                </div>
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Reported:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $labOrder->updated_at->format('M d, Y H:i')
                        }}</span>
                </div>
                <div class="flex">
                    <span class="w-24 text-sm font-medium text-gray-600 dark:text-gray-400">Priority:</span>
                    <span class="text-sm">
                        @php
                        $priorityColors = [
                        'routine' => 'text-blue-600 dark:text-blue-400',
                        'urgent' => 'text-yellow-600 dark:text-yellow-400',
                        'stat' => 'text-red-600 dark:text-red-400',
                        ];
                        @endphp
                        <span class="{{ $priorityColors[$labOrder->priority] ?? 'text-gray-600 dark:text-gray-400' }}">
                            {{ ucfirst($labOrder->priority) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sample Information -->
    @if($labOrder->labSamples->count() > 0)
    <div
        class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-900 dark:to-gray-800 rounded-lg border border-green-100 dark:border-gray-700">
        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            Sample Information
        </h4>
        @foreach($labOrder->labSamples as $sample)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
            <div class="space-y-2">
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Sample Type:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $sample->sample_type }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Status:</span>
                    <span class="text-sm">
                        @php
                        $sampleStatusColors = [
                        'pending' => 'text-yellow-600 dark:text-yellow-400',
                        'collected' => 'text-blue-600 dark:text-blue-400',
                        'accepted' => 'text-green-600 dark:text-green-400',
                        'rejected' => 'text-red-600 dark:text-red-400',
                        ];
                        @endphp
                        <span class="{{ $sampleStatusColors[$sample->status] ?? 'text-gray-600 dark:text-gray-400' }}">
                            {{ ucfirst($sample->status) }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="space-y-2">
                @if($sample->collected_at)
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Collected:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $sample->collected_at->format('M d, Y H:i')
                        }}</span>
                </div>
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Collected By:</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $sample->collectedBy->name ?? 'N/A' }}</span>
                </div>
                @endif
                @if($sample->rejection_reason)
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Rejection Reason:</span>
                    <span class="text-sm text-red-600 dark:text-red-400">{{ $sample->rejection_reason }}</span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Results Table -->
    <div class="mb-6">
        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Test Results
        </h4>

        @if($labOrder->labResults->count() > 0)
        <div
            class="overflow-hidden bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 print:shadow-none print:border">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900 print:bg-gray-100">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Parameter</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Result</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Unit</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Reference Range</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Flag</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($labOrder->labResults as $result)
                        <tr
                            class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors print:hover:bg-transparent">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white print:text-black">{{
                                $result->parameter }}</td>
                            <td class="px-4 py-3 text-sm font-medium {{ $result->flag === 'critical' 
                                ? 'text-red-600 dark:text-red-400 font-bold print:text-red-800' 
                                : 'text-gray-900 dark:text-white print:text-black' }}">
                                {{ $result->value }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 print:text-gray-700">{{
                                $result->unit }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 print:text-gray-700">{{
                                $result->reference_range }}</td>
                            <td class="px-4 py-3 text-sm">
                                @php
                                $flagColors = [
                                'normal' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                print:bg-green-100 print:text-green-800',
                                'abnormal' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                print:bg-yellow-100 print:text-yellow-800',
                                'critical' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                print:bg-red-100 print:text-red-800',
                                ];
                                @endphp
                                <span
                                    class="px-2 py-1 text-xs font-medium rounded-full {{ $flagColors[$result->flag] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                                    {{ ucfirst($result->flag) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 print:text-gray-600">
            {{ $labOrder->labResults->count() }} parameter(s) tested
        </div>
        @else
        <div
            class="p-4 bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 text-yellow-700 dark:text-yellow-300 rounded-lg">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                No results available for this test.
            </div>
        </div>
        @endif
    </div>

    <!-- Verification Information -->
    @if($labOrder->status === 'verified')
    <div
        class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-900 dark:to-gray-800 rounded-lg border border-green-100 dark:border-gray-700 print:bg-gray-100 print:border">
        <h4 class="font-medium text-gray-700 dark:text-gray-300 mb-3 flex items-center">
            <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            Verification
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if(isset($labOrder->verified_at))
            <div class="flex">
                <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Verified At:</span>
                <span class="text-sm text-gray-900 dark:text-white">{{ $labOrder->verified_at?->format('M d, Y H:i') ??
                    'N/A' }}</span>
            </div>
            @endif
            @if(isset($labOrder->verified_by))
            <div class="flex">
                <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Verified By:</span>
                <span class="text-sm text-gray-900 dark:text-white">
                    @if($labOrder->relationLoaded('verifiedBy') && $labOrder->verifiedBy)
                    {{ $labOrder->verifiedBy->name }}
                    @else
                    User ID: {{ $labOrder->verified_by }}
                    @endif
                </span>
            </div>
            @endif
            @if(isset($labOrder->verification_notes) && $labOrder->verification_notes)
            <div class="md:col-span-2">
                <div class="flex">
                    <span class="w-32 text-sm font-medium text-gray-600 dark:text-gray-400">Notes:</span>
                    <span class="text-sm text-gray-900 dark:text-white flex-1">{{ $labOrder->verification_notes
                        }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 print:border-gray-300">
        <p class="text-sm text-gray-500 dark:text-gray-400 print:text-gray-600 italic">
            <strong>Disclaimer:</strong> These results are for informational purposes only.
            Please consult with a healthcare professional for interpretation and treatment decisions.
        </p>

        <!-- Print-only footer -->
        <div class="hidden print:block mt-8 pt-4 border-t border-gray-300">
            <div class="flex justify-between text-xs text-gray-600">
                <div>
                    <p>Page generated by: {{ config('app.name') }}</p>
                    <p>Printed on: {{ now()->format('Y-m-d H:i:s') }}</p>
                </div>
                <div class="text-right">
                    <p>Report ID: LAB-{{ str_pad($labOrder->id, 6, '0', STR_PAD_LEFT) }}</p>
                    <p>Confidential Medical Document</p>
                </div>
            </div>
        </div>

        <!-- Screen-only buttons -->
        <div class="print:hidden flex justify-end space-x-3 mt-4">
            <button type="button" wire:click="$dispatch('close-modal')"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    // Define function before button is used
    document.addEventListener('DOMContentLoaded', function() {
        window.printLabResult = function() {
            const printContent = document.getElementById('printable-lab-result');
            const printWindow = window.open('', '_blank');
            
            printWindow.document.write(`
                <html>
                <head>
                    <title>Lab Results</title>
                    <style>
                        body { font-family: Arial; padding: 20px; }
                        @media print { 
                            button { display: none; } 
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    ${printContent.innerHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    });
</script>