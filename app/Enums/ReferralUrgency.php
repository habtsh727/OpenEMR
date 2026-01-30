<?php

namespace App\Enums;

enum ReferralUrgency: string
{
    case ROUTINE = 'routine';
    case URGENT = 'urgent';
    case EMERGENCY = 'emergency';
}