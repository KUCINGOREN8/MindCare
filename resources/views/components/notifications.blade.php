@props([
    'notifications' => [],
])

<div class="flex flex-col gap-4">
    <h3 class="font-semibold">Notifications</h3>
    <div class="flex flex-col gap-4">
        @foreach ($notifications as $notif)
            <x-notification-item 
                :icon="$notif['icon']"
                :title="$notif['title']"
                :message="$notif['message']"
                :time="$notif['time']"
                :type="$notif['type']"
            />
        @endforeach
    </div>
</div>