<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'psychologist_id',
        'status',
        'last_message_at',
        'unread_patient',
        'unread_psychologist'
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
        'unread_patient' => 'integer',
        'unread_psychologist' => 'integer'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function psychologist()
    {
        return $this->belongsTo(User::class, 'psychologist_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function isParticipant($userId)
    {
        return $this->patient_id == $userId || $this->psychologist_id == $userId;
    }

    public function updateLastMessageTime()
    {
        $this->update(['last_message_at' => now()]);
    }

    public function markAsReadForPatient()
    {
        $this->update(['unread_patient' => 0]);
    }

    public function markAsReadForPsychologist()
    {
        $this->update(['unread_psychologist' => 0]);
    }

    public function incrementUnreadForPatient()
    {
        $this->increment('unread_patient');
    }

    public function incrementUnreadForPsychologist()
    {
        $this->increment('unread_psychologist');
    }

    public function getUnreadCountForUser($userId)
    {
        if ($this->patient_id == $userId) {
            return $this->unread_patient;
        } elseif ($this->psychologist_id == $userId) {
            return $this->unread_psychologist;
        }
        return 0;
    }

    public function hasUnreadMessagesFor($userId)
    {
        return $this->getUnreadCountForUser($userId) > 0;
    }
}
