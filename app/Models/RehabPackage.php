<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class RehabPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'rehab_packages';

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
    protected $casts = [
        'base_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'final_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (!$package->created_by) {
                $package->created_by = Auth::id();
            }
            $package->calculateFinalPrice();
        });

        static::updating(function ($package) {
            $package->calculateFinalPrice();
        });
    }



    public function getDiscountDisplayAttribute()
    {
        if (!$this->discount_type || !$this->discount_value) {
            return 'No Discount';
        }

        return $this->discount_type === 'fixed'
            ? 'ETB ' . number_format($this->discount_value, 2)
            : $this->discount_value . '%';
    }

    public function getSavingsAttribute()
    {
        return $this->base_price - $this->final_price;
    }

    public function getSavingsPercentageAttribute()
    {
        if ($this->base_price <= 0) return 0;
        return round((($this->base_price - $this->final_price) / $this->base_price) * 100, 2);
    }

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
