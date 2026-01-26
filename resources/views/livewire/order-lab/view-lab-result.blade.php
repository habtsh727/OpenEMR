<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h3 class="text-lg font-semibold text-gray-800">Lab Test Results</h3>
            <p class="text-sm text-gray-600">Generated on {{ now()->format('M d, Y H:i') }}</p>
        </div>
        <button type="button" wire:click="printResult"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
            Print Results
        </button>
    </div>

    <!-- Patient Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
        <div>
            <h4 class="font-medium text-gray-700 mb-2">Patient Information</h4>
            <p class="text-sm text-gray-600"><strong>Name:</strong> {{ $labOrder->order->encounter->patient->full_name
                }}</p>
            <p class="text-sm text-gray-600"><strong>MRN:</strong> {{
                $labOrder->order->encounter->patient->medical_record_number ?? 'N/A' }}</p>
            <p class="text-sm text-gray-600"><strong>Age/Sex:</strong> {{ $labOrder->order->encounter->patient->age ??
                'N/A' }} / {{ $labOrder->order->encounter->patient->gender ?? 'N/A' }}</p>
        </div>
        <div>
            <h4 class="font-medium text-gray-700 mb-2">Test Information</h4>
            <p class="text-sm text-gray-600"><strong>Test:</strong> {{ $labOrder->labTest->name }} ({{
                $labOrder->labTest->code }})</p>
            <p class="text-sm text-gray-600"><strong>Ordered:</strong> {{ $labOrder->created_at->format('M d, Y H:i') }}
            </p>
            <p class="text-sm text-gray-600"><strong>Reported:</strong> {{ $labOrder->updated_at->format('M d, Y H:i')
                }}</p>
            <p class="text-sm text-gray-600"><strong>Priority:</strong> {{ ucfirst($labOrder->priority) }}</p>
        </div>
    </div>

    <!-- Sample Information -->
    @if($labOrder->labSamples->count() > 0)
    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
        <h4 class="font-medium text-gray-700 mb-2">Sample Information</h4>
        @foreach($labOrder->labSamples as $sample)
        <div class="text-sm text-gray-600">
            <p><strong>Sample Type:</strong> {{ $sample->sample_type }}</p>
            <p><strong>Status:</strong> {{ ucfirst($sample->status) }}</p>
            @if($sample->collected_at)
            <p><strong>Collected:</strong> {{ $sample->collected_at->format('M d, Y H:i') }}</p>
            <p><strong>Collected By:</strong> {{ $sample->collectedBy->name ?? 'N/A' }}</p>
            @endif
            @if($sample->rejection_reason)
            <p class="text-red-600"><strong>Rejection Reason:</strong> {{ $sample->rejection_reason }}</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Results Table -->
    <div class="mb-6">
        <h4 class="font-medium text-gray-700 mb-3">Test Results</h4>
        @if($labOrder->labResults->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Parameter</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference Range</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Flag</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($labOrder->labResults as $result)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $result->parameter }}</td>
                        <td
                            class="px-4 py-3 text-sm {{ $result->flag === 'critical' ? 'font-bold text-red-600' : 'text-gray-900' }}">
                            {{ $result->value }}
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $result->unit }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $result->reference_range }}</td>
                        <td class="px-4 py-3 text-sm">
                            @php
                            $flagColors = [
                            'normal' => 'bg-green-100 text-green-800',
                            'abnormal' => 'bg-yellow-100 text-yellow-800',
                            'critical' => 'bg-red-100 text-red-800',
                            ];
                            @endphp
                            <span class="px-2 py-1 text-xs rounded-full {{ $flagColors[$result->flag] }}">
                                {{ ucfirst($result->flag) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg">
            No results available for this test.
        </div>
        @endif
    </div>

    <!-- Verification Information -->
    @if($labOrder->status === 'verified')
    <div class="mt-6 p-4 bg-green-50 rounded-lg">
        <h4 class="font-medium text-gray-700 mb-2">Verification</h4>

        <!-- Check if columns exist -->
        @if(isset($labOrder->verified_at))
        <p class="text-sm text-gray-600">
            <strong>Verified At:</strong> {{ $labOrder->verified_at?->format('M d, Y H:i') ?? 'N/A' }}
        </p>
        @endif

        @if(isset($labOrder->verified_by))
        @if($labOrder->relationLoaded('verifiedBy') && $labOrder->verifiedBy)
        <p class="text-sm text-gray-600">
            <strong>Verified By:</strong> {{ $labOrder->verifiedBy->name }}
        </p>
        @else
        <p class="text-sm text-gray-600">
            <strong>Verified By:</strong> User ID: {{ $labOrder->verified_by }}
        </p>
        @endif
        @endif

        @if(isset($labOrder->verification_notes) && $labOrder->verification_notes)
        <p class="text-sm text-gray-600 mt-2">
            <strong>Notes:</strong> {{ $labOrder->verification_notes }}
        </p>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="mt-6 pt-4 border-t border-gray-200">
        <p class="text-sm text-gray-500">
            <strong>Disclaimer:</strong> These results are for informational purposes only.
            Please consult with a healthcare professional for interpretation and treatment decisions.
        </p>

        <div class="flex justify-end mt-4">
            <button type="button" wire:click="$dispatch('close-modal')"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Close
            </button>
        </div>
    </div>
</div>