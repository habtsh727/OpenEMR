<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CuppingLocation extends Model
{
    protected $table = 'cupping_locations';
    
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function sessionItems(): HasMany
    {
        return $this->hasMany(CuppingSessionItem::class);
    }
}