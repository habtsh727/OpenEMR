<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorQueue extends Model
{
    protected $fillable = ['patient_id','nurse_triage_id','status','doctor_id'];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function triage() { return $this->belongsTo(NurseTriage::class, 'nurse_triage_id'); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }

    public function consultation()
{
    return $this->hasOne(DoctorConsultation::class, 'patient_id', 'patient_id');
}
}
