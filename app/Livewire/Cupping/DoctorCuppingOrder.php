<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use App\Models\CuppingPackage;
use App\Models\CuppingTherapy;
use App\Models\CuppingTherapyPackage;
use App\Models\CuppingSession;
use App\Models\Encounter;
use Illuminate\Support\Facades\DB;

class DoctorCuppingOrder extends Component
{
    public $encounterId;
    public $encounter;
    public $patient;

    public $packages = [];
    public $selectedPackageId = null;
    public $selectedPackage = null;

    public $notes = '';
    public $sessions = [];
    public $minDate;
    public $maxDate;

    public $showPackagePreview = false;
    public $packageTreatments = [];
    public $packageMaterials = [];
    public $packageTotalPrice = 0;

    public $cart = [];
    public $cartTotal = 0;
    public $totalSessions = 0;

    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';
    public $showConfirmModal = false;
    public $generatedTherapyId = null;

    protected $rules = [
        'selectedPackageId' => 'required|exists:cupping_packages,id',
        'sessions.*.session_date' => 'required|date|after_or_equal:today',
        'notes' => 'nullable|string|max:1000',
    ];

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->encounterId = $encounter->id;
        $this->patient = $encounter->patient;

        if ($encounter->doctor_id != auth()->id()) {
            abort(403, 'You are not authorized to order for this patient.');
        }

        if ($encounter->status != 'in_progress') {
            abort(400, 'Patient is not in consultation.');
        }

        $this->minDate = now()->format('Y-m-d');
        $this->maxDate = now()->addMonths(3)->format('Y-m-d');

        $this->loadPackages();
    }

    public function loadPackages()
    {
        $this->packages = CuppingPackage::with(['treatments.cuppingType', 'treatments.cuppingLocation', 'materials.pharmacyItem'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    public function selectPackage($packageId)
    {
        $this->selectedPackageId = $packageId;
        $this->selectedPackage = CuppingPackage::with(['treatments.cuppingType', 'treatments.cuppingLocation', 'materials.pharmacyItem'])
            ->find($packageId);

        $this->loadPackagePreview();
        $this->showPackagePreview = true;
        $this->sessions = [];
        $this->addSession();
    }

    public function loadPackagePreview()
    {
        if (!$this->selectedPackage) return;

        $this->packageTreatments = $this->selectedPackage->treatments->map(function($treatment) {
            return [
                'type_name' => $treatment->cuppingType->name ?? 'N/A',
                'location_name' => $treatment->cuppingLocation->name ?? 'N/A',
                'price' => $treatment->treatment_price,
            ];
        });

        $this->packageMaterials = $this->selectedPackage->materials->map(function($material) {
            return [
                'item_name' => $material->pharmacyItem->name ?? 'N/A',
                'quantity' => $material->quantity_required,
                'price' => $material->unit_price_snapshot,
                'total' => $material->total_material_cost,
            ];
        });

        $this->packageTotalPrice = $this->selectedPackage->total_price;
    }

    public function addSession()
    {
        $sessionNumber = count($this->sessions) + 1;
        $defaultDate = now()->addDays($sessionNumber - 1)->format('Y-m-d');

        $this->sessions[] = [
            'id' => uniqid(),
            'session_number' => $sessionNumber,
            'session_date' => $defaultDate,
        ];
    }

    public function removeSession($index)
    {
        unset($this->sessions[$index]);
        $this->sessions = array_values($this->sessions);

        foreach ($this->sessions as $i => $session) {
            $this->sessions[$i]['session_number'] = $i + 1;
        }
    }

    public function addToCart()
    {
        $this->validate();

        if (empty($this->sessions)) {
            $this->showAlertMessage('Please add at least one session date', 'error');
            return;
        }

        $totalAmount = $this->selectedPackage->total_price * count($this->sessions);

        $this->cart[] = [
            'package_id' => $this->selectedPackage->id,
            'package_name' => $this->selectedPackage->name,
            'package_price' => $this->selectedPackage->total_price,
            'sessions' => $this->sessions,
            'session_count' => count($this->sessions),
            'total_amount' => $totalAmount,
            'treatments' => $this->packageTreatments->toArray(),
            'materials' => $this->packageMaterials->toArray(),
        ];

        $this->selectedPackageId = null;
        $this->selectedPackage = null;
        $this->showPackagePreview = false;
        $this->sessions = [];

        $this->calculateCartTotal();
        $this->showAlertMessage('Package added to order!', 'success');
    }

    public function calculateCartTotal()
    {
        $this->cartTotal = array_sum(array_column($this->cart, 'total_amount'));
        $this->totalSessions = array_sum(array_column($this->cart, 'session_count'));
    }

    public function removeFromCart($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
        $this->calculateCartTotal();
        $this->showAlertMessage('Item removed from cart', 'info');
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->cartTotal = 0;
        $this->totalSessions = 0;
        $this->showAlertMessage('Cart cleared', 'info');
    }

    public function submitOrder()
    {
        if (empty($this->cart)) {
            $this->showAlertMessage('Please add at least one package to order', 'error');
            return;
        }

        $this->showConfirmModal = true;
    }

    public function confirmOrder()
    {
        DB::beginTransaction();

        try {
            $totalAmount = $this->cartTotal;

            $packageIds = array_unique(array_column($this->cart, 'package_id'));
            $primaryPackageId = count($packageIds) === 1 ? $packageIds[0] : null;

            $therapy = CuppingTherapy::create([
                'encounter_id' => $this->encounterId,
                'doctor_id' => auth()->id(),
                'notes' => $this->notes,
                'total_amount' => $totalAmount,
                'discount' => 0,
                'final_amount' => $totalAmount,
                'total_sessions' => $this->totalSessions,
                'status' => 'ordered',
                'primary_package_id' => $primaryPackageId,
            ]);

            $globalSessionCounter = 1;

            foreach ($this->cart as $cartItem) {
                $originalPackage = CuppingPackage::with(['treatments.cuppingType', 'treatments.cuppingLocation', 'materials.pharmacyItem'])
                    ->find($cartItem['package_id']);

                if (!$originalPackage) {
                    throw new \Exception("Package not found: {$cartItem['package_id']}");
                }

                $treatmentSnapshot = [];
                foreach ($originalPackage->treatments as $treatment) {
                    $treatmentSnapshot[] = [
                        'type_name' => $treatment->cuppingType->name ?? 'Unknown',
                        'location_name' => $treatment->cuppingLocation->name ?? 'Unknown',
                        'price' => $treatment->treatment_price,
                    ];
                }

                $materialsSnapshot = [];
                foreach ($originalPackage->materials as $material) {
                    $materialsSnapshot[] = [
                        'item_name' => $material->pharmacyItem->name ?? 'Unknown',
                        'quantity' => $material->quantity_required,
                        'pharmacy_item_id' => $material->pharmacy_item_id,
                        'price' => $material->unit_price_snapshot,
                        'total' => $material->total_material_cost,
                    ];
                }

                foreach ($cartItem['sessions'] as $sessionData) {
                    $therapyPackage = CuppingTherapyPackage::create([
                        'cupping_therapy_id' => $therapy->id,
                        'cupping_package_id' => $cartItem['package_id'],
                        'session_number' => $globalSessionCounter,
                        'package_name_snapshot' => $cartItem['package_name'],
                        'package_price_snapshot' => $cartItem['package_price'],
                        'treatment_snapshot' => json_encode($treatmentSnapshot),
                        'materials_snapshot' => json_encode($materialsSnapshot),
                        'status' => 'pending',
                    ]);

                    $session = CuppingSession::create([
                        'cupping_therapy_id' => $therapy->id,
                        'cupping_therapy_package_id' => $therapyPackage->id,
                        'session_number' => $globalSessionCounter,
                        'session_date' => $sessionData['session_date'],
                        'session_amount' => $cartItem['package_price'],
                        'paid_amount' => 0,
                        'payment_status' => 'unpaid',
                        'treatment_status' => 'pending',
                        'notes' => "Session #{$globalSessionCounter} - {$cartItem['package_name']} - Scheduled for {$sessionData['session_date']}",
                    ]);

                    // Permanent fix - ensure the ID is saved
                    if (!$session->cupping_therapy_package_id) {
                        $session->cupping_therapy_package_id = $therapyPackage->id;
                        $session->save();
                    }

                    $globalSessionCounter++;
                }
            }

            DB::commit();

            $this->generatedTherapyId = $therapy->id;
            $this->showConfirmModal = false;

            $this->showAlertMessage(
                "✅ Cupping therapy ordered successfully! Total: ETB " . number_format($totalAmount, 2) . " for {$this->totalSessions} session(s).",
                'success'
            );

            $this->reset(['cart', 'cartTotal', 'notes', 'selectedPackageId', 'showPackagePreview', 'totalSessions']);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Error creating order: ' . $e->getMessage(), 'error');
            $this->showConfirmModal = false;
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
        return view('livewire.cupping.doctor-cupping-order', [
            'packages' => $this->packages,
            'patient' => $this->patient,
            'encounter' => $this->encounter,
            'minDate' => $this->minDate,
            'maxDate' => $this->maxDate,
        ]);
    }
}
