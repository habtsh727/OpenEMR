<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Referral extends Model
{
    //
    
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'encounter_id',
        'referring_doctor_id',
        'facility_name',
        'facility_type',
        'facility_location',
        'urgency',
        'reason',
        'provisional_diagnosis',
        'clinical_summary',
        'status',
        'referred_at',
    ];

    protected $casts = [
        'referred_at' => 'datetime',
    ];

    /* =========================
     | Relationships
     ========================= */

    // Patient being referred
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Encounter where referral was created
    public function encounter()
    {
        return $this->belongsTo(Encounter::class);
    }

    // Doctor who made the referral
    public function referringDoctor()
    {
        return $this->belongsTo(User::class, 'referring_doctor_id');
    }

    // Attached lab/imaging/reports
    public function attachments()
    {
        return $this->hasMany(ReferralAttachment::class);
    }

}
