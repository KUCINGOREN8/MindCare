<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Psychologist extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'short_bio',
        'about_me',
        'languages',
        'title',
        'specialization',
        'license_number',
        'years_experience',
        'consultation_fee'
    ];

    protected $casts = [
        'languages' => 'array',
        'consultation_fee' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
