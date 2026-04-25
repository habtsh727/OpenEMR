<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuppingPackage extends Model
{
    use HasFactory;

    // Remove SoftDeletes trait if present

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'discount_type',
        'discount_value',
        'total_price',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'total_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function treatments()
    {
        return $this->hasMany(CuppingPackageTreatment::class, 'package_id');
    }

    public function materials()
    {
        return $this->hasMany(CuppingPackageMaterial::class, 'package_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function therapyPackages()
    {
        return $this->hasMany(CuppingTherapyPackage::class, 'cupping_package_id');
    }

    // Helper Methods
    public function calculateBasePrice()
    {
        $treatmentTotal = $this->treatments->sum('treatment_price');
        $materialTotal = $this->materials->sum('total_material_cost');
        return $treatmentTotal + $materialTotal;
    }

    public function calculateTotalPrice()
    {
        $base = $this->base_price ?: $this->calculateBasePrice();

        if (!$this->discount_type || !$this->discount_value) {
            return $base;
        }

        if ($this->discount_type === 'percentage') {
            return $base - ($base * $this->discount_value / 100);
        } else {
            return $base - $this->discount_value;
        }
    }

    public function updatePrices()
    {
        $this->base_price = $this->calculateBasePrice();
        $this->total_price = $this->calculateTotalPrice();
        $this->saveQuietly();
    }
}
