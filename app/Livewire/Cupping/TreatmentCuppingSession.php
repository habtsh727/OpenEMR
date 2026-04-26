<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use App\Models\CuppingSession;
use App\Models\PharmacyBatch;
use App\Models\PharmacyStockTransaction;
use App\Models\CuppingReport;
use Illuminate\Support\Facades\DB;

class TreatmentCuppingSession extends Component
{
    public $session;
    public $therapy;
    public $patient;
    public $therapyPackage;

    public $materials = [];
    public $allMaterialsCollected = false;
    public $materialsCollectedCount = 0;
    public $totalMaterialsCount = 0;

    public $treatmentNotes = '';
    public $observations = '';
    public $recommendations = '';

    public $showCompleteModal = false;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    public function mount($session)
    {
        $this->session = CuppingSession::with([
            'cuppingTherapy.encounter.patient',
            'therapyPackage'
        ])->findOrFail($session);

        $this->therapy = $this->session->cuppingTherapy;
        $this->patient = $this->therapy->encounter->patient;
        $this->therapyPackage = $this->session->therapyPackage;

        $this->loadMaterials();

        if ($this->session->materials_consumed) {
            $consumed = json_decode($this->session->materials_consumed, true);
            if (!empty($consumed)) {
                $this->markExistingMaterialsAsCollected($consumed);
            }
        }

        $this->loadExistingReport();
    }

    public function loadMaterials()
    {
        if (!$this->therapyPackage) {
            $this->materials = [];
            $this->totalMaterialsCount = 0;
            $this->allMaterialsCollected = true;
            return;
        }

        $materialsSnapshot = json_decode($this->therapyPackage->materials_snapshot ?? '[]', true);
        $consumedMaterials = json_decode($this->session->materials_consumed ?? '[]', true);
        $consumedMap = [];

        foreach ($consumedMaterials as $consumed) {
            $consumedMap[$consumed['item_name']] = $consumed;
        }

        foreach ($materialsSnapshot as $index => $material) {
            $pharmacyItemId = $material['pharmacy_item_id'] ?? null;

            $availableStock = 0;
            if ($pharmacyItemId) {
                $availableStock = PharmacyBatch::where('medicine_id', $pharmacyItemId)
                    ->where('quantity', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->sum('quantity');
            }

            $isCollected = isset($consumedMap[$material['item_name']]);

            $this->materials[] = [
                'id' => $index,
                'item_name' => $material['item_name'],
                'quantity' => $material['quantity'],
                'pharmacy_item_id' => $pharmacyItemId,
                'available_stock' => $availableStock,
                'is_collected' => $isCollected,
                'can_collect' => $availableStock >= $material['quantity'],
                'collected_quantity' => $isCollected ? ($consumedMap[$material['item_name']]['collected_quantity'] ?? $material['quantity']) : 0,
            ];
        }

        $this->totalMaterialsCount = count($this->materials);
        $this->updateMaterialsCollectedCount();
    }

    public function loadExistingReport()
    {
        $report = CuppingReport::where('cupping_session_id', $this->session->id)->first();
        if ($report) {
            $this->treatmentNotes = $report->report_text ?? '';
            $this->observations = $report->observations ?? '';
            $this->recommendations = $report->recommendations ?? '';
        }
    }

    public function updateMaterialsCollectedCount()
    {
        $this->materialsCollectedCount = 0;
        foreach ($this->materials as $material) {
            if ($material['is_collected']) {
                $this->materialsCollectedCount++;
            }
        }
        $this->allMaterialsCollected = $this->materialsCollectedCount >= $this->totalMaterialsCount;
    }

    public function toggleMaterialCollected($index)
    {
        $material = $this->materials[$index];

        if (!$material['can_collect'] && !$material['is_collected']) {
            $this->showAlertMessage(
                "Insufficient stock for {$material['item_name']}. Available: {$material['available_stock']}, Required: {$material['quantity']}",
                'error'
            );
            return;
        }

        DB::beginTransaction();

        try {
            if (!$material['is_collected']) {
                $this->deductMaterialStock($material);
                $this->materials[$index]['is_collected'] = true;
                $this->materials[$index]['collected_quantity'] = $material['quantity'];
                $this->showAlertMessage("{$material['item_name']} collected and stock deducted", 'success');
            } else {
                $this->showAlertMessage("Cannot undo material collection", 'warning');
                DB::rollBack();
                return;
            }

            $this->updateSessionMaterialsConsumed();

            DB::commit();
            $this->updateMaterialsCollectedCount();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error processing material: ' . $e->getMessage(), 'error');
        }
    }

    private function deductMaterialStock($material)
    {
        $required = $material['quantity'];
        $pharmacyItemId = $material['pharmacy_item_id'];

        if (!$pharmacyItemId) {
            throw new \Exception("Pharmacy item not found for {$material['item_name']}");
        }

        $batches = PharmacyBatch::where('medicine_id', $pharmacyItemId)
            ->where('quantity', '>', 0)
            ->where('expiry_date', '>', now())
            ->orderBy('expiry_date')
            ->get();

        if ($batches->sum('quantity') < $required) {
            throw new \Exception("Insufficient stock for {$material['item_name']}");
        }

        $remainingToDeduct = $required;

        foreach ($batches as $batch) {
            if ($remainingToDeduct <= 0) break;

            $deductQuantity = min($remainingToDeduct, $batch->quantity);
            $batch->decrement('quantity', $deductQuantity);
            $remainingToDeduct -= $deductQuantity;

            PharmacyStockTransaction::create([
                'pharmacy_item_id' => $pharmacyItemId,
                'batch_id' => $batch->id,
                'transaction_type' => 'dispense',
                'quantity' => -$deductQuantity,
                'unit_price' => $batch->selling_price,
                'total_price' => -($deductQuantity * $batch->selling_price),
                'reference_type' => 'CuppingSession',
                'reference_id' => $this->session->id,
                'notes' => "Cupping session #{$this->session->session_number} - {$material['item_name']} consumed",
                'created_by' => auth()->id(),
            ]);
        }
    }

    private function updateSessionMaterialsConsumed()
    {
        $consumedMaterials = [];
        foreach ($this->materials as $material) {
            if ($material['is_collected']) {
                $consumedMaterials[] = [
                    'item_name' => $material['item_name'],
                    'quantity' => $material['quantity'],
                    'collected_quantity' => $material['collected_quantity'],
                    'collected_at' => now()->toDateTimeString(),
                ];
            }
        }

        $this->session->update([
            'materials_consumed' => json_encode($consumedMaterials),
        ]);
    }

    private function markExistingMaterialsAsCollected($consumedMaterials)
    {
        $consumedMap = [];
        foreach ($consumedMaterials as $consumed) {
            $consumedMap[$consumed['item_name']] = $consumed;
        }

        foreach ($this->materials as $index => $material) {
            if (isset($consumedMap[$material['item_name']])) {
                $this->materials[$index]['is_collected'] = true;
                $this->materials[$index]['collected_quantity'] = $consumedMap[$material['item_name']]['collected_quantity'] ?? $material['quantity'];
            }
        }

        $this->updateMaterialsCollectedCount();
    }

    public function startTreatment()
    {
        if (!$this->allMaterialsCollected && $this->totalMaterialsCount > 0) {
            $this->showAlertMessage('Please collect all materials before starting treatment', 'error');
            return;
        }

        if (!in_array($this->session->treatment_status, ['pending', 'in_queue'])) {
            $this->showAlertMessage('Treatment already started or completed', 'error');
            return;
        }

        try {
            $this->session->update([
                'treatment_status' => 'in_progress',
                'treatment_started_at' => now(),
            ]);

            if (in_array($this->therapy->status, ['ordered', 'partial_paid'])) {
                $this->therapy->update(['status' => 'in_progress']);
            }

            $this->showAlertMessage('Treatment started successfully!', 'success');

        } catch (\Exception $e) {
            $this->showAlertMessage('Error starting treatment: ' . $e->getMessage(), 'error');
        }
    }

    public function completeTreatment()
    {
        if ($this->session->treatment_status !== 'in_progress') {
            $this->showAlertMessage('Treatment is not in progress', 'error');
            return;
        }

        $this->showCompleteModal = true;
    }

    public function confirmComplete()
    {
        $this->validate([
            'treatmentNotes' => 'nullable|string|max:2000',
            'observations' => 'nullable|string|max:1000',
            'recommendations' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            $this->session->update([
                'treatment_status' => 'completed',
                'treatment_completed_at' => now(),
                'notes' => $this->treatmentNotes ?: $this->session->notes,
            ]);

            CuppingReport::updateOrCreate(
                ['cupping_session_id' => $this->session->id],
                [
                    'report_text' => $this->treatmentNotes,
                    'observations' => $this->observations,
                    'recommendations' => $this->recommendations,
                    'created_by' => auth()->id(),
                ]
            );

            $allSessionsCompleted = $this->therapy->sessions->every(function ($session) {
                return $session->treatment_status === 'completed';
            });

            if ($allSessionsCompleted) {
                $this->therapy->update(['status' => 'completed']);
            }

            DB::commit();

            $this->showCompleteModal = false;
            $this->showAlertMessage('Treatment completed successfully!', 'success');

            $this->dispatch('redirect-to-queue');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error completing treatment: ' . $e->getMessage(), 'error');
            $this->showCompleteModal = false;
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.cupping.treatment-cupping-session', [
            'session' => $this->session,
            'therapy' => $this->therapy,
            'patient' => $this->patient,
            'therapyPackage' => $this->therapyPackage,
            'treatments' => json_decode($this->therapyPackage->treatment_snapshot ?? '[]', true),
        ]);
    }
}
