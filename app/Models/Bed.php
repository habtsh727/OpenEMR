<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    //
    protected $fillable = [
        'bed_number',
        'room_id',
        'bed_type_id',
        'status',
    ];

    /**
     * Bed belongs to a room
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Bed belongs to a bed type (ICU, Pediatric, etc.)
     */
    public function bedType()
    {
        return $this->belongsTo(BedType::class);
    }

    /**
     * Shortcut: get bed class via room
     */
    public function bedClass()
    {
        return $this->room->bedClass();
    }
}
