<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PsychologistClientNote extends Model
{
    use HasFactory;

    protected $table = 'psychologist_client_notes';

    protected $fillable = [
        'psychologist_id',
        'client_id',
        'general_notes'
    ];

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }
}
