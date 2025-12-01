<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologistExperience extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistExperienceFactory> */
    use HasFactory;

    protected $fillable = [
        'psychologist_id',
        'position',
        'organization',
        'start_year',
        'end_year',
    ];

    public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }
}
