<div>
<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Order Imaging</h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createOrder">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Imaging Type *</label>
                        <select wire:model="selectedImagingType" class="form-select" required>
                            <option value="">Select Type</option>
                            @foreach($imagingTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }} - ${{ number_format($type->fee, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Body Part *</label>
                        <select wire:model="selectedBodyPart" class="form-select" required>
                            <option value="">Select Body Part</option>
                            @foreach($bodyParts as $part)
                                <option value="{{ $part->id }}">{{ $part->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Priority</label>
                        <select wire:model="priority" class="form-select">
                            <option value="routine">Routine</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Amount</label>
                       <div class="form-control bg-light">${{ number_format($this->fee, 2) }}</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Clinical Notes</label>
                        <textarea wire:model="clinicalNotes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Imaging Orders</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
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
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->imagingType->name }}</td>
                                <td>{{ $order->bodyPart->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $order->priority == 'urgent' ? 'warning' : 'secondary' }}">
                                        {{ $order->priority }}
                                    </span>
                                </td>
                                <td>${{ number_format($order->amount, 2) }}</td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'paid' => 'info',
                                            'in_progress' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->status] ?? 'secondary' }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($order->status == 'pending')
                                        <button wire:click="cancelOrder({{ $order->id }})" 
                                                class="btn btn-sm btn-danger">
                                            Cancel
                                        </button>
                                    @endif
                                    @if($order->imagingResult)
                                        <button class="btn btn-sm btn-success">
                                            View Results
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No imaging orders</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
