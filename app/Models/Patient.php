<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
