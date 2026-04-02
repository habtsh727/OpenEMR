<div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
    <div class="px-6 py-4 bg-purple-600 dark:bg-purple-700">
        <h2 class="text-2xl font-bold text-white">📝 Cupping Treatment Report</h2>
        <p class="text-purple-100">Session {{ $session->session_number }} of {{ $session->cuppingTherapy->total_sessions }}</p>
    </div>

    <div class="p-6">
        <!-- Patient Info -->
        <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Patient Name</p>
                    <p class="font-semibold">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Therapy ID</p>
                    <p class="font-semibold">#{{ $session->cupping_therapy_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Session Date</p>
                    <p class="font-semibold">{{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Amount Paid</p>
                    <p class="font-semibold text-green-600">{{ number_format($session->paid_amount, 2) }} ETB</p>
                </div>
            </div>
        </div>

        <!-- Items Performed -->
        <div class="mb-6">
            <h3 class="font-semibold mb-3 flex items-center">
                <span class="mr-2">📋</span> Items Performed
            </h3>
            <div class="space-y-2 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                @foreach($session->items as $item)
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $item->qty }}x</span>
                            <span class="ml-1">{{ $item->cuppingType->name }}</span>
                            <span class="text-gray-500 text-sm ml-2">({{ $item->cuppingLocation->name }})</span>
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ number_format($item->price, 2) }} ETB each
                        </div>
                    </div>
                    @if($item->notes)
                        <div class="text-xs text-gray-500 ml-4">Note: {{ $item->notes }}</div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Report Form -->
        <form wire:submit.prevent="submitReport">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">
                    Report Text <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="report_text" rows="5" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700"
                          placeholder="Example: Performed wet cupping on upper back. 5 cups used. Patient tolerated well. Minor bruising observed but normal."></textarea>
                @error('report_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Observations</label>
                <textarea wire:model="observations" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700"
                          placeholder="Any observations during treatment: skin reaction, pain level, patient comfort, etc."></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium mb-2">Recommendations</label>
                <textarea wire:model="recommendations" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700"
                          placeholder="Recommendations for patient: rest period, hydration, follow-up schedule, etc."></textarea>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3 mb-6">
                <p class="text-sm text-blue-800 dark:text-blue-200">
                    ⚠️ <strong>Important:</strong> Once submitted, this session will be marked as COMPLETED and cannot be edited.
                    Please ensure all information is accurate.
                </p>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-2 rounded-lg transition duration-200">
                    ✓ Complete Treatment & Submit Report
                </button>
            </div>
        </form>
    </div>
</div>