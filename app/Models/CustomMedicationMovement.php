<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomMedicationMovement extends Model
{
     use HasFactory;

    protected $table = 'custom_medication_movements';

    protected $fillable = [
        'custom_medication_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'performed_by',
        'note'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
    ];

    // public function medication()
    // {
    //     return $this->belongsTo(CustomMedication::class);
    // }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
    //
    public function medication()
    {
        return $this->belongsTo(CustomMedication::class);
    }
}
