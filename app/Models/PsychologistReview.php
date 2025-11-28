<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologistReview extends Model
{
    /** @use HasFactory<\Database\Factories\PsychologistReviewFactory> */
    use HasFactory;

    protected $fillable = [
      'psychologist_id',
      'user_id',
      'rating',
      'review'
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function psychologist() {
      return $this->hasOne(Psychologist::class);
    }
}
