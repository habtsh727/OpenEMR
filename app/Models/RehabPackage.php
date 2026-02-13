<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RehabPackage extends Model
{ 
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'discount_type',
        'discount_value',
        'final_price',
        'is_active',
        'created_by'
    ];

    public function items()
    {
        return $this->hasMany(RehabPackageItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function calculateFinalPrice()
    {
        $price = $this->base_price;

        if (!$this->discount_type || !$this->discount_value) {
            return $price;
        }

        if ($this->discount_type === 'fixed') {
            return max(0, $price - $this->discount_value);
        }

        if ($this->discount_type === 'percentage') {
            return max(0, $price - ($price * ($this->discount_value / 100)));
        }

        return $price;
    }
}
