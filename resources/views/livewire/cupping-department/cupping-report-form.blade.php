{{-- resources/views/livewire/cupping-department/cupping-report-form.blade.php --}}
<div>
    <div class="p-6 max-w-4xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">Cupping Therapy Report</h2>

        <!-- Therapy Info -->
        <div class="bg-gray-100 p-4 rounded mb-6">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p><strong>Therapy ID:</strong> #{{ $cupping->id }}</p>
                    <p><strong>Encounter ID:</strong> {{ $cupping->encounter_id }}</p>
                    <p><strong>Treatment Date:</strong> {{ $cupping->treatment_date->format('Y-m-d') }}</p>
                </div>
                <div>
                    <p><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded text-sm 
                            @if($cupping->status == 'completed') bg-green-100 text-green-800
                            @elseif($cupping->status == 'sent_to_cupping') bg-blue-100 text-blue-800
                            @else bg-gray-100 @endif">
                            {{ str_replace('_', ' ', ucfirst($cupping->status)) }}
                        </span>
                    </p>
                    <p><strong>Doctor:</strong> {{ $cupping->doctor->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Items Summary -->
        <div class="mb-6">
            <h3 class="font-semibold mb-2">Therapy Items</h3>
            <table class="w-full border">
                <thead class="bg-gray-50">
                    <tr><th class="p-2 text-left">Type</th><th class="p-2 text-left">Location</th><th class="p-2 text-right">Qty</th></tr>
                </thead>
                <tbody>
                    @foreach($cupping->items as $item)
                        <tr>
                            <td class="p-2">{{ $item->cuppingType->name }}</td>
                            <td class="p-2">{{ $item->cuppingLocation->name }}</td>
                            <td class="p-2 text-right">{{ $item->qty }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($existingReport)
            <div class="bg-green-50 p-4 rounded mb-6 border-l-4 border-green-500">
                <h3 class="font-semibold mb-2">Previous Report</h3>
                <p><strong>Report:</strong> {{ $existingReport->report_text }}</p>
                @if($existingReport->notes)
                    <p class="mt-2"><strong>Notes:</strong> {{ $existingReport->notes }}</p>
                @endif
                <p class="text-sm text-gray-500 mt-2">Submitted: {{ $existingReport->created_at->format('Y-m-d H:i') }} by {{ $existingReport->createdBy->name ?? 'N/A' }}</p>
            </div>
        @endif

        @if($cupping->status == 'sent_to_cupping')
            <form wire:submit.prevent="submitReport">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Report Text <span class="text-red-500">*</span></label>
                    <textarea wire:model="report_text" rows="6" class="w-full p-2 border rounded" placeholder="Detailed report of cupping therapy performed..."></textarea>
                    @error('report_text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Additional Notes</label>
                    <textarea wire:model="notes" rows="3" class="w-full p-2 border rounded" placeholder="Any observations, patient feedback, or recommendations..."></textarea>
                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Submit Report & Complete Session
                    </button>
                </div>
            </form>
        @elseif($cupping->status == 'completed')
            <div class="bg-green-100 p-4 rounded text-green-800 text-center">
                ✅ Therapy session completed on {{ $cupping->updated_at->format('Y-m-d H:i') }}
            </div>
        @else
            <div class="bg-yellow-100 p-4 rounded text-yellow-800 text-center">
                ⚠️ This therapy session is not ready for reporting. Status: {{ str_replace('_', ' ', ucfirst($cupping->status)) }}
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('alert', (event) => {
                alert(event.type.toUpperCase() + ': ' + event.message);
            });
        });
    </script>
</div>