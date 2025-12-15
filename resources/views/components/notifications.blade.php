@props([
    'notifications' => [],
])

<div class="flex flex-col gap-4">
    <h3 class="font-semibold">{{ __('messages.notification') }}</h3>
    <div class="flex flex-col gap-4">
        @if (count($notifications) > 0)
            @foreach ($notifications as $notif)
                <x-notification-item :icon="$notif['icon']" :title="$notif['title']" :message="$notif['message']" :time="$notif['time']"
                    :type="$notif['type']" />
            @endforeach
        @else
            <p class="text-gray-500">{{ __('messages.notifunavailable') }}</p>
        @endif
    </div>
</div>
