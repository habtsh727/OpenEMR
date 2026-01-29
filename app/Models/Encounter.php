<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Encounter extends Model
{
    /** @use HasFactory<\Database\Factories\EncounterFactory> */
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the patient that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the cardPayment that owns the Encounter
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cardPayment(): BelongsTo
    {
        return $this->belongsTo(CardPayment::class);
    }

    public function triageBy()
    {
        return $this->belongsTo(User::class, 'triage_by');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function medicalHistories()
    {
        return $this->hasMany(EncounterMedicalHistory::class);
    }

    public function chiefComplaints()
    {
        return $this->hasMany(EncounterChiefComplaint::class);
    }

    public function examinations()
    {
        return $this->hasMany(EncounterExamination::class);
    }

    public function assessments()
    {
        return $this->hasMany(EncounterAssessment::class);
    }

    // Keep generic orders if you still have Order model
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Imaging-specific methods
    

    public function labOrders()
    {
        return $this->hasManyThrough(
            LabOrder::class,
            Order::class, // Keep Order here if lab uses Order model
            'encounter_id',
            'order_id'
        )->where('orders.order_type', 'lab');
    }

    public function medicationOrder()
    {
        return $this->hasOne(MedicationOrder::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function imagingOrders()
    {
        return $this->hasMany(ImagingOrder::class);
    }

    
    public function imagingResults()
    {
        return $this->hasManyThrough(
            ImagingResult::class,
            ImagingOrder::class,
            'encounter_id',
            'imaging_order_id',
            'id',
            'id'
        );
    }
}
