@props([
    'name',
    'specialization',
    'time',
    'joinRoute' => '#',
    'rescheduleRoute' => '#',
    'isSessionAvailable' => false
])

<div class="p-4 flex flex-col gap-4 rounded-md border border-grey-border">
    <div class="flex flex-col gap-0">
        <h4>{{ $name }}</h4>
        <p class="text-caption">{{ $specialization }}</p>
    </div>

    <div class="flex items-center gap-2">
        @php
            $icon = file_get_contents(public_path('image/icons/calendar.svg'));
            echo str_replace('<svg ', '<svg class="text-caption" fill="currentColor" ', $icon);
        @endphp
        <p class="text-caption">{{ $time }}</p>
    </div>

    <div class="flex gap-2">
        <x-rounded-button text="Join Session" active="{{ $isSessionAvailable }}" route="{{ $joinRoute }}" />
        <x-rounded-button text="Reschedule" secondary="true" route="{{ $rescheduleRoute }}" />
    </div>
</div>
