<?php

namespace App\Models;

use App\Mail\OTPCodeMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

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
        'role',
        'status'
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

    public function sendOTPNotification()
    {
        $otpCode = $this->generateOTP();
        Mail::to($this->email)->send(new OTPCodeMail($otpCode));
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

    public function moods()
    {
        return $this->hasMany(Mood::class);
    }

    public function psychologist()
    {
        return $this->hasOne(Psychologist::class);
    }

    public function conversationsAsPatient()
    {
        return $this->hasMany(Conversation::class, 'patient_id');
    }

    public function conversationsAsPsychologist()
    {
        return $this->hasMany(Conversation::class, 'psychologist_id');
    }

    public function conversations()
    {
        if ($this->isPatient()) {
            return $this->conversationsAsPatient();
        } elseif ($this->isPsychologist()) {
            return $this->conversationsAsPsychologist();
        }
        return $this->hasMany(Conversation::class, 'id')->whereNull('id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function getDefaultAvatarUrl()
    {
        $gender = $this->gender ?? 'male';
        
        $avatarFiles = [
            'female' => 'user_female.svg',
            'male' => 'user_male.svg',
            'other' => 'user_other.svg',
        ];
        
        $filename = $avatarFiles[$gender] ?? 'user_male.svg';
        $path = "assets/icons/{$filename}";
        
        // fallback
        if (!file_exists(public_path($path))) {
            $path = 'assets/icons/user_male.svg';
        }
        
        return asset($path);
    }

    public function getPhotoUrlAttribute($value)
    {
        // Photo profile is customized
        if ($value) {
            if (filter_var($value, FILTER_VALIDATE_URL)) {
                return $value;
            }
            
            return asset('storage/' . $value);
        }
        
        return $this->getDefaultAvatarUrl();
    }

    public function getPhotoPathAttribute()
    {
        if (!$this->attributes['photo_url'] ?? null) {
            return null;
        }
        
        $url = $this->attributes['photo_url'];
        
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $parsedUrl = parse_url($url);
            $path = $parsedUrl['path'] ?? '';
            
            return ltrim(str_replace('/storage/', '', $path), '/');
        }
        
        return $url;
    }

    public function hasCustomPhoto()
    {
        return !empty($this->attributes['photo_url'] ?? null);
    }
}
