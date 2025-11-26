<flux:modal name="patient-detail" class="max-w-[65rem] mx-auto">
    <!-- Hero Header with Profile -->
    <div class="relative bg-gradient-to-r from-sky-500 to-sky-600 px-6 pt-8 pb-32 rounded-t-lg shadow-lg">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-4">
                <!-- Patient Avatar -->
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg">
                    <svg class="w-10 h-10 text-sky-500" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="text-white">
                    <h1 class="text-2xl font-bold">{{ $first_name }} {{ $middle_name }}</h1>
                    <p class="text-sky-100">{{ $card_number }}</p>
                </div>
            </div>
            <!-- Status Badge -->
            <div class="px-4 py-2 bg-white text-sky-600 rounded-full text-sm font-semibold shadow-lg">
                Active
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="relative -mt-24 mx-6 grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <!-- Age Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-sky-500">
            <div class="text-xs text-slate-600 font-semibold uppercase tracking-wider mb-1">Age</div>
            <div class="text-2xl font-bold text-slate-900">
                @php
                    $age = \Carbon\Carbon::parse($date_of_birth)->age ?? 'N/A';
                @endphp
                {{ $age }}
            </div>
        </div>
        <!-- Gender Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-emerald-500">
            <div class="text-xs text-slate-600 font-semibold uppercase tracking-wider mb-1">Gender</div>
            <div class="text-2xl font-bold text-slate-900">{{ $gender }}</div>
        </div>
        <!-- Contact Card -->
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-amber-500">
            <div class="text-xs text-slate-600 font-semibold uppercase tracking-wider mb-1">Phone</div>
            <a href="tel:{{ $phone_number1 }}" class="text-lg font-bold text-sky-600 hover:text-sky-700">
                {{ $phone_number1 }}
            </a>
        </div>
    </div>

    <!-- Content Sections -->
    <div class="space-y-6 px-6 pb-6">
        <!-- Personal Information -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-sky-500 rounded"></div>
                Personal Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-1 gap-3">
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Mother Name</div>
                    <div class="text-slate-900 font-medium">{{ $mother_name }}</div>
                </div>
            </div>
        </div>

        <!-- Identity & Contact -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-emerald-500 rounded"></div>
                Identity & Contact
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Card Number</div>
                    <div class="text-slate-900 font-medium">{{ $card_number }}</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Date of Birth</div>
                    <div class="text-slate-900 font-medium">{{ $date_of_birth ?? 'N/A' }}</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Phone 2</div>
                    <div class="text-slate-900 font-medium">{{ $phone_number2 ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Emergency Contact -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-amber-500 rounded"></div>
                Emergency Contact
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Person</div>
                    <div class="text-slate-900 font-medium">{{ $emergency_person }}</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Contact</div>
                    <a href="tel:{{ $emergency_contact }}" class="text-sky-600 hover:text-sky-700 font-medium">
                        {{ $emergency_contact }}
                    </a>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Relationship</div>
                    <div class="text-slate-900 font-medium">{{ $emergency_person_relationship }}</div>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-purple-500 rounded"></div>
                Location Information
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Region</div>
                    <div class="text-slate-900 font-medium">{{ $region }}</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Zone</div>
                    <div class="text-slate-900 font-medium">{{ $zone }}</div>
                </div>
                <div class="bg-slate-50 rounded-lg p-4">
                    <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Woreda</div>
                    <div class="text-slate-900 font-medium">{{ $woreda }}</div>
                </div>
            </div>
        </div>

        <!-- Additional Info -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                <div class="w-1 h-5 bg-cyan-500 rounded"></div>
                Additional Information
            </h3>
            <div class="bg-slate-50 rounded-lg p-4">
                <div class="text-xs font-semibold text-slate-600 uppercase tracking-widest mb-2">Notes</div>
                <div class="text-slate-900 text-sm leading-relaxed">{{ $description ?? 'No additional notes' }}</div>
            </div>
        </div>
    </div>

</flux:modal>
