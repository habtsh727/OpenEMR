<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //
    protected $fillable = [
        'encounter_id',
        'order_type',
        'status',
        'ordered_at',
        'completed_at',
        'notes'
    ];
    protected $casts = [
        'ordered_at' => 'datetime',
        'completed_at' => 'datetime',
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
