<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'card_number',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'phone_number1',
        'phone_number2',
        'emergency_person',
        'emergency_contact',
        'emergency_person_relationship',
        'region',
        'region_zone',
        'region_woreda',
        'mother_name',
        'last_visit_at',
        'created_by',

    ];

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

    public function cardPayments()
    {
        return $this->hasMany(CardPayment::class)->orderBy('payment_date', 'desc');
    }

    public function servicePayments()
    {

        return $this->hasMany(ServicePayment::class)->orderBy('payment_date', 'desc');
    }

    /**
     * Get all of the encounters for the Patient
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function encounters(): HasMany
    {
        return $this->hasMany(Encounter::class);
    }

    public function latestUnpaidCardPayment()
    {
        return $this->hasMany(CardPayment::class)
            ->where('is_paid', false)
            ->orderBy('payment_date', 'desc')
            ->first(); // returns a single CardPayment model or null
    }

    public function vital()
    {
        return $this->hasMany(NurseTriage::class);
    }


    public function doctorQueues()
    {
        return $this->hasMany(DoctorQueue::class);
    }
    public function consultations()
    {
        return $this->hasMany(DoctorConsultation::class);
    }

    // Latest vital record
    public function latestVital()
    {
        return $this->hasOne(NurseTriage::class)->latestOfMany();
    }
}
