<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-4 md:p-8">
    {{-- STEP INDICATOR --}}
    <div class="mb-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-8">
                @foreach ([1 => 'Vitals', 2 => 'History', 3 => 'Exam', 4 => 'Assessment', 5 => 'Orders'] as $key => $label)
                    <div class="flex flex-col items-center gap-2 flex-1">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm
                            {{ $step >= $key ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-300 text-slate-600' }}
                            {{ $step == $key ? 'ring-4 ring-blue-200' : '' }}">
                            {{ $key }}
                        </div>
                        <span class="hidden md:inline text-xs font-medium text-slate-700">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            <div class="h-1 bg-slate-200 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-300"
                    style="width: {{ ($step / 5) * 100 }}%"></div>
            </div>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-2xl shadow-lg p-6 md:p-8 space-y-6">

            {{-- STEP 1 – VITALS --}}
            @if($step === 1)
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">Vital Signs</h2>
                        <p class="text-slate-500">Patient's current vital measurements</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                            <div class="text-sm text-slate-600 font-medium mb-1">Blood Pressure</div>
                            <div class="text-2xl font-bold text-blue-700">{{ $queue->patient->latestVital->bp_systolic }}/{{ $queue->patient->latestVital->bp_diastolic }}</div>
                            <div class="text-xs text-slate-500 mt-1">mmHg</div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200">
                            <div class="text-sm text-slate-600 font-medium mb-1">Pulse</div>
                            <div class="text-2xl font-bold text-red-700">{{ $queue->patient->latestVital->pulse }}</div>
                            <div class="text-xs text-slate-500 mt-1">bpm</div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200">
                            <div class="text-sm text-slate-600 font-medium mb-1">Temperature</div>
                            <div class="text-2xl font-bold text-orange-700">{{ $queue->patient->latestVital->temperature }}</div>
                            <div class="text-xs text-slate-500 mt-1">°C</div>
                        </div>
                        <div class="p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200">
                            <div class="text-sm text-slate-600 font-medium mb-1">O₂ Saturation</div>
                            <div class="text-2xl font-bold text-green-700">{{ $queue->patient->latestVital->spo2 }}%</div>
                            <div class="text-xs text-slate-500 mt-1">SpO₂</div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- STEP 2 – HISTORY & COMPLAINTS --}}
            @if($step === 2)
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">Medical History & Complaints</h2>
                        <p class="text-slate-500">Select relevant history and current complaints</p>
                    </div>

                    {{-- Medical History --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-900">Medical History</label>
                            @if(count($history) > 0)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">{{ count($history) }} selected</span>
                            @endif
                        </div>
                        <input type="text" wire:model.live="history_search" placeholder="Search medical history..." 
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                            @foreach(['Hypertension', 'Diabetes', 'Asthma', 'Heart Disease', 'Chronic Pain', 'Thyroid Disorder'] as $option)
                                @if(str_contains(strtolower($option), strtolower($history_search)) || empty($history_search))
                                    <label class="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer transition">
                                        <input type="checkbox" wire:change="toggleHistory('{{ $option }}')" 
                                            {{ in_array($option, $history) ? 'checked' : '' }} 
                                            class="w-4 h-4 accent-blue-600">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Chief Complaints --}}
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-sm font-semibold text-slate-900">Chief Complaints</label>
                            @if(count($current_complaints) > 0)
                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">{{ count($current_complaints) }} selected</span>
                            @endif
                        </div>
                        <input type="text" wire:model.live="complaints_search" placeholder="Search complaints..." 
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                            @foreach(['Fever', 'Cough', 'Chest Pain', 'Shortness of Breath', 'Headache', 'Nausea'] as $option)
                                @if(str_contains(strtolower($option), strtolower($complaints_search)) || empty($complaints_search))
                                    <label class="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer transition">
                                        <input type="checkbox" wire:change="toggleComplaint('{{ $option }}')" 
                                            {{ in_array($option, $current_complaints) ? 'checked' : '' }} 
                                            class="w-4 h-4 accent-blue-600">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- STEP 3 – PHYSICAL EXAM --}}
            @if($step === 3)
                <div class="space-y-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">Physical Examination</h2>
                        <p class="text-slate-500">Document examination findings</p>
                    </div>
                    <input type="text" wire:model.live="exam_search" placeholder="Search examination findings..." 
                        class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                    <div class="max-h-96 overflow-y-auto border border-slate-200 rounded-lg p-4 space-y-2">
                        @foreach(['General Appearance Normal', 'Cardiovascular Normal', 'Respiratory Normal', 'Abdominal Soft', 'Neuro Intact', 'Skin Normal'] as $option)
                            @if(str_contains(strtolower($option), strtolower($exam_search)) || empty($exam_search))
                                <label class="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer transition">
                                    <input type="checkbox" wire:change="toggleExam('{{ $option }}')" 
                                        {{ in_array($option, $physical_exam) ? 'checked' : '' }} 
                                        class="w-4 h-4 accent-blue-600">
                                    <span class="text-sm text-slate-700">{{ $option }}</span>
                                </label>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- STEP 4 – ASSESSMENT --}}
            @if($step === 4)
                <div class="space-y-6">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">Assessment & Notes</h2>
                        <p class="text-slate-500">Clinical assessment and diagnosis</p>
                    </div>
                    
                    <div class="space-y-3">
                        <input type="text" wire:model.live="assessment_search" placeholder="Search diagnoses..." 
                            class="w-full px-4 py-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                        <div class="max-h-48 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                            @foreach(['Hypertension', 'Diabetes Type 2', 'Respiratory Infection', 'Dehydration', 'Migraine', 'Gastritis'] as $option)
                                @if(str_contains(strtolower($option), strtolower($assessment_search)) || empty($assessment_search))
                                    <label class="flex items-center gap-3 p-2 hover:bg-blue-50 rounded-lg cursor-pointer transition">
                                        <input type="checkbox" wire:change="toggleAssessment('{{ $option }}')" 
                                            {{ in_array($option, $assessment_options) ? 'checked' : '' }} 
                                            class="w-4 h-4 accent-blue-600">
                                        <span class="text-sm text-slate-700">{{ $option }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-slate-900 block mb-2">Clinical Notes</label>
                        <textarea wire:model="assessment_notes" rows="5" 
                            class="w-full px-4 py-3 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition resize-none"
                            placeholder="Enter clinical assessment and notes..."></textarea>
                    </div>
                </div>
            @endif

            {{-- STEP 5 – ORDERS --}}
            @if($step === 5)
                <div class="space-y-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 mb-1">Orders</h2>
                        <p class="text-slate-500">Prescriptions, lab tests, and imaging</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Lab Tests --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-900">Lab Tests</label>
                                @if(count($lab_orders) > 0)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ count($lab_orders) }}</span>
                                @endif
                            </div>
                            <input type="text" wire:model.live="lab_search" placeholder="Search labs..." 
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            <div class="max-h-40 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                                @foreach(['CBC', 'Blood Glucose', 'Liver Panel', 'Kidney Panel', 'Lipid Panel'] as $option)
                                    @if(str_contains(strtolower($option), strtolower($lab_search)) || empty($lab_search))
                                        <label class="flex items-center gap-2 p-1 hover:bg-blue-50 rounded cursor-pointer">
                                            <input type="checkbox" wire:change="toggleLabOrder('{{ $option }}')" 
                                                {{ in_array($option, $lab_orders) ? 'checked' : '' }} 
                                                class="w-4 h-4 accent-blue-600">
                                            <span class="text-sm text-slate-700">{{ $option }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Imaging --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-900">Imaging</label>
                                @if(count($imaging_orders) > 0)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ count($imaging_orders) }}</span>
                                @endif
                            </div>
                            <input type="text" wire:model.live="imaging_search" placeholder="Search imaging..." 
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            <div class="max-h-40 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                                @foreach(['X-ray', 'Ultrasound', 'CT Scan', 'MRI', 'ECG'] as $option)
                                    @if(str_contains(strtolower($option), strtolower($imaging_search)) || empty($imaging_search))
                                        <label class="flex items-center gap-2 p-1 hover:bg-blue-50 rounded cursor-pointer">
                                            <input type="checkbox" wire:change="toggleImaging('{{ $option }}')" 
                                                {{ in_array($option, $imaging_orders) ? 'checked' : '' }} 
                                                class="w-4 h-4 accent-blue-600">
                                            <span class="text-sm text-slate-700">{{ $option }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- Medications --}}
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <label class="text-sm font-semibold text-slate-900">Medications</label>
                                @if(count($medications) > 0)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ count($medications) }}</span>
                                @endif
                            </div>
                            <input type="text" wire:model.live="medications_search" placeholder="Search medications..." 
                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
                            <div class="max-h-40 overflow-y-auto border border-slate-200 rounded-lg p-3 space-y-2">
                                @forelse($pharmacyitems as $medication)
                                    @if(str_contains(strtolower($medication), strtolower($medications_search)) || empty($medications_search))
                                        <label class="flex items-center gap-2 p-1 hover:bg-blue-50 rounded cursor-pointer">
                                            <input type="checkbox" wire:change="toggleMedication('{{ $medication }}')" 
                                                {{ in_array($medication, $medications) ? 'checked' : '' }} 
                                                class="w-4 h-4 accent-blue-600">
                                            <span class="text-sm text-slate-700">{{ $medication }}</span>
                                        </label>
                                    @endif
                                @empty
                                    <p class="text-sm text-slate-500 p-2">No medications available</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- NAVIGATION --}}
            <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-200">
                <button wire:click="previousStep" 
                    {{ $step === 1 ? 'disabled' : '' }}
                    class="px-6 py-2.5 rounded-lg font-medium text-sm transition-all
                    {{ $step === 1 ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}">
                    ← Back
                </button>
                
                <div class="text-sm text-slate-500 font-medium">
                    Step {{ $step }} of 5
                </div>

                @if($step < 5)
                    <button wire:click="nextStep" class="px-8 py-2.5 bg-blue-600 text-white rounded-lg font-medium text-sm hover:bg-blue-700 transition-all shadow-md hover:shadow-lg">
                        Next →
                    </button>
                @else
                    <flux:button wire:click="saveConsultation" variant="primary" class="px-8 py-2.5 font-medium text-sm shadow-md hover:shadow-lg">
                        ✓ Save & Submit
                    </flux:button>
                @endif
            </div>
        </div>
    </div>
</div>
