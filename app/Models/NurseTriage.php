<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NurseTriage extends Model
{
    protected $fillable = [
        'patient_id',
        'bp_systolic',
        'bp_diastolic',
        'temperature',
        'pulse',
        'spo2',
        'priority',
        'processed_by',
    ];
}
