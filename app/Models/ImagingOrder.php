<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagingOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'encounter_id',
        'imaging_type_id',
        'body_part_id',
        'ordered_by',
        'amount',
        'priority',
        'clinical_notes',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

  

    public function result()
    {
        return $this->hasOne(ImagingResult::class);
    }
    // Relationships
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function imagingType()
    {
        return $this->belongsTo(ImagingType::class);
    }

    public function bodyPart()
    {
        return $this->belongsTo(BodyPart::class);
    }

    public function orderedBy()
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }

    public function imagingResult()
    {
        return $this->hasOne(ImagingResult::class, 'imaging_order_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
    
}
