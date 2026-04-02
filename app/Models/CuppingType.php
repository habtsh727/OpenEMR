<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuppingType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function cuppingTherapyItems(): HasMany
    {
        return $this->hasMany(CuppingTherapyItem::class);
    }
}