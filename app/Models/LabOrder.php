<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrder extends Model
{
    //
    protected $fillable = [
        'order_id',
        'lab_test_id',
        'priority',
        'payment_status',
        'paid_by',
        'paid_at',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }

    public function samples()
    {
        return $this->hasMany(LabSample::class);
    }

    public function results()
    {
        return $this->hasMany(LabResult::class);
    }
      public function cashier()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // Helper function
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
