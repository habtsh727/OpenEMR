<div class="bg-white dark:bg-gray-900">
    {{-- resources/views/referrals/print/referral-pdf.blade.php --}}
    <!DOCTYPE html>
    <html class="dark">

    <head>
        <meta charset="utf-8">
        <title>Referral {{ $referral->id }}</title>
        <style>
            @page {
                margin: 0.5in;
                size: letter;
            }

            body {
                font-family: 'Helvetica', 'Arial', sans-serif;
                font-size: 12px;
                line-height: 1.4;
                color: #333;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @media screen {
                body {
                    background: white;
                    padding: 20px;
                }

                .dark body {
                    background: #111827;
                    color: #f3f4f6;
                }

                .referral-border {
                    max-width: 8.5in;
                    margin: 0 auto;
                    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
                }
            }

            .referral-border {
                border: 2px solid #1e40af;
                padding: 30px;
                position: relative;
                min-height: 10in;
                background: white;
            }

            .dark .referral-border {
                border-color: #60a5fa;
                background: #1f2937;
            }

            .referral-border::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #1e40af, #3b82f6);
            }

            .dark .referral-border::before {
                background: linear-gradient(90deg, #60a5fa, #93c5fd);
            }

            .referral-border::after {
                content: "";
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 4px;
                background: linear-gradient(90deg, #1e40af, #3b82f6);
            }

            .dark .referral-border::after {
                background: linear-gradient(90deg, #60a5fa, #93c5fd);
            }

            .header {
                border-bottom: 2px solid #1e40af;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }

            .dark .header {
                border-color: #60a5fa;
            }

            h1 {
                font-size: 28px;
                font-weight: bold;
                color: #1e40af;
                margin: 0 0 10px 0;
            }

            .dark h1 {
                color: #60a5fa;
            }

            h2 {
                font-size: 20px;
                font-weight: bold;
                color: #333;
                margin: 25px 0 15px 0;
                border-left: 4px solid #3b82f6;
                padding-left: 10px;
            }

            .dark h2 {
                color: #f3f4f6;
                border-left-color: #93c5fd;
            }

            h3 {
                font-size: 16px;
                font-weight: bold;
                color: #444;
                margin: 20px 0 10px 0;
            }

            .dark h3 {
                color: #e5e7eb;
            }

            .section {
                margin-bottom: 25px;
                padding-bottom: 25px;
                border-bottom: 1px solid #e5e7eb;
            }

            .dark .section {
                border-color: #374151;
            }

            .patient-info,
            .facility-info {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin-bottom: 20px;
            }

            .info-row {
                margin-bottom: 8px;
            }

            .info-label {
                font-weight: bold;
                color: #4b5563;
                display: inline-block;
                width: 140px;
            }

            .dark .info-label {
                color: #9ca3af;
            }

            .info-value {
                color: #111827;
            }

            .dark .info-value {
                color: #f9fafb;
            }

            .clinical-summary {
                background: #f9fafb;
                border: 1px solid #d1d5db;
                padding: 15px;
                border-radius: 4px;
                white-space: pre-line;
                margin-top: 10px;
            }

            .dark .clinical-summary {
                background: #1f2937;
                border-color: #374151;
                color: #e5e7eb;
            }

            .reason-box {
                background: #f3f4f6;
                border: 1px solid #d1d5db;
                padding: 10px;
                border-radius: 4px;
                margin-top: 5px;
                font-weight: 500;
            }

            .dark .reason-box {
                background: #1f2937;
                border-color: #374151;
                color: #e5e7eb;
            }

            .badge {
                display: inline-block;
                padding: 4px 12px;
                border-radius: 20px;
                font-size: 11px;
                font-weight: bold;
                margin-right: 8px;
                margin-bottom: 8px;
            }

            .badge-sent {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-completed {
                background: #d1fae5;
                color: #065f46;
            }

            .badge-created {
                background: #dbeafe;
                color: #1e40af;
            }

            .badge-cancelled {
                background: #fee2e2;
                color: #991b1b;
            }

            .badge-urgent {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-emergency {
                background: #fee2e2;
                color: #991b1b;
            }

            .badge-routine {
                background: #f3f4f6;
                color: #4b5563;
            }

            .dark .badge-sent {
                background: #fef3c7;
                color: #92400e;
            }

            .dark .badge-completed {
                background: #d1fae5;
                color: #065f46;
            }

            .dark .badge-created {
                background: #dbeafe;
                color: #1e40af;
            }

            .dark .badge-cancelled {
                background: #fee2e2;
                color: #991b1b;
            }

            .dark .badge-urgent {
                background: #fef3c7;
                color: #92400e;
            }

            .dark .badge-emergency {
                background: #fee2e2;
                color: #991b1b;
            }

            .dark .badge-routine {
                background: #f3f4f6;
                color: #4b5563;
            }

            .timeline {
                margin: 25px 0;
            }

            .timeline-item {
                display: flex;
                margin-bottom: 15px;
            }

            .timeline-icon {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 15px;
                flex-shrink: 0;
            }

            .timeline-content {
                flex: 1;
            }

            .timeline-title {
                font-weight: bold;
                color: #111827;
                margin-bottom: 2px;
            }

            .dark .timeline-title {
                color: #f9fafb;
            }

            .timeline-date {
                font-size: 11px;
                color: #6b7280;
            }

            .dark .timeline-date {
                color: #9ca3af;
            }

            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 1px solid #d1d5db;
                text-align: center;
                font-size: 10px;
                color: #6b7280;
            }

            .dark .footer {
                border-color: #374151;
                color: #9ca3af;
            }

            .watermark {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 72px;
                color: rgba(0, 0, 0, 0.1);
                white-space: nowrap;
                z-index: -1;
            }

            .dark .watermark {
                color: rgba(255, 255, 255, 0.05);
            }

            .stamp {
                position: absolute;
                top: 100px;
                right: 50px;
                transform: rotate(15deg);
                border: 3px solid #dc2626;
                color: #dc2626;
                padding: 15px 25px;
                border-radius: 50%;
                font-weight: bold;
                font-size: 20px;
                background: rgba(255, 255, 255, 0.9);
            }

            .dark .stamp {
                background: rgba(31, 41, 55, 0.9);
            }

            .page-break {
                page-break-before: always;
            }

            .no-break {
                page-break-inside: avoid;
            }

            .letterhead {
                background: linear-gradient(to right, #1e40af, #3b82f6);
                color: white;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 4px;
            }

            .letterhead h1 {
                color: white;
                font-size: 24px;
                margin-bottom: 5px;
            }

            .letterhead p {
                margin: 2px 0;
                font-size: 12px;
                opacity: 0.9;
            }

            .clinic-letterhead {
                background: linear-gradient(to right, #059669, #10b981);
                color: white;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 4px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
                font-size: 11px;
            }

            th {
                background: #f3f4f6;
                border: 1px solid #d1d5db;
                padding: 8px 12px;
                text-align: left;
                font-weight: bold;
                color: #4b5563;
            }

            .dark th {
                background: #1f2937;
                border-color: #374151;
                color: #9ca3af;
            }

            td {
                border: 1px solid #d1d5db;
                padding: 8px 12px;
                color: #111827;
            }

            .dark td {
                border-color: #374151;
                color: #f3f4f6;
            }

            tr:nth-child(even) {
                background: #f9fafb;
            }

            .dark tr:nth-child(even) {
                background: #1f2937;
            }

            /* Print-specific overrides */
            @media print {
                body {
                    background: white !important;
                    color: black !important;
                }

                .referral-border {
                    border: 2px solid #1e40af;
                    background: white;
                }

                .referral-border::before,
                .referral-border::after {
                    background: linear-gradient(90deg, #1e40af, #3b82f6);
                }

                .header {
                    border-color: #1e40af;
                }

                h1,
                h2,
                h3,
                .info-label,
                .info-value,
                .clinical-summary,
                .reason-box,
                th,
                td {
                    color: black !important;
                }

                .clinical-summary,
                .reason-box {
                    background: #f9fafb !important;
                    border-color: #d1d5db !important;
                }

                th {
                    background: #f3f4f6 !important;
                    border-color: #d1d5db !important;
                }

                tr:nth-child(even) {
                    background: #f9fafb !important;
                }

                .footer {
                    border-color: #d1d5db !important;
                    color: #6b7280 !important;
                }

                .watermark {
                    color: rgba(0, 0, 0, 0.1) !important;
                }

                .stamp {
                    background: rgba(255, 255, 255, 0.9) !important;
                }
            }
        </style>
    </head>

    <body>
        @if($letterhead === 'hospital' || $letterhead === 'clinic')
        <div class="{{ $letterhead === 'hospital' ? 'letterhead' : 'clinic-letterhead' }} no-break">
            @if($letterhead === 'hospital')
            <h1>CITY GENERAL HOSPITAL</h1>
            <p>Department of Medical Services</p>
            <p>123 Medical Center Drive, City, State 12345</p>
            <p>Phone: (555) 123-4567 • Fax: (555) 123-4568</p>
            @else
            <h1>MEDICAL SPECIALISTS CLINIC</h1>
            <p>Referral Center</p>
            <p>456 Health Plaza, City, State 12345</p>
            <p>Phone: (555) 987-6543 • Email: referrals@clinic.com</p>
            @endif
        </div>
        @endif

        <div class="referral-border no-break">
            @if($referral->urgency === 'emergency')
            <div class="stamp">EMERGENCY</div>
            @elseif($referral->urgency === 'urgent')
            <div class="stamp" style="border-color: #f59e0b; color: #f59e0b;">URGENT</div>
            @endif

            @if($referral->status === 'completed')
            <div class="watermark">COMPLETED</div>
            @endif

            <!-- Header -->
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                    <div>
                        <h1 style="margin-bottom: 5px;">PATIENT REFERRAL</h1>
                        <div style="margin-bottom: 10px;">
                            <span class="badge badge-{{ $referral->status->value }}">
                                {{ strtoupper($referral->status->value) }}
                            </span>
                            <span class="badge badge-{{ $referral->urgency->value }}">
                                {{ strtoupper($referral->urgency->value) }} PRIORITY
                            </span>
                        </div>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 2px 0;"><strong>Referral ID:</strong> REF-{{ str_pad($referral->id, 6, '0',
                            STR_PAD_LEFT) }}</p>
                        <p style="margin: 2px 0;"><strong>Date:</strong> {{ $referral->referred_at->format('F j, Y') }}
                        </p>
                        <p style="margin: 2px 0;"><strong>Time:</strong> {{ $referral->referred_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Patient Information -->
            <div class="section">
                <h2>PATIENT INFORMATION</h2>
                <div class="patient-info">
                    <div>
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value">{{ $referral->encounter->patient->first_name ?? 'N/A' }} &nbsp; {{
                                $referral->encounter->patient->last_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Patient ID:</span>
                            <span class="info-value">{{ $referral->encounter->patient->card_number ?? 'N/A' }}</span>
                        </div>
                        {{-- <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value">{{
                                optional($referral->encounter->patient->date_of_birth)->format('m/d/Y') ?? 'N/A'
                                }}</span>
                        </div> --}}
                    </div>
                    <div>
                        <div class="info-row">
                            <span class="info-label">Gender:</span>
                            <span class="info-value">{{ $referral->encounter->patient->gender ?? 'N/A' }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Encounter ID:</span>
                            <span class="info-value">ENC-{{ str_pad($referral->encounter_id, 6, '0', STR_PAD_LEFT)
                                }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Age:</span>
                            <span class="info-value">{{
                                \Carbon\Carbon::parse($referral->encounter->patient->date_of_birth ?? now())->age ??
                                'N/A' }} years</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Facility Information -->
            <div class="section">
                <h2>REFERRAL DESTINATION</h2>
                <div class="facility-info">
                    <div>
                        <div class="info-row">
                            <span class="info-label">Facility Name:</span>
                            <span class="info-value">{{ $referral->facility_name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Facility Type:</span>
                            <span class="info-value">{{ ucfirst($referral->facility_type) }}</span>
                        </div>
                    </div>
                    <div>
                        <div class="info-row">
                            <span class="info-label">Location:</span>
                            <span class="info-value">{{ $referral->facility_location }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Urgency Level:</span>
                            <span class="info-value">{{ ucfirst($referral->urgency->value) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clinical Information -->
            <div class="section">
                <h2>CLINICAL INFORMATION</h2>

                <div style="margin-bottom: 15px;">
                    <h3>Reason for Referral</h3>
                    <div class="reason-box">{{ $referral->reason }}</div>
                </div>

                @if($includeClinicalSummary)
                <div>
                    <h3>Clinical Summary</h3>
                    <div class="clinical-summary">{{ $referral->clinical_summary }}</div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="footer">
                <div style="margin-bottom: 10px;">
                    <span style="font-weight: bold; margin: 0 15px;">CONFIDENTIAL - For Medical Use Only</span>
                    <span style="margin: 0 15px;">|</span>
                    <span style="margin: 0 15px;">Referral Copy: Facility • Patient File</span>
                    <span style="margin: 0 15px;">|</span>
                    <span style="margin: 0 15px;">Valid for 30 Days</span>
                </div>
                <div>
                    Generated on {{ now()->format('M d, Y h:i A') }} • Referral ID: REF-{{ str_pad($referral->id, 6,
                    '0', STR_PAD_LEFT) }}
                </div>
                <div style="margin-top: 5px; font-size: 9px;">
                    This is an official medical document. Unauthorized disclosure is prohibited.
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900">
            <!-- Print Controls -->
            <div class="p-6">
                <div class="max-w-6xl mx-auto">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
                                Referral Print Preview
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                Review the document below. Click Print to generate a clean printable version.
                            </p>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="printReferral()"
                                class="px-5 py-2.5 bg-blue-600 dark:bg-blue-500 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                                    </path>
                                </svg>
                                Print Document
                            </button>

                            <a href="{{ route('referrals.queue') }}"
                                class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors duration-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Go Back
                            </a>
                        </div>
                    </div>

                    <!-- Print Preview Warning -->
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-500 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-yellow-800 dark:text-yellow-300 font-medium">
                                    This preview shows how the document will appear when printed.
                                </p>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                                    Click "Print Document" to open print dialog with proper formatting.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Printable Content -->
            <div id="printableContent" style="display: none;">
                <!DOCTYPE html>
                <html>

                <head>
                    <meta charset="utf-8">
                    <title>Referral {{ $referral->id }}</title>
                    <style>
                        @page {
                            margin: 0.5in;
                            size: letter;
                        }

                        body {
                            font-family: 'Helvetica', 'Arial', sans-serif;
                            font-size: 12px;
                            line-height: 1.4;
                            color: #333;
                            margin: 0;
                            padding: 0;
                            background: white !important;
                        }

                        /* Print-specific styles - only apply when printing */
                        @media print {
                            * {
                                -webkit-print-color-adjust: exact !important;
                                print-color-adjust: exact !important;
                            }

                            /* Hide everything except the referral content */
                            body * {
                                visibility: hidden;
                            }

                            .printable-document,
                            .printable-document * {
                                visibility: visible !important;
                            }

                            .printable-document {
                                position: absolute;
                                left: 0;
                                top: 0;
                                width: 100%;
                                background: white !important;
                            }
                        }

                        /* Document styles (will print as-is) */
                        .referral-border {
                            border: 2px solid #1e40af;
                            padding: 30px;
                            position: relative;
                            min-height: 10in;
                            background: white;
                        }

                        .referral-border::before {
                            content: "";
                            position: absolute;
                            top: 0;
                            left: 0;
                            right: 0;
                            height: 4px;
                            background: linear-gradient(90deg, #1e40af, #3b82f6);
                        }

                        .referral-border::after {
                            content: "";
                            position: absolute;
                            bottom: 0;
                            left: 0;
                            right: 0;
                            height: 4px;
                            background: linear-gradient(90deg, #1e40af, #3b82f6);
                        }

                        .header {
                            border-bottom: 2px solid #1e40af;
                            padding-bottom: 20px;
                            margin-bottom: 30px;
                        }

                        h1 {
                            font-size: 28px;
                            font-weight: bold;
                            color: #1e40af;
                            margin: 0 0 10px 0;
                        }

                        h2 {
                            font-size: 20px;
                            font-weight: bold;
                            color: #333;
                            margin: 25px 0 15px 0;
                            border-left: 4px solid #3b82f6;
                            padding-left: 10px;
                        }

                        h3 {
                            font-size: 16px;
                            font-weight: bold;
                            color: #444;
                            margin: 20px 0 10px 0;
                        }

                        .section {
                            margin-bottom: 25px;
                            padding-bottom: 25px;
                            border-bottom: 1px solid #e5e7eb;
                        }

                        .patient-info,
                        .facility-info {
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 20px;
                            margin-bottom: 20px;
                        }

                        .info-row {
                            margin-bottom: 8px;
                        }

                        .info-label {
                            font-weight: bold;
                            color: #4b5563;
                            display: inline-block;
                            width: 140px;
                        }

                        .info-value {
                            color: #111827;
                        }

                        .clinical-summary {
                            background: #f9fafb;
                            border: 1px solid #d1d5db;
                            padding: 15px;
                            border-radius: 4px;
                            white-space: pre-line;
                            margin-top: 10px;
                        }

                        .reason-box {
                            background: #f3f4f6;
                            border: 1px solid #d1d5db;
                            padding: 10px;
                            border-radius: 4px;
                            margin-top: 5px;
                            font-weight: 500;
                        }

                        .badge {
                            display: inline-block;
                            padding: 4px 12px;
                            border-radius: 20px;
                            font-size: 11px;
                            font-weight: bold;
                            margin-right: 8px;
                            margin-bottom: 8px;
                        }

                        .badge-sent {
                            background: #fef3c7;
                            color: #92400e;
                        }

                        .badge-completed {
                            background: #d1fae5;
                            color: #065f46;
                        }

                        .badge-created {
                            background: #dbeafe;
                            color: #1e40af;
                        }

                        .badge-cancelled {
                            background: #fee2e2;
                            color: #991b1b;
                        }

                        .badge-urgent {
                            background: #fef3c7;
                            color: #92400e;
                        }

                        .badge-emergency {
                            background: #fee2e2;
                            color: #991b1b;
                        }

                        .badge-routine {
                            background: #f3f4f6;
                            color: #4b5563;
                        }

                        .timeline {
                            margin: 25px 0;
                        }

                        .timeline-item {
                            display: flex;
                            margin-bottom: 15px;
                        }

                        .timeline-icon {
                            width: 24px;
                            height: 24px;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin-right: 15px;
                            flex-shrink: 0;
                        }

                        .timeline-content {
                            flex: 1;
                        }

                        .timeline-title {
                            font-weight: bold;
                            color: #111827;
                            margin-bottom: 2px;
                        }

                        .timeline-date {
                            font-size: 11px;
                            color: #6b7280;
                        }

                        .footer {
                            margin-top: 40px;
                            padding-top: 20px;
                            border-top: 1px solid #d1d5db;
                            text-align: center;
                            font-size: 10px;
                            color: #6b7280;
                        }

                        .watermark {
                            position: absolute;
                            top: 50%;
                            left: 50%;
                            transform: translate(-50%, -50%) rotate(-45deg);
                            font-size: 72px;
                            color: rgba(0, 0, 0, 0.1);
                            white-space: nowrap;
                            z-index: -1;
                        }

                        .stamp {
                            position: absolute;
                            top: 100px;
                            right: 50px;
                            transform: rotate(15deg);
                            border: 3px solid #dc2626;
                            color: #dc2626;
                            padding: 15px 25px;
                            border-radius: 50%;
                            font-weight: bold;
                            font-size: 20px;
                            background: rgba(255, 255, 255, 0.9);
                        }

                        .page-break {
                            page-break-before: always;
                        }

                        .no-break {
                            page-break-inside: avoid;
                        }

                        .letterhead {
                            background: linear-gradient(to right, #1e40af, #3b82f6);
                            color: white;
                            padding: 20px;
                            margin-bottom: 30px;
                            border-radius: 4px;
                        }

                        .letterhead h1 {
                            color: white;
                            font-size: 24px;
                            margin-bottom: 5px;
                        }

                        .letterhead p {
                            margin: 2px 0;
                            font-size: 12px;
                            opacity: 0.9;
                        }

                        .clinic-letterhead {
                            background: linear-gradient(to right, #059669, #10b981);
                            color: white;
                            padding: 20px;
                            margin-bottom: 30px;
                            border-radius: 4px;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin: 15px 0;
                            font-size: 11px;
                        }

                        th {
                            background: #f3f4f6;
                            border: 1px solid #d1d5db;
                            padding: 8px 12px;
                            text-align: left;
                            font-weight: bold;
                            color: #4b5563;
                        }

                        td {
                            border: 1px solid #d1d5db;
                            padding: 8px 12px;
                            color: #111827;
                        }

                        tr:nth-child(even) {
                            background: #f9fafb;
                        }
                    </style>
                </head>

                <body>
                    <div class="printable-document">
                        @if($letterhead === 'hospital' || $letterhead === 'clinic')
                        <div class="{{ $letterhead === 'hospital' ? 'letterhead' : 'clinic-letterhead' }} no-break">
                            @if($letterhead === 'hospital')
                            <h1>CITY GENERAL HOSPITAL</h1>
                            <p>Department of Medical Services</p>
                            <p>123 Medical Center Drive, City, State 12345</p>
                            <p>Phone: (555) 123-4567 • Fax: (555) 123-4568</p>
                            @else
                            <h1>MEDICAL SPECIALISTS CLINIC</h1>
                            <p>Referral Center</p>
                            <p>456 Health Plaza, City, State 12345</p>
                            <p>Phone: (555) 987-6543 • Email: referrals@clinic.com</p>
                            @endif
                        </div>
                        @endif

                        <div class="referral-border no-break">
                            @if($referral->urgency === 'emergency')
                            <div class="stamp">EMERGENCY</div>
                            @elseif($referral->urgency === 'urgent')
                            <div class="stamp" style="border-color: #f59e0b; color: #f59e0b;">URGENT</div>
                            @endif

                            @if($referral->status === 'completed')
                            <div class="watermark">COMPLETED</div>
                            @endif

                            <!-- Header -->
                            <div class="header">
                                <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                    <div>
                                        <h1 style="margin-bottom: 5px;">PATIENT REFERRAL</h1>
                                        <div style="margin-bottom: 10px;">
                                            <span class="badge badge-{{ $referral->status->value }}">
                                                {{ strtoupper($referral->status->value) }}
                                            </span>
                                            <span class="badge badge-{{ $referral->urgency->value }}">
                                                {{ strtoupper($referral->urgency->value) }} PRIORITY
                                            </span>
                                        </div>
                                    </div>
                                    <div style="text-align: right;">
                                        <p style="margin: 2px 0;"><strong>Referral ID:</strong> REF-{{
                                            str_pad($referral->id, 6, '0', STR_PAD_LEFT) }}</p>
                                        <p style="margin: 2px 0;"><strong>Date:</strong> {{
                                            $referral->referred_at->format('F j, Y') }}</p>
                                        <p style="margin: 2px 0;"><strong>Time:</strong> {{
                                            $referral->referred_at->format('h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Patient Information -->
                            <div class="section">
                                <h2>PATIENT INFORMATION</h2>
                                <div class="patient-info">
                                    <div>
                                        <div class="info-row">
                                            <span class="info-label">Full Name:</span>
                                            <span class="info-value">{{ $referral->encounter->patient->first_name ??
                                                'N/A' }} &nbsp; {{ $referral->encounter->patient->last_name ?? 'N/A'
                                                }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Patient ID:</span>
                                            <span class="info-value">{{ $referral->encounter->patient->card_number ??
                                                'N/A' }}</span>
                                        </div>
                                        {{-- <div class="info-row">
                                            <span class="info-label">Date of Birth:</span>
                                            <span class="info-value">{{
                                                optional($referral->encounter->patient->date_of_birth)->format('m/d/Y')
                                                ?? 'N/A' }}</span>
                                        </div> --}}
                                    </div>
                                    <div>
                                        <div class="info-row">
                                            <span class="info-label">Gender:</span>
                                            <span class="info-value">{{ $referral->encounter->patient->gender ?? 'N/A'
                                                }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Encounter ID:</span>
                                            <span class="info-value">ENC-{{ str_pad($referral->encounter_id, 6, '0',
                                                STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Age:</span>
                                            <span class="info-value">{{
                                                \Carbon\Carbon::parse($referral->encounter->patient->date_of_birth ??
                                                now())->age ?? 'N/A' }} years</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Facility Information -->
                            <div class="section">
                                <h2>REFERRAL DESTINATION</h2>
                                <div class="facility-info">
                                    <div>
                                        <div class="info-row">
                                            <span class="info-label">Facility Name:</span>
                                            <span class="info-value">{{ $referral->facility_name }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Facility Type:</span>
                                            <span class="info-value">{{ ucfirst($referral->facility_type) }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="info-row">
                                            <span class="info-label">Location:</span>
                                            <span class="info-value">{{ $referral->facility_location }}</span>
                                        </div>
                                        <div class="info-row">
                                            <span class="info-label">Urgency Level:</span>
                                            <span class="info-value">{{ ucfirst($referral->urgency->value) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Clinical Information -->
                            <div class="section">
                                <h2>CLINICAL INFORMATION</h2>

                                <div style="margin-bottom: 15px;">
                                    <h3>Reason for Referral</h3>
                                    <div class="reason-box">{{ $referral->reason }}</div>
                                </div>

                                @if($includeClinicalSummary)
                                <div>
                                    <h3>Clinical Summary</h3>
                                    <div class="clinical-summary">{{ $referral->clinical_summary }}</div>
                                </div>
                                @endif
                            </div>

                            <!-- Footer -->
                            <div class="footer">
                                <div style="margin-bottom: 10px;">
                                    <span style="font-weight: bold; margin: 0 15px;">CONFIDENTIAL - For Medical Use
                                        Only</span>
                                    <span style="margin: 0 15px;">|</span>
                                    <span style="margin: 0 15px;">Referral Copy: Facility • Patient File</span>
                                    <span style="margin: 0 15px;">|</span>
                                    <span style="margin: 0 15px;">Valid for 30 Days</span>
                                </div>
                                <div>
                                    Generated on {{ now()->format('M d, Y h:i A') }} • Referral ID: REF-{{
                                    str_pad($referral->id, 6, '0', STR_PAD_LEFT) }}
                                </div>
                                <div style="margin-top: 5px; font-size: 9px;">
                                    This is an official medical document. Unauthorized disclosure is prohibited.
                                </div>
                            </div>
                        </div>
                    </div>
                </body>

                </html>
            </div>

            <script>
                // Generate preview on page load
        document.addEventListener('DOMContentLoaded', function() {
            generatePreview();
        });
        
        function generatePreview() {
            const previewContainer = document.querySelector('.max-w-4xl.mx-auto.p-6');
            const printableContent = document.getElementById('printableContent').innerHTML;
            
            // Extract just the document content (without the hidden wrapper)
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = printableContent;
            const documentContent = tempDiv.querySelector('.printable-document').outerHTML;
            
            // Create preview with theme compatibility
            previewContainer.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow dark:shadow-gray-900/50 overflow-hidden">
                    ${documentContent}
                </div>
                <div class="mt-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    This is a preview. Click "Print Document" for actual printing.
                </div>
            `;
        }
        
        function printReferral() {
            const printableContent = document.getElementById('printableContent').innerHTML;
            
            // Create a new window for printing
            const printWindow = window.open('', '_blank', 'width=800,height=600');
            
            // Write the content
            printWindow.document.write(printableContent);
            printWindow.document.close();
            
            // Wait for content to load, then print
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.onafterprint = function() {
                    printWindow.close();
                };
            };
        }
            </script>
        </div>
    </body>

    </html>
</div>