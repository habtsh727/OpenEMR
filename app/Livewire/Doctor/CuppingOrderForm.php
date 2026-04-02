<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\CuppingTherapy;
use App\Models\CuppingTherapyItem;
use App\Models\CuppingType;
use App\Models\CuppingLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingOrderForm extends Component
{
    public Encounter $encounter;
    public $treatment_date;
    public $notes = '';
    public $discount = 0;
    public $items = [];
    public $grand_total = 0;
    public $final_amount = 0;

    protected $rules = [
        'treatment_date' => 'required|date',
        'discount' => 'required|numeric|min:0',
        'items.*.cupping_type_id' => 'required|exists:cupping_types,id',
        'items.*.cupping_location_id' => 'required|exists:cupping_locations,id',
        'items.*.qty' => 'required|integer|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.notes' => 'nullable|string',
    ];

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->treatment_date = date('Y-m-d');
        $this->addItem();
    }

    public function addItem()
    {
        $this->items[] = [
            'cupping_type_id' => '',
            'cupping_location_id' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
            'notes' => '',
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotals();
    }

    public function updateItemTotal($index)
    {
        if (isset($this->items[$index])) {
            $this->items[$index]['total'] = $this->items[$index]['qty'] * $this->items[$index]['price'];
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->grand_total = collect($this->items)->sum('total');
        $this->final_amount = max(0, $this->grand_total - $this->discount);
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function save()
    {
        $this->validate();

        if (empty($this->items)) {
            $this->addError('items', 'At least one item is required.');
            return;
        }

        DB::beginTransaction();

        try {
            // Check for existing pending session
            $existingTherapy = CuppingTherapy::where('encounter_id', $this->encounter->id)
                ->whereIn('status', [
                    CuppingTherapy::STATUS_PENDING,
                    CuppingTherapy::STATUS_ORDERED,
                    CuppingTherapy::STATUS_PAYMENT_PARTIAL,
                    CuppingTherapy::STATUS_PAYMENT_COMPLETED,
                    CuppingTherapy::STATUS_SENT_TO_CUPPING,
                ])
                ->first();

            if ($existingTherapy) {
                $this->dispatch('alert', type: 'error', message: 'Patient has an active cupping session. Please complete or cancel it first.');
                DB::rollBack();
                return;
            }

            $therapy = CuppingTherapy::create([
                'encounter_id' => $this->encounter->id,
                'doctor_id' => Auth::id(),
                'treatment_date' => $this->treatment_date,
                'notes' => $this->notes,
                'total_amount' => $this->grand_total,
                'discount' => $this->discount,
                'final_amount' => $this->final_amount,
                'status' => CuppingTherapy::STATUS_ORDERED,
            ]);

            foreach ($this->items as $item) {
                CuppingTherapyItem::create([
                    'cupping_therapy_id' => $therapy->id,
                    'cupping_type_id' => $item['cupping_type_id'],
                    'cupping_location_id' => $item['cupping_location_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'total' => $item['total'],
                    'notes' => $item['notes'],
                ]);
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Cupping order created successfully!');
            return redirect()->route('cashier.cupping', $therapy->id);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert', type: 'error', message: 'Failed to save order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.doctor.cupping-order-form', [
            'cuppingTypes' => CuppingType::where('status', true)->get(),
            'cuppingLocations' => CuppingLocation::where('status', true)->get(),
        ]);
    }
}