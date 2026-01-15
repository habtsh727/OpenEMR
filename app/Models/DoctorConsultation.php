<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorConsultation extends Model
{
    protected $fillable = [
        'patient_id','doctor_id','history','current_complaints','physical_exam','assessment_options','assessment_notes','lab_orders','imaging_orders','medications','diagnosis','plan','disposition'];
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor() { return $this->belongsTo(User::class, 'doctor_id'); }
}
