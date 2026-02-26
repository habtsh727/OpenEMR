<?php

namespace App\Livewire\Rehab;

use App\Models\RehabEncounter;
use App\Models\RehabPackage;
use App\Models\RehabOrder as RehabOrderModel;
use App\Models\RehabOrderPackage;
use App\Models\RehabOrderItem;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            $this->dispatch('notify', [
                'message' => 'Package already added',
                'type' => 'warning'
            ]);
            return;
        }

        try {
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
            
            $this->dispatch('notify', [
                'message' => 'Package added successfully',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to add package: ' . $e->getMessage());
            $this->dispatch('notify', [
                'message' => 'Failed to add package',
                'type' => 'error'
            ]);
        }
    }

    public function removePackage($orderPackageId)
    {
        try {
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
            
            $this->dispatch('notify', [
                'message' => 'Package removed successfully',
                'type' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to remove package: ' . $e->getMessage());
            $this->dispatch('notify', [
                'message' => 'Failed to remove package',
                'type' => 'error'
            ]);
        }
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

    /**
     * Check if order contains any bed items
     */
    private function orderHasBedItems(): bool
    {
        foreach ($this->draftOrder->packages as $package) {
            foreach ($package->items as $item) {
                if ($item->item_type === 'bed') {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Get destination based on bed items
     */
    private function getDestination(): string
    {
        return $this->orderHasBedItems() ? 'Bed Manager' : 'Cashier';
    }

    /**
     * Get the button text based on destination
     */
    private function getButtonText(): string
    {
        return $this->orderHasBedItems() ? 'Send to Bed Manager' : 'Send to Cashier';
    }

    /**
     * Get the button color classes based on destination
     */
    private function getButtonClasses(): string
    {
        return $this->orderHasBedItems() 
            ? 'bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700' 
            : 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700';
    }

    /**
     * Send order to appropriate queue (Bed Manager if has bed, otherwise Cashier)
     */
    /**
 * Send order to appropriate queue (Bed Manager if has bed, otherwise Cashier)
 */
public function sendOrder()
{
    if ($this->draftOrder->packages->isEmpty()) {
        $this->dispatch('notify', [
            'message' => 'Please add at least one package',
            'type' => 'error'
        ]);
        return;
    }

    try {
        DB::transaction(function () {
            $hasBedItems = $this->orderHasBedItems();
            
            // Log what we're about to do
            Log::info('Processing order send', [
                'order_id' => $this->draftOrder->id,
                'encounter_id' => $this->rehabEncounter->id,
                'has_bed_items' => $hasBedItems,
                'current_order_status' => $this->draftOrder->status,
                'current_encounter_status' => $this->rehabEncounter->status
            ]);
            
            if ($hasBedItems) {
                // Order has bed items - send to bed manager first
                $this->draftOrder->update([
                    'status' => 'sent_to_bed_manager'
                ]);

                $this->rehabEncounter->update([
                    'status' => 'sent_to_bed_manager'
                ]);

                $message = 'Order sent to Bed Manager for bed selection.';
                $redirectRoute = 'rehab.bed-manager.queue';
                $destination = 'Bed Manager';
            } else {
                // No bed items - go directly to cashier
                $this->draftOrder->update([
                    'status' => 'sent_to_cashier'
                ]);

                $this->rehabEncounter->update([
                    'status' => 'sent_to_cashier'
                ]);

                $message = 'Order sent directly to Cashier for payment.';
                $redirectRoute = 'rehab.cashier.queue';
                $destination = 'Cashier';
            }

            // Log the successful update
            Log::info('Order sent to ' . $destination, [
                'order_id' => $this->draftOrder->id,
                'encounter_id' => $this->rehabEncounter->id,
                'has_bed_items' => $hasBedItems,
                'new_order_status' => $this->draftOrder->status,
                'new_encounter_status' => $this->rehabEncounter->status,
                'doctor_id' => auth()->id()
            ]);

            // Store in session
            session()->flash('success', $message);
        });

        // Dispatch success notification
        $this->dispatch('notify', [
            'message' => session('success'),
            'type' => 'success'
        ]);

        // Redirect based on destination
        // if ($this->orderHasBedItems()) {
        //     return redirect()->route('rehab.bed-manager.queue');
        // } else {
        //     return redirect()->route('rehab.cashier.queue');
        // }

    } catch (\Exception $e) {
        // Log the full error details
        Log::error('Failed to send order: ' . $e->getMessage(), [
            'order_id' => $this->draftOrder->id,
            'encounter_id' => $this->rehabEncounter->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        $this->dispatch('notify', [
            'message' => 'Failed to send order: ' . $e->getMessage(),
            'type' => 'error'
        ]);
    }
}

    /**
     * Keep old method for backward compatibility
     */
    public function sendToCashier()
    {
        return $this->sendOrder();
    }

    public function backToReview()
    {
        return $this->redirect(route('doctor.rehab.review', $this->rehabEncounter->id), navigate: true);
    }

    public function render()
    {
        $hasBedItems = $this->orderHasBedItems();
        $destination = $this->getDestination();
        $buttonText = $this->getButtonText();
        $buttonClasses = $this->getButtonClasses();
        
        return view('livewire.rehab.doctor-rehab-order', [
            'hasBedItems' => $hasBedItems,
            'destination' => $destination,
            'buttonText' => $buttonText,
            'buttonClasses' => $buttonClasses
        ]);
    }
}