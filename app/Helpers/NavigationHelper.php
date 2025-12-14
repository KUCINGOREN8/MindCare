<?php

namespace App\Helpers;

class NavigationHelper
{
    public static function getNavItems($role)
    {
        $items = [
            'patient' => [
                [
                    'icon' => 'assets/icons/home.svg',
                    'text' => 'Dashboard',
                    'route' => 'patient.dashboard',
                    'patterns' => ['patient.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/find-user.svg',
                    'text' => 'Find Psychologist',
                    'route' => 'patient.find.psychologist',
                    'patterns' => [
                        'patient.find.psychologist',
                        'patient.psychologist.search',
                        'patient.psychologist.profile',
                        'patient.psychologist.review'
                    ]
                ],
                [
                    'icon' => 'assets/icons/book.svg',
                    'text' => 'Book Appointment',
                    'route' => 'patient.book.appointment',
                    'patterns' => ['patient.book.appointment']
                ],
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'text' => 'Appointments',
                    'route' => 'patient.appointments.index',
                    'patterns' => ['patient.appointments.*']
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'text' => 'Messages',
                    'route' => 'messages',
                    'patterns' => ['messages', 'chat.*']
                ],
            ],

            'psychologist' => [
                [
                    'icon' => 'assets/icons/home.svg',
                    'text' => 'Dashboard',
                    'route' => 'psychologist.dashboard',
                    'patterns' => ['psychologist.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/users.svg',
                    'text' => 'My Clients',
                    'route' => 'psychologist.clients',
                    'patterns' => ['psychologist.clients']
                ],
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'text' => 'Appointments',
                    'route' => 'psychologist.appointments.index',
                    'patterns' => ['psychologist.appointments.*']
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'text' => 'Messages',
                    'route' => 'messages',
                    'patterns' => ['messages', 'chat.*']
                ],
            ],

            'admin' => [
                [
                    'icon' => 'assets/icons/home.svg',
                    'text' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'patterns' => ['admin.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/users.svg',
                    'text' => 'Verify Psychologists',
                    'route' => 'admin.verify.index',
                    'patterns' => ['admin.verify.*', 'profile.*']
                ],
            ]
        ];

        return $items[$role] ?? [];
    }

    public static function isActive($patterns)
    {
        foreach ((array) $patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }
        return false;
    }
}
