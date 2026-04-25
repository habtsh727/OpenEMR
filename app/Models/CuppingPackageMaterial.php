<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuppingPackageMaterial extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'pharmacy_item_id',
        'quantity_required',
        'unit_price_snapshot',
        'total_material_cost',
        'notes',
    ];

    protected $casts = [
        'quantity_required' => 'integer',
        'unit_price_snapshot' => 'decimal:2',
        'total_material_cost' => 'decimal:2',
    ];

    // Relationships
    public function package()
    {
        return $this->belongsTo(CuppingPackage::class, 'package_id');
    }

    public function pharmacyItem()
    {
        return $this->belongsTo(PharmacyItem::class, 'pharmacy_item_id');
    }

    // Helper Methods
    public function calculateTotalCost()
    {
        return $this->quantity_required * $this->unit_price_snapshot;
    }
}
