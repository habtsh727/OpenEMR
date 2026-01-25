<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'encounter_id',
        'ordered_by',
        'order_type',
        'status',
        'ordered_at',
        'notes'
    ];

    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }
}
