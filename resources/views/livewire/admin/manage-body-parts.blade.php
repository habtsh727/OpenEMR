<div>
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-body me-2"></i>Manage Body Parts
            </h5>
        </div>
        
        <div class="card-body">
            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Form Section -->
                <div class="col-md-5">
                    <div class="card border-primary">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-{{ $editingId ? 'edit' : 'plus' }} me-1"></i>
                                {{ $editingId ? 'Edit Body Part' : 'Add New Body Part' }}
                            </h6>
                        </div>
                        <div class="card-body">
                            <form wire:submit.prevent="save">
                                <div class="mb-3">
                                    <label class="form-label">Name *</label>
                                    <input type="text" wire:model="name" class="form-control" 
                                           placeholder="e.g., Chest, Abdomen, Head" required>
                                    @error('name') 
                                        <span class="text-danger small">{{ $message }}</span> 
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Code</label>
                                    <input type="text" wire:model="code" class="form-control" 
                                           placeholder="e.g., CHEST, ABD, HEAD (optional)">
                                    <small class="text-muted">Short code for reference</small>
                                    @error('code') 
                                        <span class="text-danger small">{{ $message }}</span> 
                                    @enderror
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
                                        <button type="button" wire:click="cancelEdit" 
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
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Body Parts List</h6>
                                <small class="text-muted">
                                    {{ $bodyParts->count() }} body part(s)
                                </small>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($bodyParts->count())
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Name</th>
                                                <th>Code</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($bodyParts as $bodyPart)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $bodyPart->name }}</div>
                                                        @if($bodyPart->description)
                                                            <small class="text-muted">
                                                                {{ Str::limit($bodyPart->description, 40) }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($bodyPart->code)
                                                            <span class="badge bg-info rounded-pill">
                                                                {{ $bodyPart->code }}
                                                            </span>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button wire:click="toggleStatus({{ $bodyPart->id }})" 
                                                                class="btn btn-sm btn-{{ $bodyPart->is_active ? 'success' : 'danger' }}"
                                                                title="Click to toggle status">
                                                            @if($bodyPart->is_active)
                                                                <i class="fas fa-toggle-on me-1"></i>Active
                                                            @else
                                                                <i class="fas fa-toggle-off me-1"></i>Inactive
                                                            @endif
                                                        </button>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm" role="group">
                                                            <button wire:click="edit({{ $bodyPart->id }})" 
                                                                    class="btn btn-outline-primary"
                                                                    title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button wire:click="delete({{ $bodyPart->id }})" 
                                                                    class="btn btn-outline-danger"
                                                                    onclick="return confirm('Are you sure? This will permanently delete this body part.')"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-body fa-3x mb-3"></i>
                                        <p>No body parts found. Add your first one!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Statistics -->
                    @if($bodyParts->count())
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Active:</span>
                                            <span class="badge bg-success">
                                                {{ $bodyParts->where('is_active', true)->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>Inactive:</span>
                                            <span class="badge bg-danger">
                                                {{ $bodyParts->where('is_active', false)->count() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-generate code from name -->
    <script>
        document.addEventListener('livewire:load', function () {
            const nameInput = document.querySelector('input[wire\\:model="name"]');
            const codeInput = document.querySelector('input[wire\\:model="code"]');
            
            if (nameInput && codeInput) {
                nameInput.addEventListener('blur', function() {
                    if (!@this.editingId && nameInput.value.trim() && !codeInput.value.trim()) {
                        // Generate code from name (uppercase, no spaces)
                        const code = nameInput.value.trim().toUpperCase()
                            .replace(/\s+/g, '_')
                            .replace(/[^A-Z0-9_]/g, '')
                            .substring(0, 10);
                        
                        // Only auto-generate if code field is empty
                        if (!codeInput.value.trim()) {
                            @this.set('code', code);
                        }
                    }
                });
            }
        });
    </script>
</div>