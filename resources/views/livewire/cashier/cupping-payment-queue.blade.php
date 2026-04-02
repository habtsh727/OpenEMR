<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    @if(!$showPaymentForm)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 bg-green-600 dark:bg-green-700">
                <h2 class="text-2xl font-bold text-white">💰 Cashier Payment Queue</h2>
                <p class="text-green-100">Pending payments waiting for cashier processing</p>
            </div>

            <div class="p-6">
                @if(count($queue) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left">#</th>
                                    <th class="px-4 py-3 text-left">Patient Name</th>
                                    <th class="px-4 py-3 text-left">Therapy ID</th>
                                    <th class="px-4 py-3 text-left">Session</th>
                                    <th class="px-4 py-3 text-right">Amount Due (ETB)</th>
                                    <th class="px-4 py-3 text-left">Session Date</th>
                                    <th class="px-4 py-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($queue as $item)
                                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-4 py-3 font-medium text-center">#{{ $item->position }}</td>
                                        <td class="px-4 py-3 font-medium">
                                            {{ $item->cuppingSession->cuppingTherapy->encounter->patient->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            #{{ $item->cuppingSession->cupping_therapy_id }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                                {{ $item->cuppingSession->session_number }}/{{ $item->cuppingSession->cuppingTherapy->total_sessions }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-right font-bold text-green-600 dark:text-green-400">
                                            {{ number_format($item->cuppingSession->remaining_amount, 2) }} ETB
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ \Carbon\Carbon::parse($item->cuppingSession->session_date)->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <button wire:click="processPayment({{ $item->cupping_session_id }})"
                                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                                                Process Payment
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                        Total pending: {{ count($queue) }} session(s) waiting for payment
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-6xl mb-4">💵</div>
                        <div class="text-gray-500 dark:text-gray-400">No pending payments in queue</div>
                        <p class="text-sm text-gray-400 mt-2">New orders will appear here automatically</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        @livewire('cashier.payment-form', ['session' => $selectedSession], key($selectedSession->id))
        <div class="mt-4 text-center">
            <button wire:click="closePaymentForm" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                ← Back to Queue
            </button>
        </div>
    @endif
</div>