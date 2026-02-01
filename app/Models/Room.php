<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
    protected $fillable = [
        'room_number',
        'ward_id',
        'bed_class_id',
        'floor',
    ];

    /**
     * Room belongs to a ward
     */
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Room belongs to a bed class (VIP, VVIP, General)
     */
    public function bedClass()
    {
        return $this->belongsTo(BedClass::class);
    }

    /**
     * Room has many beds
     */
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
