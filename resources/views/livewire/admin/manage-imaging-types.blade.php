<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-x-ray me-2"></i>Manage Imaging Types
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Form Section -->
                <div class="col-md-5">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                {{ $editingId ? 'Edit Imaging Type' : 'Add New Imaging Type' }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="save">
                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" wire:model="name" class="form-control" 
                                           placeholder="e.g., X-ray, CT Scan, MRI" required>
                                    @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fee ($) *</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" wire:model="fee" class="form-control" 
                                               step="0.01" min="0" placeholder="0.00" required>
                                    </div>
                                    @error('fee') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea wire:model="description" class="form-control" rows="3"
                                              placeholder="Optional description..."></textarea>
                                </div>

                                <div class="mb-3 form-check form-switch">
                                    <input type="checkbox" wire:model="is_active" 
                                           class="form-check-input" id="isActiveSwitch">
                                    <label class="form-check-label" for="isActiveSwitch">
                                        Active
                                    </label>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-save me-1"></i>
                                        {{ $editingId ? 'Update' : 'Save' }}
                                    </button>
                                    
                                    @if($editingId)
                                        <button type="button" wire:click="resetForm" 
                                                class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- List Section -->
                <div class="col-md-7">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Imaging Types List</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Fee</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($types as $type)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold">{{ $type->name }}</div>
                                                    @if($type->description)
                                                        <small class="text-muted">{{ Str::limit($type->description, 50) }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-success rounded-pill">
                                                        ${{ number_format($type->fee, 2) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($type->is_active)
                                                        <span class="badge bg-success rounded-pill">
                                                            <i class="fas fa-check-circle me-1"></i>Active
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger rounded-pill">
                                                            <i class="fas fa-times-circle me-1"></i>Inactive
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $type->created_at->format('M d, Y') }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm" role="group">
                                                        <button wire:click="edit({{ $type->id }})" 
                                                                class="btn btn-outline-primary"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <button wire:click="delete({{ $type->id }})" 
                                                                class="btn btn-outline-danger"
                                                                onclick="return confirm('Are you sure you want to delete this imaging type?')"
                                                                title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center py-4">
                                                    <div class="text-muted">
                                                        <i class="fas fa-x-ray fa-2x mb-3"></i>
                                                        <p>No imaging types found. Add your first one!</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($types->count())
                            <div class="card-footer bg-light">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    {{ $types->count() }} imaging type(s) found
                                </small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for confirmation -->
    <script>
        document.addEventListener('livewire:load', function () {
            window.addEventListener('swal:confirm', event => {
                Swal.fire({
                    title: event.detail.title,
                    text: event.detail.text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.emit('deleteConfirmed', event.detail.id);
                    }
                });
            });
        });
    </script>
</div>