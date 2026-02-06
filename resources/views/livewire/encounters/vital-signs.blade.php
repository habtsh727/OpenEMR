<div>
    {{-- resources/views/livewire/vital-signs.blade.php --}}
<div>
    @if(!$encounter)
        <div class="alert alert-info">
            Please select an encounter to manage vital signs.
        </div>
    @else
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Vital Signs for Encounter #{{ $encounter->id }}
                    <small class="text-muted">(Patient: {{ $encounter->patient->name ?? 'N/A' }})</small>
                </h5>
            </div>
            
            <div class="card-body">
                <!-- Current Vitals -->
                @if(count($vitals) > 0)
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Vital</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vitals as $index => $vital)
                                    <tr>
                                        <td>
                                            <strong>{{ $vital['name'] }}</strong>
                                            @if($vital['unit'])
                                                <small class="text-muted">({{ $vital['unit'] }})</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($vital['data_type'] === 'select')
                                                <select 
                                                    wire:model.defer="vitals.{{ $index }}.value"
                                                    wire:change="updateVital({{ $index }})"
                                                    class="form-control form-control-sm"
                                                >
                                                    <option value="">Select...</option>
                                                    @foreach($vital['options'] as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif($vital['data_type'] === 'boolean')
                                                <select 
                                                    wire:model.defer="vitals.{{ $index }}.value"
                                                    wire:change="updateVital({{ $index }})"
                                                    class="form-control form-control-sm"
                                                >
                                                    <option value="no">No</option>
                                                    <option value="yes">Yes</option>
                                                </select>
                                            @else
                                                <input 
                                                    type="{{ $vital['data_type'] === 'number' ? 'number' : 'text' }}"
                                                    wire:model.defer="vitals.{{ $index }}.value"
                                                    wire:blur="updateVital({{ $index }})"
                                                    class="form-control form-control-sm"
                                                    step="{{ $vital['data_type'] === 'number' ? '0.01' : null }}"
                                                >
                                            @endif
                                        </td>
                                        <td>
                                            <button 
                                                wire:click="deleteVital({{ $vital['id'] }})"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Delete this vital sign?')"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning mb-4">
                        No vital signs recorded for this encounter.
                    </div>
                @endif

                <!-- Add New Vital -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Add New Vital Sign</h6>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="addVital">
                            <div class="row">
                                <div class="col-md-5">
                                    <label for="vital_type_id" class="form-label">Vital Type</label>
                                    <select 
                                        id="vital_type_id"
                                        wire:model="newVital.vital_type_id"
                                        class="form-control @error('newVital.vital_type_id') is-invalid @enderror"
                                    >
                                        <option value="">Select vital type...</option>
                                        @foreach($availableVitalTypes as $type)
                                            <option value="{{ $type['id'] }}">
                                                {{ $type['name'] }} @if($type['unit']) ({{ $type['unit'] }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('newVital.vital_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-5">
                                    <label for="value" class="form-label">Value</label>
                                    
                                    @if($newVital['vital_type_id'])
                                        @php
                                            $selectedType = collect($availableVitalTypes)
                                                ->firstWhere('id', $newVital['vital_type_id']);
                                        @endphp
                                        
                                        @if($selectedType['data_type'] === 'select')
                                            <select 
                                                id="value"
                                                wire:model="newVital.value"
                                                class="form-control @error('newVital.value') is-invalid @enderror"
                                            >
                                                <option value="">Select value...</option>
                                                @foreach($selectedType['options'] as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @elseif($selectedType['data_type'] === 'boolean')
                                            <select 
                                                id="value"
                                                wire:model="newVital.value"
                                                class="form-control @error('newVital.value') is-invalid @enderror"
                                            >
                                                <option value="">Select...</option>
                                                <option value="yes">Yes</option>
                                                <option value="no">No</option>
                                            </select>
                                        @else
                                            <input 
                                                type="{{ $selectedType['data_type'] === 'number' ? 'number' : 'text' }}"
                                                id="value"
                                                wire:model="newVital.value"
                                                class="form-control @error('newVital.value') is-invalid @enderror"
                                                step="{{ $selectedType['data_type'] === 'number' ? '0.01' : null }}"
                                                placeholder="Enter value"
                                            >
                                        @endif
                                    @else
                                        <input 
                                            type="text"
                                            id="value"
                                            wire:model="newVital.value"
                                            class="form-control @error('newVital.value') is-invalid @enderror"
                                            placeholder="Select vital type first"
                                            disabled
                                        >
                                    @endif
                                    
                                    @error('newVital.value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-2 d-flex align-items-end">
                                    <button 
                                        type="submit" 
                                        class="btn btn-primary w-100"
                                        {{ !$newVital['vital_type_id'] ? 'disabled' : '' }}
                                    >
                                        Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>