<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardFee extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'is_active',
    ];
}
