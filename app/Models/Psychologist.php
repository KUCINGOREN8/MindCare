<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Psychologist extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'short_bio',
        'photo_url',
        'gender',
        'languages', 
        'title',
        'specialization',
        'license_number',
        'years_experience',
        'consultation_fee',
        'email',
        'password',
        'preferred_language',
        'agree_to_terms',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'languages' => 'array',
        'agree_to_terms' => 'boolean',
    ];

    public function educations()
    {
        return $this->hasMany(PsychologistEducation::class);
    }

    public function experiences()
    {
        return $this->hasMany(PsychologistExperience::class);
    }

    public function schedules()
    {
        return $this->hasMany(PsychologistSchedule::class);
    }

    public function reviews()
    {
        return $this->hasMany(PsychologistReview::class);
    }
}
