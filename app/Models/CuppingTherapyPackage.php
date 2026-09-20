<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CuppingTherapyPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'cupping_therapy_id',
        'cupping_package_id',
        'session_number',
        'package_name_snapshot',
        'package_price_snapshot',
        'treatment_snapshot',
        'materials_snapshot',
        'status',
    ];

    protected $casts = [
        'package_price_snapshot' => 'decimal:2',
        'treatment_snapshot' => 'array',
        'materials_snapshot' => 'array',
    ];

    // Relationships
    public function therapy()
    {
        return $this->belongsTo(CuppingTherapy::class, 'cupping_therapy_id');
    }

    public function package()
    {
        return $this->belongsTo(CuppingPackage::class, 'cupping_package_id');
    }

    public function session()
    {
        return $this->hasOne(CuppingSession::class, 'cupping_therapy_package_id');
    }

    public function payments()
    {
        return $this->hasMany(CuppingPayment::class, 'cupping_therapy_package_id');
    }

    // Helper Methods
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
    }

    public function markAsInProgress()
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }

    public function markAsCancelled()
    {
        $this->update(['status' => 'cancelled']);
    }
}
