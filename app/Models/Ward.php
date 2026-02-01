<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    //
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * A ward has many rooms
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
