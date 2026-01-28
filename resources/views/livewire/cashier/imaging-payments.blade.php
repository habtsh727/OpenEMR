<div>
    <div>
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Pending Imaging Payments</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <input type="text" wire:model.debounce.300ms="search" class="form-control"
                            placeholder="Search patient...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Patient</th>
                                <th>Type</th>
                                <th>Body Part</th>
                                <th>Amount</th>
                                <th>Priority</th>
                                <th>Ordered On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->encounter->patient->full_name ?? 'N/A' }}</td>
                                <td>{{ $order->imagingType->name }}</td>
                                <td>{{ $order->bodyPart->name }}</td>
                                <td class="text-success">
                                    <strong>${{ number_format($order->amount, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $order->priority == 'urgent' ? 'warning' : 'secondary' }}">
                                        {{ $order->priority }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
                                    <button wire:click="markAsPaid({{ $order->id }})" class="btn btn-sm btn-success">
                                        <i class="fas fa-check"></i> Mark Paid
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-check-circle fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">No pending payments</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>