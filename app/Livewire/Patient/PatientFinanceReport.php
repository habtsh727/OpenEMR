<?php

namespace App\Livewire\Patient;

use App\Models\Patient;
use App\Models\Encounter;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function mount($patientId)
    {
        $this->patient = Patient::with(['encounters' => function($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($patientId);

        $this->encounters = $this->patient->encounters;
        $this->loadFinancialData();
    }

    public function updatedEncounterId()
    {
        $this->loadFinancialData();
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

            if ($cardPayment) {
                $payment = [
                    'type' => 'Registration Card',
                    'description' => 'Patient Registration Card Fee',
                    'date' => $cardPayment->payment_date,
                    'original_amount' => $cardPayment->amount,
                    'discount' => 0,
                    'paid_amount' => $cardPayment->amount,
                    'payment_method' => $cardPayment->payment_type,
                    'status' => 'paid',
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
                ->select('service_payments.*', 'services.name as service_name')
                ->get();

            foreach ($servicePayments as $sp) {
                $payment = [
                    'type' => 'Service',
                    'description' => $sp->service_name,
                    'date' => $sp->payment_date,
                    'original_amount' => $sp->original_amount * ($sp->quantity ?? 1),
                    'discount' => $sp->discount ?? 0,
                    'paid_amount' => $sp->paid_amount,
                    'payment_method' => $sp->payment_type,
                    'quantity' => $sp->quantity,
                    'unit_price' => $sp->original_amount,
                    'status' => 'paid',
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
                ->select('lab_orders.*', 'lab_tests.name as test_name', 'lab_tests.price')
                ->get();

            foreach ($labOrders as $lab) {
                $payment = [
                    'type' => 'Lab Test',
                    'description' => $lab->test_name,
                    'date' => $lab->paid_at,
                    'original_amount' => $lab->price,
                    'discount' => 0,
                    'paid_amount' => $lab->price,
                    'payment_method' => $this->getPaymentMethodFromUser($lab->paid_by),
                    'status' => 'paid',
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
                ->select('imaging_orders.*', 'imaging_types.name as imaging_name', 'imaging_types.fee')
                ->get();

            foreach ($imagingOrders as $imaging) {
                $payment = [
                    'type' => 'Imaging',
                    'description' => $imaging->imaging_name,
                    'date' => $imaging->completed_date ?? $imaging->order_date,
                    'original_amount' => $imaging->amount,
                    'discount' => 0,
                    'paid_amount' => $imaging->amount,
                    'payment_method' => 'N/A', // Payment method not stored in imaging_orders
                    'status' => 'paid',
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // 5. Pharmacy Payments
            $medicationPayments = DB::table('medication_payments')
                ->join('medication_orders', 'medication_orders.id', '=', 'medication_payments.medication_order_id')
                ->join('encounters', 'encounters.id', '=', 'medication_orders.encounter_id')
                ->leftJoin('custom_medications', 'custom_medications.id', '=', 'medication_orders.id') // Adjust if needed
                ->where('encounters.id', $encounter->id)
                ->where('medication_orders.status', 'paid')
                ->select('medication_payments.*', 'medication_orders.total_amount', 'medication_orders.discount_amount', 'medication_orders.payable_amount')
                ->get();

            foreach ($medicationPayments as $mp) {
                $payment = [
                    'type' => 'Pharmacy',
                    'description' => 'Medication Order',
                    'date' => $mp->paid_at,
                    'original_amount' => $mp->amount + ($mp->discount ?? 0),
                    'discount' => $mp->discount ?? 0,
                    'paid_amount' => $mp->amount,
                    'payment_method' => $mp->payment_method,
                    'status' => 'paid',
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
                ->select('rehab_orders.*', 'rehab_order_packages.package_name', 'rehab_order_packages.final_price')
                ->get();

            foreach ($rehabOrders as $rehab) {
                $originalAmount = $rehab->total_amount;
                $paidAmount = $rehab->total_amount; // Assuming full payment
                $discount = 0; // Could calculate if discount fields exist

                $payment = [
                    'type' => 'Rehab Package',
                    'description' => $rehab->package_name ?? 'Rehabilitation Package',
                    'date' => $rehab->paid_at,
                    'original_amount' => $originalAmount,
                    'discount' => $discount,
                    'paid_amount' => $paidAmount,
                    'payment_method' => $rehab->payment_method,
                    'status' => 'paid',
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
                $encounterData['encounter_discount'] += $payment['discount'];
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
                ->select('rehab_bed_selections.*', 'bed_classes.name as bed_class_name')
                ->get();

            foreach ($bedSelections as $bed) {
                $payment = [
                    'type' => 'Bed',
                    'description' => $bed->bed_class_name . ' Bed - ' . $bed->duration_days . ' days',
                    'date' => $bed->selected_at,
                    'original_amount' => $bed->total_price,
                    'discount' => 0,
                    'paid_amount' => $bed->total_price,
                    'payment_method' => 'N/A', // From rehab_orders
                    'status' => 'paid',
                    'details' => $bed->duration_days . ' days @ ETB ' . number_format($bed->price_per_day, 2) . '/day',
                ];
                $encounterData['payments'][] = $payment;
                $encounterData['encounter_total'] += $payment['original_amount'];
                $encounterData['encounter_paid'] += $payment['paid_amount'];
            }

            // Sort payments by date
            usort($encounterData['payments'], function($a, $b) {
                return strtotime($a['date']) - strtotime($b['date']);
            });

            $this->financialData[] = $encounterData;
            $this->grandTotal += $encounterData['encounter_total'];
            $this->totalPaid += $encounterData['encounter_paid'];
            $this->totalDiscount += $encounterData['encounter_discount'];
        }
    }

    private function getPaymentMethodFromUser($userId)
    {
        if (!$userId) return 'N/A';
        
        // You can implement logic to get payment method from user if needed
        return 'Cash'; // Default
    }

    public function togglePrintView()
    {
        $this->showPrintView = !$this->showPrintView;
    }

    public function printReport()
    {
        $this->dispatch('print-report');
    }

    public function render()
    {
        return view('livewire.patient.patient-finance-report', [
            'patient' => $this->patient,
            'encounters' => $this->encounters,
            'financialData' => $this->financialData,
        ]);
    }
}