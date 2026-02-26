<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RehabTreatmentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'description',
        'fields',
        'is_active',
        'order',
    ];

    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    public function entries(): HasMany
    {
        return $this->hasMany(RehabTreatmentEntry::class, 'treatment_type_id');
    }

    public function getIconHtmlAttribute(): string
    {
        return $this->icon ?? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>';
    }
}