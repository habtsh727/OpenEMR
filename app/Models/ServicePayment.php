<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePayment extends Model
{
     protected $fillable = [
        'patient_id',
        'service_id',
        'original_amount',
        'discount',
        'paid_amount',
        'is_paid',
        'quantity',
        'start_date',
        'end_date',
        'payment_type',
        'processed_by',

     ];
}
