<?php
// app/Livewire/Rehab/DoctorRehabOrder.php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabPackage;
use App\Models\RehabOrder as RehabOrderModel;
use App\Models\RehabOrderPackage;
use App\Models\RehabOrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DoctorRehabOrder extends Component
{
    public $rehabEncounter;
    public $availablePackages;
    public $draftOrder;
    public $selectedPackageIds = [];
    public $totalAmount = 0;

    public function mount($rehabEncounter)
    {
        $this->rehabEncounter = RehabEncounter::with([
            'encounter.patient',
            'encounter.doctor'
        ])->findOrFail($rehabEncounter);

        // Security check
        if ($this->rehabEncounter->encounter->doctor_id !== auth()->id()) {
            abort(403, 'This rehabilitation case is not assigned to you.');
        }

        // Only allow ordering if status is doctor_review
        if ($this->rehabEncounter->status !== 'doctor_review') {
            abort(403, 'Questionnaire must be reviewed before ordering packages.');
        }

        $this->availablePackages = RehabPackage::with('items')
            ->where('is_active', true)
            ->get();

        $this->draftOrder = RehabOrderModel::with(['packages.items'])
            ->where('rehab_encounter_id', $this->rehabEncounter->id)
            ->where('status', 'draft')
            ->first();

        if (!$this->draftOrder) {
            $this->draftOrder = RehabOrderModel::create([
                'rehab_encounter_id' => $this->rehabEncounter->id,
                'doctor_id' => auth()->id(),
                'total_amount' => 0,
                'status' => 'draft'
            ]);
        }

        $this->selectedPackageIds = $this->draftOrder->packages->pluck('rehab_package_id')->toArray();
        $this->calculateTotal();
    }

    public function addPackage($packageId)
    {
        if (in_array($packageId, $this->selectedPackageIds)) {
            return;
        }

        DB::transaction(function () use ($packageId) {
            $package = RehabPackage::with('items')->findOrFail($packageId);

            $orderPackage = RehabOrderPackage::create([
                'rehab_order_id' => $this->draftOrder->id,
                'rehab_package_id' => $package->id,
                'package_name' => $package->name,
                'base_price' => $package->base_price,
                'discount_value' => $package->discount_value,
                'discount_type' => $package->discount_type,
                'final_price' => $package->final_price,
                'notes' => null
            ]);

            foreach ($package->items as $item) {
                RehabOrderItem::create([
                    'rehab_order_package_id' => $orderPackage->id,
                    'item_type' => $item->item_type,
                    'item_name' => $item->item_name,
                    'dosage' => $item->dosage,
                    'frequency' => $item->frequency,
                    'duration' => $item->duration,
                    'quantity' => $item->quantity,
                    'bed_duration_days' => $item->bed_duration_days,
                    'unit_price' => 0,
                    'total_price' => 0,
                    'notes' => $item->notes
                ]);
            }

            $this->selectedPackageIds[] = $packageId;
        });

        $this->draftOrder->refresh();
        $this->calculateTotal();
    }

    public function removePackage($orderPackageId)
    {
        DB::transaction(function () use ($orderPackageId) {
            $orderPackage = RehabOrderPackage::findOrFail($orderPackageId);

            if (($key = array_search($orderPackage->rehab_package_id, $this->selectedPackageIds)) !== false) {
                unset($this->selectedPackageIds[$key]);
            }

            $orderPackage->delete();
        });

        $this->draftOrder->refresh();
        $this->selectedPackageIds = array_values($this->selectedPackageIds);
        $this->calculateTotal();
    }

    private function calculateTotal()
    {
        if ($this->draftOrder) {
            $this->totalAmount = $this->draftOrder->packages->sum('final_price');

            $this->draftOrder->update([
                'total_amount' => $this->totalAmount
            ]);
        }
    }

    public function sendToCashier()
    {
        if ($this->draftOrder->packages->isEmpty()) {
            return;
        }

        DB::transaction(function () {
            $this->draftOrder->update([
                'status' => 'sent_to_cashier'
            ]);

            $this->rehabEncounter->update([
                'status' => 'pending_payment'
            ]);
        });

        return redirect()->route('doctor.dashboard');
    }

    public function backToReview()
    {
        return redirect()->route('doctor.rehab.review', $this->rehabEncounter->id);
    }

    public function render()
    {
        return view('livewire.rehab.doctor-rehab-order');
    }
}
