<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ReferralAttachment extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'referral_id',
        'attachment_type',
        'file_path',
        'file_name',
    ];

    /* =========================
     | Relationships
     ========================= */

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
}
