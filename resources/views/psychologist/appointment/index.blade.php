@extends('layouts.dashboard')
@section('title', 'Psychologist Schedule')

@section('content')

<div class="flex-1 flex flex-col h-full overflow-y-auto pr-2 pb-20 scroll-smooth custom-scrollbar">

    {{-- HEADER --}}
    <div class="mb-8 mt-4 flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-gray-800 font-bold text-3xl tracking-tight">My Schedule</h2>
                <span class="px-3 py-1 bg-teal-50 text-teal-700 text-xs font-bold rounded-full border border-teal-100">
                    {{ \Carbon\Carbon::now()->format('d M Y') }}
                </span>
            </div>
            <p class="text-gray-500 text-base">Manage your active sessions and client history.</p>
        </div>

        <a href="{{ request()->url() }}"
           class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm font-semibold hover:border-teal-500 hover:text-teal-600 hover:shadow-md transition-all">
            <svg class="w-4 h-4 group-hover:animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Sync Data
        </a>
    </div>

    {{-- RESCHEDULE REQUESTS --}}
    <section class="mb-10">
        <h3 class="font-bold text-xl text-gray-800 mb-5">Reschedule Requests</h3>

        <div class="flex flex-col gap-4">
            @forelse($rescheduleRequests as $item)
                <div class="bg-white p-5 rounded-2xl border shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    {{-- CLIENT INFO --}}
                    <div>
                        <h4 class="font-bold text-gray-800">
                            {{ $item->user->name ?? 'Unknown Client' }}
                        </h4>
                        <p class="text-xs text-gray-500 mt-1">
                            Requested:
                            {{ \Carbon\Carbon::parse($item->reschedule_date)->format('d M Y') }} ·
                            {{ \Carbon\Carbon::parse($item->reschedule_time)->format('H:i') }} WIB
                        </p>
                    </div>

                    {{-- ACTION --}}
                    <div class="flex gap-3">
                        <form method="POST" action="{{ route('psychologist.appointments.reschedule.accept', $item->id) }}">
                            @csrf
                            @method('PUT')
                            <button class="px-4 py-2 bg-emerald-500 text-white text-sm font-semibold rounded-xl hover:bg-emerald-600 transition">
                                Accept
                            </button>
                        </form>

                        <form method="sPOST" action="{{ route('psychologist.appointments.reschedule.decline', $item->id) }}">
                            @csrf
                            @method('PUT')
                            <button class="px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition">
                                Decline
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-400 text-sm">No reschedule requests.</p>
                </div>
            @endforelse
        </div>
    </section>

    {{-- NEXT CLIENT --}}
    <section class="mb-10">
        <h3 class="font-bold text-xl text-gray-800 mb-4">Next Client</h3>

        @if(isset($upcoming) && $upcoming->first())
            @php $next = $upcoming->first(); @endphp

            <div class="bg-gradient-to-br from-yellow-400 to-amber-500 rounded-3xl p-1 shadow-lg">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-white border border-white/20">
                    <h4 class="text-2xl font-bold">{{ $next->user->name ?? 'Unknown Client' }}</h4>
                    <p class="text-sm mt-2">
                        {{ \Carbon\Carbon::parse($next->date)->format('d F Y') }} ·
                        {{ \Carbon\Carbon::parse($next->start_time)->format('H:i') }} WIB
                    </p>
                </div>
            </div>
        @else
            <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                <p class="text-gray-400 text-sm">No upcoming sessions scheduled.</p>
            </div>
        @endif
    </section>

    {{-- HISTORY --}}
    <section class="mb-8">
        <h3 class="font-bold text-xl text-gray-800 mb-5">History</h3>

        <div class="flex flex-col gap-4">
            @forelse($history as $item)
                @php
                    $status = strtolower($item->status);
                    $badge = $status === 'completed'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-gray-100 text-gray-600';
                @endphp

                <div class="bg-white p-5 rounded-2xl border shadow-sm flex justify-between items-center">
                    <div>
                        <h4 class="font-bold text-gray-800">{{ $item->user->name ?? 'Unknown Client' }}</h4>
                        <p class="text-xs text-gray-500">
                            {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }} ·
                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }}
                        </p>
                    </div>

                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $badge }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
            @empty
                <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-400 text-sm">No session history found.</p>
                </div>
            @endforelse
        </div>
    </section>

</div>
@endsection
