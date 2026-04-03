<?php

namespace App\Livewire\Patient;

use App\Models\Patient;
use App\Models\Encounter;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PatientFinanceReport extends Component
{
    public Patient $patient;
    public $encounterId = 'all';
    public $encounters = [];
    public $financialData = [];
    public $grandTotal = 0;
    public $totalPaid = 0;
    public $totalDiscount = 0;
    public $showPrintView = false;
    public $isPrintMode = false;
    public $dateFrom;
    public $dateTo;
    public $cuppingData = [];
    protected $queryString = ['encounterId', 'showPrintView'];

    public function mount($patientId)
    {
        $this->patient = Patient::with(['encounters' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($patientId);

        $this->encounters = $this->patient->encounters;
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
        $this->loadFinancialData();
        $this->loadCuppingData();
    }
    // Add this new method to load cupping data
    public function loadCuppingData()
    {
        $this->cuppingData = [];

        $cuppingSessions = DB::table('cupping_sessions')
            ->join('cupping_therapies', 'cupping_therapies.id', '=', 'cupping_sessions.cupping_therapy_id')
            ->join('encounters', 'encounters.id', '=', 'cupping_therapies.encounter_id')
            ->leftJoin('cupping_payments', 'cupping_payments.cupping_session_id', '=', 'cupping_sessions.id')
            ->where('encounters.patient_id', $this->patient->id)
            ->whereBetween('cupping_sessions.session_date', [$this->dateFrom, $this->dateTo])
            ->select(
                'cupping_sessions.*',
                'cupping_therapies.id as therapy_id',
                'cupping_therapies.discount as therapy_discount',
                'cupping_therapies.total_amount as therapy_total',
                'cupping_therapies.final_amount as therapy_final',
                'cupping_payments.id as payment_id',
                'cupping_payments.amount as payment_amount',
                'cupping_payments.payment_method',
                'cupping_payments.paid_at',
                'encounters.id as encounter_id'
            )
            ->get();

        // Group by session
        $groupedSessions = [];
        foreach ($cuppingSessions as $session) {
            $sessionId = $session->id;
            if (!isset($groupedSessions[$sessionId])) {
                $groupedSessions[$sessionId] = [
                    'id' => $session->id,
                    'therapy_id' => $session->therapy_id,
                    'encounter_id' => $session->encounter_id,
                    'session_number' => $session->session_number,
                    'session_date' => $session->session_date,
                    'session_amount' => (float)$session->session_amount,
                    'paid_amount' => (float)$session->paid_amount,
                    'payment_status' => $session->payment_status,
                    'treatment_status' => $session->treatment_status,
                    'therapy_discount' => (float)$session->therapy_discount,
                    'therapy_total' => (float)$session->therapy_total,
                    'therapy_final' => (float)$session->therapy_final,
                    'payments' => [],
                    'items' => []
                ];
            }

            // Add payment if exists
            if ($session->payment_id) {
                $groupedSessions[$sessionId]['payments'][] = [
                    'id' => $session->payment_id,
                    'amount' => (float)$session->payment_amount,
                    'method' => $session->payment_method,
                    'date' => $session->paid_at,
                ];
            }
        }

        // Get items for each session
        if (!empty($groupedSessions)) {
            $sessionIds = array_keys($groupedSessions);
            $items = DB::table('cupping_session_items')
                ->join('cupping_types', 'cupping_types.id', '=', 'cupping_session_items.cupping_type_id')
                ->join('cupping_locations', 'cupping_locations.id', '=', 'cupping_session_items.cupping_location_id')
                ->whereIn('cupping_session_items.cupping_session_id', $sessionIds)
                ->select(
                    'cupping_session_items.*',
                    'cupping_types.name as type_name',
                    'cupping_locations.name as location_name'
                )
                ->get();

            foreach ($items as $item) {
                $sessionId = $item->cupping_session_id;
                if (isset($groupedSessions[$sessionId])) {
                    $groupedSessions[$sessionId]['items'][] = [
                        'type' => $item->type_name,
                        'location' => $item->location_name,
                        'qty' => $item->qty,
                        'price' => (float)$item->price,
                        'total' => (float)$item->total,
                    ];
                }
            }
        }

        $this->cuppingData = array_values($groupedSessions);
    }

    public function updatedEncounterId()
    {
        $this->loadFinancialData();
        $this->loadCuppingData();
    }

    public function updatedDateFrom()
    {
        $this->loadFinancialData();
        $this->loadCuppingData();
    }

    public function updatedDateTo()
    {
        $this->loadFinancialData();
        $this->loadCuppingData();
    }

    public function loadFinancialData()
    {
        $this->financialData = [];
        $this->grandTotal = 0;
        $this->totalPaid = 0;
        $this->totalDiscount = 0;

        $encounters = $this->encounterId === 'all'
            ? $this->encounters
            : $this->encounters->where('id', $this->encounterId);

        foreach ($encounters as $encounter) {
            // Filter by date range if provided
            if ($this->dateFrom && $this->dateTo) {
                $encounterDate = $encounter->created_at->format('Y-m-d');
                if ($encounterDate < $this->dateFrom || $encounterDate > $this->dateTo) {
                    continue;
                }
            }

            $encounterData = [
                'id' => $encounter->id,
                'date' => $encounter->created_at->format('Y-m-d H:i'),
                'status' => $encounter->status,
                'payments' => [],
                'encounter_total' => 0,
                'encounter_paid' => 0,
                'encounter_discount' => 0,
            ];

            // 1. Registration/Card Payment
            $cardPayment = DB::table('card_payments')
                ->where('patient_id', $this->patient->id)
                ->where('is_paid', true)
                ->first();

            if ($cardPayment && $this->isWithinDateRange($cardPayment->payment_date)) {
                $payment = [
                    'type' => 'Registration Card',
                    'type_icon' => 'credit-card',
                    'description' => 'Patient Registration Card Fee',
                    'date' => $cardPayment->payment_date,
                    'original_amount' => (float)$cardPayment->amount,
                    'discount' => 0,
                    'paid_amount' => (float)$cardPayment->amount,
                    'payment_method' => $cardPayment->payment_type,
                    'status' => 'paid',
                    'reference' => 'CARD-' . $cardPayment->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // 2. Service Payments
            $servicePayments = DB::table('service_payments')
                ->join('services', 'services.id', '=', 'service_payments.service_id')
                ->where('service_payments.patient_id', $this->patient->id)
                ->where('service_payments.is_paid', true)
                ->whereBetween('service_payments.payment_date', [$this->dateFrom, $this->dateTo])
                ->select('service_payments.*', 'services.name as service_name')
                ->get();

            foreach ($servicePayments as $sp) {
                $payment = [
                    'type' => 'Service',
                    'type_icon' => 'cog',
                    'description' => $sp->service_name,
                    'date' => $sp->payment_date,
                    'original_amount' => (float)($sp->original_amount * ($sp->quantity ?? 1)),
                    'discount' => (float)($sp->discount ?? 0),
                    'paid_amount' => (float)$sp->paid_amount,
                    'payment_method' => $sp->payment_type,
                    'quantity' => $sp->quantity ?? 1,
                    'unit_price' => (float)$sp->original_amount,
                    'status' => 'paid',
                    'reference' => 'SRV-' . $sp->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
                $encounterData['encounter_discount'] += $payment['discount'];
            }

            // 3. Lab Payments
            $labOrders = DB::table('lab_orders')
                ->join('orders', 'orders.id', '=', 'lab_orders.order_id')
                ->join('lab_tests', 'lab_tests.id', '=', 'lab_orders.lab_test_id')
                ->where('orders.encounter_id', $encounter->id)
                ->where('lab_orders.payment_status', 'paid')
                ->whereNotNull('lab_orders.paid_at')
                ->whereBetween('lab_orders.paid_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                ->select('lab_orders.*', 'lab_tests.name as test_name', 'lab_tests.price')
                ->get();

            foreach ($labOrders as $lab) {
                $payment = [
                    'type' => 'Lab Test',
                    'type_icon' => 'beaker',
                    'description' => $lab->test_name,
                    'date' => $lab->paid_at,
                    'original_amount' => (float)$lab->price,
                    'discount' => 0,
                    'paid_amount' => (float)$lab->price,
                    'payment_method' => 'cash',
                    'status' => 'paid',
                    'reference' => 'LAB-' . $lab->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // 4. Imaging Payments
            $imagingOrders = DB::table('imaging_orders')
                ->join('imaging_types', 'imaging_types.id', '=', 'imaging_orders.imaging_type_id')
                ->where('imaging_orders.encounter_id', $encounter->id)
                ->where('imaging_orders.status', 'paid')
                ->whereBetween('imaging_orders.completed_date', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                ->select('imaging_orders.*', 'imaging_types.name as imaging_name', 'imaging_types.fee')
                ->get();

            foreach ($imagingOrders as $imaging) {
                $payment = [
                    'type' => 'Imaging',
                    'type_icon' => 'camera',
                    'description' => $imaging->imaging_name,
                    'date' => $imaging->completed_date ?? $imaging->order_date,
                    'original_amount' => (float)$imaging->amount,
                    'discount' => 0,
                    'paid_amount' => (float)$imaging->amount,
                    'payment_method' => 'N/A',
                    'status' => 'paid',
                    'reference' => 'IMG-' . $imaging->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // 5. Pharmacy Payments
            $medicationPayments = DB::table('medication_payments')
                ->join('medication_orders', 'medication_orders.id', '=', 'medication_payments.medication_order_id')
                ->join('encounters', 'encounters.id', '=', 'medication_orders.encounter_id')
                ->where('encounters.id', $encounter->id)
                ->where('medication_orders.status', 'paid')
                ->whereBetween('medication_payments.paid_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                ->select('medication_payments.*', 'medication_orders.total_amount', 'medication_orders.discount_amount', 'medication_orders.payable_amount')
                ->get();

            foreach ($medicationPayments as $mp) {
                $payment = [
                    'type' => 'Pharmacy',
                    'type_icon' => 'pill',
                    'description' => 'Medication Order #' . $mp->medication_order_id,
                    'date' => $mp->paid_at,
                    'original_amount' => (float)($mp->amount + ($mp->discount ?? 0)),
                    'discount' => (float)($mp->discount ?? 0),
                    'paid_amount' => (float)$mp->amount,
                    'payment_method' => $mp->payment_method,
                    'status' => 'paid',
                    'reference' => 'MED-' . $mp->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
                $encounterData['encounter_discount'] += $payment['discount'];
            }

            // 6. Rehab Package Payments
            $rehabOrders = DB::table('rehab_orders')
                ->join('rehab_encounters', 'rehab_encounters.id', '=', 'rehab_orders.rehab_encounter_id')
                ->join('encounters', 'encounters.id', '=', 'rehab_encounters.encounter_id')
                ->leftJoin('rehab_order_packages', 'rehab_order_packages.rehab_order_id', '=', 'rehab_orders.id')
                ->where('encounters.id', $encounter->id)
                ->where('rehab_orders.status', 'paid')
                ->whereNotNull('rehab_orders.paid_at')
                ->whereBetween('rehab_orders.paid_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                ->select('rehab_orders.*', 'rehab_order_packages.package_name', 'rehab_order_packages.final_price')
                ->get();

            foreach ($rehabOrders as $rehab) {
                $payment = [
                    'type' => 'Rehab Package',
                    'type_icon' => 'clipboard-list',
                    'description' => $rehab->package_name ?? 'Rehabilitation Package',
                    'date' => $rehab->paid_at,
                    'original_amount' => (float)$rehab->total_amount,
                    'discount' => 0,
                    'paid_amount' => (float)$rehab->total_amount,
                    'payment_method' => $rehab->payment_method ?? 'N/A',
                    'status' => 'paid',
                    'reference' => 'REHAB-' . $rehab->id,
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // 7. Bed Payments
            $bedSelections = DB::table('rehab_bed_selections')
                ->join('rehab_orders', 'rehab_orders.id', '=', 'rehab_bed_selections.rehab_order_id')
                ->join('rehab_encounters', 'rehab_encounters.id', '=', 'rehab_bed_selections.rehab_encounter_id')
                ->join('encounters', 'encounters.id', '=', 'rehab_encounters.encounter_id')
                ->join('bed_classes', 'bed_classes.id', '=', 'rehab_bed_selections.bed_class_id')
                ->where('encounters.id', $encounter->id)
                ->where('rehab_bed_selections.status', 'completed')
                ->where('rehab_orders.status', 'paid')
                ->whereBetween('rehab_bed_selections.selected_at', [$this->dateFrom . ' 00:00:00', $this->dateTo . ' 23:59:59'])
                ->select('rehab_bed_selections.*', 'bed_classes.name as bed_class_name')
                ->get();

            foreach ($bedSelections as $bed) {
                $payment = [
                    'type' => 'Bed',
                    'type_icon' => 'home',
                    'description' => $bed->bed_class_name . ' Bed',
                    'date' => $bed->selected_at,
                    'original_amount' => (float)$bed->total_price,
                    'discount' => 0,
                    'paid_amount' => (float)$bed->total_price,
                    'payment_method' => $bed->payment_method ?? 'N/A',
                    'status' => 'paid',
                    'reference' => 'BED-' . $bed->id,
                    'details' => $bed->duration_days . ' days @ ETB ' . number_format($bed->price_per_day, 2) . '/day',
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // Sort payments by date
            if (!empty($encounterData['payments'])) {
                usort($encounterData['payments'], function ($a, $b) {
                    return strtotime($a['date']) - strtotime($b['date']);
                });

                $this->financialData[] = $encounterData;
                $this->grandTotal += $encounterData['encounter_total'];
                $this->totalPaid += $encounterData['encounter_paid'];
                $this->totalDiscount += $encounterData['encounter_discount'];
            }
        }
    }

    private function isWithinDateRange($date)
    {
        if (!$this->dateFrom || !$this->dateTo) return true;
        $dateStr = Carbon::parse($date)->format('Y-m-d');
        return $dateStr >= $this->dateFrom && $dateStr <= $this->dateTo;
    }

    public function togglePrintView()
    {
        $this->showPrintView = !$this->showPrintView;
    }

    public function printReport()
    {
        $this->isPrintMode = true;
        $this->showPrintView = true;
        $this->dispatch('print-window');
    }

    public function getPaymentMethodColor($method)
    {
        return match (strtolower($method)) {
            'cash' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'card' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'insurance' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'telebirr' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'bank' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }

    public function getTypeColor($type)
    {
        return match ($type) {
            'Registration Card' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'Service' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'Lab Test' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            'Imaging' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400',
            'Pharmacy' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400',
            'Rehab Package' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400',
            'Bed' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
        };
    }

    public function getTypeIcon($type)
    {
        return match ($type) {
            'Registration Card' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>',
            'Service' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
            'Lab Test' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path></svg>',
            'Imaging' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
            'Pharmacy' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 3v9a1 1 0 01-1 1h-4a1 1 0 01-1-1V7L8 4z"></path></svg>',
            'Rehab Package' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>',
            'Bed' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
            default => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
        };
    }

    public function render()
    {
        return view('livewire.patient.patient-finance-report', [
            'patient' => $this->patient,
            'encounters' => $this->encounters,
            'financialData' => $this->financialData,
            'cuppingData' => $this->cuppingData,
        ]);
    }
}
