<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Prescription #{{ $prescription->id }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; line-height: 1.6; }
        .header { text-align: center; border-bottom: 3px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .hospital-name { font-size: 28px; font-weight: bold; color: #333; }
        .hospital-details { font-size: 12px; color: #666; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 18px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .patient-info, .doctor-info { width: 48%; display: inline-block; vertical-align: top; }
        .info-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .info-table th, .info-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .info-table th { background-color: #f5f5f5; }
        .medications-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .medications-table th, .medications-table td { border: 1px solid #ddd; padding: 10px; }
        .medications-table th { background-color: #f0f0f0; font-weight: bold; }
        .warning-box { background-color: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .notes-box { background-color: #e8f4fd; border: 1px solid #b8daff; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 2px solid #333; }
        .signature-box { float: right; text-align: center; margin-top: 30px; }
        .signature-line { width: 200px; border-top: 1px solid #333; margin: 20px auto; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="hospital-name">MEDICAL HOSPITAL</div>
        <div class="hospital-details">
            123 Hospital Street, Medical City, State 12345<br>
            Phone: (123) 456-7890 | Email: info@medicalhospital.com<br>
            License: MH-12345-2024
        </div>
    </div>

    <!-- Prescription Title -->
    <div style="margin-bottom: 25px;">
        <h1 style="font-size: 24px; font-weight: bold; color: #333; margin-bottom: 5px;">PRESCRIPTION</h1>
        <div style="font-size: 14px; color: #666;">
            Date: {{ $date->format('F d, Y') }} | 
            Prescription No: RX-{{ $prescription->order->id }}-{{ $date->format('Ymd') }}
        </div>
    </div>

    <!-- Patient & Doctor Info -->
    <div class="section">
        <div class="patient-info">
            <div class="section-title">PATIENT INFORMATION</div>
            <table class="info-table">
                <tr>
                    <td style="width: 120px; font-weight: bold;">Name:</td>
                    <td>{{ $patient->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Patient ID:</td>
                    <td>{{ $patient->id ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Age/Gender:</td>
                    <td>{{ $patient->age ?? 'N/A' }} / {{ $patient->gender ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Date of Birth:</td>
                    <td>{{ $patient->date_of_birth ? $patient->date_of_birth->format('M d, Y') : 'N/A' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="doctor-info" style="float: right;">
            <div class="section-title">PRESCRIBING DOCTOR</div>
            <table class="info-table">
                <tr>
                    <td style="width: 140px; font-weight: bold;">Name:</td>
                    <td>Dr. {{ $doctor->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Qualification:</td>
                    <td>MBBS, MD</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Registration No:</td>
                    <td>MED-{{ $doctor->id ?? '0000' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Department:</td>
                    <td>{{ $doctor->department ?? 'General Medicine' }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>

    <!-- Medications -->
    <div class="section">
        <div class="section-title">PRESCRIBED MEDICATIONS</div>
        <table class="medications-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Medication</th>
                    <th style="width: 15%;">Dosage</th>
                    <th style="width: 20%;">Frequency</th>
                    <th style="width: 15%;">Duration</th>
                    <th style="width: 25%;">Instructions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->drug->name ?? $item->customMedication->name ?? 'Unknown' }}</strong>
                        @if($item->drug && $item->drug->generic_name)
                        <br><small>{{ $item->drug->generic_name }}</small>
                        @endif
                    </td>
                    <td>{{ $item->dosage }}</td>
                    <td>{{ $item->frequency->name ?? 'As directed' }}</td>
                    <td>{{ $item->duration }}</td>
                    <td>{{ $item->instructions ?? 'Take as prescribed' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Notes -->
    @if($prescription->notes)
    <div class="notes-box">
        <strong>DOCTOR'S NOTES:</strong><br>
        {{ $prescription->notes }}
    </div>
    @endif

    <!-- Warnings -->
    <div class="warning-box">
        <strong>IMPORTANT WARNINGS:</strong>
        <ul style="margin: 10px 0 0 20px; padding: 0;">
            <li>Take medications exactly as prescribed</li>
            <li>Do not share medications with others</li>
            <li>Complete the full course of treatment</li>
            <li>Report any side effects immediately</li>
            <li>Store medications properly as instructed</li>
            <li>This prescription is valid for 30 days from the date of issue</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div style="float: left; width: 60%;">
            <p><strong>FOR THE PHARMACY:</strong></p>
            <p>Please dispense as written. No substitutions without doctor's approval.</p>
            <p style="margin-top: 20px;"><strong>FOR THE PATIENT:</strong></p>
            <p>Keep this prescription for your records. Follow up as scheduled.</p>
        </div>
        
        <div class="signature-box">
            <div class="signature-line"></div>
            <p>Authorized Signature</p>
            <p>Dr. {{ $doctor->name ?? 'N/A' }}</p>
            <p>Date: {{ $date->format('F d, Y') }}</p>
        </div>
        
        <div style="clear: both;"></div>
    </div>
</body>
</html>