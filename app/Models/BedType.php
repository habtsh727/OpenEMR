<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BedType extends Model
{
    //
     protected $fillable = [
        'name',
        'code',
        'description',
    ];

    /**
     * A bed type has many beds
     */
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
