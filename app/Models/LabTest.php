<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabTest extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'sample_type',
        'department',
        'price',
        'active'
    ];
    protected $casts = [
        'price' => 'decimal:2',
        'active' => 'boolean',
    ];
    public function labOrders()
    {
        return $this->hasMany(LabOrder::class);
    }
}
