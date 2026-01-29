<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\ImagingOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ImagingResult;
use Illuminate\Support\Facades\Log;

class ViewImagingResults extends Component
{
    public Encounter $encounter;
    public $orders = [];
    public $selectedResult = null;
    public $showModal = false;
    public $search = '';
    public $selectedType = null;
    public $dateRange = 'all';

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $query = $this->encounter->imagingOrders()
            ->where('status', 'completed')
            ->with(['imagingType', 'bodyPart', 'imagingResult.radiologist']);

        // Apply search filter
        if ($this->search) {
            $query->whereHas('imagingType', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })->orWhereHas('bodyPart', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            });
        }

        // Apply type filter
        if ($this->selectedType) {
            $query->where('imaging_type_id', $this->selectedType);
        }

        // Apply date filter
        if ($this->dateRange !== 'all') {
            $days = match ($this->dateRange) {
                'today' => 1,
                'week' => 7,
                'month' => 30,
                'year' => 365,
                default => 0,
            };

            if ($days > 0) {
                $query->whereHas('imagingResult', function ($q) use ($days) {
                    $q->where('reported_at', '>=', now()->subDays($days));
                });
            }
        }

        $this->orders = $query->latest()->get();
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'selectedType', 'dateRange'])) {
            $this->loadOrders();
        }
    }

    public function viewResult($orderId)
    {
        $order = ImagingOrder::with(['imagingResult.radiologist', 'imagingType', 'bodyPart'])->find($orderId);
        if ($order && $order->imagingResult) {
            $this->selectedResult = $order->imagingResult;
            $this->selectedResult->order_details = $order;
            $this->showModal = true;
        }
    }

    public function getImagingTypesProperty()
    {
        return $this->encounter->imagingOrders()
            ->where('status', 'completed')
            ->with('imagingType')
            ->get()
            ->pluck('imagingType')
            ->unique('id')
            ->sortBy('name');
    }

    public function getSummaryStatsProperty()
    {
        return [
            'total' => $this->orders->count(),
            'today' => $this->orders->filter(
                fn($order) =>
                $order->imagingResult && $order->imagingResult->reported_at->isToday()
            )->count(),
            'last_week' => $this->orders->filter(
                fn($order) =>
                $order->imagingResult && $order->imagingResult->reported_at->gte(now()->subWeek())
            )->count(),
        ];
    }

    public function downloadReport($resultId)
    {
        // This would trigger a download in a real application
        $this->dispatch(
            'notify',
            type: 'info',
            message: 'Report download will be available soon.'
        );
    }

    // public function printReport($resultId)
    // {
    //     $this->dispatch('print-report', resultId: $resultId);
    // }
 public function printReport($resultId)
{
    try {
        // Load result with necessary relationships
        $result = ImagingResult::with([
            'radiologist',
            'imagingOrder.encounter.patient',
            'imagingOrder.imagingType',
            'imagingOrder.bodyPart',
        ])->findOrFail($resultId);
        
        // Get encounter from the result's imaging order
        $encounter = $result->imagingOrder->encounter;
        
        if (!$encounter) {
            throw new \Exception('Encounter not found for this imaging order.');
        }
        
        // Get patient
        $patient = $encounter->patient;
        
        // Build full patient name
        $patientName = trim($patient->first_name . ' ' . 
                           ($patient->middle_name ? $patient->middle_name . ' ' : '') . 
                           $patient->last_name);
        
        // Prepare data for PDF
        $data = [
            'result' => $result,
            'currentDate' => now()->format('F j, Y'),
            'reportDate' => $result->reported_at?->format('F j, Y') ?? 'N/A',
            'patient' => $patient,
            'patientName' => $patientName,
            'encounter' => $encounter,
            'imagingType' => $result->imagingOrder->imagingType,
            'bodyPart' => $result->imagingOrder->bodyPart,
            'imagingOrder' => $result->imagingOrder,
            'radiologist' => $result->radiologist,
            'orderDate' => $result->imagingOrder->created_at->format('F j, Y') ?? 'N/A',
            'nowTime' => now()->format('h:i A'),
            // Pass images directly to handle array/JSON
            'images' => $result->images,
        ];
        
        // Generate filename using encounter_id
        $filename = 'imaging-report-' . 
                   ($encounter->encounter_id ?? $encounter->id ?? 'unknown') . '-' . 
                   now()->format('Y-m-d') . '.pdf';
        
        // Generate PDF
        $pdf = Pdf::loadView('pdf.imaging-report', $data);
        $pdf->setPaper('A4', 'portrait');
        
        return response()->streamDownload(
            function () use ($pdf) {
                echo $pdf->output();
            },
            $filename,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"'
            ]
        );
        
    } catch (\Exception $e) {
        \Log::error('PDF Generation Failed', [
            'result_id' => $resultId,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        $this->dispatch('notify', [
            'type' => 'error',
            'message' => 'Failed to generate report: ' . $e->getMessage()
        ]);
        
        return null;
    }
}


    /**
     * Generate filename using encounter_id
     */
    private function generateFilename($result, $encounter): string
    {
        $baseName = 'imaging-report';

        // Get encounter_id
        $encounterId = $encounter->encounter_id ?? $encounter->id ?? 'unknown';

        // Get patient info
        $patientIdentifier = 'unknown';
        if ($encounter->patient) {
            $patient = $encounter->patient;
            $patientIdentifier = $patient->patient_id ??
                $patient->medical_record_number ??
                $patient->id ??
                'unknown';
        }

        // Get imaging type for filename
        $imagingType = 'scan';
        if ($result->imagingOrder && $result->imagingOrder->imagingType) {
            $imagingType = strtolower(str_replace(' ', '-', $result->imagingOrder->imagingType->name));
        }

        // Generate filename
        return sprintf(
            '%s-%s-%s-%s-%s.pdf',
            $baseName,
            $encounterId,
            substr($patientIdentifier, 0, 10),
            $imagingType,
            now()->format('Y-m-d')
        );
    }
    public function debugPrintReport($resultId)
{
    $result = ImagingResult::with([
        'imagingOrder.encounter.patient',
        'imagingOrder.imagingType',
        'imagingOrder.bodyPart',
        'radiologist'
    ])->find($resultId);
    
    if (!$result) {
        dd("Result not found: $resultId");
    }
    
    dd([
        'result_id' => $result->id,
        'imaging_order_exists' => $result->imagingOrder ? 'Yes' : 'No',
        'imaging_order_id' => $result->imaging_order_id,
        'encounter_exists' => $result->imagingOrder && $result->imagingOrder->encounter ? 'Yes' : 'No',
        'encounter_id' => $result->imagingOrder && $result->imagingOrder->encounter ? $result->imagingOrder->encounter->id : null,
        'patient_exists' => $result->imagingOrder && $result->imagingOrder->encounter && $result->imagingOrder->encounter->patient ? 'Yes' : 'No',
        'patient_name' => $result->imagingOrder && $result->imagingOrder->encounter && $result->imagingOrder->encounter->patient ? $result->imagingOrder->encounter->patient->name : null,
        'imaging_type' => $result->imagingOrder && $result->imagingOrder->imagingType ? $result->imagingOrder->imagingType->name : null,
        'body_part' => $result->imagingOrder && $result->imagingOrder->bodyPart ? $result->imagingOrder->bodyPart->name : null,
        'radiologist' => $result->radiologist ? $result->radiologist->name : null,
    ]);
}
    public function BackToImaging()
    {
        return $this->redirect(route('doctor.imaging.order', $this->encounter), navigate: true);
    }
    public function render()
    {
        return view('livewire.doctor.view-imaging-results', [
            'imagingTypes' => $this->imagingTypes,
            'summaryStats' => $this->summaryStats,
            'patient' => $this->encounter->patient,
        ]);
    }
}
