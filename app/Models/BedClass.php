<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BedClass extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
        'description',
        'price_per_day',
        'currency',
    ];

    /**
     * A bed class has many rooms
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
