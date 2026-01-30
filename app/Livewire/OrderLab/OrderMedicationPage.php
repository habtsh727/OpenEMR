<?php

namespace App\Livewire\OrderLab;

use App\Models\Encounter;
use App\Models\LabOrder;
use App\Models\MedicationOrder;
use App\Models\MedicationOrderItem;
use App\Models\PharmacyItem;
use App\Models\PharmacyBatch;
use App\Models\PharmacyFrequency;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class OrderMedicationPage extends Component
{
    public Encounter $encounter;
    public ?LabOrder $labOrder = null;
    public $patient;

    // Medication search
    public $search = '';
    public $searchResults = [];

    // Selected medications
    public $selectedItems = [];

    // Add medication modal
    public $showAddModal = false;
    public $currentItem = null;
    public $currentDosage = '';
    public $currentFrequencyId = null;
    public $currentDuration = '';
    public $currentInstructions = '';
    public $currentQuantity = 1;
    public $currentFromStock = true;
    public $isCustomMedication = false;
    public $customMedicationName = '';

    // Form data
    public $clinicalNotes = '';
    public $prescriptionOnly = false;

    // Available frequencies
    public $frequencies = [];

    // Flash messages
    public $successMessage = '';
    public $errorMessage = '';

    public function mount(Encounter $encounter, LabOrder $labOrder = null)
    {
        $this->encounter = $encounter->load('patient');
        $this->patient = $this->encounter->patient;
        $this->labOrder = $labOrder;

        // Check if encounter has verified lab orders
        // if ($encounter->labOrders()->where('lab_orders.status', 'verified')->count() === 0) {
        //     abort(403, 'Cannot order medication: No verified lab results found for this encounter.');
        // }

        // Load frequencies
        $this->frequencies = PharmacyFrequency::all();
    }
    public function BackToImaging()
    {
        return $this->redirect(route('doctor.imaging.order', $this->encounter), navigate: true);
    }

    public function updatedSearch($value)
    {
        if (strlen($value) >= 2) {
            $this->searchResults = PharmacyItem::with(['category', 'unit', 'pharmacyBatches' => function ($query) {
                $query->where('quantity', '>', 0)
                    ->where('expiry_date', '>', now())
                    ->where('is_active', true);
            }])
                ->where('is_active', true)
                ->where(function ($query) use ($value) {
                    $query->where('name', 'like', "%{$value}%")
                        ->orWhere('generic_name', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%");
                })
                ->limit(10)
                ->get()
                ->map(function ($item) {
                    $item->available_stock = $item->pharmacyBatches->sum('quantity');
                    $item->has_stock = $item->available_stock > 0;
                    return $item;
                });
        } else {
            $this->searchResults = [];
        }
    }

    public function selectMedication($itemId)
    {
        $this->isCustomMedication = false;
        $this->currentItem = PharmacyItem::with(['pharmacyBatches' => function ($query) {
            $query->where('quantity', '>', 0)
                ->where('expiry_date', '>', now())
                ->orderBy('expiry_date');
        }])->find($itemId);

        // Set default values
        $this->currentDosage = '1 ' . ($this->currentItem->unit->short_name ?? '');
        $this->currentQuantity = 1;
        $this->currentFromStock = $this->currentItem->has_stock ?? false;
        $this->customMedicationName = '';

        $this->showAddModal = true;
    }

    public function addCustomMedication()
    {
        $this->isCustomMedication = true;
        $this->currentItem = null;
        $this->currentDosage = '';
        $this->currentQuantity = 1;
        $this->currentFromStock = false;
        $this->customMedicationName = '';

        $this->showAddModal = true;
    }

    public function addToOrder()
{
    if ($this->isCustomMedication) {
        $this->validate([
            'customMedicationName' => 'required|string|max:255',
            'currentDosage' => 'required|string|max:100',
            'currentQuantity' => 'required|integer|min:1',
            'currentDuration' => 'nullable|string|max:50',
        ]);
    } else {
        $this->validate([
            'currentDosage' => 'required|string|max:100',
            'currentQuantity' => 'required|integer|min:1',
            'currentDuration' => 'nullable|string|max:50',
        ]);
    }

    $itemData = [
        'is_custom' => $this->isCustomMedication,
        'name' => $this->isCustomMedication ? $this->customMedicationName : $this->currentItem->name,
        'pharmacy_item_id' => $this->isCustomMedication ? null : $this->currentItem->id,
        'dosage' => $this->currentDosage,
        'frequency_id' => $this->currentFrequencyId,
        'duration' => $this->currentDuration,
        'instructions' => $this->currentInstructions,
        'quantity' => $this->currentQuantity,
        'from_stock' => $this->currentFromStock && !$this->isCustomMedication,
    ];

    // Initialize default values
    $itemData['unit_price'] = 0;
    $itemData['batch_id'] = null;
    $itemData['stock_available'] = 0;
    $itemData['batch_info'] = 'Not from stock';

    if (!$this->isCustomMedication && $this->currentItem) {
        // Default to pharmacy item price if exists
        $itemData['unit_price'] = $this->currentItem->price ?? 0;

        // If from stock and has batches, use batch selling_price
        if ($itemData['from_stock'] && $this->currentItem->pharmacyBatches->isNotEmpty()) {
            $batch = $this->currentItem->pharmacyBatches->first();
            $itemData['batch_id'] = $batch->id;
            $itemData['unit_price'] = $batch->selling_price ?? $this->currentItem->price ?? 0;
            $itemData['stock_available'] = $batch->quantity;
            $itemData['batch_info'] = "Batch: {$batch->batch_number}, Exp: " . 
                ($batch->expiry_date ? $batch->expiry_date->format('M Y') : 'N/A');
        }
    }

    // Calculate subtotal
    $itemData['subtotal'] = $itemData['unit_price'] * $itemData['quantity'];

    // Add item to selected items (ONCE)
    $this->selectedItems[] = $itemData;

    // Reset form
    $this->resetAddModal();
    $this->showAddModal = false;

    $this->successMessage = 'Medication added to order.';
}

    public function removeItem($index)
    {
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);
        $this->successMessage = 'Medication removed from order.';
    }

    public function editItem($index)
    {
        $item = $this->selectedItems[$index];

        $this->isCustomMedication = $item['is_custom'];
        $this->customMedicationName = $item['is_custom'] ? $item['name'] : '';
        $this->currentDosage = $item['dosage'];
        $this->currentFrequencyId = $item['frequency_id'];
        $this->currentDuration = $item['duration'];
        $this->currentInstructions = $item['instructions'];
        $this->currentQuantity = $item['quantity'];
        $this->currentFromStock = $item['from_stock'];

        if (!$item['is_custom']) {
            $this->currentItem = PharmacyItem::find($item['pharmacy_item_id']);
        }

        // Remove the item being edited
        unset($this->selectedItems[$index]);
        $this->selectedItems = array_values($this->selectedItems);

        $this->showAddModal = true;
    }
public function submitOrder()
{
    $this->validate([
        'clinicalNotes' => 'nullable|string|max:1000',
    ]);

    if (empty($this->selectedItems)) {
        $this->errorMessage = 'Please add at least one medication to the order.';
        return;
    }

    $totalAmount = 0;
    foreach ($this->selectedItems as $item) {
        $totalAmount += ($item['unit_price'] * $item['quantity']);
    }

    // Start transaction
    DB::beginTransaction();

    try {
        // 1. Create Medication Order
        $medicationOrder = MedicationOrder::create([
            'encounter_id' => $this->encounter->id,
            'lab_order_id' => $this->labOrder?->id,
            'status' => $this->prescriptionOnly ? 'pending' : 'sent_to_cashier',
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'clinical_notes' => $this->clinicalNotes,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Create Prescription (ALWAYS created - for both stock and custom items)
        $prescription = Prescription::create([
            'encounter_id' => $this->encounter->id,
            'lab_order_id' => $this->labOrder?->id,
            'medication_order_id' => $medicationOrder->id,
            'doctor_id' => Auth::id(),
            'patient_id' => $this->patient->id,
            'clinical_notes' => $this->clinicalNotes,
            'diagnosis' => null,
            'advice' => null,
            'status' => 'active',
            'valid_until' => now()->addDays(30),
            'is_signed' => true,
            'signed_by' => Auth::id(),
            'signed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Add items to both order and prescription
        foreach ($this->selectedItems as $item) {
            // Calculate duration days if provided
            $durationDays = null;
            if ($item['duration']) {
                preg_match('/(\d+)/', $item['duration'], $matches);
                $durationDays = $matches[1] ?? null;
            }

            // Add to medication order items
            $orderItemData = [
                'medication_order_id' => $medicationOrder->id,
                'pharmacy_item_id' => $item['is_custom'] ? null : $item['pharmacy_item_id'],
                'pharmacy_batch_id' => $item['batch_id'] ?? null,
                'dosage' => $item['dosage'],
                'frequency_id' => $item['frequency_id'],
                'duration_days' => $durationDays,
                'instructions' => $item['instructions'],
                'quantity' => $item['quantity'],
                'dispensed_quantity' => 0,
                'unit_price' => $item['unit_price'],
                'subtotal' => $item['unit_price'] * $item['quantity'],
                'from_stock' => $item['from_stock'],
                'is_custom' => $item['is_custom'],
                'custom_name' => $item['is_custom'] ? $item['name'] : null,
                'custom_instructions' => $item['instructions'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $orderItem = MedicationOrderItem::create($orderItemData);

            // Reserve stock if from stock
            if ($item['from_stock'] && isset($item['batch_id'])) {
                $batch = PharmacyBatch::find($item['batch_id']);
                if ($batch) {
                    // Use the reserveStock method if it exists
                    if (method_exists($batch, 'reserveStock')) {
                        $batch->reserveStock($item['quantity']);
                    } else {
                        // Simple reservation
                        $batch->decrement('quantity', $item['quantity']);
                    }
                }
            }

            // Add to prescription items (ALWAYS created for both stock and custom)
            $prescriptionItemData = [
                'prescription_id' => $prescription->id,
                'pharmacy_item_id' => $item['is_custom'] ? null : $item['pharmacy_item_id'],
                'dosage' => $item['dosage'],
                'frequency_id' => $item['frequency_id'],
                'route' => null,
                'duration_days' => $durationDays,
                'quantity' => $item['quantity'],
                'instructions' => $item['instructions'],
                'is_custom' => $item['is_custom'],
                'custom_name' => $item['is_custom'] ? $item['name'] : null,
                'custom_details' => $item['instructions'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            PrescriptionItem::create($prescriptionItemData);
        }

        DB::commit();

        // Reset form
        $this->resetForm();

        // Show success message and redirect
        session()->flash('success', $this->prescriptionOnly
            ? 'Prescription created successfully! The patient can fill it at any pharmacy.'
            : 'Medication order submitted successfully! Sent to cashier for payment processing.');

        return redirect()->route('doctor.lab-results');
    } catch (\Exception $e) {
        DB::rollBack();
        $this->errorMessage = 'Failed to create order: ' . $e->getMessage();
        \Log::error('Medication order failed: ' . $e->getMessage(), [
            'exception' => $e,
            'selectedItems' => $this->selectedItems,
        ]);
    }
}

    public function resetForm()
    {
        $this->selectedItems = [];
        $this->clinicalNotes = '';
        $this->prescriptionOnly = false;
        $this->search = '';
        $this->searchResults = [];
        $this->resetAddModal();
    }

    private function resetAddModal()
    {
        $this->currentItem = null;
        $this->currentDosage = '';
        $this->currentFrequencyId = null;
        $this->currentDuration = '';
        $this->currentInstructions = '';
        $this->currentQuantity = 1;
        $this->currentFromStock = true;
        $this->isCustomMedication = false;
        $this->customMedicationName = '';
        $this->showAddModal = false;
    }

    public function clearMessages()
    {
        $this->successMessage = '';
        $this->errorMessage = '';
    }

    public function render()
    {
        return view('livewire.order-lab.order-medication-page');
    }
}
