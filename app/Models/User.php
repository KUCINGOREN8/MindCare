<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'photo_url',
        'email',
        'password',
        'date_of_birth',
        'gender',
        'preferred_language',
        'agree_to_terms',
        'otp_code',
        'otp_expires_at',
        'otp_verified',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp_code'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'agree_to_terms' => 'boolean',
        'otp_verified' => 'boolean',
        'otp_expires_at' => 'datetime'
    ];

    public function generateOTP()
    {
        $this->update([
            'otp_code' => rand(100000, 999999),
            'otp_expires_at' => now()->addMinutes(10),
            'otp_verified' => false
        ]);

        return $this->otp_code;
    }

    public function isOTPValid($code)
    {
        return $this->otp_code === $code &&
            now()->lt($this->otp_expires_at);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isPsychologist()
    {
        return $this->role === 'psychologist';
    }

    public function isPatient()
    {
        return $this->role === 'patient';
    }

    public function psychologistReviews()
    {
        return $this->hasMany(PsychologistReview::class);
    }
}
