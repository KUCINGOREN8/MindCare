<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = [];

        $appointmentReminders = $this->getAppointmentReminders($user);
        if ($appointmentReminders) {
            $notifications = array_merge($notifications, $appointmentReminders);
        }

        $messageReminders = $this->getMessageReminders($user);
        if ($messageReminders) {
            $notifications = array_merge($notifications, $messageReminders);
        }

        if (empty($notifications)) {
            return $this->getEmptyNotifications($user);
        }

        usort($notifications, function ($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });

        $notifications = array_slice($notifications, 0, 4);
        return $notifications;
    }

    private function getAppointmentReminders(User $user)
    {
        $notifications = [];
        $now = Carbon::now();

        if ($user->role === 'patient') {
            $appointment = Appointment::with('psychologist.user')
                ->where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->where(function ($query) use ($now) {
                    $query->whereDate('date', '>', $now->toDateString())
                        ->orWhere(function ($q) use ($now) {
                            $q->whereDate('date', '=', $now->toDateString())
                                ->whereTime('start_time', '>', $now->format('H:i:s'));
                        });
                })
                ->orderBy('date')
                ->orderBy('start_time')
                ->first();

            if ($appointment && $appointment->psychologist && $appointment->psychologist->user) {
                $startDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time
                );

                $psychologistName = $appointment->psychologist->user->full_name;

                if ($startDateTime->isToday()) {
                    $hoursToStart = $now->diffInHours($startDateTime);
                    $minutesToStart = $now->diffInMinutes($startDateTime);

                    if ($hoursToStart > 0) {
                        $message = "Your session with Dr. {$psychologistName} starts in {$hoursToStart} " . ($hoursToStart == 1 ? 'hour' : 'hours');
                    } else {
                        $message = "Your session with Dr. {$psychologistName} starts in {$minutesToStart} " . ($minutesToStart == 1 ? 'minute' : 'minutes');
                    }
                } elseif ($startDateTime->isTomorrow()) {
                    $time = Carbon::parse($appointment->start_time)->format('g:i A');
                    $message = "Your session with Dr. {$psychologistName} is tomorrow at {$time}";
                } else {
                    $date = $appointment->date->format('M j');
                    $time = Carbon::parse($appointment->start_time)->format('g:i A');
                    $message = "Your session with Dr. {$psychologistName} is on {$date} at {$time}";
                }

                $notifications[] = [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => 'Session Reminder',
                    'message' => $message,
                    'time' => $this->formatTimeAgo($startDateTime),
                    'type' => 'reminder',
                    'timestamp' => $startDateTime->toDateTimeString(),
                    'appointment_id' => $appointment->id,
                ];
            }
        }
        elseif ($user->role === 'psychologist' && $user->psychologist) {
            $appointment = Appointment::with('user')
                ->where('psychologist_id', $user->psychologist->id)
                ->where('status', 'confirmed')
                ->where(function ($query) use ($now) {
                    $query->whereDate('date', '>', $now->toDateString())
                        ->orWhere(function ($q) use ($now) {
                            $q->whereDate('date', '=', $now->toDateString())
                                ->whereTime('start_time', '>', $now->format('H:i:s'));
                        });
                })
                ->orderBy('date')
                ->orderBy('start_time')
                ->first();

            if ($appointment && $appointment->user) {
                $startDateTime = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time
                );

                $patientName = $appointment->user->full_name;

                if ($startDateTime->isToday()) {
                    $hoursToStart = $now->diffInHours($startDateTime);
                    $minutesToStart = $now->diffInMinutes($startDateTime);

                    if ($hoursToStart > 0) {
                        $message = "Your session with {$patientName} starts in {$hoursToStart} " . ($hoursToStart == 1 ? 'hour' : 'hours');
                    } else {
                        $message = "Your session with {$patientName} starts in {$minutesToStart} " . ($minutesToStart == 1 ? 'minute' : 'minutes');
                    }
                } elseif ($startDateTime->isTomorrow()) {
                    $time = Carbon::parse($appointment->start_time)->format('g:i A');
                    $message = "Your session with {$patientName} is tomorrow at {$time}";
                } else {
                    $date = $appointment->date->format('M j');
                    $time = Carbon::parse($appointment->start_time)->format('g:i A');
                    $message = "Your session with {$patientName} is on {$date} at {$time}";
                }

                $notifications[] = [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => 'Session Reminder',
                    'message' => $message,
                    'time' => $this->formatTimeAgo($startDateTime),
                    'type' => 'reminder',
                    'timestamp' => $startDateTime->toDateTimeString(),
                    'appointment_id' => $appointment->id,
                ];
            }
        }

        return $notifications;
    }

    private function getMessageReminders(User $user)
    {
        $now = Carbon::now();
        $conversation = Conversation::where(function($query) use ($user) {
                if ($user->role === 'patient') {
                    $query->where('patient_id', $user->id)
                        ->where('unread_patient', '>', 0);
                }
                elseif ($user->role === 'psychologist') {
                    $query->where('psychologist_id', $user->id)
                        ->where('unread_psychologist', '>', 0);
                }
            })
            ->whereHas('latestMessage')
            ->with(['latestMessage', 'psychologist', 'patient'])
            ->orderBy('last_message_at', 'desc')
            ->first();

        if (!$conversation || !$conversation->latestMessage) {
            return [];
        }

        if (($user->role === 'patient' && (!$conversation->psychologist)) ||
            ($user->role === 'psychologist' && (!$conversation->patient))) {
            return [];
        }

        $latestMessage = $conversation->latestMessage;

        if ($user->role === 'patient') {
            $senderName = $conversation->psychologist->full_name ?? 'Psychologist';
            $prefix = "Dr. ";
        } else {
            $senderName = $conversation->patient->full_name ?? 'Patient';
            $prefix = "";
        }

        $messageText = strlen($latestMessage->message) > 50
            ? substr($latestMessage->message, 0, 50) . '...'
            : $latestMessage->message;

        if ($latestMessage->hasAttachment()) {
            $attachmentIcon = $latestMessage->getAttachmentIcon();
            $messageText = "{$attachmentIcon} Sent an attachment";
        }

        return [
            [
                'icon' => 'assets/icons/messages.svg',
                'title' => 'New Message',
                'message' => "{$prefix}{$senderName}: {$messageText}",
                'time' => $this->formatTimeAgo($latestMessage->created_at),
                'type' => 'message',
                'timestamp' => $latestMessage->created_at->toDateTimeString(),
                'conversation_id' => $conversation->id,
            ]
        ];
    }

    private function formatTimeAgo(Carbon $time)
    {
        $now = Carbon::now();
        $diffInMinutes = $now->diffInMinutes($time);

        if ($diffInMinutes < 1) {
            return 'Just now';
        } elseif ($diffInMinutes < 60) {
            return "{$diffInMinutes} minutes ago";
        } elseif ($diffInMinutes < 1440) {
            $hours = floor($diffInMinutes / 60);
            return "{$hours} " . ($hours == 1 ? 'hour' : 'hours') . " ago";
        } else {
            $days = floor($diffInMinutes / 1440);
            return "{$days} " . ($days == 1 ? 'day' : 'days') . " ago";
        }
    }

    private function getEmptyNotifications(User $user)
    {
        if ($user->role === 'patient') {
            return [
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => 'No Upcoming Sessions',
                    'message' => 'You don\'t have any upcoming sessions scheduled',
                    'time' => 'Just now',
                    'type' => 'reminder',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'title' => 'No New Messages',
                    'message' => 'Check back later for updates from your psychologist',
                    'time' => 'Today',
                    'type' => 'message',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/check.svg',
                    'title' => 'Track Your Mood',
                    'message' => 'Log your daily mood to see insights and progress',
                    'time' => 'This week',
                    'type' => 'achievement',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/tips.svg',
                    'title' => 'Daily Wellness Tip',
                    'message' => 'Try a 5-minute meditation to start your day',
                    'time' => '1 day ago',
                    'type' => 'tip',
                    'timestamp' => Carbon::now()->subDay()->toDateTimeString(),
                ],
            ];
        }
        elseif ($user->role === 'psychologist') {
            return [
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => 'No Upcoming Sessions',
                    'message' => 'You don\'t have any upcoming sessions scheduled',
                    'time' => 'Just now',
                    'type' => 'reminder',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'title' => 'No New Messages',
                    'message' => 'Check back later for messages from patients',
                    'time' => 'Today',
                    'type' => 'message',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/check.svg',
                    'title' => 'Session Notes',
                    'message' => 'Review and update your session notes',
                    'time' => '',
                    'type' => 'achievement',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/tips.svg',
                    'title' => 'Patient Progress',
                    'message' => 'Track your patients\' wellbeing progress',
                    'time' => '',
                    'type' => 'tip',
                    'timestamp' => Carbon::now()->subDay()->toDateTimeString(),
                ],
            ];
        }

        return [];
    }
}
