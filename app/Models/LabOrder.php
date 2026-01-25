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
}
