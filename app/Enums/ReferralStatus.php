<?php

namespace App\Enums;
enum ReferralStatus: string
{
    case CREATED = 'created';
    case SENT = 'sent';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
}