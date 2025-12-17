<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorConsultation extends Model
{
    protected $fillable = ['patient_id','doctor_id','history','physical_exam','diagnosis','plan','disposition'];
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
}
