@props([
    'icon',            {{-- path icon SVG --}}
    'title',           {{-- judul notifikasi --}}
    'message',         {{-- isi notifikasi --}}
    'time',            {{-- waktu --}}
    'type' => 'reminder',  {{-- reminder | message | tip | achievement --}}
])

@php
    // Mapping warna berdasarkan tipe notifikasi
    $typeMap = [
        'reminder' => ['border' => 'border-notification-blue', 'bg' => 'bg-notification-blue-bg', 'text' => 'text-notification-blue'],
        'message' => ['border' => 'border-notification-purple', 'bg' => 'bg-notification-purple-bg', 'text' => 'text-notification-purple'],
        'tip' => ['border' => 'border-notification-yellow', 'bg' => 'bg-notification-yellow-bg', 'text' => 'text-notification-yellow'],
        'achievement' => ['border' => 'border-notification-green', 'bg' => 'bg-notification-green-bg', 'text' => 'text-notification-green'],
    ];

    $selected = $typeMap[$type] ?? $typeMap['reminder'];
@endphp

<div class="flex flex-wrap gap-3 border-l-4 {{ $selected['border'] }} {{ $selected['bg'] }} p-4 rounded-md items-start hover:brightness-95 transition-all duration-200">
    {{-- ICON --}}
    <div class="py-1">
        {!! str_replace(
            '<svg ',
            '<svg class="' . $selected['text'] . '" ',
            file_get_contents(public_path($icon))
        ) !!}
    </div>

    {{-- TEXT CONTENT --}}
    <div class="flex flex-col flex-1 min-w-0">
        <h3 class="font-semibold">{{ $title }}</h3>
        <p class="text-caption-dark text-sm break-words whitespace-normal leading-snug">
            {{ $message }}
        </p>
        <p class="text-caption text-xs mt-1">{{ $time }}</p>
    </div>
</div>