<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologistSchedule extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistScheduleFactory> */
    use HasFactory;

    protected $fillable = [
        'psychologist_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }
}
