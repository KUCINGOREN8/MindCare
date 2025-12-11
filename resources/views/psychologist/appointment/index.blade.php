@extends('layouts.dashboard')
@section('title', 'Psychologist Schedule')

@section('content')

    <div class="flex-1 flex flex-col h-full overflow-y-auto pr-2 pb-20 scroll-smooth custom-scrollbar">

        {{-- Header Section --}}
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
            
            {{-- Tombol Refresh --}}
            <a href="{{ request()->url() }}" class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm font-semibold hover:border-teal-500 hover:text-teal-600 hover:shadow-md transition-all duration-300">
                <svg class="w-4 h-4 group-hover:animate-spin transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                Sync Data
            </a>
        </div>

        {{-- A. UPCOMING SESSION --}}
        <section class="mb-10">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                    Next Client
                    @if(isset($ongoing) && $ongoing)
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-teal-500"></span>
                        </span>
                    @endif
                </h3>
            </div>

            @if(isset($ongoing) && $ongoing)
                {{-- Card Control Center (Teal Gradient & Glassmorphism) --}}
                <div class="relative bg-gradient-to-br from-teal-500 to-emerald-600 rounded-3xl p-1 shadow-xl shadow-teal-500/20 group overflow-hidden">
                    
                    {{-- Abstract Background Shapes --}}
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl group-hover:scale-110 transition duration-1000"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-teal-300 opacity-20 rounded-full blur-2xl"></div>

                    <div class="relative bg-white/5 backdrop-blur-sm rounded-[20px] p-6 sm:p-8 text-white border border-white/10">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                            
                            {{-- Profile Info --}}
                            <div class="flex items-center gap-6">
                                <div class="relative">
                                    <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 flex items-center justify-center text-3xl font-bold uppercase shadow-lg text-white">
                                        {{ substr($ongoing->user->name ?? 'C', 0, 1) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-400 border-2 border-teal-600 rounded-full"></div>
                                </div>
                                
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="bg-teal-900/30 px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider text-teal-50 border border-teal-200/20 shadow-sm">
                                            Confirmed Session
                                        </span>
                                    </div>
                                    <h4 class="text-3xl font-bold tracking-tight mb-1">{{ $ongoing->user->name ?? 'Unknown Client' }}</h4>
                                    <div class="flex items-center gap-4 text-teal-50 text-sm font-medium opacity-90">
                                        <span class="flex items-center gap-1.5 bg-black/10 px-3 py-1 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ \Carbon\Carbon::parse($ongoing->date)->format('d F Y') }}
                                        </span>
                                        <span class="flex items-center gap-1.5 bg-black/10 px-3 py-1 rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ \Carbon\Carbon::parse($ongoing->start_time)->format('H:i') }} WIB
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex flex-col sm:flex-row items-stretch gap-3 min-w-[200px]">
                                <a href="{{ $ongoing->link_meeting ?? '#' }}" target="_blank" class="flex-1 px-6 py-3.5 bg-white text-teal-700 hover:bg-teal-50 rounded-xl text-sm font-bold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 group/btn">
                                    <svg class="w-5 h-5 group-hover/btn:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    Join Room
                                </a>
                                <button class="flex-1 px-6 py-3.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-semibold transition border border-white/20 flex items-center justify-center gap-2 text-white">
                                    <svg class="w-4 h-4 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Notes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                {{-- State Kosong Upcoming (KEMBALI KE STYLE SIMPLE & DASHED) --}}
                <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                    <p class="text-gray-400 text-sm">No upcoming sessions scheduled right now.</p>
                </div>
            @endif
        </section>

        {{-- B. HISTORY --}}
        <section class="mb-8">
            <h3 class="font-bold text-xl text-gray-800 mb-5 pl-1">History</h3>
            <div class="flex flex-col gap-4">
                @forelse($history ?? [] as $item)
                    {{-- Logika Warna Badge --}}
                    @php
                        $status = strtolower($item->status ?? 'completed');
                        $badgeClass = match($status) {
                            'completed' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'cancelled', 'canceled' => 'bg-rose-100 text-rose-700 border-rose-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200'
                        };
                        $iconColor = $status == 'completed' ? 'text-emerald-500 bg-emerald-50' : 'text-gray-400 bg-gray-50';
                    @endphp

                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-teal-200 hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-between group cursor-pointer relative overflow-hidden">
                        {{-- Hover Accent --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500 opacity-0 group-hover:opacity-100 transition"></div>

                        <div class="flex items-center gap-5">
                            {{-- Icon --}}
                            <div class="w-12 h-12 rounded-xl {{ $iconColor }} flex items-center justify-center group-hover:scale-110 transition duration-300">
                                @if($status == 'completed')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                @endif
                            </div>
                            
                            <div>
                                <h5 class="font-bold text-gray-800 text-base mb-1 group-hover:text-teal-700 transition">{{ $item->user->name ?? 'Unknown Client' }}</h5>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ isset($item->date) ? \Carbon\Carbon::parse($item->date)->format('d M Y') : '-' }}
                                    </span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ isset($item->start_time) ? \Carbon\Carbon::parse($item->start_time)->format('H:i') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold uppercase tracking-wide border {{ $badgeClass }}">
                                {{ $status }}
                            </span>
                            {{-- Arrow Icon --}}
                            <div class="text-gray-300 group-hover:text-teal-500 transition transform group-hover:translate-x-1">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- State Kosong History (KEMBALI KE STYLE SIMPLE & DASHED) --}}
                    <div class="bg-white p-8 rounded-2xl border border-dashed border-gray-300 text-center">
                        <p class="text-gray-400 text-sm">No session history found.</p>
                    </div>
                @endforelse
            </div>
        </section>

    </div>

@endsection