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
                    'text' => __('nav.dashboard'),
                    'route' => 'patient.dashboard',
                    'patterns' => ['patient.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/find-user.svg',
                    'text' => __('nav.find_psychologist'),
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
                    'text' => __('nav.book_appointment'),
                    'route' => 'patient.book.appointment',
                    'patterns' => ['patient.book.appointment', 'patient.payment.*']
                ],
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'text' => __('nav.appointments'),
                    'route' => 'patient.appointments.index',
                    'patterns' => ['patient.appointments.*']
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'text' => __('nav.messages'),
                    'route' => 'messages',
                    'patterns' => ['messages', 'chat.*']
                ],
            ],

            'psychologist' => [
                [
                    'icon' => 'assets/icons/home.svg',
                    'text' => __('nav.dashboard'),
                    'route' => 'psychologist.dashboard',
                    'patterns' => ['psychologist.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/users.svg',
                    'text' => __('nav.my_clients'),
                    'route' => 'psychologist.clients',
                    'patterns' => ['psychologist.clients', 'psychologist.clients.*']
                ],
                [
                    'icon' => 'assets/icons/calendar.svg',
                    'text' => __('nav.appointments'),
                    'route' => 'psychologist.appointments.index',
                    'patterns' => ['psychologist.appointments.*']
                ],
                [
                    'icon' => 'assets/icons/messages.svg',
                    'text' => __('nav.messages'),
                    'route' => 'messages',
                    'patterns' => ['messages', 'chat.*']
                ],
            ],

            'admin' => [
                [
                    'icon' => 'assets/icons/home.svg',
                    'text' => __('nav.dashboard'),
                    'route' => 'admin.dashboard',
                    'patterns' => ['admin.dashboard', 'profile.*']
                ],
                [
                    'icon' => 'assets/icons/users.svg',
                    'text' => __('nav.verify_psychologists'),
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
