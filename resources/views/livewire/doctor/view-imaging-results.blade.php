<div>
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Imaging Results</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Type</th>
                            <th>Body Part</th>
                            <th>Date Ordered</th>
                            <th>Date Reported</th>
                            <th>Radiologist</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->imagingType->name }}</td>
                                <td>{{ $order->bodyPart->name }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>{{ $order->imagingResult->reported_at->format('M d, Y') ?? 'N/A' }}</td>
                                <td>{{ $order->imagingResult->radiologist->name ?? 'N/A' }}</td>
                                <td>
                                    <button wire:click="viewResult({{ $order->id }})" 
                                            class="btn btn-sm btn-info">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No results available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($showModal && $selectedResult)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5)">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Imaging Report</h5>
                        <button wire:click="$set('showModal', false)" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6>Report:</h6>
                            <div class="border rounded p-3 bg-light">
                                {!! nl2br(e($selectedResult->report)) !!}
                            </div>
                        </div>

                        @if($selectedResult->images)
                            <div class="mb-4">
                                <h6>Images:</h6>
                                <div class="row">
                                    @foreach($selectedResult->images as $image)
                                        <div class="col-md-4 mb-3">
                                            <img src="{{ Storage::url($image) }}" 
                                                 class="img-fluid rounded border"
                                                 style="max-height: 200px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <strong>Reported by:</strong>
                                <p>{{ $selectedResult->radiologist->name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6">
                                <strong>Reported on:</strong>
                                <p>{{ $selectedResult->reported_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button wire:click="$set('showModal', false)" class="btn btn-secondary">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>