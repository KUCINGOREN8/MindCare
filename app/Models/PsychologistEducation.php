<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologistEducation extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistEducationFactory> */
    use HasFactory;

    protected $fillable = [
        'psychologist_id',
        'degree',
        'institution',
        'year',
    ];

    public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }
}
