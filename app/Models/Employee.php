<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'employee_code',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'status' => 'string',
    ];

    // Relationships
  
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->user ? $this->user->name : 'N/A';
    }

    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : 'N/A';
    }
}
