<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function download(Prescription $prescription)
    {
        // Load prescription with relationships
        $prescription->load([
            'order.items.drug',
            'order.items.customMedication',
            'order.items.frequency',
            'order.encounter.patient',
            'order.encounter.doctor'
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('prescriptions.pdf', [
            'prescription' => $prescription,
            'items' => $prescription->order->items,
            'patient' => $prescription->order->encounter->patient,
            'doctor' => $prescription->order->encounter->doctor,
            'date' => $prescription->printed_at ?? now()
        ]);

        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        
        // Download with filename
        return $pdf->download("prescription-{$prescription->id}.pdf");
    }

    public function print(Prescription $prescription)
    {
        // Similar to download but for printing
        $prescription->load([
            'order.items.drug',
            'order.items.customMedication',
            'order.items.frequency',
            'order.encounter.patient',
            'order.encounter.doctor'
        ]);

        $pdf = Pdf::loadView('prescriptions.pdf', [
            'prescription' => $prescription,
            'items' => $prescription->order->items,
            'patient' => $prescription->order->encounter->patient,
            'doctor' => $prescription->order->encounter->doctor,
            'date' => $prescription->printed_at ?? now()
        ]);

        $pdf->setPaper('A4', 'portrait');
        
        return $pdf->stream("prescription-{$prescription->id}.pdf");
    }
}