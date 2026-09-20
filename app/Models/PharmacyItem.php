<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PharmacyItem extends Model
{
    protected $fillable = [
        'code',
        'name',
        'generic_name',
        'category_id',
        'unit_id',
        'route_id',
        'strength',
        'is_prescription_required',
        'is_active'
    ];

    public function category()
    {
        return $this->belongsTo(PharmacyCategory::class);
    }
    public function unit()
    {
        return $this->belongsTo(PharmacyUnit::class);
    }
    public function route()
    {
        return $this->belongsTo(PharmacyRoute::class);
    }
    public function batches()
    {
        return $this->hasMany(PharmacyBatch::class, 'medicine_id');
    }
    public function pharmacyBatches()
    {
        return $this->hasMany(PharmacyBatch::class, 'medicine_id');
    }
}
