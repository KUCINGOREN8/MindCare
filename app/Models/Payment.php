<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'paymentable_id',
        'paymentable_type',
        'order_id',
        'amount',
        'status',
        'payment_type',
        'transaction_id',
        'va_number',
        'payment_url',
        'payload'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payload' => 'array'
    ];

    public function paymentable()
    {
        return $this->morphTo();
    }
}
