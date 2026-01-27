<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'encounter_id',
        'lab_order_id',
        'medication_order_id',
        'doctor_id',
        'patient_id',
        'clinical_notes',
        'diagnosis',
        'advice',
        'status',
        'valid_until',
        'is_signed',
        'signed_by',
        'signed_at',
    ];

    protected $casts = [
        'valid_until' => 'date',
        'is_signed' => 'boolean',
        'signed_at' => 'datetime',
    ];

    // Relationships
    public function encounter(): BelongsTo
    {
        return $this->belongsTo(Encounter::class);
    }

    public function labOrder(): BelongsTo
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function medicationOrder(): BelongsTo
    {
        return $this->belongsTo(MedicationOrder::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'signed_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    // Helper Methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function sign($userId)
    {
        $this->update([
            'is_signed' => true,
            'signed_by' => $userId,
            'signed_at' => now(),
        ]);
    }

    public function markAsDispensed()
    {
        $this->update(['status' => 'dispensed']);
    }
       public function getIsValidAttribute()
    {
        return $this->valid_until && $this->valid_until->isFuture();
    }

    // Get total items count
    public function getTotalItemsAttribute()
    {
        return $this->items->count();
    }

    // Get custom items count
    public function getCustomItemsAttribute()
    {
        return $this->items->where('is_custom', true)->count();
    }

    // Get pharmacy items count
    public function getPharmacyItemsAttribute()
    {
        return $this->items->where('is_custom', false)->count();
    }
}