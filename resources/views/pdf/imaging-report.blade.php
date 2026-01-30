<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Imaging Report - {{ $patientName ?? 'Patient' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            margin: 1.5cm;
            size: A4;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 5rem;
            color: rgba(0, 0, 0, 0.05);
            z-index: -10;
            font-weight: 800;
            white-space: nowrap;
            opacity: 0.7;
            letter-spacing: 2px;
        }
        
        .medical-border {
            border-color: #1e40af;
        }
        
        .report-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .signature-line {
            border-top: 2px dashed #4b5563;
            width: 250px;
            margin-top: 60px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .avoid-break {
            page-break-inside: avoid;
        }
        
        .print\:shadow-none {
            box-shadow: none !important;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            .print-border {
                border: 1px solid #e5e7eb;
            }
            
            .print-bg-white {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .print-text-black {
                color: #000000 !important;
            }
        }
    </style>
</head>
<body class="bg-white text-gray-800 print:text-black print:bg-white">

    <!-- Watermark -->
    <div class="watermark">MEDICAL IMAGING</div>
    
    <!-- Official Header -->
    <div class="report-header text-white rounded-xl p-6 mb-8 print-border">
        <div class="text-center">
            <div class="flex justify-center items-center mb-3">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mr-3">
                    <svg class="w-6 h-6 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold tracking-tight">RADIOLOGY DEPARTMENT</h1>
            </div>
            <p class="text-blue-100 text-sm font-medium mb-1">CERTIFIED DIAGNOSTIC IMAGING CENTER</p>
            <p class="text-blue-200 text-xs">
                123 Medical Avenue • Healthcare City, HC 10001 • Tel: (555) 123-4567 • Fax: (555) 123-4568
            </p>
            <p class="text-blue-200 text-xs mt-1">Email: radiology@medicalcenter.org • Web: www.medicalcenter.org/radiology</p>
        </div>
    </div>

    <!-- Report Identification -->
    <div class="border-2 border-blue-800 rounded-lg p-5 mb-8 print-border avoid-break">
        <div class="flex justify-between items-center mb-2">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">DIAGNOSTIC IMAGING REPORT</h2>
                <p class="text-sm text-gray-600 font-medium">Official Medical Document</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-50 px-3 py-2 rounded-lg inline-block">
                    <p class="text-xs font-semibold text-blue-700 uppercase tracking-wide">Report ID</p>
                    <p class="text-lg font-bold text-blue-900">#{{ str_pad($result->id ?? '0000', 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mt-4">
            <div>
                <p class="text-sm font-semibold text-gray-600">Report Date</p>
                <p class="text-lg font-bold text-gray-900">{{ $reportDate }}</p>
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-600">Printed On</p>
                <p class="text-lg font-medium text-gray-900">{{ $currentDate }} at {{ $nowTime }}</p>
            </div>
        </div>
    </div>

    <!-- Patient Information Section -->
    <div class="mb-8 avoid-break">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900 border-b-2 border-blue-200 pb-2 flex-1">
                PATIENT DEMOGRAPHICS
            </h3>
        </div>
        
        <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 print-border">
            <div class="grid grid-cols-2 gap-6">
                <!-- Column 1 -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Full Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $patientName ?? 'Not Available' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Patient ID</label>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 00-1 1v1a1 1 0 002 0V3a1 1 0 00-1-1zM4 4h3a3 3 0 006 0h3a2 2 0 012 2v9a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2zm2.5 7a1.5 1.5 0 100-3 1.5 1.5 0 000 3zm2.45 4a2.5 2.5 0 10-4.9 0h4.9zM12 9a1 1 0 100 2h3a1 1 0 100-2h-3zm-1 4a1 1 0 011-1h2a1 1 0 110 2h-2a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <p class="text-base font-medium text-gray-800">{{ $patient->card_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Date of Birth</label>
                        <p class="text-base font-medium text-gray-800">
                            {{ $patient->date_of_birth ? \Carbon\Carbon::parse($patient->date_of_birth)->format('F j, Y') : 'Not Available' }}
                        </p>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Age / Gender</label>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                @if($patient && $patient->date_of_birth)
                                    {{ \Carbon\Carbon::parse($patient->date_of_birth)->age }} years
                                @else
                                    N/A
                                @endif
                            </span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm font-semibold">
                                {{ $patient->gender ? strtoupper($patient->gender) : 'N/A' }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Encounter Reference</label>
                        <p class="text-base font-medium text-gray-800">
                            #{{ $encounter->encounter_id ?? $encounter->id ?? 'N/A' }}
                        </p>
                    </div>
                    
                    @if($patient->phone_number1)
                    <div>
                        <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Contact Number</label>
                        <p class="text-base font-medium text-gray-800">{{ $patient->phone_number1 }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($patient->mother_name)
            <div class="mt-4 pt-4 border-t border-blue-200">
                <label class="block text-xs font-semibold text-blue-700 uppercase tracking-wide mb-1">Next of Kin</label>
                <p class="text-base font-medium text-gray-800">Mother: {{ $patient->mother_name }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Study Information -->
    <div class="mb-8 avoid-break">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900 border-b-2 border-blue-200 pb-2 flex-1">
                STUDY DETAILS
            </h3>
        </div>
        
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 print-border">
            <div class="grid grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Imaging Procedure</label>
                        <p class="text-lg font-bold text-gray-900">{{ $imagingType->name ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Anatomic Region</label>
                        <p class="text-base font-medium text-gray-800">{{ $bodyPart->name ?? 'Not Specified' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Study Date & Time</label>
                        <p class="text-base font-medium text-gray-800">{{ $orderDate }}</p>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1">Order Information</label>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order #:</span>
                                <span class="font-bold text-gray-900">#{{ $imagingOrder->id ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Priority:</span>
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                   {{ (($imagingOrder->priority ?? 'routine') === 'urgent') ? 'bg-red-100 text-red-800' : 
   ((($imagingOrder->priority ?? 'routine') === 'stat') ? 'bg-orange-100 text-orange-800' : 
   'bg-blue-100 text-blue-800') }}">
                                    {{ strtoupper($imagingOrder->priority ?? 'ROUTINE') }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="px-2 py-1 text-xs rounded-full font-semibold
                                    {{ $result->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                       ($result->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($result->status === 'pending' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                    {{ strtoupper(str_replace('_', ' ', $result->status)) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    @if(!empty($imagingOrder->clinical_notes))
                    <div class="mt-4">
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">Clinical Indication</label>
                        <div class="bg-white border border-gray-300 rounded p-3">
                            <p class="text-sm text-gray-700 italic">"{{ $imagingOrder->clinical_notes }}"</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Radiologist Report -->
    <div class="mb-10 avoid-break">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900 border-b-2 border-blue-200 pb-2 flex-1">
                RADIOLOGIST'S INTERPRETATION
            </h3>
        </div>
        
        <div class="border-2 border-gray-300 rounded-xl overflow-hidden print-border">
            <div class="bg-gray-800 text-white px-5 py-3">
                <h4 class="text-lg font-bold">FINAL REPORT</h4>
                <p class="text-sm text-gray-300">Dictated and Electronically Signed</p>
            </div>
            
            <div class="p-6 bg-white min-h-[400px]">
                @if($result && !empty($result->report))
                    <div class="prose prose-sm max-w-none">
                        {!! nl2br(e($result->report)) !!}
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-64 text-gray-400">
                        <svg class="w-16 h-16 mb-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-lg font-medium">No report content available</p>
                        <p class="text-sm">Report pending or not completed</p>
                    </div>
                @endif
            </div>
            
            @if($result->findings || $result->measurements)
            <div class="border-t border-gray-300">
                <div class="grid grid-cols-2 divide-x divide-gray-300">
                    @if($result->findings)
                    <div class="p-4">
                        <h5 class="font-bold text-gray-700 mb-2">KEY FINDINGS</h5>
                        <p class="text-sm text-gray-600">{{ $result->findings }}</p>
                    </div>
                    @endif
                    
                    @if($result->measurements)
                    <div class="p-4">
                        <h5 class="font-bold text-gray-700 mb-2">MEASUREMENTS</h5>
                        <p class="text-sm text-gray-600">{{ $result->measurements }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Images Section -->
    @php
        // Safely handle images data
        $imageCount = 0;
        $imagesArray = [];
        
        if (!empty($images)) {
            if (is_array($images)) {
                $imagesArray = $images;
            } elseif (is_string($images)) {
                $decoded = json_decode($images, true);
                if (is_array($decoded)) {
                    $imagesArray = $decoded;
                }
            }
            
            $imageCount = count($imagesArray);
        }
        
        function getImageBase64($path) {
            try {
                $fullPath = storage_path('app/public/' . $path);
                if (file_exists($fullPath)) {
                    $imageData = file_get_contents($fullPath);
                    $base64 = base64_encode($imageData);
                    $mimeType = mime_content_type($fullPath);
                    return "data:{$mimeType};base64,{$base64}";
                }
            } catch (\Exception $e) {
                return null;
            }
            return null;
        }
    @endphp

    @if($imageCount > 0)
    <div class="mb-8 avoid-break">
        <div class="flex items-center mb-4">
            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-blue-900 border-b-2 border-blue-200 pb-2 flex-1">
                ATTACHED IMAGES ({{ $imageCount }})
            </h3>
        </div>
        
        <div class="border border-gray-300 rounded-xl overflow-hidden print-border">
            <div class="bg-gray-50 px-5 py-3 border-b border-gray-300">
                <div class="flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-gray-800">DICOM IMAGES</h4>
                        <p class="text-xs text-gray-600">Order #{{ $imagingOrder->id ?? 'N/A' }} • {{ $imageCount }} image(s)</p>
                    </div>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                        DIGITAL ARCHIVE
                    </span>
                </div>
            </div>
            
            <div class="p-4 bg-white">
                @if($imageCount <= 2)
                <div class="grid grid-cols-2 gap-4">
                    @foreach($imagesArray as $index => $imagePath)
                        @php
                            $filename = basename($imagePath);
                            $base64Image = getImageBase64($imagePath);
                        @endphp
                        
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-3 py-2 border-b border-gray-300">
                                <p class="text-xs font-semibold text-gray-700 text-center">
                                    IMAGE {{ $index + 1 }}
                                </p>
                            </div>
                            
                            @if($base64Image)
                                <div class="p-4 flex justify-center items-center min-h-[200px]">
                                    <img src="{{ $base64Image }}" 
                                         alt="Medical Image {{ $index + 1 }}"
                                         class="max-w-full max-h-[250px] object-contain shadow-sm">
                                </div>
                            @else
                                <div class="p-6 text-center bg-gray-50">
                                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                    </svg>
                                    <p class="text-sm text-gray-500">Image unavailable for display</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $filename }}</p>
                                </div>
                            @endif
                            
                            <div class="bg-gray-50 px-3 py-2 border-t border-gray-300">
                                <p class="text-xs text-gray-600 text-center truncate" title="{{ $filename }}">
                                    {{ $filename }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="grid grid-cols-3 gap-3">
                    @foreach($imagesArray as $index => $imagePath)
                        @php
                            $filename = basename($imagePath);
                            $base64Image = getImageBase64($imagePath);
                        @endphp
                        
                        <div class="border border-gray-300 rounded overflow-hidden">
                            <div class="bg-gray-100 px-2 py-1 border-b border-gray-300">
                                <p class="text-xs font-semibold text-gray-700 text-center truncate">
                                    IMG {{ $index + 1 }}
                                </p>
                            </div>
                            
                            @if($base64Image)
                                <div class="p-2 flex justify-center items-center min-h-[120px]">
                                    <img src="{{ $base64Image }}" 
                                         alt="Image {{ $index + 1 }}"
                                         class="max-w-full max-h-[120px] object-contain">
                                </div>
                            @endif
                            
                            <div class="bg-gray-50 px-2 py-1 border-t border-gray-300">
                                <p class="text-xs text-gray-600 text-center truncate" title="{{ $filename }}">
                                    {{ Str::limit($filename, 15) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
                
                @if($imageCount > 0)
                <div class="mt-4 pt-4 border-t border-gray-300">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <p>All images are stored in the PACS (Picture Archiving and Communication System) under Order #{{ $imagingOrder->id ?? 'N/A' }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Radiologist Signature & Credentials -->
    <div class="mt-12 avoid-break">
        <div class="border-t-2 border-blue-300 pt-6">
            <h3 class="text-xl font-bold text-blue-900 mb-6 text-center">
                INTERPRETING RADIOLOGIST
            </h3>
            
            <div class="grid grid-cols-2 gap-8">
                <!-- Left Column: Radiologist Details -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-2">RADIOLOGIST</label>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xl font-bold text-gray-900">{{ $radiologist->name ?? 'Not Available' }}</p>
                                <p class="text-sm text-gray-600">Board Certified Radiologist</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Medical License #</label>
                            <p class="text-base font-medium text-gray-800">{{ $radiologist->medical_license ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">NPI Number</label>
                            <p class="text-base font-medium text-gray-800">{{ $radiologist->npi_number ?? 'N/A' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide mb-1">Signature Date</label>
                            <p class="text-base font-medium text-gray-800">{{ $reportDate }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Right Column: Signature -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wide mb-4">ELECTRONIC SIGNATURE</label>
                    <div class="relative">
                        <div class="signature-line"></div>
                        <div class="mt-2 text-center">
                            <p class="text-lg font-bold text-gray-900 mb-1">{{ $radiologist->name ?? '' }}</p>
                            <p class="text-sm text-gray-600">Digitally Signed and Authenticated</p>
                            <p class="text-xs text-gray-500 mt-2">Date: {{ $reportDate }}</p>
                        </div>
                        <div class="absolute top-0 right-0">
                            <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="mt-16 pt-8 border-t-2 border-gray-300 text-center text-xs text-gray-600 avoid-break">
        <div class="mb-4">
            <p class="font-bold text-gray-800 uppercase tracking-wide mb-2">IMPORTANT NOTICE</p>
            <p class="mb-1">This report is an electronically generated medical document and is valid without handwritten signature.</p>
            <p class="mb-3">Original report maintained in the electronic medical record system.</p>
        </div>
        
        <div class="grid grid-cols-4 gap-4 mb-4 text-sm">
            <div>
                <p class="font-semibold text-gray-700">Report ID</p>
                <p class="font-bold text-gray-900">{{ $result->id ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700">Order ID</p>
                <p class="font-bold text-gray-900">#{{ $imagingOrder->id ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700">Encounter</p>
                <p class="font-bold text-gray-900">{{ $encounter->encounter_id ?? $encounter->id ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="font-semibold text-gray-700">Patient ID</p>
                <p class="font-bold text-gray-900">{{ $patient->card_number ?? 'N/A' }}</p>
            </div>
        </div>
        
        <div class="border-t border-gray-300 pt-4 mt-4">
            <p class="font-medium text-gray-800">
                For questions regarding this report, contact the Radiology Department at (555) 123-4567
            </p>
            <p class="text-gray-600 mt-1">
                Generated electronically on {{ $currentDate }} at {{ $nowTime }} • 
                This document contains {{ $imageCount }} attached images
            </p>
        </div>
        
        <!-- Page Number -->
        <div class="mt-6 pt-4 border-t border-gray-300">
            <p class="text-gray-500">Page 1 of 1 • Official Medical Record</p>
        </div>
    </div>

</body>
</html>