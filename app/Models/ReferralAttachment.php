<?php

namespace App\Models;

use App\Enums\AttachmentType;
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

     protected $casts = [
        'attachment_type' => AttachmentType::class,
    ];
     
    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
    /* =========================
     | Relationships
     ========================= */

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }
}
