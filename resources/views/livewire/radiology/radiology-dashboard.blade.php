<div>
<div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Paid Imaging Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order #</th>
                                    <th>Patient</th>
                                    <th>Type</th>
                                    <th>Body Part</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($paidOrders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->encounter->patient->full_name ?? 'N/A' }}</td>
                                        <td>{{ $order->imagingType->name }}</td>
                                        <td>{{ $order->bodyPart->name }}</td>
                                        <td>
                                            <span class="badge bg-{{ $order->priority == 'urgent' ? 'warning' : 'secondary' }}">
                                                {{ $order->priority }}
                                            </span>
                                        </td>
                                        <td>
                                            <button wire:click="selectOrder({{ $order->id }})" 
                                                    class="btn btn-sm btn-primary">
                                                Upload Results
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-check fa-2x text-muted mb-2"></i>
                                            <p class="text-muted">No pending orders</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            @if($selectedOrder)
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Upload Results</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Order #{{ $selectedOrder->id }}</strong><br>
                            <small class="text-muted">
                                Patient: {{ $selectedOrder->encounter->patient->full_name ?? 'N/A' }}<br>
                                Type: {{ $selectedOrder->imagingType->name }}<br>
                                Body Part: {{ $selectedOrder->bodyPart->name }}
                            </small>
                        </div>

                        <form wire:submit.prevent="submitResult">
                            <div class="mb-3">
                                <label class="form-label">Upload Images</label>
                                <input type="file" wire:model="images" multiple class="form-control">
                                <small class="text-muted">Multiple images allowed (max 5MB each)</small>
                                @error('images.*') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Report *</label>
                                <textarea wire:model="report" class="form-control" rows="6" required></textarea>
                                @error('report') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select wire:model="status" class="form-select">
                                    <option value="in_progress">In Progress</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-upload"></i> Submit Results
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-x-ray fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Select an order to upload results</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div></div>
