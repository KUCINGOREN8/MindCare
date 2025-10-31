@props([
    'name',
    'specialization',
    'time',
    'joinRoute' => '#',
    'rescheduleRoute' => '#',
])

<div class="p-4 flex flex-col gap-3 rounded-md border border-grey-border">
    <h4>{{ $name }}</h4>
    <p class="text-caption">{{ $specialization }}</p>

    <div class="flex items-center gap-2">
        @php
            $icon = file_get_contents(public_path('image/icons/calendar.svg'));
            echo str_replace('<svg ', '<svg class="text-caption" fill="currentColor" ', $icon);
        @endphp
        <p class="text-caption">{{ $time }}</p>
    </div>

    <div class="flex gap-2">
        <x-rounded-button text="Join Session" active="true" route="{{ $joinRoute }}" />
        <x-rounded-button text="Reschedule" active="false" route="{{ $rescheduleRoute }}" />
    </div>
</div>
