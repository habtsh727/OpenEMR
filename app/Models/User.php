<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable // implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
public function rehabOrders()
{
    return $this->hasMany(RehabOrder::class, 'doctor_id');
}
    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn(string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }
    public function employee()
    {
        return $this->hasOne(\App\Models\Employee::class);
    }
    public function labOrdersPaid()
    {
        return $this->hasMany(LabOrder::class, 'paid_by');
    }

    public function labSamplesCollected()
    {
        return $this->hasMany(LabSample::class, 'collected_by');
    }
    public function referralsMade()
    {
        return $this->hasMany(Referral::class, 'referring_doctor_id');
    }
    public function processedPayments()
    {
        return $this->hasMany(CardPayment::class, 'processed_by');
    }
    public function labPayments()
    {
        return $this->hasMany(LabOrder::class, 'paid_by');
    }
}
