<div>
    <div>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pending Imaging Orders</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Patient</th>
                                    <th>Imaging Type</th>
                                    <th>Body Part</th>
                                    <th>Amount</th>
                                    <th>Order Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->encounter->patient->full_name }}</td>
                                        <td>{{ $order->imagingType->name }}</td>
                                        <td>{{ $order->bodyPart->name }}</td>
                                        <td>${{ number_format($order->amount, 2) }}</td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <button wire:click="selectOrder({{ $order->id }})" 
                                                    class="btn btn-sm btn-primary">
                                                Process Payment
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No pending orders</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            @if($processingOrder)
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Process Payment</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Patient:</strong>
                            <p>{{ $processingOrder->encounter->patient->full_name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Order Details:</strong>
                            <p>{{ $processingOrder->imagingType->name }} - {{ $processingOrder->bodyPart->name }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Amount Due:</strong>
                            <h4>${{ number_format($paymentAmount, 2) }}</h4>
                        </div>
                        
                        <form wire:submit.prevent="processPayment">
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select wire:model="paymentMethod" class="form-select">
                                    <option value="cash">Cash</option>
                                    <option value="card">Credit/Debit Card</option>
                                    <option value="insurance">Insurance</option>
                                    <option value="online">Online Payment</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Receipt Number</label>
                                <input type="text" wire:model="receiptNumber" class="form-control" readonly>
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">
                                Confirm Payment
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                        <p>Select an order to process payment</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
</div>