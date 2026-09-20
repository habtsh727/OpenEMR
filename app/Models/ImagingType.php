<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImagingType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'fee', 'description', 'is_active'];

    protected $casts = [
        'fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function imagingOrders()
    {
        return $this->hasMany(ImagingOrder::class);
    }
}