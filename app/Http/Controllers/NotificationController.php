<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Conversation;
use App\Models\Mood;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    protected $openAIService;

    protected function getOpenAIService()
    {
        return app(\App\Services\OpenAIService::class);
    }

    public function getNotifications()
    {
        $user = Auth::user();
        $notifications = [];

        if ($user->role === 'admin') {
            $adminNotifications = $this->getAdminNotifications($user);
            if ($adminNotifications) {
                $notifications = array_merge($notifications, $adminNotifications);
            }
        } else {
            $appointmentReminders = $this->getAppointmentReminders($user);
            if ($appointmentReminders) {
                $notifications = array_merge($notifications, $appointmentReminders);
            }

            $messageReminders = $this->getMessageReminders($user);
            if ($messageReminders) {
                $notifications = array_merge($notifications, $messageReminders);
            }

            $moodAchievements = $this->getMoodAchievements($user);
            if ($moodAchievements) {
                $notifications = array_merge($notifications, $moodAchievements);
            }

            $dailyTip = $this->getDailyWellnessTip($user);
            if ($dailyTip) {
                $notifications = array_merge($notifications, [$dailyTip]);
            }
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
                $this->processAppointment($appointment, $notifications, $now, true);
            }
        } elseif ($user->role === 'psychologist' && $user->psychologist) {
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
                $this->processAppointment($appointment, $notifications, $now, false);
            }
        }

        return $notifications;
    }

    private function processAppointment($appointment, &$notifications, $now, $isPatient)
    {
        $startDateTime = Carbon::createFromFormat(
            'Y-m-d H:i:s',
            $appointment->date->format('Y-m-d') . ' ' . $appointment->start_time
        );

        if ($isPatient) {
            $nameDisplay = __('notifications.dr_prefix') . $appointment->psychologist->user->full_name;
        } else {
            $nameDisplay = $appointment->user->full_name;
        }

        if ($startDateTime->isToday()) {
            $minutesToStart = $now->diffInMinutes($startDateTime);

            if ($minutesToStart >= 60) {
                $hours = round($minutesToStart / 60, 1);
                $message = __('notifications.session_starts_hours', ['name' => $nameDisplay, 'count' => $hours]);
            } else {
                $message = __('notifications.session_starts_minutes', ['name' => $nameDisplay, 'count' => $minutesToStart]);
            }
        } elseif ($startDateTime->isTomorrow()) {
            $time = Carbon::parse($appointment->start_time)->format('H:i');
            $message = __('notifications.session_tomorrow', ['name' => $nameDisplay, 'time' => $time]);
        } else {
            $date = $appointment->date->translatedFormat('d M');
            $time = Carbon::parse($appointment->start_time)->format('H:i');
            $message = __('notifications.session_date', ['name' => $nameDisplay, 'date' => $date, 'time' => $time]);
        }

        $notifications[] = [
            'icon' => 'assets/icons/calendar.svg',
            'title' => __('notifications.session_reminder_title'),
            'message' => $message,
            'time' => number_format((float) $this->formatTimeAgo($startDateTime), 1),
            'type' => 'reminder',
            'timestamp' => $startDateTime->toDateTimeString(),
            'appointment_id' => $appointment->id,
        ];
    }

    private function getMessageReminders(User $user)
    {
        $conversation = Conversation::where(function ($query) use ($user) {
            if ($user->role === 'patient') {
                $query->where('patient_id', $user->id)
                    ->where('unread_patient', '>', 0);
            } elseif ($user->role === 'psychologist') {
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

        $latestMessage = $conversation->latestMessage;

        if ($latestMessage->sender_id === $user->id) {
            return [];
        }

        if (
            ($user->role === 'patient' && (!$conversation->psychologist)) ||
            ($user->role === 'psychologist' && (!$conversation->patient))
        ) {
            return [];
        }

        if ($user->role === 'patient') {
            $senderName = $conversation->psychologist->full_name ?? __('notifications.default_psychologist');
            $prefix = __('notifications.dr_prefix');
        } else {
            $senderName = $conversation->patient->full_name ?? __('notifications.default_patient');
            $prefix = "";
        }

        $messageText = strlen($latestMessage->message) > 50
            ? substr($latestMessage->message, 0, 50) . '...'
            : $latestMessage->message;

        if ($latestMessage->hasAttachment()) {
            $attachmentIcon = $latestMessage->getAttachmentIcon();
            $messageText = __('notifications.sent_attachment', ['icon' => $attachmentIcon]);
        }

        return [
            [
                'icon' => 'assets/icons/messages.svg',
                'title' => __('notifications.new_message_title'),
                'message' => "{$prefix}{$senderName}: {$messageText}",
                'time' => $this->formatTimeAgo($latestMessage->created_at),
                'type' => 'message',
                'timestamp' => $latestMessage->created_at->toDateTimeString(),
                'conversation_id' => $conversation->id,
            ]
        ];
    }

    private function getMoodAchievements(User $user)
    {
        if ($user->role !== 'patient') {
            return [];
        }

        $service = $this->getOpenAIService();
        $streakDays = $this->calculateMoodStreak($user->id);

        if ($streakDays <= 1) {
            return [];
        }

        $achievementMessage = $service->generateAchievementMessage($streakDays);

        return [
            [
                'icon' => 'assets/icons/check.svg',
                'title' => __('notifications.achievement_title'),
                'message' => $achievementMessage,
                'time' => __('notifications.today'),
                'type' => 'achievement',
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]
        ];
    }

    private function getDailyWellnessTip(User $user)
    {
        if ($user->role !== 'patient') {
            return null;
        }

        $service = $this->getOpenAIService();

        $todayMood = Mood::where('user_id', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        $moodType = $todayMood->mood ?? null;

        $streakDays = $this->calculateMoodStreak($user->id);

        $tip = $service->generateDailyTip($moodType, $streakDays);

        if (!$tip) {
            return null;
        }

        return [
            'icon' => 'assets/icons/tips.svg',
            'title' => __('notifications.wellness_tip_title'),
            'message' => $tip,
            'time' => __('notifications.today'),
            'type' => 'tip',
            'timestamp' => Carbon::now()->toDateTimeString(),
        ];
    }

     private function calculateMoodStreak($userId): int
    {
        $today = Carbon::today();
        $streak = 0;

        for ($i = 0; $i < 365; $i++) {
            $checkDate = $today->copy()->subDays($i);

            $hasMood = Mood::where('user_id', $userId)
                ->whereDate('created_at', $checkDate)
                ->exists();

            if ($hasMood) {
                $streak++;
            } else {
                if ($i > 0) {
                    break;
                }
            }
        }

        return $streak;
    }

    private function getAdminNotifications(User $user)
    {
        if ($user->role !== 'admin') {
            return [];
        }

        $notifications = [];

        $pendingPsychologists = User::where('role', 'psychologist')
            ->where('status', 'pending')
            ->where('otp_verified', true)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        foreach ($pendingPsychologists as $psychologist) {
            $timeAgo = $this->formatTimeAgo($psychologist->created_at);

            $notifications[] = [
                'icon' => 'assets/icons/calendar.svg',
                'title' => __('notifications.new_psychologist_title'),
                'message' => __('notifications.waiting_verification', ['name' => $psychologist->full_name]),
                'time' => $timeAgo,
                'type' => 'reminder',
                'timestamp' => $psychologist->created_at->toDateTimeString(),
                'user_id' => $psychologist->id,
                'psychologist_id' => $psychologist->psychologist->id ?? null,
            ];
        }

        return $notifications;
    }

    private function formatTimeAgo(Carbon $time)
    {
        $now = Carbon::now();

        $diffInMinutes = $now->diffInMinutes($time, false);

        if ($diffInMinutes > 0) {
            if ($diffInMinutes < 60) {
                return __('notifications.session_starts_minutes', [
                    'count' => $diffInMinutes
                ]);
            }

            if ($diffInMinutes < 1440) {
                $hours = (int) ceil($diffInMinutes / 60);
                return __('notifications.session_starts_hours', [
                    'count' => $hours
                ]);
            }

            $days = (int) ceil($diffInMinutes / 1440);
            return "in {$days} days";
        }

        $diffInMinutes = abs($diffInMinutes);

        if ($diffInMinutes < 1) {
            return __('notifications.just_now');
        }

        if ($diffInMinutes < 60) {
            return __('notifications.minutes_ago', [
                'count' => $diffInMinutes
            ]);
        }

        if ($diffInMinutes < 1440) {
            $hours = (int) floor($diffInMinutes / 60);
            return __('notifications.hours_ago', [
                'count' => $hours
            ]);
        }

        $days = (int) floor($diffInMinutes / 1440);
        return __('notifications.days_ago', [
            'count' => $days
        ]);
    }

    private function getEmptyNotifications(User $user)
    {
        if ($user->role === 'patient') {
            return [
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => __('notifications.no_upcoming_title'),
                    'message' => __('notifications.no_upcoming_body'),
                    'time' => __('notifications.just_now'),
                    'type' => 'reminder',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'title' => __('notifications.no_msg_title'),
                    'message' => __('notifications.no_msg_patient_body'),
                    'time' => __('notifications.today'),
                    'type' => 'message',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/check.svg',
                    'title' => __('notifications.track_mood_title'),
                    'message' => __('notifications.track_mood_body'),
                    'time' => __('notifications.this_week'),
                    'type' => 'achievement',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/tips.svg',
                    'title' => __('notifications.wellness_tip_title'),
                    'message' => __('notifications.wellness_tip_body'),
                    'time' => __('notifications.one_day_ago'),
                    'type' => 'tip',
                    'timestamp' => Carbon::now()->subDay()->toDateTimeString(),
                ],
            ];
        } elseif ($user->role === 'psychologist') {
            return [
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => __('notifications.no_upcoming_title'),
                    'message' => __('notifications.no_upcoming_body'),
                    'time' => __('notifications.just_now'),
                    'type' => 'reminder',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'title' => __('notifications.no_msg_title'),
                    'message' => __('notifications.no_msg_psych_body'),
                    'time' => __('notifications.today'),
                    'type' => 'message',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/check.svg',
                    'title' => __('notifications.session_notes_title'),
                    'message' => __('notifications.session_notes_body'),
                    'time' => '',
                    'type' => 'achievement',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/tips.svg',
                    'title' => __('notifications.patient_progress_title'),
                    'message' => __('notifications.patient_progress_body'),
                    'time' => '',
                    'type' => 'tip',
                    'timestamp' => Carbon::now()->subDay()->toDateTimeString(),
                ],
            ];
        } elseif ($user->role === 'admin') {
            return [
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'title' => __('notifications.all_verified_title'),
                    'message' => __('notifications.all_verified_body'),
                    'time' => __('notifications.just_now'),
                    'type' => 'reminder',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
                [
                    'icon' => 'assets/icons/check.svg',
                    'title' => __('notifications.system_monitoring_title'),
                    'message' => __('notifications.system_monitoring_body'),
                    'time' => __('notifications.today'),
                    'type' => 'achievement',
                    'timestamp' => Carbon::now()->toDateTimeString(),
                ],
            ];
        }

        return [];
    }
}
