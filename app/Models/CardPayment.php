<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardPayment extends Model
{

    protected $dates = ['created_at', 'due_date'];
    protected $fillable = [
        'patient_id',
        'amount',
        'payment_date',
        'processed_by',
        'is_paid',
        'payment_type',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'is_paid' => 'boolean',
        'amount' => 'decimal:2'
    ];

    public function getDueDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value) : $this->created_at->copy()->addDays(10);
    }
    // app/Models/Patient.php
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    public function cardPayments()
    {
        return $this->hasMany(\App\Models\CardPayment::class);
    }
}
