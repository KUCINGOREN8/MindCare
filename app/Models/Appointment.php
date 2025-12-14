<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    public function user()
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

    public function getStartDateTimeAttribute()
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->date->format('Y-m-d') . ' ' . $this->start_time,
            config('app.timezone', 'Asia/Jakarta')
        );
    }

    public function getEndDateTimeAttribute()
    {
        return Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $this->date->format('Y-m-d') . ' ' . $this->end_time,
            config('app.timezone', 'Asia/Jakarta')
        );
    }

    public function getIsUpcomingAttribute()
    {
        return $this->end_date_time > now();
    }

    public function getIsPastAttribute()
    {
        return $this->end_date_time < now();
    }

    public function getIsOngoingAttribute()
    {
        $now = now();
        return $now >= $this->start_date_time && $now <= $this->end_date_time;
    }

    public function getIsSessionAvailableAttribute()
    {
        if ($this->status !== 'confirmed') {
            return false;
        }

        $sessionStart = $this->start_date_time;
        $sessionEnd = $this->end_date_time;
        $now = now()->timezone(config('app.timezone'));
        $availableFrom = $sessionStart->copy()->subMinutes(30);

        return $now >= $availableFrom && $now <= $sessionEnd;
    }

    public function getCanBeReviewedAttribute()
    {
        return $this->status === 'completed' && 
            auth()->check() && 
            auth()->id() === $this->user_id;
    }

    public function getHasBeenReviewedAttribute()
    {
        if (!$this->can_be_reviewed) {
            return false;
        }
        
        return $this->psychologist->reviews()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
