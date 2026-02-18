<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RehabOrder extends Model
{
    //
    protected $fillable = [
        'rehab_encounter_id',
        'doctor_id',
        'total_amount',
        'status',
        'payment_method',
        'paid_at',
    ];
    protected $casts = [
        'paid_at' => 'datetime', // Add this cast
    ];

    public function encounter()
    {
        return $this->belongsTo(RehabEncounter::class, 'rehab_encounter_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function packages()
    {
        return $this->hasMany(RehabOrderPackage::class);
    }

    public function items()
    {
        return $this->hasManyThrough(
            RehabOrderItem::class,
            RehabOrderPackage::class
        );
    }
}
