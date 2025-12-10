@extends('layouts.dashboard')
@section('title', 'Psychologist Schedule')

@section('content')

    <div class="flex-1 flex flex-col h-full overflow-y-auto pr-2 pb-20 scroll-smooth">

        {{-- Header --}}
        <div class="mb-8 mt-2 flex justify-between items-end">
            <div>
                <h2 class="text-primary font-bold text-3xl">My Schedule</h2>
                <p class="text-captiondark text-base">Manage your active sessions and client history.</p>
            </div>
            {{-- Tombol Refresh --}}
            <a href="{{ request()->url() }}" class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-50 transition shadow-sm">
                Refresh
            </a>
        </div>

        {{-- A. UPCOMING SESSION --}}
        <section class="mb-8">
            <div class="flex items-center gap-2 mb-4">
                <h3 class="font-bold text-lg text-gray-800">Next Client</h3>

                {{-- Indikator Live --}}
                @if(isset($ongoing) && $ongoing)
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                    </span>
                @endif
            </div>

            @if(isset($ongoing) && $ongoing)
                {{-- Card Control Center (Teal Gradient) --}}
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl group-hover:scale-110 transition duration-700"></div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-full bg-white/20 backdrop-blur-sm border-2 border-white/30 flex items-center justify-center text-2xl font-bold uppercase shadow-inner">
                                {{ substr($ongoing->user->name ?? 'C', 0, 1) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="bg-teal-800/30 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider text-teal-100 border border-teal-400/20">
                                        Confirmed
                                    </span>
                                </div>
                                <h4 class="text-2xl font-bold tracking-tight">{{ $ongoing->user->name ?? 'Unknown Client' }}</h4>
                                <p class="text-teal-50 opacity-90 text-sm flex items-center gap-2 mt-1 font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ \Carbon\Carbon::parse($ongoing->date)->format('d M Y') }} • {{ \Carbon\Carbon::parse($ongoing->time)->format('H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button class="px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-semibold transition border border-white/10 flex items-center gap-2">
                                <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Client Notes
                            </button>
                            <a href="{{ $ongoing->link_meeting ?? '#' }}" target="_blank" class="px-6 py-2.5 bg-white text-teal-700 hover:bg-teal-50 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                Join Room
                            </a>
                        </div>
                    </div>
                </div>
            @else
                {{-- State Kosong Upcoming (STYLE UTAMA) --}}
                <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-400 text-sm">No upcoming sessions scheduled right now.</p>
                </div>
            @endif
        </section>

        {{-- B. HISTORY --}}
        <section class="mb-8">
            <h3 class="font-bold text-lg text-gray-800 mb-4">History</h3>
            <div class="space-y-3">
                @forelse($history ?? [] as $item)
                    <div class="bg-white p-4 rounded-xl border border-gray-100 hover:shadow-md transition flex items-center justify-between group cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $item->user->name ?? 'Unknown Client' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d F Y') : '-' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700 uppercase tracking-wide">
                            {{ $item->status ?? 'Completed' }}
                        </span>
                    </div>
                @empty
                    {{-- State Kosong History (SEKARANG SAMA PERSIS DENGAN UPCOMING) --}}
                    <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                        <p class="text-gray-400 text-sm">No session history found.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>

@endsection