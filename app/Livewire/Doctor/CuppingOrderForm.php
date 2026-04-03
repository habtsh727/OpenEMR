<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Encounter;
use App\Models\CuppingTherapy;
use App\Models\CuppingSession;
use App\Models\CuppingSessionItem;
use App\Models\CuppingType;
use App\Models\CuppingLocation;
use App\Models\CuppingQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CuppingOrderForm extends Component
{
    public Encounter $encounter;
    public $notes = '';
    public $discount = 0;
    public $total_sessions = 1;
    public $sessions = [];
    public $grand_total = 0;
    public $final_amount = 0;
    
    // Alert properties
    public $showAlert = false;
    public $alertMessage = '';
    public $alertType = 'success';

    protected $rules = [
        'total_sessions' => 'required|integer|min:1|max:10',
        'discount' => 'required|numeric|min:0',
        'sessions.*.session_date' => 'required|date',
        'sessions.*.items.*.cupping_type_id' => 'required|exists:cupping_types,id',
        'sessions.*.items.*.cupping_location_id' => 'required|exists:cupping_locations,id',
        'sessions.*.items.*.qty' => 'required|integer|min:1',
        'sessions.*.items.*.price' => 'required|numeric|min:0',
    ];

    public function mount(Encounter $encounter)
    {
        $this->encounter = $encounter;
        $this->initializeSessions();
    }

    public function initializeSessions()
    {
        $this->sessions = [];
        $total = (int) $this->total_sessions;
        for ($i = 1; $i <= $total; $i++) {
            $this->sessions[] = [
                'session_number' => $i,
                'session_date' => now()->addDays($i - 1)->format('Y-m-d'),
                'session_amount' => 0,
                'items' => [
                    [
                        'cupping_type_id' => '',
                        'cupping_location_id' => '',
                        'qty' => 1,
                        'price' => 0,
                        'total' => 0,
                        'notes' => '',
                    ]
                ]
            ];
        }
    }

    public function updatedTotalSessions()
    {
        $newTotal = (int) $this->total_sessions;
        $currentCount = count($this->sessions);

        if ($newTotal < 1) {
            $newTotal = 1;
            $this->total_sessions = 1;
        }
        if ($newTotal > 10) {
            $newTotal = 10;
            $this->total_sessions = 10;
        }

        if ($newTotal > $currentCount) {
            for ($i = $currentCount + 1; $i <= $newTotal; $i++) {
                $this->sessions[] = [
                    'session_number' => $i,
                    'session_date' => now()->addDays($i - 1)->format('Y-m-d'),
                    'session_amount' => 0,
                    'items' => [
                        [
                            'cupping_type_id' => '',
                            'cupping_location_id' => '',
                            'qty' => 1,
                            'price' => 0,
                            'total' => 0,
                            'notes' => '',
                        ]
                    ]
                ];
            }
        } elseif ($newTotal < $currentCount) {
            $this->sessions = array_slice($this->sessions, 0, $newTotal);
        }

        $this->calculateTotals();
    }

    public function addItem($sessionIndex)
    {
        $this->sessions[$sessionIndex]['items'][] = [
            'cupping_type_id' => '',
            'cupping_location_id' => '',
            'qty' => 1,
            'price' => 0,
            'total' => 0,
            'notes' => '',
        ];
    }

    public function removeItem($sessionIndex, $itemIndex)
    {
        unset($this->sessions[$sessionIndex]['items'][$itemIndex]);
        $this->sessions[$sessionIndex]['items'] = array_values($this->sessions[$sessionIndex]['items']);
        $this->calculateSessionTotal($sessionIndex);
    }

    public function updateItemTotal($sessionIndex, $itemIndex)
    {
        $item = &$this->sessions[$sessionIndex]['items'][$itemIndex];
        $item['total'] = $item['qty'] * $item['price'];
        $this->calculateSessionTotal($sessionIndex);
    }

    public function calculateSessionTotal($sessionIndex)
    {
        $sessionTotal = collect($this->sessions[$sessionIndex]['items'])->sum('total');
        $this->sessions[$sessionIndex]['session_amount'] = $sessionTotal;
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->grand_total = collect($this->sessions)->sum('session_amount');
        $this->final_amount = max(0, $this->grand_total - $this->discount);
    }

    public function updatedDiscount()
    {
        $this->calculateTotals();
    }

    public function save()
    {
        $this->validate();

        if (empty($this->sessions)) {
            $this->addError('sessions', 'At least one session is required.');
            return;
        }

        DB::beginTransaction();

        try {
            $therapy = CuppingTherapy::create([
                'encounter_id' => $this->encounter->id,
                'doctor_id' => Auth::id(),
                'notes' => $this->notes,
                'total_amount' => $this->grand_total,
                'discount' => $this->discount,
                'final_amount' => $this->final_amount,
                'total_sessions' => (int) $this->total_sessions,
                'status' => CuppingTherapy::STATUS_ORDERED,
            ]);

            foreach ($this->sessions as $sessionData) {
                $session = CuppingSession::create([
                    'cupping_therapy_id' => $therapy->id,
                    'session_number' => $sessionData['session_number'],
                    'session_date' => $sessionData['session_date'],
                    'session_amount' => $sessionData['session_amount'],
                    'paid_amount' => 0,
                    'payment_status' => 'unpaid',
                    'treatment_status' => 'pending',
                    'notes' => $sessionData['notes'] ?? null,
                ]);

                foreach ($sessionData['items'] as $item) {
                    CuppingSessionItem::create([
                        'cupping_session_id' => $session->id,
                        'cupping_type_id' => $item['cupping_type_id'],
                        'cupping_location_id' => $item['cupping_location_id'],
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'total' => $item['total'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }

                $lastPosition = CuppingQueue::where('queue_type', 'payment')
                    ->where('status', 'waiting')
                    ->max('position') ?? 0;

                CuppingQueue::create([
                    'cupping_session_id' => $session->id,
                    'queue_type' => 'payment',
                    'position' => (int) $lastPosition + 1,
                    'status' => 'waiting'
                ]);
            }

            DB::commit();

            $this->reset(['notes', 'discount']);
            $this->total_sessions = 1;
            $this->initializeSessions();
            $this->calculateTotals();

            $this->showAlertMessage("✓ Cupping order created successfully! {$this->total_sessions} session(s) added to cashier payment queue.", 'success');

        } catch (\Exception $e) {
            DB::rollBack();
            $this->showAlertMessage('Failed to save order: ' . $e->getMessage(), 'error');
        }
    }

    public function showAlertMessage($message, $type = 'success')
    {
        $this->alertMessage = $message;
        $this->alertType = $type;
        $this->showAlert = true;
        
        $this->dispatch('alert-shown');
    }

    public function closeAlert()
    {
        $this->showAlert = false;
    }

    public function render()
    {
        return view('livewire.doctor.cupping-order-form', [
            'cuppingTypes' => CuppingType::where('status', true)->get(),
            'cuppingLocations' => CuppingLocation::where('status', true)->get(),
        ]);
    }
}