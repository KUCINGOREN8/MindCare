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
        'is_reschedule_pending',
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

    public function isRescheduled()
    {
        return $this->reschedule_date && $this->reschedule_time;
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
        $startDateTime = $this->start_date_time;
        if (!$startDateTime) return false;

        return $startDateTime->gt(now());
    }

    public function getIsPastAttribute()
    {
        $endDateTime = $this->end_date_time;
        if (!$endDateTime) return false;

        return $endDateTime->lt(now());
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

    public function markAsExpired()
    {
        $this->update(['status' => 'expired']);
        Payment::where('paymentable_id', $this->id)
            ->where('paymentable_type', self::class)
            ->update(['status' => 'expired']);
    }

    public function markAsCompleted()
    {
        if ($this->is_past && $this->status === 'confirmed') {
            $this->update(['status' => 'completed']);
        }
    }

    public function scopePast($query)
    {
        return $query->where(function($q) {
            $q->where(function($q2) {
                $q2->whereDate('date', '<', now()->format('Y-m-d'));
            })->orWhere(function($q2) {
                $q2->whereDate('date', '=', now()->format('Y-m-d'))
                   ->whereTime('end_time', '<', now()->format('H:i:s'));
            });
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where(function($q) {
            $q->where(function($q2) {
                $q2->whereDate('date', '>', now()->format('Y-m-d'));
            })->orWhere(function($q2) {
                $q2->whereDate('date', '=', now()->format('Y-m-d'))
                   ->whereTime('end_time', '>', now()->format('H:i:s'));
            });
        });
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending_payment', 'pending', 'confirmed']);
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

    public function getCanRescheduleAttribute()
    {
        return $this->status === 'confirmed' &&
           $this->is_upcoming &&
           !$this->is_ongoing &&
           auth()->check() &&
           auth()->id() === $this->user_id;
    }

    public function getFormattedDateTimeAttribute()
    {
        return Carbon::parse($this->date)->format('d M Y') .
            ' at ' .
            Carbon::parse($this->start_time)->format('H:i') .
            ' - ' .
            Carbon::parse($this->end_time)->format('H:i');
    }
}
