{{-- resources/views/livewire/cashier/cupping-payment-form.blade.php --}}
<div>
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Cupping Payment</h2>

        <!-- Therapy Info -->
        <div class="bg-gray-100 p-4 rounded mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><strong>Therapy ID:</strong> #{{ $cupping->id }}</p>
                    <p><strong>Encounter ID:</strong> {{ $cupping->encounter_id }}</p>
                    <p><strong>Treatment Date:</strong> {{ $cupping->treatment_date->format('Y-m-d') }}</p>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-sm 
                            @if($cupping->status == 'payment_completed') bg-green-100 text-green-800
                            @elseif($cupping->status == 'payment_partial') bg-yellow-100 text-yellow-800
                            @elseif($cupping->status == 'sent_to_cupping') bg-blue-100 text-blue-800
                            @elseif($cupping->status == 'completed') bg-gray-100 text-gray-800
                            @else bg-gray-100 @endif">
                            {{ str_replace('_', ' ', ucfirst($cupping->status)) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p><strong>Final Amount:</strong> {{ number_format($cupping->final_amount, 2) }}</p>
                    <p><strong>Total Paid:</strong> {{ number_format($total_paid, 2) }}</p>
                    <p><strong>Remaining:</strong> <span class="font-bold {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($remaining, 2) }}</span></p>
                </div>
            </div>
        </div>

        <!-- Items Summary -->
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Therapy Items</h3>
            <table class="w-full border">
                <thead class="bg-gray-50">
                    <tr><th class="p-2 text-left">Type</th><th class="p-2 text-left">Location</th><th class="p-2 text-right">Qty</th><th class="p-2 text-right">Price</th><th class="p-2 text-right">Total</th></tr>
                </thead>
                <tbody>
                    @foreach($cupping->items as $item)
                        <tr>
                            <td class="p-2">{{ $item->cuppingType->name }}</td>
                            <td class="p-2">{{ $item->cuppingLocation->name }}</td>
                            <td class="p-2 text-right">{{ $item->qty }}</td>
                            <td class="p-2 text-right">{{ number_format($item->price, 2) }}</td>
                            <td class="p-2 text-right">{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($remaining > 0)
            <!-- Payment Form -->
            <div class="border-t pt-6">
                <h3 class="font-semibold mb-4">Make Payment</h3>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Amount</label>
                        <input type="number" step="0.01" wire:model.live="amount" class="w-full p-2 border rounded">
                        @error('amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">Payment Method</label>
                        <select wire:model="payment_method" class="w-full p-2 border rounded">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button" wire:click="processPayment" class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600">
                        Process Payment
                    </button>
                </div>
            </div>
        @else
            <div class="bg-green-100 p-4 rounded mb-4 text-green-800">
                ✅ Payment completed! Total paid: {{ number_format($total_paid, 2) }}
            </div>
        @endif

        <!-- Payment History -->
        @if($payments->count() > 0)
            <div class="mt-6">
                <h3 class="font-semibold mb-2">Payment History</h3>
                <table class="w-full border">
                    <thead class="bg-gray-50">
                        <tr><th class="p-2 text-left">Date</th><th class="p-2 text-right">Amount</th><th class="p-2 text-left">Method</th><th class="p-2 text-left">Received By</th></tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                            <tr>
                                <td class="p-2">{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td class="p-2 text-right">{{ number_format($payment->amount, 2) }}</td>
                                <td class="p-2">{{ ucfirst($payment->payment_method) }}</td>
                                <td class="p-2">{{ $payment->receivedBy->name ?? 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-6 flex justify-end gap-3">
            @if($cupping->status == 'payment_completed')
                <button type="button" wire:click="markAsSentToCupping" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                    Send to Cupping Department
                </button>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert', (event) => {
                alert(event.type.toUpperCase() + ': ' + event.message);
            });
            
            Livewire.on('payment-completed', () => {
                alert('Payment completed! You can now send the therapy to cupping department.');
            });
        });
    </script>
</div>