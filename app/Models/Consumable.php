<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consumable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'unit',
        'current_stock',
        'minimum_stock',
        'unit_cost',
        'billable',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'billable' => 'boolean',
        'is_active' => 'boolean',
    ];
}
