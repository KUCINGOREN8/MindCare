<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

     protected $fillable = [
        'user_id',
        'psychologist_id',
        'with',
        'job_title',
        'date',
        'time',
        'status',
        'notes',
        'reschedule_date',
        'reschedule_time',
        'reschedule_reason',
    ];

        public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }

}
