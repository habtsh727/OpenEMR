<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-slate-950 dark:to-slate-900 p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">
                        Patient Consultation
                    </h1>
                    <div class="flex flex-col sm:flex-row sm:gap-4 text-sm text-slate-600 dark:text-slate-400">
                        <span class="font-semibold">{{ $queue->patient->name }}</span>
                        <span class="hidden sm:inline text-slate-400">•</span>
                        <span>ID: {{ $queue->patient->card_number }}</span>
                        <span class="hidden sm:inline text-slate-400">•</span>
                        <span>{{ $queue->created_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>
                <div class="flex gap-3 flex-wrap">
                    <button wire:click="printConsultation"
                        class="px-4 py-2 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition font-medium text-sm">
                        🖨️ Print Record
                    </button>
                    <button wire:click="finishConsultation"
                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition font-medium text-sm">
                        ✓ Complete
                    </button>
                </div>
            </div>
        </div>

        <!-- Vital Signs Dashboard -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <!-- Blood Pressure -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Blood
                        Pressure</span>
                    <span class="text-2xl">💓</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $queue->patient?->latestVital->bp_systolic }}/{{ $queue->patient?->latestVital->bp_diastolic }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">mmHg</p>
            </div>

            <!-- Heart Rate -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Heart
                        Rate</span>
                    <span class="text-2xl">❤️</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $queue->patient->latestVital->pulse }}
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">beats per minute</p>
            </div>

            <!-- Temperature -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-3">
                    <span
                        class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">Temperature</span>
                    <span class="text-2xl">🌡️</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $queue->patient->latestVital->temperature }}°C
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">celsius</p>
            </div>

            <!-- Oxygen Level -->
            <div
                class="bg-white dark:bg-slate-800 rounded-xl shadow-md hover:shadow-lg transition p-6 border-l-4 border-cyan-500">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm font-semibold text-slate-600 dark:text-slate-400 uppercase tracking-wide">O₂
                        Saturation</span>
                    <span class="text-2xl">🫁</span>
                </div>
                <p class="text-3xl font-bold text-slate-900 dark:text-white">
                    {{ $queue->patient->latestVital->spo2 }}%
                </p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">blood oxygen</p>
            </div>
        </div>

        <!-- Main Consultation Form -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - Patient Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Medical History -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">📋</span> Medical History
                    </h2>
                    <select wire:model.defer="history" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="Hypertension">Hypertension</option>
                        <option value="Diabetes">Diabetes</option>
                        <option value="Heart Disease">Heart Disease</option>
                        <option value="Asthma">Asthma</option>
                        <option value="Allergies">Allergies</option>
                        <option value="Previous Surgery">Previous Surgery</option>
                        <option value="Medication Use">Medication Use</option>
                        <option value="Chronic Kidney Disease">Chronic Kidney Disease</option>
                        <option value="Liver Disease">Liver Disease</option>
                        <option value="Other">Other</option>
                    </select>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Hold Ctrl/Cmd to select multiple items
                    </p>
                </div>

                <!-- Current Complaints -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">🛠️</span> Chief Complaints
                    </h2>
                    <select wire:model.defer="current_complaints" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="Fever">Fever</option>
                        <option value="Cough">Cough</option>
                        <option value="Shortness of Breath">Shortness of Breath</option>
                        <option value="Chest Pain">Chest Pain</option>
                        <option value="Abdominal Pain">Abdominal Pain</option>
                        <option value="Headache">Headache</option>
                        <option value="Dizziness">Dizziness</option>
                        <option value="Fatigue">Fatigue</option>
                        <option value="Nausea/Vomiting">Nausea/Vomiting</option>
                        <option value="Other">Other</option>
                    </select>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Hold Ctrl/Cmd to select multiple items
                    </p>
                </div>

                <!-- Physical Examination -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">🔍</span> Physical Examination
                    </h2>
                    <select wire:model.defer="physical_exam" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="General Appearance Normal">General Appearance Normal</option>
                        <option value="Head & Neck Exam Normal">Head & Neck Exam Normal</option>
                        <option value="Cardiovascular Normal">Cardiovascular Normal</option>
                        <option value="Respiratory Normal">Respiratory Normal</option>
                        <option value="Abdomen Soft & Non-Tender">Abdomen Soft & Non-Tender</option>
                        <option value="Extremities Normal">Extremities Normal</option>
                        <option value="Neurological Exam Normal">Neurological Exam Normal</option>
                        <option value="Skin Normal">Skin Normal</option>
                        <option value="Other Abnormal Findings">Other Abnormal Findings</option>
                    </select>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Hold Ctrl/Cmd to select multiple items
                    </p>
                </div>
            </div>

            <!-- Right Column - Assessment & Notes -->
            <div class="space-y-6">
                <!-- Assessment -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <span class="text-xl">📊</span> Assessment
                    </h2>
                    <select wire:model.defer="assessment_options" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-28 text-sm mb-3">
                        <option value="Hypertension">Hypertension</option>
                        <option value="Diabetes">Diabetes</option>
                        <option value="Respiratory Infection">Respiratory Infection</option>
                        <option value="Gastroenteritis">Gastroenteritis</option>
                        <option value="Anemia">Anemia</option>
                        <option value="Viral Infection">Viral Infection</option>
                        <option value="Dehydration">Dehydration</option>
                        <option value="Other">Other</option>
                    </select>
                    <textarea wire:model.defer="assessment_notes" rows="4"
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none text-sm resize-none"
                        placeholder="Additional clinical notes..."></textarea>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-md p-6 mt-6">
            <h2 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                <span class="text-xl">💊</span> Treatment Orders
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Lab Tests -->
                <div>
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-3">🧪 Lab Tests</label>
                    <select wire:model.defer="lab_orders" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="CBC">Complete Blood Count</option>
                        <option value="Blood Glucose">Blood Glucose</option>
                        <option value="Liver Panel">Liver Panel</option>
                        <option value="Kidney Function">Kidney Function</option>
                        <option value="Electrolytes">Electrolytes</option>
                    </select>
                </div>

                <!-- Imaging -->
                <div>
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-3">🖼️ Imaging</label>
                    <select wire:model.defer="imaging_orders" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="X-ray">X-ray</option>
                        <option value="MRI">MRI Scan</option>
                        <option value="Ultrasound">Ultrasound</option>
                        <option value="CT Scan">CT Scan</option>
                        <option value="Echocardiogram">Echocardiogram</option>
                    </select>
                </div>

                <!-- Medications -->
                <div>
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-3">💊
                        Medications</label>
                    <select wire:model.defer="medications" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        @foreach($pharmacyitems as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                       @endforeach
                    </select>
                </div>

                <!-- Procedures -->
                <div>
                    <label class="text-sm font-bold text-slate-700 dark:text-slate-300 block mb-3">🏥
                        Procedures</label>
                    <select wire:model.defer="procedures" multiple
                        class="w-full p-3 rounded-lg border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none min-h-32 text-sm">
                        <option value="IV Line">IV Line Setup</option>
                        <option value="Wound Dressing">Wound Dressing</option>
                        <option value="Catheterization">Catheterization</option>
                        <option value="Injection">Injection</option>
                        <option value="Vaccination">Vaccination</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 mt-8">
            <button wire:click="saveDraft"
                class="px-6 py-3 bg-slate-200 dark:bg-slate-700 text-slate-900 dark:text-white rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 transition font-semibold">
                💾 Save Draft
            </button>
            <button wire:click="saveConsultation"
                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-lg transition font-semibold shadow-lg">
                ✓ Save & Submit
            </button>
        </div>
    </div>
    <!-- Responsive Adjustments -->
    <style>
        @media (max-width: 768px) {
            select[multiple] {
                min-height: 120px;
            }
        }
    </style>
</div>
