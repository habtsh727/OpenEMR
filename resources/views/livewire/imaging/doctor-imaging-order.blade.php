<div>
<div>
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Order Imaging</h4>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createOrder">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Imaging Type *</label>
                            <select wire:model="selectedImagingType" class="form-select">
                                <option value="">Select Imaging Type</option>
                                @foreach($imagingTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} - ${{ number_format($type->fee, 2) }}</option>
                                @endforeach
                            </select>
                            @error('selectedImagingType') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Body Part *</label>
                            <select wire:model="selectedBodyPart" class="form-select">
                                <option value="">Select Body Part</option>
                                @foreach($bodyParts as $part)
                                    <option value="{{ $part->id }}">{{ $part->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedBodyPart') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select wire:model="priority" class="form-select">
                                <option value="routine">Routine</option>
                                <option value="urgent">Urgent</option>
                                <option value="stat">Stat</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Estimated Fee</label>
                            <div class="form-control bg-light">${{ number_format($fee, 2) }}</div>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Clinical Notes</label>
                            <textarea wire:model="clinicalNotes" class="form-control" rows="3" placeholder="Enter clinical notes..."></textarea>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            Create Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <div class="card mt-4">
        <div class="card-header">
            <h4 class="card-title">Imaging Orders</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Body Part</th>
                            <th>Priority</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->imagingType->name }}</td>
                                <td>{{ $order->bodyPart->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->priority == 'urgent' ? 'warning' : ($order->priority == 'stat' ? 'danger' : 'info') }}">
                                        {{ ucfirst($order->priority) }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : 'primary') }}">
                                        {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($order->imagingResult)
                                        <button wire:click="viewResult({{ $order->id }})" class="btn btn-sm btn-info">
                                            View Results
                                        </button>
                                    @else
                                        <span class="text-muted">Pending...</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No imaging orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Results Modal -->
    @if($showResultModal && $selectedResult)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imaging Results</h5>
                        <button type="button" wire:click="$set('showResultModal', false)" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Report:</strong>
                            <div class="border p-3 mt-2 rounded">
                                {!! nl2br(e($selectedResult->report)) !!}
                            </div>
                        </div>
                        
                        @if($selectedResult->images)
                            <div class="mb-3">
                                <strong>Images:</strong>
                                <div class="row mt-2">
                                    @foreach($selectedResult->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ Storage::url($image) }}" class="img-fluid rounded border" alt="Imaging">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Radiologist:</strong>
                                <p>{{ $selectedResult->radiologist->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Reported At:</strong>
                                <p>{{ $selectedResult->reported_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>
