<div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 dark:from-purple-700 dark:to-indigo-700 px-6 py-4">
        <h2 class="text-2xl font-bold text-white">📝 Cupping Treatment Report</h2>
        <p class="text-purple-100">Session {{ $session->session_number }} of {{ $session->cuppingTherapy->total_sessions }}</p>
    </div>

    <div class="p-6 max-h-[70vh] overflow-y-auto">
        <!-- Patient Info -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Patient Name</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $session->cuppingTherapy->encounter->patient->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Therapy ID</p>
                    <p class="font-semibold text-gray-900 dark:text-white">#{{ $session->cupping_therapy_id }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Session Date</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($session->session_date)->format('F d, Y') }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Amount Paid</p>
                    <p class="font-semibold text-green-600">{{ number_format($session->paid_amount, 2) }} ETB</p>
                </div>
            </div>
        </div>

        <!-- Doctor's Order Summary -->
        <div class="mb-6">
            <h3 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Doctor's Prescription
            </h3>
            <div class="space-y-2 bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                @foreach($session->items as $item)
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $item->qty }}x</span>
                            <span class="ml-1 text-gray-700 dark:text-gray-300">{{ $item->cuppingType->name }}</span>
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
                
                @if($session->cuppingTherapy->notes)
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-3 mt-3">
                        <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Doctor's Notes:</strong> {{ $session->cuppingTherapy->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Report Form -->
        <form wire:submit.prevent="submitReport">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Treatment Report <span class="text-red-500">*</span>
                </label>
                <textarea wire:model="report_text" rows="5" 
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Example: Performed wet cupping on upper back. 5 cups used. Patient tolerated well. Minor bruising observed but normal."></textarea>
                @error('report_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Clinical Observations</label>
                <textarea wire:model="observations" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Any observations during treatment: skin reaction, pain level, patient comfort, cup marks, etc."></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Recommendations for Patient</label>
                <textarea wire:model="recommendations" rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white"
                          placeholder="Recommendations: rest period, hydration, follow-up schedule, activity restrictions, etc."></textarea>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Important Note</p>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Once submitted, this session will be marked as COMPLETED and cannot be edited. The patient will be moved out of the treatment queue.</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-lg">
                    ✓ Complete Treatment & Submit Report
                </button>
            </div>
        </form>
    </div>
</div>