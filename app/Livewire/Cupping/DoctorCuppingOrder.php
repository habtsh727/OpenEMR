<?php

namespace App\Livewire\Cupping;

use Livewire\Component;
use App\Models\CuppingPackage;
use App\Models\CuppingTherapy;
use App\Models\Encounter;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DoctorCuppingOrder extends Component
{
    public $encounterId;
    public $encounter;
    public $patient;

    // Package selection
    public $packages = [];
    public $selectedPackageId = null;
    public $selectedPackage = null;

    // Order details
    public $notes = '';

    // Session schedule
    public $sessions = [];
    public $minDate;
    public $maxDate;

    // Package preview
    public $showPackagePreview = false;
    public $packageTreatments = [];
    public $packageMaterials = [];
    public $packageTotalPrice = 0;

    // Cart
    public $cart = [];
    public $cartTotal = 0;
    public $totalSessions = 0;

    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    // Confirmation modal
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

        // Security check - only assigned doctor can order
        if ($encounter->doctor_id != auth()->id()) {
            abort(403, 'You are not authorized to order for this patient.');
        }

        if ($encounter->status != 'in_progress') {
            abort(400, 'Patient is not in consultation.');
        }

        // Set min/max dates
        $this->minDate = Carbon::today()->format('Y-m-d');
        $this->maxDate = Carbon::today()->addMonths(3)->format('Y-m-d');

        // Load active packages
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

        // Initialize sessions with 1 session by default
        $this->sessions = [];
        $this->addSession();
    }

    public function loadPackagePreview()
    {
        if (!$this->selectedPackage) return;

        $this->packageTreatments = $this->selectedPackage->treatments->map(function ($treatment) {
            return [
                'type_name' => $treatment->cuppingType->name ?? 'N/A',
                'location_name' => $treatment->cuppingLocation->name ?? 'N/A',
                'price' => $treatment->treatment_price,
            ];
        });

        $this->packageMaterials = $this->selectedPackage->materials->map(function ($material) {
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
        $defaultDate = Carbon::today()->addDays($sessionNumber - 1)->format('Y-m-d');

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

        // Renumber sessions
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

        // Calculate total amount
        $totalAmount = $this->selectedPackage->total_price * count($this->sessions);

        // Add to cart with session dates
        $this->cart[] = [
            'package_id' => $this->selectedPackage->id,
            'package_name' => $this->selectedPackage->name,
            'package_price' => $this->selectedPackage->total_price,
            'sessions' => $this->sessions,
            'session_count' => count($this->sessions),
            'total_amount' => $totalAmount,
            'treatments' => $this->packageTreatments,
            'materials' => $this->packageMaterials,
        ];

        // Reset selection
        $this->selectedPackageId = null;
        $this->selectedPackage = null;
        $this->showPackagePreview = false;
        $this->sessions = [];

        $this->calculateCartTotal();
        $this->showAlertMessage('Package added to order with ' . count($this->sessions) . ' session(s)!', 'success');
    }

    public function calculateCartTotal()
    {
        $this->cartTotal = array_sum(array_column($this->cart, 'total_amount'));
        $this->totalSessions = array_sum(array_map(function ($item) {
            return count($item['sessions']);
        }, $this->cart));
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
            // Calculate total amount
            $totalAmount = $this->cartTotal;

            // Create cupping therapy
            $therapy = CuppingTherapy::create([
                'encounter_id' => $this->encounterId,
                'doctor_id' => auth()->id(),
                'notes' => $this->notes,
                'total_amount' => $totalAmount,
                'discount' => 0,
                'final_amount' => $totalAmount,
                'total_sessions' => $this->totalSessions,
                'status' => 'ordered',
                'primary_package_id' => $this->cart[0]['package_id'] ?? null,
            ]);

            $globalSessionCounter = 1;

            // Create therapy packages and sessions
            foreach ($this->cart as $cartItem) {
                foreach ($cartItem['sessions'] as $sessionData) {
                    // Create therapy package record
                    $therapyPackage = \App\Models\CuppingTherapyPackage::create([
                        'cupping_therapy_id' => $therapy->id,
                        'cupping_package_id' => $cartItem['package_id'],
                        'session_number' => $globalSessionCounter,
                        'package_name_snapshot' => $cartItem['package_name'],
                        'package_price_snapshot' => $cartItem['package_price'],
                        'treatment_snapshot' => json_encode($cartItem['treatments']),
                        'materials_snapshot' => json_encode($cartItem['materials']),
                        'status' => 'pending',
                    ]);

                    // Create session record with doctor-specified date
                    \App\Models\CuppingSession::create([
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

                    $globalSessionCounter++;
                }
            }

            DB::commit();

            $this->generatedTherapyId = $therapy->id;
            $this->showConfirmModal = false;

            $this->showAlertMessage(
                "✅ Cupping therapy ordered successfully! Total: ETB " . number_format($totalAmount, 2) . " for {$this->totalSessions} session(s). Patient has been sent to cashier queue.",
                'success'
            );

            // Reset form
            $this->reset(['cart', 'cartTotal', 'notes', 'selectedPackageId', 'showPackagePreview', 'totalSessions']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to create cupping order: ' . $e->getMessage());
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
