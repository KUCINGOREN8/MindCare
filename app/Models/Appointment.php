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
        'start_time',
        'end_time',
        'consultation_fee',
        'status',
        'notes',
        'reschedule_date',
        'reschedule_time',
        'reschedule_reason',
    ];

    protected $casts = [
        'date' => 'date',
        'consultation_fee' => 'decimal:2'
    ];

    public function psychologist()
    {
        return $this->belongsTo(Psychologist::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
    {
        return $this->morphOne(Payment::class, 'paymentable');
    }

    public function getPaymentStatusAttribute()
    {
        return $this->payment?->status ?? 'unpaid';
    }

    public function getIsPaidAttribute()
    {
        return $this->payment?->status === 'success';
    }
}
